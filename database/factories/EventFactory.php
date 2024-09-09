<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EventReminder;
use App\Models\EventMember;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = fake();

        // $event_date = $fake->dateTimeBetween('+1 day', 'now');

        return [
            'title' => $fake->name(),
            'description' => $fake->paragraph(),
            'event_date' => date('Y-m-d'),
            'event_time' => date('H:m:00'),
            'location' => $fake->words(asText: true),
        ];
    }

    public function configure(){
        return $this->afterCreating(function(Event $event){
            $member = [
                new EventMember([
                    'status_member' => 'external',
                    'email_external_member' => 'fardana.fiuca31@gmail.com'
                ])
            ];

            $reminder = [
                new EventReminder([
                    'time_before' => 120,
                ]),
                new EventReminder([
                    'time_before' => 60,
                ]),
                new EventReminder([
                    'time_before' => 0,
                ]),
            ];

            $event->eventMemberExternal()->saveMany($member);
            $event->eventReminder()->saveMany($reminder);
        });
    }
}
