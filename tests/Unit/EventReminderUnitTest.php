<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Sources\Services\EventReminderService;
use App\Models\Event;

class EventReminderUnitTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    protected $event;

    function setUp(): void{
        parent::setUp();

        $this->event = Event::first();
    }

    public function test_add(): void
    {
        $data = [
            [
                'time_before' => 60
            ]
        ];

        $cls = new EventReminderService($this->event);
        $a = $cls->add($data);

        $this->assertTrue($a);
    }
}
