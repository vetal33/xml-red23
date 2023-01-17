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
        Schema::create('parcels', function (Blueprint $table) {
            $table->id();
            $table->string('cad_num')->nullable();
            $table->string('usage')->nullable();
            $table->string('extent')->nullable();
            $table->string('file_name')->nullable();
            $table->boolean('is_passed')->default(0);
            $table->unsignedDouble('area_origin')->nullable();
            $table->unsignedDouble('area_calculate')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('geom_id')->nullable();
            //$table->multiPolygon('geom')->nullable();
            $table->geometry('geom')->nullable();
            $table->geometry('original_geom')->nullable();
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
        Schema::dropIfExists('parcels');
    }
};
