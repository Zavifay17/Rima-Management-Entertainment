import 'package:postgres/postgres.dart';

Future<void> main() async {
  print('Connecting to database...');
  final conn = await Connection.open(
    Endpoint(
      host: 'aws-1-ap-southeast-1.pooler.supabase.com',
      database: 'postgres',
      username: 'postgres.lurtyqgtfjdokoytixzi',
      password: 'rimaentertaiment2004',
      port: 5432,
    ),
    settings: const ConnectionSettings(
      sslMode: SslMode.require,
    ),
  );

  try {
    print('Checking for running queries and locks...');
    
    print('\n--- Active Running Queries ---');
    final activeQueries = await conn.execute(
      "SELECT pid, query, state, age(clock_timestamp(), query_start) as age FROM pg_stat_activity WHERE state != 'idle'"
    );
    for (final row in activeQueries) {
      final map = row.toColumnMap();
      print('PID: ${map['pid']}, State: ${map['state']}, Age: ${map['age']}, Query: ${map['query']}');
    }
    
    print('\n--- Blocked/Blocking Locks ---');
    final locks = await conn.execute('''
      SELECT blocked_locks.pid     AS blocked_pid,
             blocking_locks.pid    AS blocking_pid,
             blocked_activity.query    AS blocked_statement,
             blocking_activity.query   AS blocking_statement
      FROM  pg_catalog.pg_locks         blocked_locks
      JOIN pg_catalog.pg_stat_activity blocked_activity ON blocked_activity.pid = blocked_locks.pid
      JOIN pg_catalog.pg_locks         blocking_locks 
          ON blocking_locks.locktype = blocked_locks.locktype
          AND blocking_locks.database IS NOT DISTINCT FROM blocked_locks.database
          AND blocking_locks.relation IS NOT DISTINCT FROM blocked_locks.relation
          AND blocking_locks.page IS NOT DISTINCT FROM blocked_locks.page
          AND blocking_locks.tuple IS NOT DISTINCT FROM blocked_locks.tuple
          AND blocking_locks.virtualxid IS NOT DISTINCT FROM blocked_locks.virtualxid
          AND blocking_locks.transactionid IS NOT DISTINCT FROM blocked_locks.transactionid
          AND blocking_locks.classid IS NOT DISTINCT FROM blocked_locks.classid
          AND blocking_locks.objid IS NOT DISTINCT FROM blocked_locks.objid
          AND blocking_locks.objsubid IS NOT DISTINCT FROM blocked_locks.objsubid
          AND blocking_locks.pid != blocked_locks.pid
      JOIN pg_catalog.pg_stat_activity blocking_activity ON blocking_activity.pid = blocking_locks.pid
      WHERE NOT blocked_locks.granted
    ''');
    
    if (locks.isEmpty) {
      print('No blocked locks found.');
    } else {
      for (final row in locks) {
        final map = row.toColumnMap();
        print('Blocked PID: ${map['blocked_pid']} waiting for Blocking PID: ${map['blocking_pid']}');
        print('Blocked Statement: ${map['blocked_statement']}');
        print('Blocking Statement: ${map['blocking_statement']}');
        print('-----------------------------------------');
      }
    }
    
  } catch (e) {
    print('Error: $e');
  } finally {
    await conn.close();
    print('Connection closed.');
  }
}
