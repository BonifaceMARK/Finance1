<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_management', function (Blueprint $table) {
            $table->id();
            $table->decimal('inflow', 10, 2)->default(0); // Inflow amount
            $table->decimal('outflow', 10, 2)->default(0); // Outflow amount
            $table->decimal('net_income', 10, 2)->default(0); // Net income column

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
        Schema::dropIfExists('cash_management');
    }
}
