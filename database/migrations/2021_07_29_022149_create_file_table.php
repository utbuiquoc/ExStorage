<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fileName');
            $table->string('name');
            $table->string('dir')->default('default');
            $table->string('parent')->default('root');
            $table->string('type');
            $table->string('owner');
            $table->text('viewer');
            $table->boolean('allowshare')->default(false);
            $table->boolean('allcanview')->default(false);
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
        Schema::dropIfExists('file');
    }
}
