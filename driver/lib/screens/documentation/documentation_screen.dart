import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import 'dart:io';
import '../../core/constants/app_colors.dart';
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
      final XFile? image = await _picker.pickImage(
        source: source,
        maxWidth: 1280,
        maxHeight: 960,
        imageQuality: 85,
      );

      if (image == null) return;

      setState(() => _isUploading = true);
      // Simulate upload delay
      await Future.delayed(const Duration(milliseconds: 1200));

      if (mounted) {
        context.read<TaskProvider>().addPhotoToTask(taskId, image.path);
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

  void _showImageSourceDialog(String taskId) {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) => SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(20),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Pilih Sumber Foto', style: AppTextStyles.heading3),
              const SizedBox(height: 20),
              _SourceOption(
                icon: Icons.camera_alt_rounded,
                label: 'Kamera',
                subtitle: 'Ambil foto langsung',
                onTap: () {
                  Navigator.pop(context);
                  _pickImage(taskId, ImageSource.camera);
                },
              ),
              const SizedBox(height: 12),
              _SourceOption(
                icon: Icons.photo_library_rounded,
                label: 'Galeri',
                subtitle: 'Pilih dari galeri foto',
                onTap: () {
                  Navigator.pop(context);
                  _pickImage(taskId, ImageSource.gallery);
                },
              ),
              const SizedBox(height: 8),
            ],
          ),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final taskId = ModalRoute.of(context)!.settings.arguments as String;
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
                color: AppColors.infoLight,
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: AppColors.info.withOpacity(0.2)),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      const Icon(Icons.info_outline,
                          size: 16, color: AppColors.info),
                      const SizedBox(width: 6),
                      Text(
                        'Panduan Dokumentasi',
                        style: AppTextStyles.labelLarge
                            .copyWith(color: AppColors.info),
                      ),
                    ],
                  ),
                  const SizedBox(height: 10),
                  _GuideItem('📦 Foto kondisi alat sebelum diambil'),
                  _GuideItem('🚗 Foto alat saat dimuat ke kendaraan'),
                  _GuideItem('📍 Foto alat saat tiba di lokasi'),
                  _GuideItem('✅ Foto bukti serah terima dengan pelanggan'),
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
                  value: (task.photos.length > 2
                          ? task.photos.length - 1
                          : task.photos.length)
                      .toString(),
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
                    : () => _showImageSourceDialog(task.id),
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
  final String photoPath;
  final int index;

  const _PhotoTile({required this.photoPath, required this.index});

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        ClipRRect(
          borderRadius: BorderRadius.circular(12),
          child: File(photoPath).existsSync()
              ? Image.file(
                  File(photoPath),
                  fit: BoxFit.cover,
                  width: double.infinity,
                  height: double.infinity,
                )
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
        Positioned(
          top: 6,
          right: 6,
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
            decoration: BoxDecoration(
              color: AppColors.success,
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
