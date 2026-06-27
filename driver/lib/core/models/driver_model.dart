class DriverModel {
  final String id;
  final String name;
  final String email;
  final String phone;
  final String vehicleType;
  final String vehiclePlate;
  final String photoUrl;
  final int totalTrips;
  final bool isOnline;
  final double balance;
  final String joinDate;

  DriverModel({
    required this.id,
    required this.name,
    required this.email,
    required this.phone,
    required this.vehicleType,
    required this.vehiclePlate,
    required this.photoUrl,
    required this.totalTrips,
    required this.isOnline,
    required this.balance,
    required this.joinDate,
  });

  DriverModel copyWith({
    String? name,
    String? phone,
    String? photoUrl,
    bool? isOnline,
    double? balance,
    int? totalTrips,
  }) {
    return DriverModel(
      id: id,
      name: name ?? this.name,
      email: email,
      phone: phone ?? this.phone,
      vehicleType: vehicleType,
      vehiclePlate: vehiclePlate,
      photoUrl: photoUrl ?? this.photoUrl,
      totalTrips: totalTrips ?? this.totalTrips,
      isOnline: isOnline ?? this.isOnline,
      balance: balance ?? this.balance,
      joinDate: joinDate,
    );
  }

  factory DriverModel.fromJson(Map<String, dynamic> json) {
    return DriverModel(
      id: (json['id_driver'] ?? json['id'] ?? '').toString(),
      name: json['nama'] ?? json['name'] ?? '',
      email: json['username'] ?? json['email'] ?? '',
      phone: json['no_hp'] ?? json['phone'] ?? '',
      vehicleType: json['vehicleType'] ?? 'Pickup Truck',
      vehiclePlate: json['vehiclePlate'] ?? 'B 1234 XYZ',
      photoUrl: json['photoUrl'] ?? json['foto_url'] ?? '',
      totalTrips: json['totalTrips'] ?? 0,
      isOnline: json['isOnline'] ?? true,
      balance: (json['balance'] ?? 0.0).toDouble(),
      joinDate: json['joinDate'] ?? 'Juni 2026',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id_driver': id,
      'nama': name,
      'username': email,
      'no_hp': phone,
      'vehicleType': vehicleType,
      'vehiclePlate': vehiclePlate,
      'photoUrl': photoUrl,
      'foto_url': photoUrl,
      'totalTrips': totalTrips,
      'isOnline': isOnline,
      'balance': balance,
      'joinDate': joinDate,
    };
  }

  static DriverModel get mockDriver => DriverModel(
        id: 'D001',
        name: 'Budi Santoso',
        email: 'driver@eventdriver.com',
        phone: '081234567890',
        vehicleType: 'Pickup Truck',
        vehiclePlate: 'B 1234 XYZ',
        photoUrl: '',
        totalTrips: 127,
        isOnline: true,
        balance: 2350000,
        joinDate: 'Januari 2024',
      );
}
