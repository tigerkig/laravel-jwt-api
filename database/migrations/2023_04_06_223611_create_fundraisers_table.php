<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundraisersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fundraisers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('isFeatured')->default(0);
            $table->decimal('target', 10, 2);
            $table->decimal('amount_raised', 10, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('organization_id');
            $table->timestamps();

            $table->foreign('organization_id')
                ->references('id')->on('organizations')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fundraisers');
    }
}
