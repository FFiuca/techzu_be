<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class EventReminder extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //SECTION - rel
    public function event(){
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }
}
