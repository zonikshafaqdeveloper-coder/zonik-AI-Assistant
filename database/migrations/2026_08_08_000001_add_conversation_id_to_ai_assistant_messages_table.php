<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_assistant_messages', function (Blueprint $table) {
            $table->string('conversation_id', 64)->nullable()->after('outlet_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('ai_assistant_messages', function (Blueprint $table) {
            $table->dropIndex(['conversation_id']);
            $table->dropColumn('conversation_id');
        });
    }
};
