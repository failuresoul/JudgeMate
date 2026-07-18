<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

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

    /**
     * Mark a notification as read and redirect to its URL.
     */
    public function redirect(Request $request, $id): RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        
        if ($notification->unread()) {
            $notification->markAsRead();
        }

        $url = $notification->data['url'] ?? route('dashboard');
        return redirect()->to($url);
    }
}
