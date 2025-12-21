<?php
namespace App\Http\Controllers;
use App\Http\Resources\notifications;
use App\Models\admin;
use App\Models\doctor;
use App\Models\notification as NotificationModel;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use App\Models\notification;
use Illuminate\Http\Request;

class NotificationTestController extends Controller
{
    //عرض اشعارات مدير
    public function showNotificationManager()
    {
        $user = Auth::user();
        $doctor = User::find($user->id);
        if (!$doctor) {
            return response()->json([
                'message' => 'Manager not found.',
                'data' => []
            ], 404);
        }
        $notifications = $doctor->Notifiables;
        return response()->json([
            'message' => 'Notifications retrieved successfully.',
            'data' => $notifications
        ], 200);
    }

    public function destroyNotifiables($id): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized: User not logged in.'
            ], 401);
        }

        $notification = Notification::find($id);

        if (!$notification || $notification->notifiable_id != $user->id) {
            return response()->json([
                'message' => 'Notification not found or you do not have permission to delete it.',
                'data' => []
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully.'
        ], 200);
    }
    ////وضع توكين
    /// مدير
    public function updateDeviceTokenPatient(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $validateDate = $request->validate([
            'fcm_token' => 'required',
        ]);

        User::where('id', $user_id)->update([
            'fcm_token' => $validateDate['fcm_token']
        ]);

        return response()->json([
            'message' => 'Token stored successfully.'
        ], 200);
    }
}
