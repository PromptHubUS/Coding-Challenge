<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromptResponsesTable extends Migration
{
    public function up()
    {
        Schema::create('prompt_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('model_id')->constrained('ai_models')->onDelete('cascade');
            $table->text('prompt');
            $table->text('modifier');
            $table->text('intermediate_result');
            $table->text('final_result');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prompt_responses');
    }
}
