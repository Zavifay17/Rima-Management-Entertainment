import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import 'dart:io';
import 'dart:convert';
import '../../core/constants/app_colors.dart';
import '../../core/utils/watermark_helper.dart';
import '../../providers/task_provider.dart';

class DocumentationScreen extends StatefulWidget {
  const DocumentationScreen({super.key});

  @override
  State<DocumentationScreen> createState() => _DocumentationScreenState();
}

class _DocumentationScreenState extends State<DocumentationScreen> {
  final ImagePicker _picker = ImagePicker();
  bool _isUploading = false;

  Future<void> _pickImage(String taskId, ImageSource source) async {
    try {
      final taskProvider = context.read<TaskProvider>();
      final task = taskProvider.getTaskById(taskId);
      if (task == null) return;

      final XFile? image = await _picker.pickImage(
        source: source,
        maxWidth: 1024,
        maxHeight: 768,
        imageQuality: 85,
      );

      if (image == null) return;

      setState(() => _isUploading = true);
      // Extract order number and address for watermark
      final parts = task.title.split('#');
      final orderId = parts.length > 1 ? parts.last : task.id;
      final fallbackAddress = task.type == 'delivery' ? task.deliveryAddress : task.pickupAddress;

      // Apply watermark (Date, Time, GPS Coordinates, Location, and Order ID)
      final String watermarkedPath = await WatermarkHelper.applyWatermark(
        image.path,
        orderId: orderId,
        fallbackAddress: fallbackAddress,
      );
      // Simulate upload delay
      await Future.delayed(const Duration(milliseconds: 600));

      if (mounted) {
        taskProvider.addPhotoToTask(taskId, watermarkedPath);
        setState(() => _isUploading = false);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: const Text('Foto berhasil diunggah'),
            backgroundColor: AppColors.success,
            behavior: SnackBarBehavior.floating,
            shape:
                RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
          ),
        );
      }
    } catch (e) {
      setState(() => _isUploading = false);
    }
  }

  // Camera-only picking, dialog removed as requested

  @override
  Widget build(BuildContext context) {
    final args = ModalRoute.of(context)!.settings.arguments;
    final String taskId = args is Map ? args['taskId'] as String : args as String;
    final bool fromChecklist = args is Map ? args['fromChecklist'] as bool? ?? false : false;
    final bool isDeparture = args is Map ? args['isDeparture'] as bool? ?? false : false;
    final taskProvider = context.watch<TaskProvider>();
    final task = taskProvider.getTaskById(taskId);

    if (task == null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Dokumentasi')),
        body: const Center(child: Text('Tugas tidak ditemukan')),
      );
    }

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: AppColors.surface,
        elevation: 0,
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Dokumentasi Foto', style: AppTextStyles.heading3),
            Text(
              task.title,
              style: AppTextStyles.caption.copyWith(color: AppColors.grey400),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
          ],
        ),
        leading: GestureDetector(
          onTap: () => Navigator.of(context).pop(),
          child: Container(
            margin: const EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: AppColors.grey100,
              borderRadius: BorderRadius.circular(10),
            ),
            child: const Icon(Icons.arrow_back_rounded,
                color: AppColors.grey700),
          ),
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Instructions
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: fromChecklist ? AppColors.warningLight : AppColors.infoLight,
                borderRadius: BorderRadius.circular(12),
                border: Border.all(
                  color: fromChecklist
                      ? AppColors.warning.withOpacity(0.2)
                      : AppColors.info.withOpacity(0.2),
                ),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Icon(
                        fromChecklist ? Icons.warning_amber_rounded : Icons.info_outline,
                        size: 16,
                        color: fromChecklist ? AppColors.warning : AppColors.info,
                      ),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          fromChecklist
                              ? 'Penting: Wajib Unggah Bukti Foto!'
                              : 'Panduan Dokumentasi',
                          style: AppTextStyles.labelLarge.copyWith(
                            color: fromChecklist ? AppColors.warning : AppColors.info,
                          ),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 10),
                  if (fromChecklist) ...[
                    Text(
                      isDeparture
                          ? 'Untuk memulai pengantaran, Anda wajib mengunggah minimal 3 foto bukti muat barang ke kendaraan.'
                          : 'Untuk menyelesaikan tugas ini, Anda wajib mengunggah minimal 3 foto bukti penyerahan ke pelanggan / masuk gudang.',
                      style: AppTextStyles.bodySmall.copyWith(
                        color: AppColors.warning,
                        height: 1.5,
                      ),
                    ),
                  ] else ...[
                    _GuideItem('📦 Foto kondisi alat sebelum diambil'),
                    _GuideItem('🚗 Foto alat saat dimuat ke kendaraan'),
                    _GuideItem('📍 Foto alat saat tiba di lokasi'),
                    _GuideItem('✅ Foto bukti serah terima dengan pelanggan'),
                  ],
                ],
              ),
            ).animate().fadeIn(),

            const SizedBox(height: 24),

            // Stats
            Row(
              children: [
                _StatBadge(
                  label: 'Total Foto',
                  value: task.photos.length.toString(),
                  color: AppColors.primary,
                ),
                const SizedBox(width: 12),
                _StatBadge(
                  label: 'Diverifikasi',
                  value: task.photos.length.toString(),
                  color: AppColors.success,
                ),
              ],
            ),

            const SizedBox(height: 20),

            // Photo Grid
            Text('Foto yang Diupload', style: AppTextStyles.heading3),
            const SizedBox(height: 12),

            if (task.photos.isEmpty)
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(40),
                decoration: BoxDecoration(
                  color: AppColors.surface,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(
                      color: AppColors.grey200, style: BorderStyle.solid),
                ),
                child: Column(
                  children: [
                    const Icon(Icons.add_a_photo_outlined,
                        size: 48, color: AppColors.grey300),
                    const SizedBox(height: 12),
                    Text('Belum ada foto', style: AppTextStyles.bodyMedium),
                    const SizedBox(height: 4),
                    Text('Tambahkan foto dokumentasi pengiriman',
                        style: AppTextStyles.bodySmall),
                  ],
                ),
              )
            else
              GridView.builder(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 3,
                  crossAxisSpacing: 8,
                  mainAxisSpacing: 8,
                  childAspectRatio: 1,
                ),
                itemCount: task.photos.length,
                itemBuilder: (context, index) {
                  return _PhotoTile(
                    taskId: task.id,
                    photoPath: task.photos[index],
                    index: index + 1,
                  ).animate(delay: (index * 80).ms).fadeIn().scale(begin: const Offset(0.8, 0.8));
                },
              ),

            const SizedBox(height: 24),

            // Upload Button
            SizedBox(
              width: double.infinity,
              height: 56,
              child: ElevatedButton.icon(
                onPressed: _isUploading
                    ? null
                    : () => _pickImage(task.id, ImageSource.camera),
                icon: _isUploading
                    ? const SizedBox(
                        width: 20,
                        height: 20,
                        child: CircularProgressIndicator(
                          strokeWidth: 2.5,
                          color: Colors.white,
                        ),
                      )
                    : const Icon(Icons.add_a_photo_rounded, size: 20),
                label: Text(
                  _isUploading ? 'Mengunggah...' : 'Tambah Foto',
                  style: const TextStyle(
                      fontSize: 15, fontWeight: FontWeight.w600),
                ),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  foregroundColor: Colors.white,
                  disabledBackgroundColor: AppColors.primary.withOpacity(0.5),
                  shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(16)),
                  elevation: 0,
                ),
              ),
            ),

            if (fromChecklist) ...[
              const SizedBox(height: 16),
              SizedBox(
                width: double.infinity,
                height: 56,
                child: ElevatedButton(
                  onPressed: task.photos.length >= 3 && !_isUploading
                      ? () async {
                          setState(() => _isUploading = true);
                          final taskProvider = context.read<TaskProvider>();
                          if (isDeparture) {
                            await taskProvider.confirmDeliveryChecklist(task.id);
                          } else {
                            await taskProvider.confirmPickupChecklist(task.id);
                          }
                          if (mounted) {
                            HapticFeedback.heavyImpact();
                            Navigator.of(context).popUntil((route) => route.isFirst);
                          }
                        }
                      : null,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.success,
                    foregroundColor: Colors.white,
                    disabledBackgroundColor: AppColors.grey200,
                    shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(16)),
                    elevation: 0,
                  ),
                  child: Text(
                    task.photos.length >= 3
                        ? (isDeparture ? 'Konfirmasi Keberangkatan' : 'Konfirmasi & Selesaikan Tugas')
                        : 'Upload Minimal 3 Foto (${task.photos.length}/3)',
                    style: const TextStyle(
                        fontSize: 15, fontWeight: FontWeight.w700),
                  ),
                ),
              ),
            ],

            const SizedBox(height: 32),
          ],
        ),
      ),
    );
  }
}

