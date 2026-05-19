<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->string('link_url')->nullable();
            $table->string('placement')->default('sidebar');  // sidebar, feed, banner, welcome
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedBigInteger('click_count')->default(0);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'placement']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
