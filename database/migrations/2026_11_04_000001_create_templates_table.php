<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $t) {
            $t->id();

            $t->string('key')->unique();
            $t->string('name');
            $t->string('preview_url')->nullable();

            $t->json('schema')->nullable();
            $t->json('sample_data')->nullable();

            $t->boolean('is_active')->default(true);
            $t->text('description')->nullable();

            $t->boolean('is_public')->default(true);
            $t->unsignedSmallInteger('sort')->default(0);

            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