class _GuideItem extends StatelessWidget {
  final String text;
  const _GuideItem(this.text);

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 4),
      child: Text(
        text,
        style: AppTextStyles.bodySmall.copyWith(color: AppColors.info),
      ),
    );
  }
}

class _StatBadge extends StatelessWidget {
  final String label;
  final String value;
  final Color color;

  const _StatBadge({
    required this.label,
    required this.value,
    required this.color,
  });

  @override
  Widget build(BuildContext context) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: color.withOpacity(0.08),
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: color.withOpacity(0.2)),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              value,
              style: TextStyle(
                  fontSize: 24,
                  fontWeight: FontWeight.w700,
                  color: color),
            ),
            Text(label, style: AppTextStyles.bodySmall),
          ],
        ),
      ),
    );
  }
}

class _PhotoTile extends StatelessWidget {
  final String taskId;
  final String photoPath;
  final int index;

  const _PhotoTile({
    required this.taskId,
    required this.photoPath,
    required this.index,
  });

  Widget _buildImageWidget(String path, {required BoxFit fit}) {
    if (path.startsWith('data:image')) {
      try {
        final cleanBase64 = path.split(',').last;
        return Image.memory(
          base64Decode(cleanBase64.replaceAll(RegExp(r'\s+'), '')),
          fit: fit,
          width: double.infinity,
          height: double.infinity,
        );
      } catch (e) {
        return const Center(
          child: Icon(Icons.broken_image, color: Colors.white, size: 48),
        );
      }
    }
    return File(path).existsSync()
        ? Image.file(
            File(path),
            fit: fit,
            width: double.infinity,
            height: double.infinity,
          )
        : const Center(
            child: Icon(Icons.broken_image, color: Colors.white, size: 48),
          );
  }

