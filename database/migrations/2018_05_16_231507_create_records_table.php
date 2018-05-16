<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->date('date');
            $table->string('description');
            $table->decimal('value');
            $table->unsignedInteger('category_id');

            $table->foreign('category_id')->references('id')->on('categories');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('records');
    }
}
