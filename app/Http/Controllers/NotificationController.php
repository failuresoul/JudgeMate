<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get the unread notifications count for the authenticated user.
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $count = $request->user()->unreadNotifications()->count();

        return response()->json(['unread_count' => $count]);
    }

    /**
     * Mark unread notifications as read.
     */
    public function markRead(Request $request): JsonResponse
    {
        if ($request->has('id')) {
            $notification = $request->user()->unreadNotifications()->find($request->id);
            if ($notification) {
                $notification->markAsRead();
            }
        } else {
            $request->user()->unreadNotifications->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}
