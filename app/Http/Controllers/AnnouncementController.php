<?php

namespace App\Http\Controllers;

use App\Models\InternalAnnouncement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = InternalAnnouncement::with('user')->latest()->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,warning,success,primary',
        ]);

        $announcement = InternalAnnouncement::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
        ]);

        \App\Models\ActivityLog::log('Announcement', 'Create', "Menerbitkan pengumuman: {$announcement->title}");

        // Broadcast to WA if requested
        if ($request->has('broadcast_wa')) {
            $teachers = \App\Models\User::where('role', 'teacher')->whereNotNull('phone')->get();
            
            $waMessage = "📢 *PENGUMUMAN INTERNAL BARU* 📢\n";
            $waMessage .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
            $waMessage .= "📌 *Judul:* " . $announcement->title . "\n";
            $waMessage .= "👤 *Oleh:* " . auth()->user()->name . "\n\n";
            
            $waMessage .= "📝 *Pesan:*\n";
            $waMessage .= "-------------------\n";
            $waMessage .= $announcement->content . "\n";
            $waMessage .= "-------------------\n\n";
            
            $waMessage .= "💻 _Silakan cek dashboard aplikasi untuk detail lebih lanjut._\n";
            $waMessage .= "━━━━━━━━━━━━━━━━━━━━━━";

            foreach ($teachers as $teacher) {
                $formattedNumber = \App\Services\WhatsappService::formatNumber($teacher->phone);
                \App\Services\WhatsappService::send($formattedNumber, $waMessage);
            }
        }

        return back()->with('success', 'Pengumuman berhasil diterbitkan' . ($request->has('broadcast_wa') ? ' dan disiarkan via WhatsApp.' : '.'));
    }

    public function destroy(InternalAnnouncement $announcement)
    {
        \App\Models\ActivityLog::log('Announcement', 'Delete', "Menghapus pengumuman: {$announcement->title}");
        $announcement->delete();
        return back()->with('success', 'Pengumuman dihapus.');
    }
}
