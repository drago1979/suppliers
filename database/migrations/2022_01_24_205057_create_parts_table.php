<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->unsignedMediumInteger('id', true)->index();
            $table->unsignedSmallInteger('supplier_id');
            $table->unsignedTinyInteger('condition_id')->nullable();
            $table->unsignedSmallInteger('category_id')->nullable();

            $table->char('days_valid', 3)->nullable();
            $table->string('part_number', 100);
            $table->string('part_description', 250)->nullable();
            $table->char('quantity', 3)->nullable();
            $table->float('price')->nullable();

            $table->timestamps();

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onDelete('cascade');

            $table->foreign('condition_id')
                ->references('id')
                ->on('conditions');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parts');
    }
}
