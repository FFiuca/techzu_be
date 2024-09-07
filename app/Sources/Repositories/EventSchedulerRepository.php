<?php
namespace App\Sources\Repositories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

abstract class EventSchedulerRepository{
    abstract public function getCurrentTrigger() : array;
    abstract public function sendEmail(array $events): bool;
    abstract public function runScheduler(): bool;
}
