<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Use UUID for the notification ID
            $table->string('type'); // Notification type (class)
            $table->string('notifiable_type'); // The model type (e.g., User)
            $table->unsignedBigInteger('notifiable_id'); // The ID of the notifiable entity (User ID)
            $table->json('data'); // Notification data in JSON format
            $table->boolean('read')->default(false); // Whether the notification is read or not
            $table->timestamp('read_at')->nullable(); // Timestamp for when the notification was read
            $table->timestamps(); // Created at and updated at timestamps

            // Optionally, you can add foreign key constraints if you need relationships
            $table->foreign('notifiable_id')->references('id')->on('users')->onDelete('cascade'); // Adjust 'users' if needed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_notifications');
    }
}
