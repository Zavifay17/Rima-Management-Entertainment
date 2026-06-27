import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:provider/provider.dart';
import '../../core/constants/app_colors.dart';
import '../../core/constants/app_routes.dart';
import '../../core/utils/helpers.dart';
import '../../core/models/task_model.dart';
import '../../providers/task_provider.dart';

class TaskListScreen extends StatefulWidget {
  const TaskListScreen({super.key});

  @override
  State<TaskListScreen> createState() => _TaskListScreenState();
}

class _TaskListScreenState extends State<TaskListScreen>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 3, vsync: this);
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final taskProvider = context.watch<TaskProvider>();

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: AppColors.surface,
        elevation: 0,
        title: Text('Daftar Tugas', style: AppTextStyles.heading3),
        bottom: PreferredSize(
          preferredSize: const Size.fromHeight(52),
          child: Container(
            margin: const EdgeInsets.fromLTRB(20, 0, 20, 8),
            decoration: BoxDecoration(
              color: AppColors.grey100,
              borderRadius: BorderRadius.circular(12),
            ),
            child: TabBar(
              controller: _tabController,
              indicator: BoxDecoration(
                color: AppColors.primary,
                borderRadius: BorderRadius.circular(10),
              ),
              labelColor: Colors.white,
              unselectedLabelColor: AppColors.grey500,
              labelStyle: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
              ),
              unselectedLabelStyle: const TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w400,
              ),
              tabs: [
                Tab(
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Text('Aktif'),
                      if (taskProvider.activeTasks.isNotEmpty) ...[
                        const SizedBox(width: 4),
                        Container(
                          padding: const EdgeInsets.symmetric(
                              horizontal: 6, vertical: 1),
                          decoration: BoxDecoration(
                            color: Colors.orange.withOpacity(0.8),
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: Text(
                            taskProvider.activeTasks.length.toString(),
                            style: const TextStyle(
                                fontSize: 10, color: Colors.white),
                          ),
                        ),
                      ],
                    ],
                  ),
                ),
                const Tab(text: 'Baru'),
                const Tab(text: 'Selesai'),
              ],
            ),
          ),
        ),
      ),
      body: TabBarView(
        controller: _tabController,
        children: [
          _TaskTabContent(
            tasks: taskProvider.activeTasks,
            isLoading: taskProvider.isLoading,
            emptyMessage: 'Tidak ada tugas aktif',
          ),
          _TaskTabContent(
            tasks: taskProvider.pendingTasks,
            isLoading: taskProvider.isLoading,
            emptyMessage: 'Tidak ada tugas baru',
          ),
          _TaskTabContent(
            tasks: taskProvider.completedTasks,
            isLoading: taskProvider.isLoading,
            emptyMessage: 'Belum ada tugas selesai',
          ),
        ],
      ),
    );
  }
}

class _TaskTabContent extends StatelessWidget {
  final List<TaskModel> tasks;
  final bool isLoading;
  final String emptyMessage;

  const _TaskTabContent({
    required this.tasks,
    required this.isLoading,
    required this.emptyMessage,
  });

  @override
  Widget build(BuildContext context) {
    if (isLoading) {
      return const Center(child: CircularProgressIndicator());
    }

    if (tasks.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.inbox_outlined, size: 56, color: AppColors.grey200),
            const SizedBox(height: 16),
            Text(emptyMessage, style: AppTextStyles.bodyMedium),
          ],
        ),
      );
    }

    return RefreshIndicator(
      onRefresh: () => context.read<TaskProvider>().loadTasks(),
      color: AppColors.primary,
      child: ListView.builder(
        padding: const EdgeInsets.fromLTRB(20, 16, 20, 24),
        itemCount: tasks.length,
        itemBuilder: (context, index) {
          final task = tasks[index];
          return Padding(
            padding: const EdgeInsets.only(bottom: 12),
            child: TaskCard(task: task),
          ).animate(delay: (index * 80).ms).fadeIn().slideY(begin: 0.1);
        },
      ),
    );
  }
}

class TaskCard extends StatelessWidget {
  final TaskModel task;

  const TaskCard({super.key, required this.task});

