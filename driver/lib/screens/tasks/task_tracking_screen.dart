import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:provider/provider.dart';
import '../../core/constants/app_colors.dart';
import '../../core/constants/app_routes.dart';
import '../../core/utils/helpers.dart';
import '../../providers/task_provider.dart';
import 'item_checklist_screen.dart';

class TaskTrackingScreen extends StatefulWidget {
  const TaskTrackingScreen({super.key});

  @override
  State<TaskTrackingScreen> createState() => _TaskTrackingScreenState();
}

class _TaskTrackingScreenState extends State<TaskTrackingScreen> {
  bool _isUpdating = false;

  final List<_StepData> _steps = [
    _StepData(
      status: 'accepted',
      label: 'Tugas Diterima',
      description: 'Tugas telah diterima oleh driver',
      icon: Icons.check_circle_outline_rounded,
    ),
    _StepData(
      status: 'pickup',
      label: 'Alat Diambil',
      description: 'Alat event telah diambil dari gudang',
      icon: Icons.inventory_2_outlined,
    ),
    _StepData(
      status: 'on_the_way',
      label: 'Dalam Perjalanan',
      description: 'Driver sedang menuju lokasi event',
      icon: Icons.local_shipping_outlined,
    ),
    _StepData(
      status: 'arrived',
      label: 'Tiba di Lokasi',
      description: 'Driver telah tiba di lokasi event',
      icon: Icons.location_on_outlined,
    ),
    _StepData(
      status: 'done',
      label: 'Tugas Selesai',
      description: 'Pengiriman berhasil diselesaikan',
      icon: Icons.celebration_outlined,
    ),
  ];

  Future<void> _updateStatus(String taskId, String currentStatus) async {
    // Saat akan "Ambil Alat" (accepted → pickup): buka checklist dulu
    if (currentStatus == 'accepted') {
      context.read<TaskProvider>().resetChecklist(taskId);
      final result = await Navigator.of(context).pushNamed(
        AppRoutes.itemChecklist,
        arguments: ChecklistArgs(taskId: taskId, checklistType: 'delivery'),
      );
      // result == true berarti checklist sudah dikonfirmasi & status sudah diupdate di provider
      if (result == true) setState(() {});
      return;
    }

    // Saat akan "Selesaikan Tugas" (arrived → done): buka checklist dulu
    if (currentStatus == 'arrived') {
      context.read<TaskProvider>().resetChecklist(taskId);
      final result = await Navigator.of(context).pushNamed(
        AppRoutes.itemChecklist,
        arguments: ChecklistArgs(taskId: taskId, checklistType: 'pickup'),
      );
      if (result == true) setState(() {});
      return;
    }

    // Status lainnya (pickup → on_the_way, on_the_way → arrived) langsung update
    final nextStatus = StatusHelper.getNextStatus(currentStatus);
    if (nextStatus == currentStatus) return;

    setState(() => _isUpdating = true);
    await Future.delayed(const Duration(milliseconds: 800));

    if (!mounted) return;

    final taskProvider = context.read<TaskProvider>();
    await taskProvider.updateTaskStatus(taskId, nextStatus);

    if (mounted) setState(() => _isUpdating = false);
  }

  @override
  Widget build(BuildContext context) {
    final taskId = ModalRoute.of(context)!.settings.arguments as String;
    final taskProvider = context.watch<TaskProvider>();
    final task = taskProvider.getTaskById(taskId);

    if (task == null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Tracking')),
        body: const Center(child: Text('Tugas tidak ditemukan')),
      );
    }

