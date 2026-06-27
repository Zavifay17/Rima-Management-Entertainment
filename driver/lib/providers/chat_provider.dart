import 'package:flutter/foundation.dart';
import '../core/models/chat_model.dart';

class ChatProvider extends ChangeNotifier {
  List<ChatConversation> _conversations = [];
  bool _isLoading = false;

  List<ChatConversation> get conversations => _conversations;
  bool get isLoading => _isLoading;
  int get totalUnread =>
      _conversations.fold(0, (sum, c) => sum + c.unreadCount);

  Future<void> loadConversations() async {
    _isLoading = true;
    notifyListeners();
    await Future.delayed(const Duration(milliseconds: 600));
    _conversations = ChatConversation.getMockConversations();
    _isLoading = false;
    notifyListeners();
  }

  ChatConversation? getConversationById(String id) {
    try {
      return _conversations.firstWhere((c) => c.id == id);
    } catch (e) {
      return null;
    }
  }

  ChatConversation? getConversationByTaskId(String taskId) {
    try {
      return _conversations.firstWhere((c) => c.taskId == taskId);
    } catch (e) {
      return null;
    }
  }

  void sendMessage(String conversationId, String message) {
    final conv = getConversationById(conversationId);
    if (conv != null) {
      final newMsg = ChatMessage(
        id: 'M${DateTime.now().millisecondsSinceEpoch}',
        senderId: 'D001',
        senderName: 'Budi Santoso',
        message: message,
        timestamp: DateTime.now(),
        isFromDriver: true,
      );
      conv.messages.add(newMsg);
      notifyListeners();
    }
  }

  void markAsRead(String conversationId) {
    final conv = getConversationById(conversationId);
    if (conv != null) {
      conv.unreadCount = 0;
      notifyListeners();
    }
  }
}
