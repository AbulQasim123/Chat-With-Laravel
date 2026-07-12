<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'attachment',
        'message_type',
        'reply_to_id',
        'is_read',
        'is_deleted',
        'delivered_at',
        'read_at',
        'sent_at',
        'status',
        'meta',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_deleted' => 'boolean',
        'meta' => 'array',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function replyTo()
    {
        return $this->belongsTo(Chat::class, 'reply_to_id');
    }
}
