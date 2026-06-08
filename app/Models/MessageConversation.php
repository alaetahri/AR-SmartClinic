<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageConversation extends Model
{
    protected $table = 'messages_conversation';

    protected $fillable = [
        'conversation_id',
        'expediteur',
        'message',
    ];

    // Un message appartient à une conversation
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}