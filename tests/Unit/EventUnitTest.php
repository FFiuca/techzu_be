<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Sources\Services\EventService;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\EventMember;
use App\Sources\Services\EventSchedulerService;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventReminderMail;
use Maatwebsite\Excel\Concerns\ToArray;

class EventUnitTest extends TestCase
{
    // use RefreshDatabase;
    // protected $seed = true;

    protected $eventF = [];
    function setUp(): void{
        parent::setUp();

        $et = Carbon::now();
        $etArr = [
            $et->format('H:i:00'),
            $et->addMinutes(1)->format('H:i:00'),
            $et->addMinutes(2)->format('H:i:00'),
            $et->addMinutes(3)->format('H:i:00'),
        ];

        foreach($etArr as $key=>$r){
            $this->eventF[] = Event::factory()->create([
                'event_date' => $et->format('Y-m-d'),
                'event_time' => $r
            ]);
        }
    }



    public function test_add(): void
    {
        // dump('test');
        $data = [
            'user_id' => 1,
            'title' => 'test',
            'event_date' => '2024-09-30'
        ];
        // dump($data);
        $i = (new EventService)->add($data);

        $this->assertNotNull($i->id);
    }

    public function test_scheduler_email():void{
        Mail::fake();

        $cls =new EventSchedulerService;

        $check = $cls->getCurrentTrigger();
        $a = $cls->runScheduler();

        $this->assertTrue($a);

        // check
        $receiver = EventMember::whereIn('event_id', collect($check)->pluck('id')->toArray())->get()->pluck('email_external_member')->ToArray();
        Mail::assertSent(EventReminderMail::class, $receiver);

    }


}
