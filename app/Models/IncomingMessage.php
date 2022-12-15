<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_name',
        'update_id',
        'message_id',
        'from_id',
        'first_name',
        'last_name',
        'username',
        'chat_id',
        'date',
        'text',
        'callback_data',
        'callback_message_id',
        'callback_chat_id',
        'mark',
        'notes',
    ];
}
