<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
                $table->string('currency')->nullable();
                $table->string('firstname')->nullable();
                $table->string('lastname')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('country')->nullable();
                $table->string('state')->nullable();
                $table->string('city')->nullable();
                $table->string('paymentMethod')->nullable();
                $table->decimal('amount', 8, 2)->nullable();
                $table->string('status')->default('pending');
                $table->boolean('isAnonymous');
                $table->unsignedBigInteger('fundraiser_id');
                $table->foreign('fundraiser_id')->references('id')->on('fundraisers');
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
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
}
