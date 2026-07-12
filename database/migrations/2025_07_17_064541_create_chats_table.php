<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();

            // Participants
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id')->nullable(); 

            // Message Content
            $table->text('message')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('message_type', ['text', 'image', 'video', 'file', 'system'])->default('text');

            // Reply/Threading
            $table->unsignedBigInteger('reply_to_id')->nullable(); // self reference

            // Delivery & Status
            $table->boolean('is_read')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('sent_at')->nullable();

            $table->enum('status', ['pending', 'sent', 'delivered', 'read', 'failed'])->default('sent');


            $table->json('meta')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reply_to_id')->references('id')->on('chats')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
