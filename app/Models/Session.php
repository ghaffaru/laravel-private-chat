<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'blocked' => 'boolean'
    ];
    public function chats()
    {
        return $this->hasManyThrough(Chat::class, Message::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function deleteChats()
    {
        $this->chats()->where('user_id', auth()->id())->delete();
    }

    public function deleteMessages()
    {
        $this->messages()->delete();
    }

    public function block()
    {
        $this->update(['blocked' => true, 'blocked_by_id' => auth()->id()]);
    }

    public function unblock()
    {
        $this->update(['blocked' => false, 'blocked_by_id' => null]);
    }
}
