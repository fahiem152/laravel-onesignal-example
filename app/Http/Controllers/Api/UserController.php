<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Berkayk\OneSignal\OneSignalFacade as OneSignal;

class UserController extends Controller
{
    // update subscription id
    public function updateSubscriptionId(Request $request, $id)
    {
        try {
            $request->validate([
                'subscription_id' => 'required',
            ]);

            $user = User::findOrFail($id);

            if ($user) {
                $user->subscription_id = $request->input('subscription_id');
                $user->save();
                return response()->json([
                    'success' => true,
                    'status_code' => 200,
                    'message' => 'FCM ID user updated successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'status_code' => 404,
                    'error' => 'user not found',
                    'message' => 'user not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Failed to update subscription id user',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    // test notification one signal by request
    public function testNotification( Request $request, $id )
    {
      try {
        $request->validate([
            'message' => 'required',
        ]);

        $user = User::find($id);

        if ($user->subscription_id!=null) {
            OneSignal::setParam('priority', 10)
            // ->setParam('small_icon', 'ic_stat_onesignal_default')
            // ->setParam('large_icon', 'large_icon')
            ->setParam('led_color', 'FFFFFF')->sendNotificationToUser(
                $request->message,
                $user->subscription_id,
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null
            );
            return response()->json([
                'message' => 'Notification sent successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Subscription ID not found',
            ]);
        }
      } catch (\Throwable $th) {
        return response()->json([
            'success' => false,
            'status_code' => 500,
            'message' => 'Failed to Notif',
            'error' => $th->getMessage()
        ], 500);
      }

    }

}