    final currentStep = StatusHelper.getStatusStep(task.status);
    final isDone = task.status == 'done';
    final nextStatusLabel = StatusHelper.getNextStatusLabel(task.status);

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: AppColors.surface,
        elevation: 0,
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Tracking Pengiriman', style: AppTextStyles.heading3),
            Text(
              task.id,
              style:
                  AppTextStyles.caption.copyWith(color: AppColors.grey400),
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
      body: Column(
        children: [
          Expanded(
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Task Header
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: AppDecorations.card,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(task.title,
                            style: AppTextStyles.labelLarge,
                            maxLines: 2,
                            overflow: TextOverflow.ellipsis),
                        const SizedBox(height: 6),
                        Row(
                          children: [
                            const Icon(Icons.person_outline,
                                size: 14, color: AppColors.grey400),
                            const SizedBox(width: 4),
                            Text(task.customerName,
                                style: AppTextStyles.bodySmall),
                            const Spacer(),
                            const Icon(Icons.schedule_rounded,
                                size: 14, color: AppColors.grey400),
                            const SizedBox(width: 4),
                            Text(
                                DateFormatter.formatDateTime(task.dueTime),
                                style: AppTextStyles.bodySmall),
                          ],
                        ),
                      ],
                    ),
                  ).animate().fadeIn(),

                  const SizedBox(height: 24),

                  Text('Status Pengiriman', style: AppTextStyles.heading3),
                  const SizedBox(height: 16),

                  // Stepper
                  ...(_steps.asMap().entries.map((entry) {
                    final i = entry.key;
                    final step = entry.value;
                    final stepIndex = i + 1;
                    final isCompleted = currentStep >= stepIndex;
                    final isCurrent = currentStep == stepIndex - 1 &&
                        !isDone &&
                        i != 4;
                    final isActive = isDone && i == 4;

                    return _StepItem(
                      step: step,
                      isCompleted: isCompleted,
                      isCurrent: isCurrent || isActive,
                      isLast: i == _steps.length - 1,
                    ).animate(delay: (i * 100).ms).fadeIn().slideX(begin: -0.1);
                  })),
                ],
              ),
            ),
          ),

          // Bottom Action
          if (!isDone)
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: AppColors.surface,
                boxShadow: [
                  BoxShadow(
                    color: AppColors.grey900.withOpacity(0.06),
                    blurRadius: 16,
                    offset: const Offset(0, -4),
                  ),
                ],
              ),
              child: SafeArea(
                child: Column(
                  children: [
                    Row(
                      children: [
                        const Icon(Icons.info_outline,
                            size: 14, color: AppColors.grey400),
                        const SizedBox(width: 6),
                        Expanded(
                          child: Text(
                            'Update status sesuai dengan kondisi terkini',
                            style: AppTextStyles.caption,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),
                    SizedBox(
                      width: double.infinity,
                      height: 54,
                      child: ElevatedButton(
                        onPressed: _isUpdating
                            ? null
                            : () => _updateStatus(task.id, task.status),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: AppColors.primary,
                          foregroundColor: Colors.white,
                          disabledBackgroundColor:
                              AppColors.primary.withOpacity(0.5),
                          shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(16)),
                          elevation: 0,
                        ),
                        child: _isUpdating
                            ? const SizedBox(
                                width: 22,
                                height: 22,
                                child: CircularProgressIndicator(
                                  strokeWidth: 2.5,
                                  color: Colors.white,
                                ),
                              )
                            : Text(
                                nextStatusLabel.isEmpty
                                    ? 'Selesai'
                                    : nextStatusLabel,
                                style: const TextStyle(
                                    fontSize: 15,
                                    fontWeight: FontWeight.w700),
                              ),
                      ),
                    ),
                  ],
                ),
              ),
            )
          else
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: AppColors.successLight,
                boxShadow: [
                  BoxShadow(
                    color: AppColors.grey900.withOpacity(0.06),
                    blurRadius: 16,
                    offset: const Offset(0, -4),
                  ),
                ],
              ),
              child: SafeArea(
                child: Row(
                  children: [
                    Container(
                      width: 44,
                      height: 44,
                      decoration: BoxDecoration(
                        color: AppColors.success,
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: const Icon(Icons.check_rounded,
                          color: Colors.white, size: 24),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Tugas Selesai! 🎉',
                              style: AppTextStyles.labelLarge.copyWith(
                                  color: AppColors.success)),
                          Text(
                              'Anda telah menyelesaikan tugas ini',
                              style: AppTextStyles.bodySmall.copyWith(
                                  color: AppColors.success)),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),
        ],
      ),
    );
  }
}

class _StepData {
  final String status;
  final String label;
  final String description;
  final IconData icon;

  const _StepData({
    required this.status,
    required this.label,
    required this.description,
    required this.icon,
  });
}

class _StepItem extends StatelessWidget {
  final _StepData step;
  final bool isCompleted;
  final bool isCurrent;
  final bool isLast;

  const _StepItem({
    required this.step,
    required this.isCompleted,
    required this.isCurrent,
    required this.isLast,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Timeline column with fixed width
        SizedBox(
          width: 40,
          child: Column(
            children: [
              Container(
                width: 40,
                height: 40,
                decoration: BoxDecoration(
                  color: isCompleted
                      ? AppColors.success
                      : isCurrent
                          ? AppColors.primary
                          : AppColors.grey100,
                  shape: BoxShape.circle,
                  border: Border.all(
                    color: isCompleted
                        ? AppColors.success
                        : isCurrent
                            ? AppColors.primaryLight
                            : AppColors.grey200,
                    width: 2,
                  ),
                ),
                child: Icon(
                  isCompleted ? Icons.check_rounded : step.icon,
                  size: 18,
                  color: isCompleted || isCurrent
                      ? Colors.white
                      : AppColors.grey300,
                ),
              ),
              if (!isLast)
                Container(
                  width: 2,
                  height: isCurrent ? 72 : 48,
                  margin: const EdgeInsets.symmetric(vertical: 4),
                  decoration: BoxDecoration(
                    color: isCompleted ? AppColors.success : AppColors.grey200,
                    borderRadius: BorderRadius.circular(1),
                  ),
                ),
            ],
          ),
        ),

        const SizedBox(width: 16),

        // Content
        Expanded(
          child: Padding(
            padding: EdgeInsets.only(bottom: isLast ? 0 : 12, top: 8),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  step.label,
                  style: AppTextStyles.labelLarge.copyWith(
                    color: isCompleted
                        ? AppColors.success
                        : isCurrent
                            ? AppColors.primary
                            : AppColors.grey400,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  step.description,
                  style: AppTextStyles.bodySmall.copyWith(
                    color: isCompleted || isCurrent
                        ? AppColors.grey600
                        : AppColors.grey300,
                  ),
                ),
                if (isCurrent) ...[
                  const SizedBox(height: 8),
                  Container(
                    padding: const EdgeInsets.symmetric(
                        horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: AppColors.primary.withOpacity(0.1),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Container(
                          width: 6,
                          height: 6,
                          decoration: const BoxDecoration(
                            color: AppColors.primary,
                            shape: BoxShape.circle,
                          ),
                        ),
                        const SizedBox(width: 6),
                        const Text(
                          'Tahap Saat Ini',
                          style: TextStyle(
                            fontSize: 11,
                            color: AppColors.primary,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ],
            ),
          ),
        ),
      ],
    );
  }
}
