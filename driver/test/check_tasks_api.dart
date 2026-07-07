import 'dart:convert';
import '../lib/core/services/supabase_direct_service.dart';

Future<void> main() async {
  print('Fetching tasks for driver 1...');
  try {
    final list = await SupabaseDirectService.instance.getTasks('1');
    print('SUCCESS! Tasks count: ${list.length}');
    print(const JsonEncoder.withIndent('  ').convert(list));
  } catch (e, stack) {
    print('ERROR FETCHING TASKS: $e');
    print(stack);
  }
}
