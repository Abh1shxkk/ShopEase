<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Brand Story sections
        Schema::create('brand_story_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('content');
            $table->string('image')->nullable();
            $table->string('image_position')->default('right'); // left, right, full, background
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Process steps
        Schema::create('process_steps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('icon')->nullable(); // icon name or image
            $table->string('image')->nullable();
            $table->integer('step_number');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Team members (optional for about page)
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->text('bio')->nullable();
            $table->string('image')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('process_steps');
        Schema::dropIfExists('brand_story_sections');
    }
};
