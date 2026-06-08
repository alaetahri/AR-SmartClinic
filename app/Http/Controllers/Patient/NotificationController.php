<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    // Liste des notifications du patient connecté uniquement
    public function index()
    {
        $notifications = Notification::where('user_id', session('user_id'))
            ->orderBy('lu', 'asc')
            ->orderBy('date_envoi', 'desc') 
            ->paginate(15);

        return view('patient.notifications', compact('notifications'));
    }

    // Marquer une notification comme lue
    public function marquerLu($id)
    {
        Notification::where('user_id', session('user_id'))
            ->where('id', $id)
            ->update(['lu' => true]);

        return redirect()->back()->with('success', 'Notification marquée comme lue.');
    }

    // Marquer toutes les notifications comme lues
    public function marquerToutLu()
    {
        Notification::where('user_id', session('user_id'))
            ->where('lu', false)
            ->update(['lu' => true]);

        return redirect()->back()->with('success', 'Toutes les notifications ont été lues.');
    }
}