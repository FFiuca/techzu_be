<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMember extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //NOTE - PROP
    static public $enumRegistered = 'registered';
    static public $enumExternal = 'external';

    public function scopeMemberRegistered($q){
        return $q->where('status_member', static::$enumRegistered);
    }

    public function scopeMemberExternal($q){
        return $q->where('status_member', static::$enumExternal);
    }

    public function scopeEmailFilled($q){
        return $q->whereNotNull('email_external_member');
    }

    //SECTION - rel
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function event(){
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

}
