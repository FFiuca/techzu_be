<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Sources\Services\EventService;

class EventUnitTest extends TestCase
{
    // use RefreshDatabase;
    // protected $seed = true;

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
}
