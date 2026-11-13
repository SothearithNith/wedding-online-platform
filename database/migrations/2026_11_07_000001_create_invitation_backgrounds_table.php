<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitation_backgrounds', function (Blueprint $t) {
            $t->id();
            $t->string('template', 50)->default('gold');
            $t->string('section', 50)->default('all');
            $t->string('name')->nullable();
            $t->text('path');
            $t->string('event_type', 50)->default('wedding');
            $t->boolean('is_default')->default(false);
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();

            $t->index(['template', 'section', 'is_default'], 'inv_bg_lookup');
        });

        Schema::table('invitations', function (Blueprint $t) {
            if (! Schema::hasColumn('invitations', 'backgrounds')) {
                $t->json('backgrounds')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $t) {
            if (Schema::hasColumn('invitations', 'backgrounds')) {
                $t->dropColumn('backgrounds');
            }
        });
        Schema::dropIfExists('invitation_backgrounds');
    }
};
