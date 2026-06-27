import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:provider/provider.dart';
import '../../core/constants/app_colors.dart';
import '../../core/models/task_model.dart';
import '../../providers/task_provider.dart';

/// Argumen yang diteruskan ke layar ini via Navigator.pushNamed
class ChecklistArgs {
  /// ID tugas
  final String taskId;

  /// 'delivery' = checklist saat mengantar (ambil dari gudang)
  /// 'pickup'   = checklist saat menjemput (ambil dari lokasi event)
  final String checklistType;

  const ChecklistArgs({required this.taskId, required this.checklistType});
}

class ItemChecklistScreen extends StatefulWidget {
  const ItemChecklistScreen({super.key});

  @override
  State<ItemChecklistScreen> createState() => _ItemChecklistScreenState();
}

class _ItemChecklistScreenState extends State<ItemChecklistScreen>
    with TickerProviderStateMixin {
  bool _isConfirming = false;
  late AnimationController _progressController;
  late Animation<double> _progressAnimation;
  double _lastProgress = 0;

  @override
  void initState() {
    super.initState();
    _progressController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 400),
    );
    _progressAnimation = Tween<double>(begin: 0, end: 0).animate(
      CurvedAnimation(parent: _progressController, curve: Curves.easeOutCubic),
    );
  }

  @override
  void dispose() {
    _progressController.dispose();
    super.dispose();
  }

  void _animateProgress(double newProgress) {
    _progressAnimation = Tween<double>(
      begin: _lastProgress,
      end: newProgress,
    ).animate(
      CurvedAnimation(parent: _progressController, curve: Curves.easeOutCubic),
    );
    _lastProgress = newProgress;
    _progressController.forward(from: 0);
  }

  Future<void> _confirm(
    BuildContext context,
    TaskModel task,
    String checklistType,
  ) async {
    if (!task.allItemsChecked) return;

    final bool? confirmed = await showDialog<bool>(
      context: context,
      barrierDismissible: false,
      builder: (ctx) => _ConfirmDialog(
        checklistType: checklistType,
        taskTitle: task.title,
      ),
    );

    if (confirmed != true || !mounted) return;

    setState(() => _isConfirming = true);

    // Simpan provider dan navigator sebelum await agar tidak cross async gap
    final taskProvider = context.read<TaskProvider>();
    final navigator = Navigator.of(context);

    await Future.delayed(const Duration(milliseconds: 600));
    if (!mounted) return;

    if (checklistType == 'delivery') {
      await taskProvider.confirmDeliveryChecklist(task.id);
    } else {
      await taskProvider.confirmPickupChecklist(task.id);
    }

    if (!mounted) return;
    HapticFeedback.heavyImpact();

    // Kembali ke layar sebelumnya dengan hasil true
    navigator.pop(true);
  }

  @override
  Widget build(BuildContext context) {
    final args =
        ModalRoute.of(context)!.settings.arguments as ChecklistArgs;
    final taskProvider = context.watch<TaskProvider>();
    final task = taskProvider.getTaskById(args.taskId);

    if (task == null) {
      return const Scaffold(
        body: Center(child: Text('Tugas tidak ditemukan')),
      );
    }

    final isDelivery = args.checklistType == 'delivery';
    final Color themeColor = isDelivery ? AppColors.primary : AppColors.warning;
    final Color themeLightColor =
        isDelivery ? const Color(0xFFEFF6FF) : AppColors.warningLight;

    final int checked = task.checkedCount;
    final int total = task.totalItems;
    final double progress = total > 0 ? checked / total : 0.0;

    // Picu animasi progress setiap kali ada perubahan
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if ((progress - _lastProgress).abs() > 0.001) {
        _animateProgress(progress);
      }
    });

    return Scaffold(
      backgroundColor: AppColors.background,
      body: Column(
        children: [
          // ─── Header ────────────────────────────────────────────────
          _ChecklistHeader(
            task: task,
            isDelivery: isDelivery,
            themeColor: themeColor,
            checked: checked,
            total: total,
            progress: progress,
            progressAnimation: _progressAnimation,
            progressController: _progressController,
          ),

          // ─── Item List ─────────────────────────────────────────────
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.fromLTRB(20, 16, 20, 100),
              itemCount: task.equipmentList.length,
              itemBuilder: (context, index) {
                final item = task.equipmentList[index];
                return _ChecklistItemTile(
                  index: index,
                  item: item,
                  themeColor: themeColor,
                  themeLightColor: themeLightColor,
                  onToggle: () {
                    HapticFeedback.selectionClick();
                    context
                        .read<TaskProvider>()
                        .toggleEquipmentCheck(task.id, index);
                  },
                )
                    .animate(delay: (index * 60).ms)
                    .fadeIn(duration: 300.ms)
                    .slideX(begin: 0.15, duration: 300.ms, curve: Curves.easeOut);
              },
            ),
          ),
        ],
      ),

      // ─── Bottom CTA ────────────────────────────────────────────────
      bottomNavigationBar: _BottomConfirmBar(
        allChecked: task.allItemsChecked,
        isConfirming: _isConfirming,
        themeColor: themeColor,
        checkedCount: checked,
        totalCount: total,
        onConfirm: () => _confirm(context, task, args.checklistType),
      ),
    );
  }
}

