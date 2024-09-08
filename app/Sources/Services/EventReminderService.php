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
        // delete first
        EventReminder::where('event_id', $this->event->id)->delete();

        foreach($data as $key=>$r)
            $data[$key]['event_id'] = $this->event->id;

        $upsert = EventReminder::upsert(
            $data,
            [
                'event_id',
                'time_before'
            ]
        );
        if($upsert<0)
            throw new Exception('Upsert add reminder error '.$upsert);

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
