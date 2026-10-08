<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->paginate(15);

        return view('halaman-dosen.notifikasi', compact('notifications'));
    }

    public function read(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->find($id);

        if (! $notification) {
            return redirect()->route('dosen.notifications.index')
                ->with('info', 'Notifikasi tidak ditemukan atau sudah dihapus.');
        }

        $notification->markAsRead();

        if (($notification->data['jenis'] ?? null) === 'registrasi') {
            return redirect()->route('dosen.status');
        }

        return redirect()->route('dosen.hki.index');
    }

    public function readAll(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi sudah ditandai dibaca.');
    }
}
