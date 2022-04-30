<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dir', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dir')->default('default');
            $table->string('parent')->default('root');
            $table->string('owner');
            $table->string('viewer');
            $table->boolean('allowshare')->default(false);
            $table->boolean('allcanview')->default(false);
            $table->boolean('is_exercise')->default(true);
            $table->longText('exFile')->nullable();
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
        Schema::dropIfExists('dir');
    }
}
