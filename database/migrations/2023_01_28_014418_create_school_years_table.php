<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id');
            $table->foreignId('campus_id');
            $table->string('name');
            $table->foreignId('status_id');
            $table->timestamps();
            $table->foreign('district_id')->references('id')->on('districts');
            $table->foreign('campus_id')->references('id')->on('campuses');
            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_years');
    }
};