  void _showPreviewDialog(BuildContext context, String path, int index) {
    showDialog(
      context: context,
      builder: (context) => Dialog(
        insetPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 24),
        backgroundColor: Colors.transparent,
        child: Stack(
          alignment: Alignment.center,
          children: [
            Container(
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(16),
                color: Colors.black,
              ),
              clipBehavior: Clip.antiAlias,
              child: InteractiveViewer(
                minScale: 0.5,
                maxScale: 4.0,
                child: _buildImageWidget(path, fit: BoxFit.contain),
              ),
            ),
            Positioned(
              top: 16,
              right: 16,
              child: CircleAvatar(
                backgroundColor: Colors.black.withOpacity(0.6),
                child: IconButton(
                  icon: const Icon(Icons.close, color: Colors.white),
                  onPressed: () => Navigator.of(context).pop(),
                ),
              ),
            ),
            Positioned(
              bottom: 16,
              left: 16,
              right: 16,
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                decoration: BoxDecoration(
                  color: Colors.black.withOpacity(0.6),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Text(
                  'Preview Foto $index (Cubit untuk Zoom)',
                  style: const TextStyle(color: Colors.white, fontSize: 13),
                  textAlign: TextAlign.center,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Stack(
      clipBehavior: Clip.none,
      children: [
        GestureDetector(
          onTap: () => _showPreviewDialog(context, photoPath, index),
          child: Container(
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: AppColors.grey200),
            ),
            clipBehavior: Clip.antiAlias,
            child: photoPath.isNotEmpty
                ? _buildImageWidget(photoPath, fit: BoxFit.cover)
                : Container(
                    color: AppColors.grey100,
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Icon(Icons.image_outlined,
                            color: AppColors.grey400, size: 28),
                        Text(
                          'Foto $index',
                          style: AppTextStyles.caption,
                        ),
                      ],
                    ),
                  ),
          ),
        ),
        Positioned(
          bottom: 6,
          right: 6,
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
            decoration: BoxDecoration(
              color: AppColors.success.withOpacity(0.9),
              borderRadius: BorderRadius.circular(6),
            ),
            child: const Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(Icons.check_rounded, size: 10, color: Colors.white),
                SizedBox(width: 2),
                Text(
                  'OK',
                  style: TextStyle(
                      fontSize: 9,
                      color: Colors.white,
                      fontWeight: FontWeight.w700),
                ),
              ],
            ),
          ),
        ),
        Positioned(
          top: -4,
          right: -4,
          child: GestureDetector(
            onTap: () {
              context.read<TaskProvider>().removePhotoFromTask(taskId, photoPath);
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                  content: Text('Foto $index berhasil dihapus'),
                  duration: const Duration(seconds: 1),
                  behavior: SnackBarBehavior.floating,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                ),
              );
            },
            child: Container(
              padding: const EdgeInsets.all(4),
              decoration: const BoxDecoration(
                color: Colors.redAccent,
                shape: BoxShape.circle,
              ),
              child: const Icon(
                Icons.close_rounded,
                size: 14,
                color: Colors.white,
              ),
            ),
          ),
        ),
      ],
    );
  }
}

class _SourceOption extends StatelessWidget {
  final IconData icon;
  final String label;
  final String subtitle;
  final VoidCallback onTap;

  const _SourceOption({
    required this.icon,
    required this.label,
    required this.subtitle,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: AppColors.grey50,
          borderRadius: BorderRadius.circular(14),
          border: Border.all(color: AppColors.grey200),
        ),
        child: Row(
          children: [
            Container(
              width: 44,
              height: 44,
              decoration: BoxDecoration(
                color: AppColors.primary.withOpacity(0.1),
                borderRadius: BorderRadius.circular(12),
              ),
              child: Icon(icon, color: AppColors.primary, size: 22),
            ),
            const SizedBox(width: 14),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(label, style: AppTextStyles.labelLarge),
                  Text(subtitle, style: AppTextStyles.bodySmall),
                ],
              ),
            ),
            const Icon(Icons.arrow_forward_ios_rounded,
                size: 14, color: AppColors.grey300),
          ],
        ),
      ),
    );
  }
}
