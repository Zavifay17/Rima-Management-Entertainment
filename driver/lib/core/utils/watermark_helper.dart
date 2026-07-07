import 'dart:io';
import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:geolocator/geolocator.dart';
import 'package:image/image.dart' as img;
import 'package:intl/intl.dart';
import 'package:http/http.dart' as http;

class WatermarkHelper {
  /// Gets coordinates, reverse geocodes the address, and watermarks the image file at [imagePath] with:
  /// Line 1: Date & Time in lowercase Indonesian month format (e.g., 05 juli 2026 17.45.10 WIB)
  /// Line 2: GPS coordinates separated by comma (e.g., -6.18909, 106.8484217)
  /// Line 3: Subdistrict, City, Province (e.g., SENEN, KOTA JAKARTA PUSAT, DKI JAKARTA)
  /// Line 4: Order Number label (e.g., No. Pesanan: 2)
  ///
  /// Returns the path to the watermarked image file.
  static Future<String> applyWatermark(
    String imagePath, {
    required String orderId,
    required String fallbackAddress,
  }) async {
    double? latitude;
    double? longitude;
    String addressText = '';

    try {
      // Check location service
      bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
      if (serviceEnabled) {
        LocationPermission permission = await Geolocator.checkPermission();
        if (permission == LocationPermission.denied) {
          permission = await Geolocator.requestPermission();
        }

        if (permission == LocationPermission.always || permission == LocationPermission.whileInUse) {
          final position = await Geolocator.getCurrentPosition(
            desiredAccuracy: LocationAccuracy.high,
            timeLimit: const Duration(seconds: 4),
          );
          latitude = position.latitude;
          longitude = position.longitude;

          // Attempt to reverse geocode the location to get Subdistrict, City, Province
          try {
            final url = Uri.parse(
              'https://nominatim.openstreetmap.org/reverse?format=json&lat=$latitude&lon=$longitude&zoom=18&addressdetails=1',
            );
            final response = await http.get(url, headers: {
              'User-Agent': 'RMEDriverApp/1.0.0 (contact: support@rme.com)'
            }).timeout(const Duration(seconds: 3));

            if (response.statusCode == 200) {
              final data = json.decode(response.body);
              final address = data['address'] as Map<String, dynamic>?;
              if (address != null) {
                final subdistrict = address['subdistrict'] ??
                    address['village'] ??
                    address['suburb'] ??
                    address['neighbourhood'] ??
                    '';
                final city = address['city'] ??
                    address['municipality'] ??
                    address['regency'] ??
                    address['county'] ??
                    '';
                final state = address['state'] ?? '';

                final parts = [
                  subdistrict.toString().toUpperCase(),
                  city.toString().toUpperCase(),
                  state.toString().toUpperCase()
                ].where((p) => p.isNotEmpty).toList();

                addressText = parts.join(', ');
              }
            }
          } catch (e) {
            debugPrint('Error reverse geocoding coordinates: $e');
          }
        }
      }
    } catch (e) {
      debugPrint('Error getting location for watermark: $e');
    }

    try {
      final file = File(imagePath);
      if (!await file.exists()) return imagePath;

      final bytes = await file.readAsBytes();
      final image = img.decodeImage(bytes);
      if (image == null) return imagePath;

      // Dynamic sizing based on image resolution (adjusted for 4 lines of larger text)
      img.BitmapFont font = img.arial24;
      int padding = 20;
      int boxHeight = 132;
      int lineSpacing = 28;
      int textOffsetX = 16;
      int textOffsetY = 12;

      if (image.width >= 1200) {
        font = img.arial48;
        padding = 32;
        boxHeight = 240;
        lineSpacing = 48;
        textOffsetX = 24;
        textOffsetY = 16;
      } else if (image.width >= 800) {
        font = img.arial24;
        padding = 20;
        boxHeight = 132;
        lineSpacing = 28;
        textOffsetX = 16;
        textOffsetY = 12;
      } else {
        font = img.arial14;
        padding = 12;
        boxHeight = 78;
        lineSpacing = 16;
        textOffsetX = 10;
        textOffsetY = 6;
      }

      // Responsive width spanning almost the full image width
      final int boxWidth = image.width - (padding * 2);
      final x1 = padding;
      final y1 = image.height - boxHeight - padding;
      final x2 = padding + boxWidth;
      final y2 = image.height - padding;

      // Draw filled semi-transparent black rectangle
      img.fillRect(
        image,
        x1: x1,
        y1: y1,
        x2: x2,
        y2: y2,
        color: img.ColorRgba8(0, 0, 0, 160),
      );

      // Safe date formatting in lowercase Indonesian with dot time separator
      final now = DateTime.now();
      final monthNames = [
        'januari', 'februari', 'maret', 'april', 'mei', 'juni',
        'juli', 'agustus', 'september', 'oktober', 'november', 'desember'
      ];
      final dateText = '${now.day.toString().padLeft(2, '0')} ${monthNames[now.month - 1]} ${now.year} ${DateFormat('HH.mm.ss').format(now)} WIB';
      
      // Coordinate text matching first image format: Lat, Lng directly
      final coordsText = (latitude != null && longitude != null)
          ? '${latitude.toStringAsFixed(7)}, ${longitude.toStringAsFixed(7)}'
          : '-6.2088000, 106.8456000';

      // Address text: reverse geocoded or fallback (converted to uppercase)
      final displayAddress = addressText.isNotEmpty
          ? addressText
          : fallbackAddress.toUpperCase().replaceAll('LOKASI EVENT: ', '');

      // Order number label matching "No. Pesanan: X"
      final orderText = 'No. Pesanan: $orderId';

      // Draw 4 text lines
      img.drawString(
        image,
        dateText,
        font: font,
        x: x1 + textOffsetX,
        y: y1 + textOffsetY,
        color: img.ColorRgb8(255, 255, 255),
      );

      img.drawString(
        image,
        coordsText,
        font: font,
        x: x1 + textOffsetX,
        y: y1 + textOffsetY + lineSpacing,
        color: img.ColorRgb8(255, 255, 255),
      );

      img.drawString(
        image,
        displayAddress,
        font: font,
        x: x1 + textOffsetX,
        y: y1 + textOffsetY + lineSpacing * 2,
        color: img.ColorRgb8(255, 255, 255),
      );

      img.drawString(
        image,
        orderText,
        font: font,
        x: x1 + textOffsetX,
        y: y1 + textOffsetY + lineSpacing * 3,
        color: img.ColorRgb8(255, 255, 255),
      );

      // Save to a new file to force Flutter image cache reload
      final directory = file.parent.path;
      final filename = file.uri.pathSegments.last;
      final newPath = '$directory/wm_${DateTime.now().millisecondsSinceEpoch}_$filename';

      final watermarkedBytes = img.encodeJpg(image, quality: 85);
      await File(newPath).writeAsBytes(watermarkedBytes);
      
      debugPrint('Watermark successfully applied. Saved to new path: $newPath');
      
      // Attempt to delete original un-watermarked cache file to free space
      try {
        await file.delete();
      } catch (_) {}

      return newPath;
    } catch (e) {
      debugPrint('Error applying watermark to image: $e');
      return imagePath;
    }
  }
}
