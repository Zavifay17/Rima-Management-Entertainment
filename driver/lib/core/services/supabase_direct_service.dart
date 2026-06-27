import 'package:postgres/postgres.dart';
import 'package:bcrypt/bcrypt.dart';
import 'package:flutter/foundation.dart';

class SupabaseDirectService {
  // Centralized Database Credentials
  static const String _host = 'aws-1-ap-southeast-1.pooler.supabase.com';
  static const int _port = 6543;
  static const String _database = 'postgres';
  static const String _username = 'postgres.lurtyqgtfjdokoytixzi';
  static const String _password = 'rimaentertaiment2004';

  static final SupabaseDirectService instance = SupabaseDirectService._internal();

  SupabaseDirectService._internal();

  /// Helper to open a connection with SSL required
  Future<Connection> _connect() async {
    return await Connection.open(
      Endpoint(
        host: _host,
        database: _database,
        username: _username,
        password: _password,
        port: _port,
      ),
      settings: const ConnectionSettings(
        sslMode: SslMode.require,
      ),
    );
  }

  /// Direct Login Verification using SQL & BCrypt
  Future<Map<String, dynamic>?> login(String username, String password) async {
    Connection? conn;
    try {
      conn = await _connect();
      final result = await conn.execute(
        Sql.named('SELECT id_driver, nama, username, password, no_hp, status_aktif, foto_url FROM driver WHERE username = @username'),
        parameters: {'username': username},
      );

      if (result.isEmpty) {
        return null;
      }

      final row = result.first;
      final map = row.toColumnMap();
      final hashedPassword = map['password'] as String;

      // Verify Bcrypt hash
      final passwordMatch = BCrypt.checkpw(password, hashedPassword);
      if (!passwordMatch) {
        return null;
      }

      if (map['status_aktif'] == false || map['status_aktif'] == 0) {
        throw Exception('Akun driver Anda dinonaktifkan oleh administrator.');
      }

      return {
        'id_driver': map['id_driver'],
        'nama': map['nama'],
        'username': map['username'],
        'no_hp': map['no_hp'],
        'status_aktif': map['status_aktif'],
        'foto_url': map['foto_url'],
      };
    } finally {
      await conn?.close();
    }
  }

  /// Update Driver Profile (Name, Phone Number, and Photo Base64)
  Future<bool> updateDriverProfile(String idDriver, String name, String phone, String? photoUrl) async {
    Connection? conn;
    try {
      conn = await _connect();
      final intIdDriver = int.tryParse(idDriver) ?? 0;
      await conn.execute(
        Sql.named('UPDATE driver SET nama = @name, no_hp = @phone, foto_url = @photoUrl, updated_at = NOW() WHERE id_driver = @idDriver'),
        parameters: {
          'idDriver': intIdDriver,
          'name': name,
          'phone': phone,
          'photoUrl': photoUrl,
        },
      );
      return true;
    } catch (e) {
      debugPrint('Error updating driver profile: $e');
      return false;
    } finally {
      await conn?.close();
    }
  }

