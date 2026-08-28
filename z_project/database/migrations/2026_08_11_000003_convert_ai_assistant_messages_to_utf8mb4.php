<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ConvertAiAssistantMessagesToUtf8mb4 extends Migration
{
    public function up()
    {
        // MySQL's legacy `utf8` charset only stores three-byte characters and
        // rejects emoji. Assistant messages need full Unicode for multilingual
        // conversations and natural shopkeeper-style replies.
        DB::statement('ALTER TABLE ai_assistant_messages CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    public function down()
    {
        // Do not convert back to utf8mb3: doing so could destroy valid four-byte
        // Unicode messages that were saved after this migration.
    }
}