  @override
  Widget build(BuildContext context) {
    final statusColor = _getStatusColor(task.status);

    return GestureDetector(
      onTap: () => Navigator.of(context)
          .pushNamed(AppRoutes.taskDetail, arguments: task.id),
      child: Container(
        decoration: AppDecorations.card,
        child: Column(
          children: [
            // Header
            Container(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      // Type badge
                      Container(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 8, vertical: 4),
                        decoration: BoxDecoration(
                          color: task.type == 'delivery'
                              ? AppColors.primary.withOpacity(0.1)
                              : AppColors.warningLight,
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Icon(
                              task.type == 'delivery'
                                  ? Icons.local_shipping_outlined
                                  : Icons.keyboard_return_rounded,
                              size: 12,
                              color: task.type == 'delivery'
                                  ? AppColors.primary
                                  : AppColors.warning,
                            ),
                            const SizedBox(width: 4),
                            Text(
                              task.type == 'delivery' ? 'Pengiriman' : 'Pick Up',
                              style: TextStyle(
                                fontSize: 11,
                                fontWeight: FontWeight.w600,
                                color: task.type == 'delivery'
                                    ? AppColors.primary
                                    : AppColors.warning,
                              ),
                            ),
                          ],
                        ),
                      ),
                      const Spacer(),
                      // Status badge
                      Container(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 10, vertical: 4),
                        decoration: BoxDecoration(
                          color: statusColor.withOpacity(0.1),
                          borderRadius: BorderRadius.circular(20),
                        ),
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Container(
                              width: 6,
                              height: 6,
                              decoration: BoxDecoration(
                                color: statusColor,
                                shape: BoxShape.circle,
                              ),
                            ),
                            const SizedBox(width: 5),
                            Text(
                              StatusHelper.getTaskStatusLabel(task.status),
                              style: TextStyle(
                                color: statusColor,
                                fontSize: 11,
                                fontWeight: FontWeight.w600,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),

                  const SizedBox(height: 12),

                  Text(
                    task.title,
                    style: AppTextStyles.labelLarge,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),

                  const SizedBox(height: 8),

                  // Customer
                  _InfoRow(
                    icon: Icons.person_outline,
                    text: task.customerName,
                  ),
                  const SizedBox(height: 4),
                  // Address
                  _InfoRow(
                    icon: Icons.location_on_outlined,
                    text: task.deliveryAddress,
                    maxLines: 2,
                  ),
                  const SizedBox(height: 4),
                  // Time
                  _InfoRow(
                    icon: Icons.schedule_rounded,
                    text: DateFormatter.formatDateTime(task.dueTime),
                  ),
                ],
              ),
            ),

            // Footer
            Container(
              padding:
                  const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
              decoration: BoxDecoration(
                color: AppColors.grey50,
                borderRadius: const BorderRadius.vertical(
                    bottom: Radius.circular(16)),
              ),
              child: Row(
                children: [
                  // Distance
                  const Icon(Icons.social_distance_outlined,
                      size: 14, color: AppColors.grey400),
                  const SizedBox(width: 4),
                  Text('${task.distanceKm} km',
                      style: AppTextStyles.bodySmall),
                  const SizedBox(width: 16),
                  // Equipment count
                  const Icon(Icons.inventory_2_outlined,
                      size: 14, color: AppColors.grey400),
                  const SizedBox(width: 4),
                  Text('${task.equipmentList.length} item',
                      style: AppTextStyles.bodySmall),

                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'pending':
        return AppColors.statusPending;
      case 'accepted':
        return AppColors.statusActive;
      case 'pickup':
        return AppColors.warning;
      case 'on_the_way':
        return AppColors.info;
      case 'arrived':
        return AppColors.success;
      case 'done':
        return AppColors.statusCompleted;
      case 'cancelled':
        return AppColors.statusCancelled;
      default:
        return AppColors.grey500;
    }
  }
}

class _InfoRow extends StatelessWidget {
  final IconData icon;
  final String text;
  final int maxLines;

  const _InfoRow({
    required this.icon,
    required this.text,
    this.maxLines = 1,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Icon(icon, size: 14, color: AppColors.grey400),
        const SizedBox(width: 6),
        Expanded(
          child: Text(
            text,
            style: AppTextStyles.bodySmall,
            maxLines: maxLines,
            overflow: TextOverflow.ellipsis,
          ),
        ),
      ],
    );
  }
}
