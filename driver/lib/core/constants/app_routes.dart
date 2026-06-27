import 'package:flutter/foundation.dart';

class AppRoutes {
  static const String splash = '/';
  static const String login = '/login';
  static const String home = '/home';
  static const String taskList = '/tasks';
  static const String taskDetail = '/task-detail';
  static const String taskTracking = '/task-tracking';
  static const String chat = '/chat';
  static const String chatDetail = '/chat-detail';
  static const String documentation = '/documentation';
  static const String profile = '/profile';
  static const String itemChecklist = '/item-checklist';
}

class AppStrings {
  static String get baseApiUrl {
    if (kIsWeb) {
      return 'http://localhost:8000/api';
    }
    return 'http://10.0.2.2:8000/api';
  }
  static const String appName = 'EventDriver';
  static const String appTagline = 'Pengiriman Alat Event Profesional';

  // Auth
  static const String loginTitle = 'Selamat Datang';
  static const String loginSubtitle = 'Login untuk melanjutkan sebagai driver';
  static const String emailHint = 'Email';
  static const String passwordHint = 'Password';
  static const String loginButton = 'Masuk';
  static const String loginLoading = 'Memverifikasi...';

  // Demo credentials
  static const String demoUsername = 'driver_slamet';
  static const String demoPassword = 'driver123';

  // Navigation
  static const String navHome = 'Beranda';
  static const String navTasks = 'Tugas';
  static const String navChat = 'Chat';
  static const String navProfile = 'Profil';

  // Task Status
  static const String statusPending = 'Menunggu';
  static const String statusAccepted = 'Diterima';
  static const String statusPickup = 'Diambil';
  static const String statusOnTheWay = 'Dalam Perjalanan';
  static const String statusArrived = 'Sampai';
  static const String statusDone = 'Selesai';
  static const String statusCancelled = 'Dibatalkan';

  // Task Type
  static const String typeDelivery = 'Pengiriman';
  static const String typePickup = 'Pick Up';

  // Common
  static const String loading = 'Memuat...';
  static const String noData = 'Belum ada data';
  static const String retry = 'Coba Lagi';
  static const String cancel = 'Batal';
  static const String confirm = 'Konfirmasi';
  static const String save = 'Simpan';
  static const String close = 'Tutup';
  static const String logout = 'Keluar';
}
