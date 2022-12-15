<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomingMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incoming_messages', function (Blueprint $table) {
            $table->id();

            $table->string('bot_name', 64)->nullable(); // имя бота

            $table->string('update_id', 64)->nullable(); // обновление
            $table->string('message_id', 64)->nullable(); // идентификатор сообщения
            $table->string('from_id', 64)->nullable(); // идентификатор отправителя
            $table->string('first_name', 128)->nullable(); // имя отправителя
            $table->string('last_name', 128)->nullable(); // фамилия отправителя
            $table->string('username', 128)->nullable(); // ник отправителя
            $table->string('chat_id', 64)->nullable(); // идентификатор чата
            $table->string('date', 64)->nullable(); // временная метка отправления
            $table->text('text')->nullable(); // текст сообщения

            $table->string('callback_data', 64)->nullable(); // callback-команда
            $table->string('callback_message_id', 64)->nullable(); // идентификатор callback-сообщения
            $table->string('callback_chat_id', 64)->nullable(); // идентификатор callback-чата

            $table->integer('mark')->nullable(); // числовой маркер
            $table->text('notes')->nullable(); // текстовая метка
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incoming_messages');
    }
}
