<?php
namespace App\Sources\Services;
use App\Sources\Repositories\EventMemberRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\EventMember;

class EventMemberService extends EventMemberRepository{

    function __construct(public Event|null $event=null){

    }

    function add(array $data): array|bool|Model{
        // split data
        $registered = collect($data)
                        ->filter(fn($e)=> $e['status_member']==EventMember::$enumRegistered)
                        ->values()
                        ->toArray();
        $external = collect($data)
                    ->filter(fn($e)=> $e['status_member']==EventMember::$enumExternal)
                    ->values()
                    ->toArray();

        // delete first
        $delete = EventMember::where('event_id', $this->event->id)->delete();
        if($delete===false)
            throw new Exception('Delete insert event member error');

        // add for internal first
        if(count($registered)>0){
            foreach($registered as $key=>$r)
                $registered[$key] = new EventMember($r);

            $add = $this->event->eventMemberRegistered()->saveMany( $registered );
            if ($add==false)
                throw new Exception('Add insert event member registered error');
        }

        if(count($external)>0){
            foreach($external as $key=>$r)
                $external[$key] = new EventMember($r);

            $add = $this->event->eventMemberRegistered()->saveMany( $external, );
            if ($add==false)
                throw new Exception('Add insert event member external error');
        }


        return true;
    }

    function update(int|string $id, array $data): array|bool{
        $update = EventMember::where('id', $id)->update($data);

        return $update;
    }

    function delete(int|string $id): bool{
        $delete = EventMember::where('id', $id)->delete();

        return $delete;
    }

    function detail(int|string|null $id): array{
        $que = EventMember::where('id', $id);
        if(isset($this->event))
            $que::where('event_id', $this->event->id);

        return $que->first();
    }
}
