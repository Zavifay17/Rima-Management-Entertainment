import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../core/models/driver_model.dart';
import '../core/constants/app_routes.dart';
import '../core/services/supabase_direct_service.dart';

class AuthProvider extends ChangeNotifier {
  bool _isAuthenticated = false;
  bool _isLoading = false;
  String? _errorMessage;
  DriverModel? _driver;

  bool get isAuthenticated => _isAuthenticated;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;
  DriverModel? get driver => _driver;

  Future<void> checkAuthStatus() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');
    final driverJson = prefs.getString('auth_driver');
    if (token != null && driverJson != null) {
      try {
        _isAuthenticated = true;
        _driver = DriverModel.fromJson(jsonDecode(driverJson) as Map<String, dynamic>);
        notifyListeners();
      } catch (e) {
        await logout();
      }
    }
  }

  Future<bool> login(String username, String password) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final driverData = await SupabaseDirectService.instance.login(username, password);

      if (driverData != null) {
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('auth_token', 'token_${driverData['id_driver']}_${DateTime.now().millisecondsSinceEpoch}');
        await prefs.setString('auth_driver', jsonEncode(driverData));

        _driver = DriverModel.fromJson(driverData);
        _isAuthenticated = true;
        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        _errorMessage = 'Username atau password salah';
        _isLoading = false;
        notifyListeners();
        return false;
      }
    } catch (e) {
      _errorMessage = 'Gagal terhubung ke database Supabase: $e';
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
    await prefs.remove('auth_driver');
    _isAuthenticated = false;
    _driver = null;
    notifyListeners();
  }

  void toggleOnlineStatus() {
    if (_driver != null) {
      _driver = _driver!.copyWith(isOnline: !_driver!.isOnline);
      notifyListeners();
    }
  }

  Future<bool> updateProfile({
    required String name,
    required String phone,
    String? photoBase64,
  }) async {
    if (_driver == null) return false;
    _isLoading = true;
    notifyListeners();

    try {
      final success = await SupabaseDirectService.instance.updateDriverProfile(
        _driver!.id,
        name,
        phone,
        photoBase64 ?? _driver!.photoUrl,
      );

      if (success) {
        // Update local driver model instance
        _driver = DriverModel(
          id: _driver!.id,
          name: name,
          email: _driver!.email,
          phone: phone,
          vehicleType: _driver!.vehicleType,
          vehiclePlate: _driver!.vehiclePlate,
          photoUrl: photoBase64 ?? _driver!.photoUrl,
          totalTrips: _driver!.totalTrips,
          isOnline: _driver!.isOnline,
          balance: _driver!.balance,
          joinDate: _driver!.joinDate,
        );

        // Update stored driver in SharedPreferences
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('auth_driver', jsonEncode(_driver!.toJson()));

        _isLoading = false;
        notifyListeners();
        return true;
      }
    } catch (e) {
      debugPrint('Error updating profile in AuthProvider: $e');
    }

    _isLoading = false;
    notifyListeners();
    return false;
  }
}
