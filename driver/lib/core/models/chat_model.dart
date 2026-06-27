class ChatMessage {
  final String id;
  final String senderId;
  final String senderName;
  final String message;
  final DateTime timestamp;
  final bool isFromDriver;
  final String? imageUrl;

  ChatMessage({
    required this.id,
    required this.senderId,
    required this.senderName,
    required this.message,
    required this.timestamp,
    required this.isFromDriver,
    this.imageUrl,
  });
}

class ChatConversation {
  final String id;
  final String taskId;
  final String customerId;
  final String customerName;
  final String customerPhone;
  final String taskTitle;
  List<ChatMessage> messages;
  int unreadCount;

  ChatConversation({
    required this.id,
    required this.taskId,
    required this.customerId,
    required this.customerName,
    required this.customerPhone,
    required this.taskTitle,
    required this.messages,
    this.unreadCount = 0,
  });

  ChatMessage? get lastMessage =>
      messages.isNotEmpty ? messages.last : null;

  static List<ChatConversation> getMockConversations() {
    final now = DateTime.now();
    return [
      ChatConversation(
        id: 'CH001',
        taskId: 'T001',
        customerId: 'C001',
        customerName: 'Aditya Raharjo',
        customerPhone: '081298765432',
        taskTitle: 'Pengiriman Sound System - Wedding Aditya',
        unreadCount: 2,
        messages: [
          ChatMessage(
            id: 'M001',
            senderId: 'C001',
            senderName: 'Aditya Raharjo',
            message: 'Halo, bagaimana kondisi peralatan yang akan dikirim?',
            timestamp: now.subtract(const Duration(hours: 2)),
            isFromDriver: false,
          ),
          ChatMessage(
            id: 'M002',
            senderId: 'D001',
            senderName: 'Budi Santoso',
            message:
                'Semua peralatan sudah siap Pak Aditya, kami akan segera berangkat.',
            timestamp: now.subtract(const Duration(hours: 1, minutes: 50)),
            isFromDriver: true,
          ),
          ChatMessage(
            id: 'M003',
            senderId: 'C001',
            senderName: 'Aditya Raharjo',
            message: 'Oke, parkir di basement ya. Ada akses langsung ke ballroom.',
            timestamp: now.subtract(const Duration(hours: 1, minutes: 30)),
            isFromDriver: false,
          ),
          ChatMessage(
            id: 'M004',
            senderId: 'C001',
            senderName: 'Aditya Raharjo',
            message: 'Estimasi tiba jam berapa?',
            timestamp: now.subtract(const Duration(minutes: 15)),
            isFromDriver: false,
          ),
          ChatMessage(
            id: 'M005',
            senderId: 'C001',
            senderName: 'Aditya Raharjo',
            message: 'Sudah di jalan belum pak?',
            timestamp: now.subtract(const Duration(minutes: 5)),
            isFromDriver: false,
          ),
        ],
      ),
      ChatConversation(
        id: 'CH002',
        taskId: 'T002',
        customerId: 'C002',
        customerName: 'Budi Hartono',
        customerPhone: '082198765432',
        taskTitle: 'Pengambilan Tenda & Dekorasi',
        unreadCount: 0,
        messages: [
          ChatMessage(
            id: 'M010',
            senderId: 'C002',
            senderName: 'Budi Hartono',
            message:
                'Selamat pagi, bisa tolong hubungi security TIM saat tiba?',
            timestamp: now.subtract(const Duration(hours: 3)),
            isFromDriver: false,
          ),
          ChatMessage(
            id: 'M011',
            senderId: 'D001',
            senderName: 'Budi Santoso',
            message: 'Siap Pak Budi, akan kami koordinasikan.',
            timestamp: now.subtract(const Duration(hours: 2, minutes: 30)),
            isFromDriver: true,
          ),
          ChatMessage(
            id: 'M012',
            senderId: 'D001',
            senderName: 'Budi Santoso',
            message: 'Kami sudah dalam perjalanan, sekitar 15 menit lagi.',
            timestamp: now.subtract(const Duration(minutes: 30)),
            isFromDriver: true,
          ),
        ],
      ),
      ChatConversation(
        id: 'CH003',
        taskId: 'T003',
        customerId: 'C003',
        customerName: 'Sari Dewi',
        customerPhone: '083198765432',
        taskTitle: 'Pengiriman Lighting - Concert Indie Fest',
        unreadCount: 1,
        messages: [
          ChatMessage(
            id: 'M020',
            senderId: 'C003',
            senderName: 'Sari Dewi',
            message:
                'Halo driver, untuk tugas besok mohon datang lebih awal ya. Jadwal setup ketat.',
            timestamp: now.subtract(const Duration(minutes: 45)),
            isFromDriver: false,
          ),
        ],
      ),
    ];
  }
}
