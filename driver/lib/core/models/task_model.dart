class EquipmentItem {
  final String name;
  final int quantity;
  final String unit;
  bool isChecked;

  EquipmentItem({
    required this.name,
    required this.quantity,
    required this.unit,
    this.isChecked = false,
  });

  factory EquipmentItem.fromJson(Map<String, dynamic> json) {
    return EquipmentItem(
      name: json['nama_barang'] ?? json['name'] ?? '',
      quantity: json['kuantitas'] ?? json['quantity'] ?? 0,
      unit: json['satuan'] ?? json['unit'] ?? 'unit',
      isChecked: json['isChecked'] ?? false,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'nama_barang': name,
      'kuantitas': quantity,
      'satuan': unit,
      'isChecked': isChecked,
    };
  }
}

class TaskModel {
  final String id;
  final String title;
  final String customerName;
  final String customerPhone;
  final String pickupAddress;
  final String deliveryAddress;
  final DateTime eventDate;
  final DateTime dueTime;
  final List<EquipmentItem> equipmentList;
  String status;
  final String type; // 'delivery' or 'pickup'
  final double fee;
  final String notes;
  List<String> photos;
  final double distanceKm;
  final String customerId;
  bool deliveryChecklistDone;
  bool pickupChecklistDone;

  TaskModel({
    required this.id,
    required this.title,
    required this.customerName,
    required this.customerPhone,
    required this.pickupAddress,
    required this.deliveryAddress,
    required this.eventDate,
    required this.dueTime,
    required this.equipmentList,
    required this.status,
    required this.type,
    required this.fee,
    this.notes = '',
    this.photos = const [],
    required this.distanceKm,
    required this.customerId,
    this.deliveryChecklistDone = false,
    this.pickupChecklistDone = false,
  });

  factory TaskModel.fromJson(Map<String, dynamic> json) {
    final order = json['order'] as Map<String, dynamic>? ?? {};
    final itemsList = order['items'] as List? ?? [];
    final items = itemsList.map((item) => EquipmentItem.fromJson(item as Map<String, dynamic>)).toList();
    
    final tglJadwalStr = json['tgl_jadwal'] ?? DateTime.now().toIso8601String();
    final eventDate = DateTime.tryParse(tglJadwalStr) ?? DateTime.now();

    final orderId = order['id_order']?.toString() ?? '';
    final typeStr = json['tipe_tugas'] == 'Antar' ? 'delivery' : 'pickup';
    final customer = order['pelanggan'] as Map<String, dynamic>? ?? {};

    // Determine addresses based on task type
    final whAddress = 'Gudang RME Logistics, Jl. Raya Bogor No. 12, Jakarta Timur';
    final custAddress = 'Lokasi Event: ${customer['nama'] ?? 'Pelanggan'} (Order #$orderId)';
    
    final pickupAddress = typeStr == 'delivery' ? whAddress : custAddress;
    final deliveryAddress = typeStr == 'delivery' ? custAddress : whAddress;

    // Map photo url
    final photosList = <String>[];
    if (json['bukti_foto_url'] != null && json['bukti_foto_url'].toString().isNotEmpty) {
      photosList.add(json['bukti_foto_url'].toString());
    }

    final isDone = json['status_tugas'] == 'done' || json['status_tugas'] == 'selesai';
    
    return TaskModel(
      id: (json['id_pengiriman'] ?? json['id'] ?? '').toString(),
      title: '${json['tipe_tugas'] ?? 'Pengantaran'} Alat - Order #$orderId',
      customerName: customer['nama'] ?? '',
      customerPhone: customer['no_hp'] ?? '',
      pickupAddress: pickupAddress,
      deliveryAddress: deliveryAddress,
      eventDate: eventDate,
      dueTime: eventDate, // Using same date as dueTime
      equipmentList: items,
      status: json['status_tugas'] ?? 'pending',
      type: typeStr,
      fee: typeStr == 'delivery' ? 300000.0 : 250000.0,
      notes: json['catatan_kondisi_alat'] ?? '',
      photos: photosList,
      distanceKm: 12.5,
      customerId: (customer['id_pelanggan'] ?? '').toString(),
      deliveryChecklistDone: isDone || json['status_tugas'] == 'pickup' || json['status_tugas'] == 'on_the_way' || json['status_tugas'] == 'arrived',
      pickupChecklistDone: isDone,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id_pengiriman': id,
      'tipe_tugas': type == 'delivery' ? 'Antar' : 'Jemput',
      'status_tugas': status,
      'catatan_kondisi_alat': notes,
      'bukti_foto_url': photos.isNotEmpty ? photos.first : null,
      'tgl_jadwal': eventDate.toIso8601String().split('T')[0],
      'order': {
        'id_order': title.split('#').last,
        'pelanggan': {
          'nama': customerName,
          'no_hp': customerPhone,
        },
        'items': equipmentList.map((e) => e.toJson()).toList(),
      }
    };
  }

