<?php
namespace App\Services;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class NotificationAddTranscationWithBigBalance
{
public function sendNotification($manager, string $title, string $message, string $type, array $transactionDetails = [])
{
// تحقق من المدير
if (!$manager) {
Log::warning('Manager is null');
return 0;
}

if (!$manager->fcm_token) {
Log::warning('Manager has no FCM token',
['manager_id' => $manager->id]);
return 0;
}

// إرسال الإشعار
try {
// 1. إرسال إشعار Firebase
$serviceAccountPath = storage_path('app/dash-65d84-firebase-adminsdk-fbsvc-bd71bf6e7d.json');
$factory = (new Factory)->withServiceAccount($serviceAccountPath);
$messaging = $factory->createMessaging();

$cloudMessage = CloudMessage::withTarget('token', $manager->fcm_token)
->withNotification([
'title' => $title,
'body' => $message,
])
->withData([
'type' => $type,
'transactionDetails' => json_encode($transactionDetails)
]);

// 2. إلغاء التعليق عن هذا السطر عند التأكد
 $messaging->send($cloudMessage);

// 3. حفظ الإشعار في قاعدة البيانات
Notification::create([
'type' => $type,
'user_id' => $manager->id,
'title' => $title,
'data' => [
'message' => $message,
'transactionDetails' => $transactionDetails
],
'read_at' => null,
]);

Log::info('Notification saved successfully', [
'manager_id' => $manager->id,
'notification_type' => $type
]);

return 1;

} catch (\Exception $e) {
Log::error('Notification Error: ' . $e->getMessage(), [
'manager_id' => $manager->id ?? null,
'trace' => $e->getTraceAsString()
]);
return 0;
}
}}
