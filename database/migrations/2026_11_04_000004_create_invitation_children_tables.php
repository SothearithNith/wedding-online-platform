<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invitation_people', function (Blueprint $t) {
            $t->id();
            $t->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $t->string('role', 10);                       // groom | bride
            $t->string('name');
            $t->string('parents')->nullable();
            $t->text('photo')->nullable();
            $t->timestamps();

            $t->unique(['invitation_id', 'role']);
        });

        Schema::create('invitation_events', function (Blueprint $t) {
            $t->id();
            $t->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $t->string('time', 5);                        // HH:MM
            $t->string('title');
            $t->string('place')->nullable();
            $t->text('map_url')->nullable();
            $t->unsignedSmallInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('invitation_stories', function (Blueprint $t) {
            $t->id();
            $t->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $t->string('year', 10);
            $t->string('title');
            $t->text('text')->nullable();
            $t->unsignedSmallInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('invitation_gallery', function (Blueprint $t) {
            $t->id();
            $t->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $t->text('url');
            $t->unsignedSmallInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('rsvps', function (Blueprint $t) {
            $t->id();
            $t->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $t->string('guest_name');
            $t->boolean('attending')->default(true);
            $t->unsignedTinyInteger('guests_count')->default(1);
            $t->text('message')->nullable();
            $t->string('ip', 45)->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rsvps');
        Schema::dropIfExists('invitation_gallery');
        Schema::dropIfExists('invitation_stories');
        Schema::dropIfExists('invitation_events');
        Schema::dropIfExists('invitation_people');
    }
};
