<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_plans', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // Unique reference string
            $table->string('name');
            $table->decimal('amount', 10, 2); // Amount allocated for the budget plan
            $table->date('start_date'); // Start date of the budget plan
            $table->date('end_date'); // End date of the budget plan
            $table->text('description')->nullable(); // Description of the budget plan (optional)
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
        Schema::dropIfExists('budget_plans');
    }
}
