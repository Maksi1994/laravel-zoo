<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttributesForAnimals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('animals', function (Blueprint $table) {
            $table->integer('weight');
            $table->integer('age');
            $table->string('name');
            $table->bigInteger('place_id')->unsigned()->nullable();
            $table->foreign('place_id')->references('id')->on('places')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropForeign('animals_place_id_foreign');
            $table->dropColumn([
              'weight',
              'age',
              'name',
              'place_id'
            ]);
        });
    }
}
