import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import '../../core/constants/app_colors.dart';
import '../../core/constants/app_routes.dart';
import '../../core/utils/helpers.dart';
import '../../providers/auth_provider.dart';
import '../../providers/task_provider.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    final taskProvider = context.watch<TaskProvider>();
    final driver = auth.driver;

    return Scaffold(
      backgroundColor: AppColors.background,
      body: CustomScrollView(
        slivers: [
          // Profile Header
          SliverToBoxAdapter(
            child: Container(
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                  colors: [Color(0xFF1E40AF), Color(0xFF2563EB)],
                ),
                borderRadius:
                    BorderRadius.vertical(bottom: Radius.circular(28)),
              ),
              child: SafeArea(
                child: Padding(
                  padding: const EdgeInsets.fromLTRB(20, 16, 20, 28),
                  child: Column(
                    children: [
                      // Avatar & Info
                      Row(
                        children: [
                          Stack(
                            children: [
                              Container(
                                width: 72,
                                height: 72,
                                decoration: BoxDecoration(
                                  color: Colors.white.withOpacity(0.2),
                                  borderRadius: BorderRadius.circular(20),
                                  border: Border.all(
                                      color: Colors.white.withOpacity(0.4),
                                      width: 2),
                                ),
                                child: _buildAvatar(driver?.photoUrl),
                              ),
                              Positioned(
                                bottom: 0,
                                right: 0,
                                child: GestureDetector(
                                  onTap: () => _showEditProfileBottomSheet(context, auth),
                                  child: Container(
                                    padding: const EdgeInsets.all(6),
                                    decoration: const BoxDecoration(
                                      color: AppColors.primary,
                                      shape: BoxShape.circle,
                                    ),
                                    child: const Icon(Icons.camera_alt_rounded,
                                        color: Colors.white, size: 12),
                                  ),
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  children: [
                                    Expanded(
                                      child: Text(
                                        driver?.name ?? 'Driver',
                                        style: const TextStyle(
                                          color: Colors.white,
                                          fontSize: 20,
                                          fontWeight: FontWeight.w700,
                                        ),
                                        maxLines: 1,
                                        overflow: TextOverflow.ellipsis,
                                      ),
                                    ),
                                    IconButton(
                                      onPressed: () => _showEditProfileBottomSheet(context, auth),
                                      icon: const Icon(Icons.edit_rounded, color: Colors.white70, size: 18),
                                      padding: EdgeInsets.zero,
                                      constraints: const BoxConstraints(),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  driver?.email ?? '',
                                  style: TextStyle(
                                    color: Colors.white.withOpacity(0.75),
                                    fontSize: 13,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 24),
                      // Stats Row
                      Row(
                        children: [
                          _ProfileStat(
                            label: 'Total Tugas',
                            value: '${driver?.totalTrips ?? 0}',
                            icon: Icons.assignment_turned_in_outlined,
                          ),
                          const SizedBox(width: 12),
                          _ProfileStat(
                            label: 'Bergabung',
                            value: driver?.joinDate ?? '-',
                            icon: Icons.calendar_today_outlined,
                          ),
                          const SizedBox(width: 12),
                          _ProfileStat(
                            label: 'Kendaraan',
                            value: driver?.vehicleType ?? '-',
                            icon: Icons.local_shipping_outlined,
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ),
            ).animate().fadeIn(),
          ),

          // Menu Items
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Vehicle Info Card
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: AppDecorations.card,
                    child: Row(
                      children: [
                        Container(
                          width: 44,
                          height: 44,
                          decoration: BoxDecoration(
                            color: AppColors.primary.withOpacity(0.1),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: const Icon(Icons.local_shipping_rounded,
                              color: AppColors.primary, size: 22),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text('Kendaraan',
                                  style: AppTextStyles.bodySmall),
                              Text(
                                '${driver?.vehicleType ?? '-'} • ${driver?.vehiclePlate ?? '-'}',
                                style: AppTextStyles.labelLarge,
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ).animate(delay: 100.ms).fadeIn().slideY(begin: 0.1),

                  const SizedBox(height: 12),

                  // Phone Card
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: AppDecorations.card,
                    child: Row(
                      children: [
                        Container(
                          width: 44,
                          height: 44,
                          decoration: BoxDecoration(
                            color: AppColors.success.withOpacity(0.1),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: const Icon(Icons.phone_outlined,
                              color: AppColors.success, size: 22),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text('Nomor Telepon',
                                  style: AppTextStyles.bodySmall),
                              Text(
                                driver?.phone ?? '-',
                                style: AppTextStyles.labelLarge,
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ).animate(delay: 150.ms).fadeIn().slideY(begin: 0.1),

                  const SizedBox(height: 24),

                  Text('Menu', style: AppTextStyles.heading3),
                  const SizedBox(height: 12),

                  // Menu List


                  _MenuItem(
                    icon: Icons.assignment_outlined,
                    iconColor: AppColors.primary,
                    label: 'Semua Tugas',
                    subtitle:
                        '${taskProvider.completedTasks.length} tugas selesai',
                    onTap: () =>
                        Navigator.of(context).pushNamed(AppRoutes.taskList),
                    delay: 250,
                  ),

                  _MenuItem(
                    icon: Icons.help_outline_rounded,
                    iconColor: AppColors.info,
                    label: 'Bantuan & Dukungan',
                    subtitle: 'FAQ, hubungi kami',
                    onTap: () {},
                    delay: 300,
                  ),

                  _MenuItem(
                    icon: Icons.info_outline_rounded,
                    iconColor: AppColors.grey500,
                    label: 'Tentang Aplikasi',
                    subtitle: 'EventDriver v1.0.0',
                    onTap: () => _showAboutDialog(context),
                    delay: 350,
                  ),

                  const SizedBox(height: 24),

                  // Logout Button
                  SizedBox(
                    width: double.infinity,
                    height: 52,
                    child: OutlinedButton.icon(
                      onPressed: () => _showLogoutDialog(context, auth),
                      icon: const Icon(Icons.logout_rounded, size: 18),
                      label: const Text('Keluar dari Akun'),
                      style: OutlinedButton.styleFrom(
                        foregroundColor: AppColors.danger,
                        side: const BorderSide(color: AppColors.danger),
                        shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(14)),
                        textStyle: const TextStyle(
                            fontSize: 14, fontWeight: FontWeight.w600),
                      ),
                    ),
                  ).animate(delay: 400.ms).fadeIn(),

                  const SizedBox(height: 32),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  void _showLogoutDialog(BuildContext context, AuthProvider auth) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: const Text('Keluar dari Akun?'),
        content: const Text(
            'Anda akan logout dari aplikasi EventDriver. Yakin?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Batal'),
          ),
          ElevatedButton(
            onPressed: () async {
              Navigator.pop(ctx);
              await auth.logout();
              if (context.mounted) {
                Navigator.of(context).pushReplacementNamed(AppRoutes.login);
              }
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.danger,
              foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10)),
            ),
            child: const Text('Keluar'),
          ),
        ],
      ),
    );
  }

  Widget _buildAvatar(String? photoUrl) {
    if (photoUrl != null && photoUrl.isNotEmpty) {
      try {
        final cleanBase64 = photoUrl.startsWith('data:image') 
            ? photoUrl.split(',').last 
            : photoUrl;
        final bytes = base64Decode(cleanBase64.replaceAll(RegExp(r'\s+'), ''));
        return ClipRRect(
          borderRadius: BorderRadius.circular(18),
          child: Image.memory(
            bytes,
            width: 72,
            height: 72,
            fit: BoxFit.cover,
          ),
        );
      } catch (e) {
        debugPrint('Error decoding base64 avatar: $e');
      }
    }
    return const Icon(Icons.person_rounded, color: Colors.white, size: 38);
  }

  void _showEditProfileBottomSheet(BuildContext context, AuthProvider auth) {
    final driver = auth.driver;
    if (driver == null) return;

    final nameController = TextEditingController(text: driver.name);
    final phoneController = TextEditingController(text: driver.phone);
    String? selectedPhotoBase64 = driver.photoUrl;

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return StatefulBuilder(
          builder: (BuildContext context, StateSetter setState) {
            return Container(
              decoration: const BoxDecoration(
                color: AppColors.surface,
                borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
              ),
              padding: EdgeInsets.only(
                top: 20,
                left: 24,
                right: 24,
                bottom: MediaQuery.of(context).viewInsets.bottom + 24,
              ),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text('Edit Profil', style: AppTextStyles.heading3),
                      IconButton(
                        icon: const Icon(Icons.close),
                        onPressed: () => Navigator.pop(context),
                      ),
                    ],
                  ),
                  const SizedBox(height: 20),
                  
                  // Photo picker preview
                  Center(
                    child: Stack(
                      children: [
                        CircleAvatar(
                          radius: 50,
                          backgroundColor: AppColors.grey100,
                          backgroundImage: selectedPhotoBase64 != null && selectedPhotoBase64!.isNotEmpty
                              ? MemoryImage(base64Decode(selectedPhotoBase64!.startsWith('data:image') 
                                  ? selectedPhotoBase64!.split(',').last 
                                  : selectedPhotoBase64!.replaceAll(RegExp(r'\s+'), '')))
                              : null,
                          child: selectedPhotoBase64 == null || selectedPhotoBase64!.isEmpty
                              ? const Icon(Icons.person_rounded, size: 50, color: AppColors.grey400)
                              : null,
                        ),
                        Positioned(
                          bottom: 0,
                          right: 0,
                          child: GestureDetector(
                            onTap: () async {
                              final source = await showDialog<ImageSource>(
                                context: context,
                                builder: (ctx) => AlertDialog(
                                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                                  title: const Text('Pilih Sumber Foto', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                                  content: Column(
                                    mainAxisSize: MainAxisSize.min,
                                    children: [
                                      ListTile(
                                        leading: const Icon(Icons.camera_alt_rounded, color: AppColors.primary),
                                        title: const Text('Kamera'),
                                        onTap: () => Navigator.pop(ctx, ImageSource.camera),
                                      ),
                                      ListTile(
                                        leading: const Icon(Icons.photo_library_rounded, color: AppColors.primary),
                                        title: const Text('Galeri'),
                                        onTap: () => Navigator.pop(ctx, ImageSource.gallery),
                                      ),
                                    ],
                                  ),
                                ),
                              );

                              if (source != null) {
                                final ImagePicker picker = ImagePicker();
                                final XFile? file = await picker.pickImage(
                                  source: source,
                                  maxWidth: 300,
                                  maxHeight: 300,
                                  imageQuality: 60,
                                );
                                if (file != null) {
                                  final bytes = await file.readAsBytes();
                                  setState(() {
                                    selectedPhotoBase64 = base64Encode(bytes);
                                  });
                                }
                              }
                            },
                            child: Container(
                              padding: const EdgeInsets.all(8),
                              decoration: const BoxDecoration(
                                color: AppColors.primary,
                                shape: BoxShape.circle,
                              ),
                              child: const Icon(Icons.edit_rounded, color: Colors.white, size: 16),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 24),

                  // Name Input
                  Text('Nama Lengkap', style: AppTextStyles.labelLarge),
                  const SizedBox(height: 8),
                  TextField(
                    controller: nameController,
                    decoration: InputDecoration(
                      hintText: 'Masukkan nama lengkap',
                      prefixIcon: const Icon(Icons.person_outline),
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: BorderSide(color: AppColors.grey300),
                      ),
                      enabledBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: BorderSide(color: AppColors.grey200),
                      ),
                      focusedBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: const BorderSide(color: AppColors.primary),
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),

                  // Phone Input
                  Text('Nomor Telepon / WhatsApp', style: AppTextStyles.labelLarge),
                  const SizedBox(height: 8),
                  TextField(
                    controller: phoneController,
                    keyboardType: TextInputType.phone,
                    decoration: InputDecoration(
                      hintText: 'Contoh: 08123456789',
                      prefixIcon: const Icon(Icons.phone_outlined),
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: BorderSide(color: AppColors.grey300),
                      ),
                      enabledBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: BorderSide(color: AppColors.grey200),
                      ),
                      focusedBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: const BorderSide(color: AppColors.primary),
                      ),
                    ),
                  ),
                  const SizedBox(height: 28),

                  // Save Button
                  SizedBox(
                    width: double.infinity,
                    height: 52,
                    child: ElevatedButton(
                      onPressed: () async {
                        final name = nameController.text.trim();
                        final phone = phoneController.text.trim();

                        if (name.isEmpty || phone.isEmpty) {
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(
                              content: Text('Nama dan Nomor Telepon tidak boleh kosong'),
                              backgroundColor: AppColors.danger,
                            ),
                          );
                          return;
                        }

                        // Close bottom sheet and perform upload
                        Navigator.pop(context);
                        
                        // Show simple loading dialog
                        showDialog(
                          context: context,
                          barrierDismissible: false,
                          builder: (ctx) => const Center(
                            child: Card(
                              child: Padding(
                                padding: EdgeInsets.all(24.0),
                                child: CircularProgressIndicator(),
                              ),
                            ),
                          ),
                        );

                        final success = await auth.updateProfile(
                          name: name,
                          phone: phone,
                          photoBase64: selectedPhotoBase64,
                        );

                        if (context.mounted) {
                          Navigator.pop(context); // Close loading dialog
                          ScaffoldMessenger.of(context).showSnackBar(
                            SnackBar(
                              content: Text(success
                                  ? 'Profil berhasil diperbarui'
                                  : 'Gagal memperbarui profil'),
                              backgroundColor: success ? AppColors.success : AppColors.danger,
                            ),
                          );
                        }
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.primary,
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                        elevation: 0,
                      ),
                      child: const Text('Simpan Perubahan', style: TextStyle(fontWeight: FontWeight.w700)),
                    ),
                  ),
                ],
              ),
            );
          },
        );
      },
    );
  }

  void _showAboutDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: Row(
          children: [
            Container(
              width: 36,
              height: 36,
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                    colors: [Color(0xFF2563EB), Color(0xFF3B82F6)]),
                borderRadius: BorderRadius.circular(10),
              ),
              child: const Icon(Icons.local_shipping_rounded,
                  color: Colors.white, size: 18),
            ),
            const SizedBox(width: 10),
            const Text('EventDriver'),
          ],
        ),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Versi: 1.0.0', style: AppTextStyles.bodyMedium),
            const SizedBox(height: 4),
            Text('Mata Kuliah: Mobile Programming',
                style: AppTextStyles.bodySmall),
            const SizedBox(height: 4),
            Text('Deskripsi: Aplikasi manajemen pengiriman dan pick up alat event untuk driver profesional.',
                style: AppTextStyles.bodySmall),
          ],
        ),
        actions: [
          ElevatedButton(
            onPressed: () => Navigator.pop(ctx),
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.primary,
              foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10)),
            ),
            child: const Text('OK'),
          ),
        ],
      ),
    );
  }
}

