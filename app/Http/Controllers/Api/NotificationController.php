<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
    }

    /**
     * Get all notifications for the authenticated user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {

        $user = $request->user();

        $notifications = $this->notificationService->getUserNotifications($user);
        
        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Get unread notifications for the authenticated user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function unread(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = $this->notificationService->getUnreadNotifications($user);
        $count = $notifications->count();
        
        return response()->json([
            'success' => true,
            'count' => $count,
            'data' => $notifications
        ]);
    }

    /**
     * Mark a notification as read
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function markAsRead(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
            
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }
        
        $this->notificationService->markAsRead($notification);
        
        return $this->responseMessage('success','Notification marked as read', null, 200);
      
    }

    /**
     * Mark all notifications as read
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->notificationService->markAllAsRead($user);
        return $this->responseMessage('success','All notifications marked as read', null, 200);
    }

    /**
     * Delete a notification
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        if (!$notification) {
            return $this->responseMessage('success','Notification not found', null, 200);
        }
        $this->notificationService->deleteNotification($notification);
        return $this->responseMessage('success','Notification deleted', null, 200);
    }

    public function destroryAll(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = Notification::where('user_id', $user->id)->get();
        if (!$notifications) {
            return $this->responseMessage('success','Notification not found', null, 200);
        }
        $this->notificationService->deleteNotifications($notifications);
        return $this->responseMessage('success','Notification deleted', null, 200);
    }
}