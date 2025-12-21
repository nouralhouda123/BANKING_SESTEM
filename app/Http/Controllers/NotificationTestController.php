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
    public function showNotification()
    {
        $user = Auth::user();
        $admin = Admin::find($user->id);

        if (!$admin) {
            return response()->json([
                'message' => 'Admin not found.',
                'data' => []
            ], 404);
        }
        $notifications = $admin->Notifiables;

        if ($notifications->isEmpty()) {
            return response()->json([
                'message' => 'No notifications found.',
                'data' => []
            ], 404);
        }



        return response()->json([
            'message' => 'Notifications retrieved successfully.',
            'data' => $notifications
        ], 200);
    }
    //عرض اشعارات طبيب
    public function showNotificationDoctor()
    {
        $user = Auth::user();
        $doctor = doctor::find($user->id);

        if (!$doctor) {
            return response()->json([
                'message' => 'doctor not found.',
                'data' => []
            ], 404);
        }
        $notifications = $doctor->Notifiables;

        if ($notifications->isEmpty()) {
            return response()->json([
                'message' => 'No notifications found.',
                'data' => []
            ], 404);
        }



        return response()->json([
            'message' => 'Notifications retrieved successfully.',
            'data' => $notifications
        ], 200);
    }

    //عرض اشعارات مريض
    public function ShowNotificationPatient()
    {
        $user = Auth::user();
        $Patient = Patient::find($user->id);

        if (!$Patient) {
            return response()->json([
                'message' => 'Patient not found.',
                'data' => []
            ], 404);
        }

        $notifications = $Patient->Notifiables;

        if ($notifications->isEmpty()) {
            return response()->json([
                'message' => 'No notifications found.',
                'data' => []
            ], 404);
        }



        return response()->json([
            'message' => 'Notifications retrieved successfully.',
            'data' => $notifications
        ], 200);
    }

//عرض تفاصيل اشعار.
    public function indexDetailNotifiable($id): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized: User not logged in.'
            ], 401);
        }

        $notification = Notification::find($id);

        if (!$notification || $notification->notifiable_id !== $user->id) {
            return response()->json([
                'message' => 'Notification not found or you do not have permission to delete it.',
                'data' => []
            ], 404);

    }

        $notification->read_at = Carbon::now();
        $notification->save();


        return response()->json([
            'message' => 'Notification retrieved successfully.',
            'data'=> $notification
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
    /// مريض
    public function updateDeviceTokenPatient(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $validateDate = $request->validate([
            'fcm_token' => 'required',
        ]);

        Patient::where('id', $user_id)->update([
            'fcm_token' => $validateDate['fcm_token']
        ]);

        return response()->json([
            'message' => 'Token stored successfully.'
        ], 200);
    }
//
    ////وضع توكين الدكتور
    public function updateDevicesTokenss(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $validateDate = $request->validate([
            'fcm_token' => 'required',
        ]);

        doctor::where('id', $user_id)->update([
            'fcm_token' => $validateDate['fcm_token']
        ]);

        return response()->json([
            'message' => 'Token stored successfully.'
        ], 200);
    }

    public function sendNotificationToPatient(Request $request,$id,)
    {
        $type = 'basic';
        $title=$request->title;
        $message=$request->message;
        $patient = Patient::find($id);

        if (!$patient) {
            Log::error("Patient with ID $id not found.");
            return 0;
        }

        if (!$patient->fcm_token) {
            Log::error("No FCM token for patient ID $id.");
            return 0;
        }

        $serviceAccountPath = storage_path('app/dash-admin-e8367-firebase-adminsdk-fbsvc-5c687d56f5 (2).json');

        $factory = (new Factory)->withServiceAccount($serviceAccountPath);
        $messaging = $factory->createMessaging();

        $notification = [
            'title' => 'kk',
            'body' => $message,
            'sound' => 'default',
        ];

        $data = [
            'type' => $type,
            'id' => $patient->id,
            'message' => $message,
        ];

        $cloudMessage = CloudMessage::withTarget('token', $patient->fcm_token)
            ->withNotification($notification)
            ->withData($data);

        try {
            $messaging->send($cloudMessage);

            NotificationModel::create([
                'type' => 'AppNotificationsPatientNotification',
                'title' => 'l',

                'notifiable_type' => 'AppModelsPatient',
                'notifiable_id' => $patient->id,
                'data' => json_encode([
                    'patient' => $patient->first_name . ' ' . $patient->last_name,
                    'message' => $message,
                    'title' => 'l',
                ]),
            ]);

            return 1;
        } catch (KreaitFirebaseExceptionMessagingException $e) {
            Log::error($e->getMessage());
            return 0;
        } catch (KreaitFirebaseExceptionFirebaseException $e) {
            Log::error($e->getMessage());
            return 0;
        }
    }

    public function updateDeviceToken(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $validateDate = $request->validate([
            'fcm_token' => 'required',
        ]);

        Admin::where('id', $user_id)->update([
            'fcm_token' => $validateDate['fcm_token']
        ]);

        return response()->json([
            'message' => 'Token stored successfully.'
        ], 200);
    }

}