class _ProfileStat extends StatelessWidget {
  final String label;
  final String value;
  final IconData icon;

  const _ProfileStat({
    required this.label,
    required this.value,
    required this.icon,
  });

  @override
  Widget build(BuildContext context) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: Colors.white.withOpacity(0.15),
          borderRadius: BorderRadius.circular(14),
          border: Border.all(color: Colors.white.withOpacity(0.2)),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Icon(icon, color: Colors.white, size: 16),
            const SizedBox(height: 6),
            Text(
              value,
              style: const TextStyle(
                color: Colors.white,
                fontSize: 14,
                fontWeight: FontWeight.w700,
              ),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
            Text(
              label,
              style: TextStyle(
                color: Colors.white.withOpacity(0.7),
                fontSize: 10,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _MenuItem extends StatelessWidget {
  final IconData icon;
  final Color iconColor;
  final String label;
  final String subtitle;
  final VoidCallback onTap;
  final int delay;

  const _MenuItem({
    required this.icon,
    required this.iconColor,
    required this.label,
    required this.subtitle,
    required this.onTap,
    required this.delay,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 10),
      child: GestureDetector(
        onTap: onTap,
        child: Container(
          padding: const EdgeInsets.all(14),
          decoration: AppDecorations.card,
          child: Row(
            children: [
              Container(
                width: 44,
                height: 44,
                decoration: BoxDecoration(
                  color: iconColor.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Icon(icon, color: iconColor, size: 22),
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
      ),
    )
        .animate(delay: delay.ms)
        .fadeIn()
        .slideY(begin: 0.1);
  }
}
