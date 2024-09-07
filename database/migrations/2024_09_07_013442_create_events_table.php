<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->char('id', 100)->primary();
            $table->foreignIdFor(User::class)->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->char('title', 100);
            $table->date('event_date');
            $table->timeTz('event_time')->nullable()->comment('in case wheater whole day or not');
            $table->text('description')->nullable();
            $table->char('location', 255)->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
