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

    //SECTION - rel
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function event(){
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

}
