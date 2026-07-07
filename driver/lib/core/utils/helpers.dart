import 'package:intl/intl.dart';

class DateFormatter {
  static String formatDate(DateTime date) {
    return DateFormat('dd MMM yyyy', 'id_ID').format(date);
  }

  static String formatDateTime(DateTime date) {
    return DateFormat('dd MMM yyyy, HH:mm', 'id_ID').format(date);
  }

  static String formatTime(DateTime date) {
    return DateFormat('HH:mm').format(date);
  }

  static String formatRelative(DateTime date) {
    final now = DateTime.now();
    final diff = now.difference(date);

    if (diff.inMinutes < 1) return 'Baru saja';
    if (diff.inMinutes < 60) return '${diff.inMinutes} menit lalu';
    if (diff.inHours < 24) return '${diff.inHours} jam lalu';
    if (diff.inDays < 7) return '${diff.inDays} hari lalu';
    return formatDate(date);
  }

  static String formatCurrency(double amount) {
    return NumberFormat.currency(
      locale: 'id_ID',
      symbol: 'Rp ',
      decimalDigits: 0,
    ).format(amount);
  }
}

class StatusHelper {
  static String getTaskStatusLabel(String status) {
    switch (status) {
      case 'pending':
        return 'Menunggu';
      case 'accepted':
        return 'Diterima';
      case 'pickup':
        return 'Alat Diambil';
      case 'on_the_way':
        return 'Dalam Perjalanan';
      case 'arrived':
        return 'Sampai Lokasi';
      case 'done':
        return 'Selesai';
      case 'cancelled':
        return 'Dibatalkan';
      default:
        return status;
    }
  }

  static String getNextStatusLabel(String status) {
    switch (status) {
      case 'accepted':
        return 'Ambil Alat';
      case 'pickup':
        return 'Mulai Perjalanan';
      case 'on_the_way':
        return 'Tiba di Lokasi';
      case 'arrived':
        return 'Selesaikan Tugas';
      default:
        return '';
    }
  }

  static String getNextStatus(String status) {
    switch (status) {
      case 'accepted':
        return 'pickup';
      case 'pickup':
        return 'on_the_way';
      case 'on_the_way':
        return 'arrived';
      case 'arrived':
        return 'done';
      default:
        return status;
    }
  }

  static int getStatusStep(String status) {
    switch (status) {
      case 'pending':
        return 0;
      case 'accepted':
        return 1;
      case 'pickup':
        return 2;
      case 'on_the_way':
        return 3;
      case 'arrived':
        return 4;
      case 'done':
        return 5;
      default:
        return 0;
    }
  }
}