// ─── Header ─────────────────────────────────────────────────────────────────

class _ChecklistHeader extends StatelessWidget {
  final TaskModel task;
  final bool isDelivery;
  final Color themeColor;
  final int checked;
  final int total;
  final double progress;
  final Animation<double> progressAnimation;
  final AnimationController progressController;

  const _ChecklistHeader({
    required this.task,
    required this.isDelivery,
    required this.themeColor,
    required this.checked,
    required this.total,
    required this.progress,
    required this.progressAnimation,
    required this.progressController,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: isDelivery
              ? [const Color(0xFF1E40AF), const Color(0xFF3B82F6)]
              : [const Color(0xFF92400E), const Color(0xFFD97706)],
        ),
      ),
      child: SafeArea(
        bottom: false,
        child: Padding(
          padding: const EdgeInsets.fromLTRB(20, 8, 20, 24),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Back button & title
              Row(
                children: [
                  GestureDetector(
                    onTap: () => Navigator.of(context).pop(),
                    child: Container(
                      width: 38,
                      height: 38,
                      decoration: BoxDecoration(
                        color: Colors.white.withValues(alpha: 0.2),
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: const Icon(Icons.arrow_back_rounded,
                          color: Colors.white, size: 20),
                    ),
                  ),
                  const SizedBox(width: 14),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          isDelivery
                              ? '📦 Checklist Pengiriman'
                              : '🔄 Checklist Penjemputan',
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 16,
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                        Text(
                          isDelivery
                              ? 'Pastikan semua barang sebelum berangkat'
                              : 'Pastikan semua barang sudah dikembalikan',
                          style: TextStyle(
                            color: Colors.white.withValues(alpha: 0.8),
                            fontSize: 11,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),

              const SizedBox(height: 20),

              // Task title chip
              Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                decoration: BoxDecoration(
                  color: Colors.white.withValues(alpha: 0.15),
                  borderRadius: BorderRadius.circular(10),
                  border: Border.all(color: Colors.white.withValues(alpha: 0.2)),
                ),
                child: Text(
                  task.title,
                  style: const TextStyle(
                      color: Colors.white,
                      fontSize: 12,
                      fontWeight: FontWeight.w500),
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                ),
              ),

              const SizedBox(height: 20),

              // Progress section
              Row(
                children: [
                  Text(
                    '$checked/$total Barang',
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 13,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const Spacer(),
                  Text(
                    '${(progress * 100).toInt()}%',
                    style: TextStyle(
                      color: Colors.white.withValues(alpha: 0.9),
                      fontSize: 13,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 8),

              // Animated progress bar
              ClipRRect(
                borderRadius: BorderRadius.circular(6),
                child: Container(
                  height: 8,
                  color: Colors.white.withValues(alpha: 0.25),
                  child: AnimatedBuilder(
                    animation: progressAnimation,
                    builder: (_, __) => FractionallySizedBox(
                      alignment: Alignment.centerLeft,
                      widthFactor: progress, // langsung pakai nilai nyata
                      child: Container(
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(6),
                        ),
                      ),
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    ).animate().fadeIn(duration: 400.ms);
  }
}

// ─── Item Tile ───────────────────────────────────────────────────────────────

class _ChecklistItemTile extends StatelessWidget {
  final int index;
  final EquipmentItem item;
  final Color themeColor;
  final Color themeLightColor;
  final VoidCallback onToggle;

  const _ChecklistItemTile({
    required this.index,
    required this.item,
    required this.themeColor,
    required this.themeLightColor,
    required this.onToggle,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onToggle,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 250),
        margin: const EdgeInsets.only(bottom: 10),
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: item.isChecked ? themeLightColor : AppColors.surface,
          borderRadius: BorderRadius.circular(14),
          border: Border.all(
            color: item.isChecked
                ? themeColor.withValues(alpha: 0.4)
                : AppColors.grey200,
            width: item.isChecked ? 1.5 : 1,
          ),
          boxShadow: [
            BoxShadow(
              color: AppColors.grey900.withValues(alpha: 0.05),
              blurRadius: 8,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        child: Row(
          children: [
            // Nomor urut / checkbox
            AnimatedContainer(
              duration: const Duration(milliseconds: 250),
              width: 36,
              height: 36,
              decoration: BoxDecoration(
                color: item.isChecked ? themeColor : AppColors.grey100,
                borderRadius: BorderRadius.circular(10),
              ),
              child: Center(
                child: item.isChecked
                    ? const Icon(Icons.check_rounded,
                        color: Colors.white, size: 18)
                    : Text(
                        '${index + 1}',
                        style: TextStyle(
                          fontSize: 13,
                          fontWeight: FontWeight.w600,
                          color: AppColors.grey500,
                        ),
                      ),
              ),
            ),

            const SizedBox(width: 12),

            // Nama & jumlah barang
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    item.name,
                    style: TextStyle(
                      fontSize: 13,
                      fontWeight: FontWeight.w600,
                      color: item.isChecked ? themeColor : AppColors.grey800,
                      decoration: item.isChecked
                          ? TextDecoration.none
                          : TextDecoration.none,
                    ),
                  ),
                  const SizedBox(height: 3),
                  Row(
                    children: [
                      Icon(Icons.inventory_2_outlined,
                          size: 12, color: AppColors.grey400),
                      const SizedBox(width: 4),
                      Text(
                        '${item.quantity} ${item.unit}',
                        style: TextStyle(
                            fontSize: 11, color: AppColors.grey500),
                      ),
                    ],
                  ),
                ],
              ),
            ),

            // Status icon
            AnimatedSwitcher(
              duration: const Duration(milliseconds: 250),
              child: item.isChecked
                  ? Container(
                      key: const ValueKey('checked'),
                      padding: const EdgeInsets.symmetric(
                          horizontal: 10, vertical: 5),
                      decoration: BoxDecoration(
                        color: themeColor,
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: const Text(
                        'OK ✓',
                        style: TextStyle(
                            color: Colors.white,
                            fontSize: 11,
                            fontWeight: FontWeight.w700),
                      ),
                    )
                  : Container(
                      key: const ValueKey('unchecked'),
                      padding: const EdgeInsets.symmetric(
                          horizontal: 10, vertical: 5),
                      decoration: BoxDecoration(
                        color: AppColors.grey100,
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Text(
                        'Belum',
                        style: TextStyle(
                            color: AppColors.grey400,
                            fontSize: 11,
                            fontWeight: FontWeight.w600),
                      ),
                    ),
            ),
          ],
        ),
      ),
    );
  }
}

// ─── Bottom Confirm Bar ──────────────────────────────────────────────────────

class _BottomConfirmBar extends StatelessWidget {
  final bool allChecked;
  final bool isConfirming;
  final Color themeColor;
  final int checkedCount;
  final int totalCount;
  final VoidCallback onConfirm;

  const _BottomConfirmBar({
    required this.allChecked,
    required this.isConfirming,
    required this.themeColor,
    required this.checkedCount,
    required this.totalCount,
    required this.onConfirm,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.fromLTRB(20, 16, 20, 0),
      decoration: BoxDecoration(
        color: AppColors.surface,
        boxShadow: [
          BoxShadow(
            color: AppColors.grey900.withValues(alpha: 0.07),
            blurRadius: 20,
            offset: const Offset(0, -6),
          ),
        ],
      ),
      child: SafeArea(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            // Warning jika belum semua dicentang
            if (!allChecked)
              Container(
                margin: const EdgeInsets.only(bottom: 12),
                padding:
                    const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                decoration: BoxDecoration(
                  color: AppColors.warningLight,
                  borderRadius: BorderRadius.circular(10),
                  border: Border.all(
                      color: AppColors.warning.withValues(alpha: 0.3)),
                ),
                child: Row(
                  children: [
                    const Icon(Icons.warning_amber_rounded,
                        size: 16, color: AppColors.warning),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        'Centang semua ${totalCount - checkedCount} barang yang tersisa sebelum konfirmasi',
                        style: TextStyle(
                            fontSize: 11,
                            color: AppColors.warning,
                            fontWeight: FontWeight.w500),
                      ),
                    ),
                  ],
                ),
              ),

            // Confirm button
            SizedBox(
              width: double.infinity,
              height: 54,
              child: AnimatedContainer(
                duration: const Duration(milliseconds: 300),
                child: ElevatedButton(
                  onPressed: allChecked && !isConfirming ? onConfirm : null,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: themeColor,
                    foregroundColor: Colors.white,
                    disabledBackgroundColor: AppColors.grey200,
                    disabledForegroundColor: AppColors.grey400,
                    shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(16)),
                    elevation: 0,
                  ),
                  child: isConfirming
                      ? const SizedBox(
                          width: 22,
                          height: 22,
                          child: CircularProgressIndicator(
                              strokeWidth: 2.5, color: Colors.white),
                        )
                      : Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(
                              allChecked
                                  ? Icons.verified_rounded
                                  : Icons.lock_outline_rounded,
                              size: 18,
                            ),
                            const SizedBox(width: 8),
                            Text(
                              allChecked
                                  ? 'Konfirmasi — Semua Barang Lengkap'
                                  : 'Centang Semua Barang Terlebih Dahulu',
                              style: const TextStyle(
                                  fontSize: 13, fontWeight: FontWeight.w700),
                            ),
                          ],
                        ),
                ),
              ),
            ),
            const SizedBox(height: 8),
          ],
        ),
      ),
    );
  }
}

