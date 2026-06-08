<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $query = Conversation::with(['patient.user', 'specialiteChoisie']);

        $query->when($request->search, function ($q) use ($request) {
            return $q->whereHas('patient.user', function ($q2) use ($request) {
                $q2->where('nom', 'like', '%' . $request->search . '%')
                   ->orWhere('prenom', 'like', '%' . $request->search . '%');
            });
        });

        $conversations = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.conversations.index', compact('conversations'));
    }

    public function show($id)
    {
        $conversation = Conversation::with([
            'patient.user',
            'messages',
            'specialiteChoisie',
            'specialitesProposees.specialite',
            'symptomes',
        ])->findOrFail($id);

        return view('admin.conversations.show', compact('conversation'));
    }
}