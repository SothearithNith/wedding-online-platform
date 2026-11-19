<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('template_id')->constrained('templates');
            $t->string('slug')->unique();                 // wd-6541e708
            $t->string('label');
            $t->dateTime('event_date')->nullable();       // stored in UTC
            $t->string('date_text')->nullable();
            $t->text('intro')->nullable();
            $t->text('quote')->nullable();
            $t->text('apology')->nullable();
            $t->text('thanks')->nullable();
            $t->string('cover')->nullable();              // url or static path
            $t->string('music')->nullable();
            $t->json('dress_code')->nullable();
            $t->json('gift')->nullable();
            $t->json('backgrounds')->nullable();
            $t->text('location')->nullable();
            $t->json('extra')->nullable();                // template-specific fields
            $t->string('status', 20)->default('draft');   // draft | published
            $t->timestamp('published_at')->nullable();
            $t->timestamps();

            $t->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