  /// Apakah semua barang di checklist sudah dicentang
  bool get allItemsChecked =>
      equipmentList.isNotEmpty && equipmentList.every((e) => e.isChecked);

  /// Jumlah barang yang sudah dicentang
  int get checkedCount => equipmentList.where((e) => e.isChecked).length;

  /// Total barang dalam daftar
  int get totalItems => equipmentList.length;

  /// Reset semua centang (dipanggil sebelum membuka checklist baru)
  void resetChecklist() {
    for (final item in equipmentList) {
      item.isChecked = false;
    }
  }

  static List<TaskModel> getMockTasks() {
    final now = DateTime.now();
    return [
      TaskModel(
        id: 'T001',
        title: 'Pengiriman Sound System - Wedding Aditya',
        customerName: 'Aditya Raharjo',
        customerPhone: '081298765432',
        pickupAddress: 'Gudang Event Pro, Jl. Raya Bogor No. 12, Jakarta Timur',
        deliveryAddress:
            'Gedung Serbaguna Cempaka, Jl. Cempaka Putih Raya No. 45, Jakarta Pusat',
        eventDate: now.add(const Duration(hours: 3)),
        dueTime: now.add(const Duration(hours: 2)),
        equipmentList: [
          EquipmentItem(name: 'Speaker JBL PRX915', quantity: 4, unit: 'unit'),
          EquipmentItem(name: 'Amplifier Crown XTi', quantity: 2, unit: 'unit'),
          EquipmentItem(
              name: 'Mixer Soundcraft Ui24R', quantity: 1, unit: 'unit'),
          EquipmentItem(name: 'Kabel XLR 10m', quantity: 10, unit: 'buah'),
          EquipmentItem(name: 'Stand Speaker', quantity: 4, unit: 'buah'),
        ],
        status: 'accepted',
        type: 'delivery',
        fee: 350000,
        notes:
            'Pastikan semua kabel terbungkus rapi. Parkir di basement gedung.',
        distanceKm: 12.5,
        customerId: 'C001',
      ),
      TaskModel(
        id: 'T002',
        title: 'Pengambilan Tenda & Dekorasi - Event Budi',
        customerName: 'Budi Hartono',
        customerPhone: '082198765432',
        pickupAddress:
            'Taman Ismail Marzuki, Jl. Cikini Raya No. 73, Jakarta Pusat',
        deliveryAddress: 'Gudang Event Pro, Jl. Raya Bogor No. 12, Jakarta Timur',
        eventDate: now.subtract(const Duration(hours: 2)),
        dueTime: now.add(const Duration(hours: 1)),
        equipmentList: [
          EquipmentItem(name: 'Tenda Pesta 5x10m', quantity: 2, unit: 'unit'),
          EquipmentItem(name: 'Kursi Plastik', quantity: 100, unit: 'buah'),
          EquipmentItem(name: 'Meja Bundar', quantity: 10, unit: 'buah'),
          EquipmentItem(
              name: 'Backdrop Dekorasi', quantity: 1, unit: 'set'),
        ],
        status: 'on_the_way',
        type: 'pickup',
        fee: 275000,
        notes: 'Hubungi security TIM untuk akses keluar.',
        distanceKm: 8.3,
        customerId: 'C002',
      ),
      TaskModel(
        id: 'T003',
        title: 'Pengiriman Lighting - Concert Indie Fest',
        customerName: 'Sari Dewi',
        customerPhone: '083198765432',
        pickupAddress: 'Gudang Event Pro, Jl. Raya Bogor No. 12, Jakarta Timur',
        deliveryAddress:
            'Lapangan Banteng, Jl. Medan Merdeka Tim., Jakarta Pusat',
        eventDate: now.add(const Duration(days: 1)),
        dueTime: now.add(const Duration(hours: 6)),
        equipmentList: [
          EquipmentItem(name: 'Moving Head LED', quantity: 8, unit: 'unit'),
          EquipmentItem(name: 'Par LED RGB', quantity: 20, unit: 'unit'),
          EquipmentItem(name: 'Fog Machine', quantity: 2, unit: 'unit'),
          EquipmentItem(name: 'Truss 3m', quantity: 6, unit: 'batang'),
          EquipmentItem(name: 'DMX Controller', quantity: 1, unit: 'unit'),
        ],
        status: 'pending',
        type: 'delivery',
        fee: 420000,
        notes: 'Event besar, koordinasi dengan tim produksi setiba di lokasi.',
        distanceKm: 15.7,
        customerId: 'C003',
      ),
      TaskModel(
        id: 'T004',
        title: 'Pengiriman Kursi & Meja - Seminar IT',
        customerName: 'PT. TechCorp Indonesia',
        customerPhone: '02198765432',
        pickupAddress: 'Gudang Event Pro, Jl. Raya Bogor No. 12, Jakarta Timur',
        deliveryAddress:
            'Hotel Grand Hyatt Jakarta, Jl. M.H. Thamrin No. 28, Jakarta Pusat',
        eventDate: now.add(const Duration(hours: 5)),
        dueTime: now.add(const Duration(hours: 4)),
        equipmentList: [
          EquipmentItem(name: 'Kursi Eksekutif', quantity: 50, unit: 'buah'),
          EquipmentItem(name: 'Meja Persegi Panjang', quantity: 5, unit: 'buah'),
          EquipmentItem(name: 'Podium', quantity: 1, unit: 'unit'),
          EquipmentItem(name: 'Proyektor Epson', quantity: 2, unit: 'unit'),
        ],
        status: 'done',
        type: 'delivery',
        fee: 300000,
        notes: '',
        distanceKm: 20.1,
        customerId: 'C004',
        deliveryChecklistDone: true,
        pickupChecklistDone: true,
      ),
      TaskModel(
        id: 'T005',
        title: 'Pengambilan Sound System - Wedding Raisa',
        customerName: 'Raisa Maharani',
        customerPhone: '084198765432',
        pickupAddress:
            'Ballroom Ritz Carlton Pacific Place, Jl. Jend. Sudirman No. 52-53',
        deliveryAddress: 'Gudang Event Pro, Jl. Raya Bogor No. 12, Jakarta Timur',
        eventDate: now.subtract(const Duration(days: 1)),
        dueTime: now.add(const Duration(hours: 8)),
        equipmentList: [
          EquipmentItem(name: 'Speaker Line Array', quantity: 6, unit: 'unit'),
          EquipmentItem(name: 'Subwoofer', quantity: 4, unit: 'unit'),
          EquipmentItem(name: 'Mixer Digital', quantity: 1, unit: 'unit'),
        ],
        status: 'done',
        type: 'pickup',
        fee: 380000,
        notes: 'Koordinasi dengan hotel concierge untuk akses loading area.',
        distanceKm: 25.4,
        customerId: 'C005',
        deliveryChecklistDone: true,
        pickupChecklistDone: true,
      ),
    ];
  }
}
