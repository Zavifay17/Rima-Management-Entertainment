import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../core/models/task_model.dart';
import '../core/services/supabase_direct_service.dart';

class TaskProvider extends ChangeNotifier {
  List<TaskModel> _tasks = [];
  bool _isLoading = false;

  List<TaskModel> get tasks => _tasks;
  bool get isLoading => _isLoading;

  List<TaskModel> get pendingTasks =>
      _tasks.where((t) => t.status == 'pending').toList();

  List<TaskModel> get activeTasks => _tasks
      .where((t) =>
          t.status == 'accepted' ||
          t.status == 'pickup' ||
          t.status == 'on_the_way' ||
          t.status == 'arrived')
      .toList();

  List<TaskModel> get completedTasks =>
      _tasks.where((t) => t.status == 'done' || t.status == 'selesai' || t.status == 'cancelled').toList();

  TaskModel? getTaskById(String id) {
    try {
      return _tasks.firstWhere((t) => t.id == id);
    } catch (e) {
      return null;
    }
  }

  Future<void> loadTasks() async {
    _isLoading = true;
    notifyListeners();

    try {
      final prefs = await SharedPreferences.getInstance();
      final driverJson = prefs.getString('auth_driver');
      if (driverJson != null) {
        final driverData = jsonDecode(driverJson) as Map<String, dynamic>;
        final idDriver = driverData['id_driver'].toString();

        final list = await SupabaseDirectService.instance.getTasks(idDriver);
        _tasks = list.map((json) => TaskModel.fromJson(json)).toList();
      }
    } catch (e) {
      if (kDebugMode) {
        print('Error loading tasks: $e');
      }
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> _updateStatusOnServer(String taskId, String status, {String? notes, String? photoUrl}) async {
    return await SupabaseDirectService.instance.updateTaskStatus(
      taskId,
      status,
      notes: notes,
      photoUrl: photoUrl,
    );
  }

  Future<void> acceptTask(String taskId) async {
    final task = getTaskById(taskId);
    if (task != null && task.status == 'pending') {
      final success = await _updateStatusOnServer(taskId, 'accepted');
      if (success) {
        task.status = 'accepted';
        notifyListeners();
      }
    }
  }

  Future<void> updateTaskStatus(String taskId, String newStatus) async {
    final task = getTaskById(taskId);
    if (task != null) {
      final success = await _updateStatusOnServer(taskId, newStatus, notes: task.notes, photoUrl: task.photos.isNotEmpty ? task.photos.first : null);
      if (success) {
        task.status = newStatus;
        notifyListeners();
      }
    }
  }

  Future<void> addPhotoToTask(String taskId, String photoPath) async {
    final task = getTaskById(taskId);
    if (task != null) {
      task.photos = [...task.photos, photoPath];
      notifyListeners();
      await _updateStatusOnServer(taskId, task.status, notes: task.notes, photoUrl: photoPath);
    }
  }

  /// Centang / uncentang satu item barang berdasarkan index
  void toggleEquipmentCheck(String taskId, int index) {
    final task = getTaskById(taskId);
    if (task != null && index >= 0 && index < task.equipmentList.length) {
      task.equipmentList[index].isChecked = !task.equipmentList[index].isChecked;
      notifyListeners();
    }
  }

  /// Centang semua barang sekaligus (atau uncentang semua)
  void checkAllEquipment(String taskId, bool value) {
    final task = getTaskById(taskId);
    if (task != null) {
      for (final item in task.equipmentList) {
        item.isChecked = value;
      }
      notifyListeners();
    }
  }

  /// Tandai checklist pengiriman (delivery) sudah selesai dan update status
  Future<void> confirmDeliveryChecklist(String taskId) async {
    final task = getTaskById(taskId);
    if (task != null && task.allItemsChecked) {
      final success = await _updateStatusOnServer(taskId, 'pickup', notes: task.notes);
      if (success) {
        task.deliveryChecklistDone = true;
        task.status = 'pickup';
        notifyListeners();
      }
    }
  }

  /// Tandai checklist penjemputan (pickup) sudah selesai dan update status
  Future<void> confirmPickupChecklist(String taskId) async {
    final task = getTaskById(taskId);
    if (task != null && task.allItemsChecked) {
      final success = await _updateStatusOnServer(taskId, 'done', notes: task.notes);
      if (success) {
        task.pickupChecklistDone = true;
        task.status = 'done';
        notifyListeners();
      }
    }
  }

  /// Reset semua centang pada checklist (panggil sebelum membuka layar checklist)
  void resetChecklist(String taskId) {
    final task = getTaskById(taskId);
    if (task != null) {
      task.resetChecklist();
      notifyListeners();
    }
  }

  int get totalActiveToday {
    return activeTasks.length;
  }

  int get totalCompletedToday {
    final today = DateTime.now();
    return completedTasks.where((t) {
      return t.eventDate.day == today.day &&
          t.eventDate.month == today.month &&
          t.eventDate.year == today.year;
    }).length;
  }
}
