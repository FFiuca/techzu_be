<?php
namespace App\Sources\Services;

use App\Models\Event;
use App\Models\EventReminder;
use App\Sources\Repositories\EventReminderRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;

class EventReminderService extends EventReminderRepository{
    function __construct(protected Event $event){

    }

    function add(array $data): array|bool|Model{
        $dataOld = collect($data)->filter(fn($e)=> isset($e->id));
        $dataNew = collect($data)->filter(fn($e)=> isset($e->id)==false)->values()->toArray();

        $idDelete = $dataOld->pluck('id')->toArray();
        $dataOld = $dataOld->values()->toArray();

        if(count($idDelete)>0){
            $delete = EventReminder::whereNotIn('id', $idDelete)->delete();
            if($delete===false)
                throw new Exception('Delete add reminder error');
        }

        if(count($dataNew)>0){
            foreach($dataNew as $key=>$r)
                $dataNew[$key] = new EventReminder($r);

            $insert = $this->event->eventReminder()->saveMany($dataNew);
        }

        return true;
    }

    function update(int|string $id, array $data): Model|array|bool{
        $update = EventReminder::where('id', $id)->update($data);

        return $update;
    }

    function detail(int|string $id): array{
        $delete = EventReminder::where('id', $id)->delete();

        return $delete;
    }

    function delete(int|string $id): bool{
        $que = EventReminder::where('id', $id);
        if(isset($this->event))
            $que::where('event_id', $this->event->id);

        return $que->first();
    }

}