  /// Direct Query to Fetch Logistic Tasks joined with Orders and LayananSewa
  Future<List<Map<String, dynamic>>> getTasks(String idDriver) async {
    Connection? conn;
    try {
      conn = await _connect();
      final intIdDriver = int.tryParse(idDriver) ?? 0;

      // 1. Fetch pengiriman and linked order details
      final result = await conn.execute(
        Sql.named('''
          SELECT 
              p.id_pengiriman,
              p.id_order,
              p.tipe_tugas,
              p.tgl_jadwal,
              p.status_tugas,
              p.catatan_kondisi_alat,
              p.bukti_foto_url,
              p.created_at,
              o.tgl_mulai,
              o.tgl_selesai,
              o.total_harga,
              o.status_sewa,
              o.nama_pelanggan,
              o.no_hp_pelanggan,
              o.email_pelanggan
          FROM pengiriman p
          JOIN orders o ON p.id_order = o.id_order
          WHERE p.id_driver = @idDriver
          ORDER BY p.id_pengiriman DESC
        '''),
        parameters: {'idDriver': intIdDriver},
      );

      final List<Map<String, dynamic>> tasksList = [];

      for (final row in result) {
        final p = row.toColumnMap();
        final orderId = p['id_order'] as int;

        // 2. Fetch detailed rented equipment items for this order
        final itemsResult = await conn.execute(
          Sql.named('''
            SELECT 
                od.id_detail,
                od.id_layanan,
                od.kuantitas,
                od.subtotal,
                ls.nama_layanan,
                ls.kategori,
                ls.satuan,
                ls.harga
            FROM order_details od
            JOIN layanan_sewa ls ON od.id_layanan = ls.id
            WHERE od.id_order = @orderId
          '''),
          parameters: {'orderId': orderId},
        );

        final List<Map<String, dynamic>> items = itemsResult.map((itemRow) {
          final item = itemRow.toColumnMap();
          return {
            'id_detail': item['id_detail'],
            'id_layanan': item['id_layanan'],
            'nama_barang': item['nama_layanan'],
            'kategori': item['kategori'],
            'satuan': item['satuan'],
            'harga_satuan': (item['harga'] as num?)?.toDouble() ?? 0.0,
            'kuantitas': item['kuantitas'],
            'subtotal': (item['subtotal'] as num?)?.toDouble() ?? 0.0,
          };
        }).toList();

        // Standardize format to fit TaskModel parser
        tasksList.add({
          'id_pengiriman': p['id_pengiriman'],
          'tipe_tugas': p['tipe_tugas'],
          'tgl_jadwal': p['tgl_jadwal'] != null ? (p['tgl_jadwal'] as DateTime).toIso8601String().split('T')[0] : '',
          'status_tugas': p['status_tugas'],
          'catatan_kondisi_alat': p['catatan_kondisi_alat'] ?? '',
          'bukti_foto_url': p['bukti_foto_url'] ?? '',
          'created_at': p['created_at'] != null ? (p['created_at'] as DateTime).toIso8601String() : null,
          'order': {
            'id_order': p['id_order'],
            'tgl_mulai': p['tgl_mulai'] != null ? (p['tgl_mulai'] as DateTime).toIso8601String().split('T')[0] : '',
            'tgl_selesai': p['tgl_selesai'] != null ? (p['tgl_selesai'] as DateTime).toIso8601String().split('T')[0] : '',
            'total_harga': (p['total_harga'] as num?)?.toDouble() ?? 0.0,
            'status_sewa': p['status_sewa'],
            'pelanggan': {
              'id_pelanggan': p['id_order'],
              'nama': p['nama_pelanggan'] ?? '',
              'no_hp': p['no_hp_pelanggan'] ?? '',
            },
            'items': items,
          }
        });
      }

      return tasksList;
    } finally {
      await conn?.close();
    }
  }

  /// Direct Update for Logistic Task Status
  Future<bool> updateTaskStatus(String taskId, String status, {String? notes, String? photoUrl}) async {
    Connection? conn;
    try {
      conn = await _connect();
      final intTaskId = int.tryParse(taskId) ?? 0;

      if (notes != null && photoUrl != null) {
        await conn.execute(
          Sql.named('''
            UPDATE pengiriman 
            SET status_tugas = @status, 
                catatan_kondisi_alat = @notes, 
                bukti_foto_url = @photoUrl, 
                updated_at = NOW() 
            WHERE id_pengiriman = @taskId
          '''),
          parameters: {
            'status': status,
            'notes': notes,
            'photoUrl': photoUrl,
            'taskId': intTaskId,
          },
        );
      } else if (notes != null) {
        await conn.execute(
          Sql.named('''
            UPDATE pengiriman 
            SET status_tugas = @status, 
                catatan_kondisi_alat = @notes, 
                updated_at = NOW() 
            WHERE id_pengiriman = @taskId
          '''),
          parameters: {
            'status': status,
            'notes': notes,
            'taskId': intTaskId,
          },
        );
      } else if (photoUrl != null) {
        await conn.execute(
          Sql.named('''
            UPDATE pengiriman 
            SET status_tugas = @status, 
                bukti_foto_url = @photoUrl, 
                updated_at = NOW() 
            WHERE id_pengiriman = @taskId
          '''),
          parameters: {
            'status': status,
            'photoUrl': photoUrl,
            'taskId': intTaskId,
          },
        );
      } else {
        await conn.execute(
          Sql.named('''
            UPDATE pengiriman 
            SET status_tugas = @status, 
                updated_at = NOW() 
            WHERE id_pengiriman = @taskId
          '''),
          parameters: {
            'status': status,
            'taskId': intTaskId,
          },
        );
      }

      return true;
    } catch (e) {
      if (kDebugMode) {
        print('Error updating task status directly: $e');
      }
      return false;
    } finally {
      await conn?.close();
    }
  }
}
