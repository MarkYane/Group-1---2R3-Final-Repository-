<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonesTable extends Migration
{
    public function up()
    {
        Schema::create('dones', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('title');
            $table->string('type'); // Movie, Music, or Book
            $table->boolean('done')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dones');
    }
}
