<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('youtube');
            $table->string('thumbnail')->nullable();
            $table->boolean('featured')->default(false);
            $table->string('client')->nullable();
            $table->string('date')->nullable(); // stored as 'YYYY-MM'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_items');
    }
};