// ─── Confirm Dialog ───────────────────────────────────────────────────────────

class _ConfirmDialog extends StatelessWidget {
  final String checklistType;
  final String taskTitle;

  const _ConfirmDialog({
    required this.checklistType,
    required this.taskTitle,
  });

  @override
  Widget build(BuildContext context) {
    final isDelivery = checklistType == 'delivery';
    return Dialog(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 64,
              height: 64,
              decoration: BoxDecoration(
                color: isDelivery
                    ? const Color(0xFFEFF6FF)
                    : AppColors.warningLight,
                borderRadius: BorderRadius.circular(18),
              ),
              child: Icon(
                isDelivery
                    ? Icons.local_shipping_outlined
                    : Icons.assignment_turned_in_outlined,
                size: 32,
                color: isDelivery ? AppColors.primary : AppColors.warning,
              ),
            ),
            const SizedBox(height: 16),
            Text(
              isDelivery ? 'Siap Berangkat?' : 'Konfirmasi Selesai?',
              style: const TextStyle(
                  fontSize: 17, fontWeight: FontWeight.w700),
            ),
            const SizedBox(height: 8),
            Text(
              isDelivery
                  ? 'Semua barang sudah Anda cek dan siap dikirim. Lanjutkan perjalanan?'
                  : 'Semua barang sudah Anda kembalikan. Tandai tugas ini selesai?',
              textAlign: TextAlign.center,
              style: TextStyle(
                  fontSize: 13,
                  color: AppColors.grey600,
                  height: 1.5),
            ),
            const SizedBox(height: 20),
            Row(
              children: [
                Expanded(
                  child: OutlinedButton(
                    onPressed: () => Navigator.of(context).pop(false),
                    style: OutlinedButton.styleFrom(
                      side: const BorderSide(color: AppColors.grey300),
                      shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12)),
                      padding: const EdgeInsets.symmetric(vertical: 12),
                    ),
                    child: const Text('Periksa Lagi',
                        style: TextStyle(
                            color: AppColors.grey600,
                            fontWeight: FontWeight.w600)),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: ElevatedButton(
                    onPressed: () => Navigator.of(context).pop(true),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: isDelivery
                          ? AppColors.primary
                          : AppColors.warning,
                      foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12)),
                      padding: const EdgeInsets.symmetric(vertical: 12),
                      elevation: 0,
                    ),
                    child: Text(
                      isDelivery ? 'Berangkat' : 'Selesai',
                      style: const TextStyle(fontWeight: FontWeight.w700),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
