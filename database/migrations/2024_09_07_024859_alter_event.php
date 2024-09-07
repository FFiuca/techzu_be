<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Event;
use App\Models\EventReminder;
use App\Models\EventAttachment;
use App\Models\EventMember;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::disableForeignKeyConstraints();

        $tableEvent = (new EventReminder)->getTable();
        Schema::table($tableEvent, function(Blueprint $t){
            // $t->dropConstrainedForeignIdFor(Event::class); // drop column also
            $t->dropForeign(['event_id']);
        });
        Schema::table((new EventAttachment())->getTable(), function(Blueprint $t){
            // $t->dropConstrainedForeignIdFor(Event::class);
            $t->dropForeign(['event_id']);
        });
        Schema::table((new EventMember())->getTable(), function(Blueprint $t){
            // $t->dropConstrainedForeignIdFor(Event::class);
            $t->dropForeign(['event_id']);
        });

        Schema::table((new Event)->getTable(), function(Blueprint $t){
            $t->char('id', 100)->change();
        });

        Schema::table((new EventReminder)->getTable(), function(Blueprint $t) use ($tableEvent){
            // $t->foreignIdFor(Event::class)->after('id'); // will create int column
            $t->char('event_id', 100)->change();
            // $t->foreign('event_id')->references('id')->on($tableEvent);
        });
        Schema::table((new EventAttachment())->getTable(), function(Blueprint $t)use ($tableEvent){
            $t->char('event_id', 100)->change();
            // $t->foreign('event_id')->references('id')->on($tableEvent);
        });
        Schema::table((new EventMember())->getTable(), function(Blueprint $t)use ($tableEvent){
            $t->char('event_id', 100)->change();
            // $t->foreign('event_id')->references('id')->on($tableEvent);
        });

        // Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableEvent = (new EventReminder)->getTable();

        Schema::table((new Event)->getTable(), function(Blueprint $t){
            $t->bigIncrements('id')->change();
        });

        Schema::table((new EventReminder)->getTable(), function(Blueprint $t) use ($tableEvent){
            $t->bigInteger('event_id')->change();
        });
        Schema::table((new EventAttachment())->getTable(), function(Blueprint $t)use ($tableEvent){
            $t->bigInteger('event_id')->change();
        });
        Schema::table((new EventMember())->getTable(), function(Blueprint $t)use ($tableEvent){
            $t->bigInteger('event_id')->change();
        });
    }
};
