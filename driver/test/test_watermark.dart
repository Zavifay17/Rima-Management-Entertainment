import 'dart:io';
import 'package:flutter/foundation.dart';
import 'package:image/image.dart' as img;
import 'package:event_driver/core/utils/watermark_helper.dart';

void main() async {
  try {
    print('Creating a mock image for testing...');
    final mockImage = img.Image(width: 800, height: 600);
    // Draw some noise or colors so it's not empty
    img.fillRect(mockImage, x1: 0, y1: 0, x2: 800, y2: 600, color: img.ColorRgb8(100, 100, 100));
    
    final tempFile = File('test_mock_image.jpg');
    await tempFile.writeAsBytes(img.encodeJpg(mockImage));
    print('Mock image saved at: ${tempFile.absolute.path}');

    print('Applying watermark...');
    await WatermarkHelper.applyWatermark(tempFile.path);

    print('Checking if file updated...');
    final resultBytes = await tempFile.readAsBytes();
    final resultImg = img.decodeImage(resultBytes);
    if (resultImg != null) {
      print('Watermark applied successfully! Final image width: ${resultImg.width}, height: ${resultImg.height}');
    } else {
      print('Failed to decode the watermarked image!');
    }

    // Clean up
    if (await tempFile.exists()) {
      await tempFile.delete();
      print('Mock image deleted.');
    }
  } catch (e, stack) {
    print('Exception occurred: $e');
    print(stack);
  }
}
