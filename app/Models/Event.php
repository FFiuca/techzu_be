<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Sources\Services\EventService;
use App\Models\EventMember;
use App\Models\EventReminder;
use App\Models\EventAttachmentl;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted(): void{
        static::creating(fn (Model $model)=> $model->id = EventService::generateId());
    }

    //SECTION - rel
    public function user(){
        return $this->belongsToMany(User::class, EventMember::class, 'event_id', 'user_id');
    }

    public function eventMember(){
        return $this->hasMany(EventMember::class, 'event_id', 'id');
    }

    public function eventMemberRegistered(){
        return $this->hasMany(EventMember::class, 'event_id', 'id')
                ->with('user')
                ->where('status_member', EventMember::$enumRegistered);
    }

    public function eventMemberExternal(){
        return $this->hasMany(EventMember::class, 'event_id', 'id')
                ->where('status_member', EventMember::$enumExternal)
                ->emailFilled();
    }

    public function eventReminder(){
        return $this->hasMany(EventReminder::class, 'event_id', 'id');
    }

    public function eventAttachment(){
        return $this->hasMany(EventAttachment::class, 'event_id', 'id');
    }

}
