<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\Notification;
use App\Models\Conversation;
use App\Models\MessageConversation;
use App\Models\Specialite;
use App\Models\SpecialiteProposee;
use App\Models\Symptome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PatientController extends Controller
{
    // ─────────────────────────────────────────────
    // DASHBOARD — inchangé (ton ancien code)
    // ─────────────────────────────────────────────
    public function dashboard()
    {
        $patient = Patient::where('user_id', session('user_id'))->first();

        $prochainRendezVous = RendezVous::where('patient_id', $patient->id)
            ->where('date_rendez_vous', '>=', today())
            ->where('statut', 'confirme')
            ->orderBy('date_rendez_vous')
            ->first();

        $totalRendezVous = RendezVous::where('patient_id', $patient->id)->count();

        $totalConsultations = Consultation::where('patient_id', $patient->id)
            ->where('statut', 'terminee')
            ->count();

        $notifications = Notification::where('user_id', session('user_id'))
            ->where('lu', false)
            ->orderBy('date_envoi', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.patient', compact(
            'patient',
            'prochainRendezVous',
            'totalRendezVous',
            'totalConsultations',
            'notifications'
        ));
    }

    // ─────────────────────────────────────────────
    // ASSISTANT IA — afficher la conversation
    // ─────────────────────────────────────────────
    public function assistant()
    {
        $patient = Patient::where('user_id', session('user_id'))->first();

        $conversation = Conversation::where('patient_id', $patient->id)
            ->whereNull('specialite_choisie_id')
            ->latest()
            ->first();

        $messages             = collect();
        $specialitesProposees = collect();

        if ($conversation) {
            $messages = MessageConversation::where('conversation_id', $conversation->id)
                ->orderBy('id')
                ->get();

            $specialitesProposees = SpecialiteProposee::where('conversation_id', $conversation->id)
                ->where('choisie', false)
                ->with('specialite')
                ->orderByDesc('score_confiance')
                ->get();
        }

        $specialites = Specialite::orderBy('nom')->get();

        return view('patient.ai', compact(
            'conversation',
            'messages',
            'specialitesProposees',
            'specialites'
        ));
    }

    // ─────────────────────────────────────────────
    // ASSISTANT IA — envoyer un message
    // ─────────────────────────────────────────────
    public function envoyerMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $patient = Patient::where('user_id', session('user_id'))->first();

        $conversation = Conversation::where('patient_id', $patient->id)
            ->whereNull('specialite_choisie_id')
            ->latest()
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'patient_id' => $patient->id,
                'titre'      => 'Consultation IA — ' . now()->format('d/m/Y H:i'),
                'resume'     => null,
            ]);
        }

        // Sauvegarder message patient
        MessageConversation::create([
            'conversation_id' => $conversation->id,
            'expediteur'      => 'patient',
            'message'         => $request->message,
        ]);

        // Historique complet pour contexte IA
        $historique = MessageConversation::where('conversation_id', $conversation->id)
            ->orderBy('id')
            ->get();

        // Appel IA
        $reponseIA = $this->obtenirReponseIA($request->message, $historique, $conversation->id);

        // Sauvegarder réponse IA
        MessageConversation::create([
            'conversation_id' => $conversation->id,
            'expediteur'      => 'ai',
            'message'         => $reponseIA['message'],
        ]);

        // Sauvegarder spécialités proposées
        if (!empty($reponseIA['specialites'])) {
            SpecialiteProposee::where('conversation_id', $conversation->id)
                ->where('choisie', false)
                ->delete();

            foreach ($reponseIA['specialites'] as $sp) {
                $specialite = Specialite::find($sp['id']);
                if ($specialite) {
                    SpecialiteProposee::create([
                        'conversation_id' => $conversation->id,
                        'specialite_id'   => $specialite->id,
                        'score_confiance' => $sp['score'] ?? 70,
                        'choisie'         => false,
                    ]);
                }
            }
        }

        // Sauvegarder symptômes détectés
        if (!empty($reponseIA['symptomes_ids'])) {
            foreach ($reponseIA['symptomes_ids'] as $symptomeId) {
                $exists = \DB::table('conversation_symptome')
                    ->where('conversation_id', $conversation->id)
                    ->where('symptome_id', $symptomeId)
                    ->exists();
                if (!$exists) {
                    \DB::table('conversation_symptome')->insert([
                        'conversation_id' => $conversation->id,
                        'symptome_id'     => $symptomeId,
                    ]);
                }
            }
        }

        return redirect()->route('patient.assistant');
    }

    // ─────────────────────────────────────────────
    // ASSISTANT IA — choisir une spécialité
    // ─────────────────────────────────────────────
    public function choisirSpecialite(Request $request)
    {
        $request->validate([
            'specialite_id'   => 'required|exists:specialites,id',
            'conversation_id' => 'required|exists:conversations,id',
        ]);

        $patient = Patient::where('user_id', session('user_id'))->first();

        $conversation = Conversation::where('id', $request->conversation_id)
            ->where('patient_id', $patient->id)
            ->firstOrFail();

        SpecialiteProposee::where('conversation_id', $conversation->id)
            ->where('specialite_id', $request->specialite_id)
            ->update(['choisie' => true]);

        $conversation->update([
            'specialite_choisie_id' => $request->specialite_id,
        ]);

        return redirect()->route('patient.rendez-vous.create', [
            'specialite_id' => $request->specialite_id,
        ])->with('success', 'Spécialité sélectionnée. Choisissez un médecin et un créneau.');
    }

    // ─────────────────────────────────────────────
    // MÉTHODE PRIVÉE — Appel Groq API
    // ─────────────────────────────────────────────
    private function obtenirReponseIA(string $messagePatient, $historique, int $conversationId): array
    {
        $specialites     = Specialite::orderBy('nom')->get();
        $listeSpecialites = $specialites->map(fn($s) => "- ID {$s->id} : {$s->nom}")->join("\n");

        $symptomes     = Symptome::with('specialite')->get();
        $listeSymptomes = $symptomes->map(fn($s) => "- ID {$s->id} : {$s->nom} (spécialité: {$s->specialite?->nom})")->join("\n");

        $messagesGroq = [
            [
                'role'    => 'system',
                'content' => "Tu es un assistant médical intelligent pour AR SmartClinic, une clinique marocaine moderne.
Le patient te décrit ses symptômes en français ou en darija marocain.

SPÉCIALITÉS DISPONIBLES DANS LA CLINIQUE :
{$listeSpecialites}

SYMPTÔMES CONNUS EN BASE (optionnel, utilise-les si pertinents) :
{$listeSymptomes}

INSTRUCTIONS :
1. Accueille chaleureusement le patient si c'est le premier message
2. Pose des questions de suivi si les symptômes sont insuffisants
3. Quand tu as assez d'informations, propose 2-3 spécialités médicales adaptées
4. Utilise UNIQUEMENT les IDs des spécialités listées ci-dessus
5. Réponds TOUJOURS en JSON valide avec ce format exact :
{
  \"message\": \"Ta réponse en français (bienveillante, professionnelle, claire)\",
  \"specialites\": [
    {\"id\": <int>, \"nom\": \"<string>\", \"score\": <int 0-100>}
  ],
  \"symptomes_ids\": [<int>, ...]
}

RÈGLES :
- Si tu manques d'infos, mets \"specialites\": []
- Scores entre 50 et 98
- Sois empathique et rassurant
- Ne donne JAMAIS de diagnostic définitif
- Recommande toujours de consulter un médecin",
            ],
        ];

        foreach ($historique as $msg) {
            $messagesGroq[] = [
                'role'    => $msg->expediteur === 'patient' ? 'user' : 'assistant',
                'content' => $msg->message,
            ];
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                    'Content-Type'  => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model'       => 'llama3-8b-8192',
                    'messages'    => $messagesGroq,
                    'max_tokens'  => 600,
                    'temperature' => 0.4,
                ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content', '');
                $content = preg_replace('/```json\s*|\s*```/', '', trim($content));
                $data    = json_decode($content, true);

                if (json_last_error() === JSON_ERROR_NONE && isset($data['message'])) {
                    return [
                        'message'       => $data['message'],
                        'specialites'   => $data['specialites'] ?? [],
                        'symptomes_ids' => $data['symptomes_ids'] ?? [],
                    ];
                }

                return [
                    'message'     => $content ?: "Je n'ai pas pu analyser votre demande. Pouvez-vous reformuler ?",
                    'specialites' => [],
                ];
            }

            \Log::error('Groq API error', [
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);

        } catch (\Exception $e) {
            \Log::error('Groq API exception', ['message' => $e->getMessage()]);
        }

        return [
            'message'     => "Je rencontre une difficulté technique. Veuillez réessayer ou contacter notre secrétariat.",
            'specialites' => [],
        ];
    }
}