<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestorSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investor_summaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('investor_id');
            $table->tinyInteger('active')->default(1);

            $table->decimal('amount_tl', 20, 8);
            $table->decimal('amount_usd', 20, 8);

            $table->decimal('cost_tl', 20, 8);
            $table->decimal('cost_usd', 20, 8);

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
        Schema::dropIfExists('investor_summaries');
    }
}
