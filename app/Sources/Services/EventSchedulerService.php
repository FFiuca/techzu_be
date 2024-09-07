<?php
namespace App\Sources\Services;

use App\Mail\EventReminderMail;
use App\Models\EventReminder;
use App\Sources\Repositories\EventSchedulerRepository;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class EventSchedulerService extends EventSchedulerRepository{
    function getCurrentTrigger(): array{
        // range 1 minute for scheduler
        $data = DB::select(" SELECT
                    e.id,
                    e.title,
                    e.event_date,
                    e.event_time
                FROM
                    events e
                INNER JOIN
                    (SELECT id FROM events WHERE event_time IS NOT NULL) AS e2 ON e.id = e2.id
                INNER JOIN
                    event_reminders er ON er.event_id = e.id
                WHERE
                    NOW() BETWEEN (TIMESTAMP(e.event_date, e.event_time) - INTERVAL er.time_before SECOND)
                    AND (TIMESTAMP(e.event_date, e.event_time) - INTERVAL er.time_before SECOND + INTERVAL 60 SECOND)
                GROUP BY e.id
                UNION
                SELECT e.id, e.title, e.event_date,
                    e.event_time FROM events e WHERE e.event_time is null and TIMESTAMPDIFF(SECOND, CONCAT(e.event_date, ' 00:00:00'), NOW())>=0 AND TIMESTAMPDIFF(SECOND, CONCAT(e.event_date, ' 00:01:00'), NOW())<=0 GROUP BY e.id");

        // normalize
        foreach($data as $key=>$r)
            $data[$key] = (array) $r;

        return $data;
    }

    function sendEmail(array $events): bool{
        $events = collect($events);


        $data = Event::with([
            'eventMemberRegistered' => fn($que) => $que->with('user'),
            'eventMemberExternal' => fn($que) => $que->whereNotNull('email_external_member'),
        ])
        ->whereIn('id', $events->pluck('id')->toArray())
        ->get();

        // sent registered first
        foreach($data as $row){
            foreach($row->eventMemberRegistered as $key=>$r){
                Mail::to($r->user->email)->send(new EventReminderMail($r->only(['id', 'title'])));
            }

            foreach($row->eventMemberExternal as $key=>$r){
                Mail::to($r->email)->send(new EventReminderMail($r->only(['id', 'title'])));
            }
        }

        return true;
    }

    function runScheduler(): bool{
        $data = $this->getCurrentTrigger();

        if(count($data)>0)
            $send = $this->sendEmail($data);

        return true;

    }
}
