<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // upload or external
            $table->enum('source', ['upload', 'external'])
                ->default('upload');

            // Storage configuration
            $table->string('disk')->nullable(); // cloudinary
            $table->string('path')->nullable(); // Cloudinary public_id

            // External image URL
            $table->text('original_url')->nullable();

            // Media information
            $table->string('resource_type')->default('image');
            $table->string('mime', 100)->nullable();
            $table->unsignedBigInteger('size')->default(0);

            // Optional metadata
            $table->string('alt')->nullable();
            $table->string('name')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'source']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
