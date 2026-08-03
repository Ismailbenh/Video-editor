<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();

            $table->string('service_name'); // snapshot, survives service edits/deletes
            $table->string('tier_name');
            $table->decimal('price', 8, 2);

            $table->string('client_name');
            $table->string('client_email');
            $table->text('description')->nullable();
            $table->date('deadline')->nullable();
            $table->string('file_link')->nullable();

            // Workflow status — only meaningful once payment_status is 'paid'
            $table->string('status')->default('New');

            // pending -> paid | failed
            $table->string('payment_status')->default('pending');
            $table->string('stripe_session_id')->nullable();

            $table->text('notes')->nullable(); // internal, editor-only

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
