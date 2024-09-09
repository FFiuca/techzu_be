<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Event;
use App\Models\EventReminder;
use App\Models\EventMember;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableEvent = (new Event)->getTable();
        $tableEventReminder = (new EventReminder)->getTable();
        $tableEventMember = (new EventMember())->getTable();

        Schema::table($tableEventReminder, function(Blueprint $t) use ($tableEvent){
            $t->foreign('event_id')->references('id')->on($tableEvent)->constrained()->onDelete('no action')->cascadeOnUpdate();
        });
        Schema::table($tableEventMember, function(Blueprint $t) use ($tableEvent){
            $t->foreign('event_id')->references('id')->on($tableEvent)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableEvent = (new Event)->getTable();
        $tableEventReminder = (new EventReminder)->getTable();
        $tableEventMember = (new EventMember())->getTable();

        Schema::table($tableEventMember, function(Blueprint $t){
            $t->dropForeign(['event_id']);
        });

        Schema::table($tableEventReminder, function(Blueprint $t){
            $t->dropForeign(['event_id']);
        });
    }
};
