<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\EventMember;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Sources\Services\EventMemberService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventMemberUnitTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    private $event;
    public function setUp(): void{
        parent::setUp();

        // dump('this');
        $this->event = Event::first();
        // dump('this');
    }
    public function test_add(): void
    {
        // dump('aa');
        $cls = new EventMemberService($this->event);
        $a = $cls->add([
            [
                'user_id' => 1,
                'status_member'=> EventMember::$enumRegistered
            ],
            [
                'user_id' => 1,
                'status_member'=> EventMember::$enumExternal
            ],
            [
                'email_external_member' => 'fkrfiuca@gmail.com',
                'status_member'=> EventMember::$enumExternal
            ],
        ]);
        // dump($a);

        $this->assertTrue($a);
    }
}
