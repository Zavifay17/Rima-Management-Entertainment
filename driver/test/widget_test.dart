import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:event_driver/app.dart';
import 'package:event_driver/providers/auth_provider.dart';
import 'package:event_driver/providers/task_provider.dart';
import 'package:event_driver/providers/chat_provider.dart';
import 'package:provider/provider.dart';

void main() {
  testWidgets('EventDriver smoke test', (WidgetTester tester) async {
    await tester.pumpWidget(
      MultiProvider(
        providers: [
          ChangeNotifierProvider(create: (_) => AuthProvider()),
          ChangeNotifierProvider(create: (_) => TaskProvider()),
          ChangeNotifierProvider(create: (_) => ChatProvider()),
        ],
        child: const EventDriverApp(),
      ),
    );
    // App should start and show something
    expect(find.byType(MaterialApp), findsOneWidget);
  });
}
