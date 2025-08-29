<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Send a notification to a user
     *
     * @param User $user
     * @param string $type
     * @param array $data
     * @return Notification
     */
    public function sendNotification(User $user, string $type, array $data): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'data' => $data,
        ]);
    }

    /**
     * Send a notification to multiple users
     *
     * @param array $userIds
     * @param string $type
     * @param array $data
     * @return void
     */
    public function sendBulkNotifications(array $userIds, string $type, array $data): void
    {
        $notifications = [];
        
        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $type,
                'data' => json_encode($data),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        if (!empty($notifications)) {
            Notification::insert($notifications);
        }
    }

    /**
     * Get all notifications for a user
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserNotifications(User $user)
    {
        return $user->notifications()->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get unread notifications for a user
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUnreadNotifications(User $user)
    {
        return $user->notifications()->whereNull('read_at')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Mark a notification as read
     *
     * @param Notification $notification
     * @return void
     */
    public function markAsRead(Notification $notification): void
    {
        $notification->markAsRead();
    }

    /**
     * Mark all notifications as read for a user
     *
     * @param User $user
     * @return void
     */
    public function markAllAsRead(User $user): void
    {
        $user->notifications()->whereNull('read_at')->update(['read_at' => now()]);
    }

    /**
     * Delete a notification
     *
     * @param Notification $notification
     * @return bool
     */
    public function deleteNotification(Notification $notification): bool
    {
        return $notification->delete();
    }

    
    public function deleteNotifications(Collection $notifications): bool
    {
        foreach($notifications as $notification){
            $notification->delete();
        }
        return true;
    }
}