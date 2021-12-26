<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_summaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_id');
            $table->tinyInteger('active')->default(1);

            $table->unsignedInteger('quantity');
            $table->decimal('amount_tl', 20, 8);
            $table->decimal('amount_usd', 20, 8);
            $table->decimal('price_tl', 20, 8);
            $table->decimal('price_usd', 20, 8);

            $table->decimal('cost_tl', 20, 8);
            $table->decimal('cost_usd', 20, 8);
            $table->decimal('profit_tl', 20, 8);
            $table->decimal('profit_usd', 20, 8);
            $table->decimal('profit_percent_tl', 20, 8);
            $table->decimal('profit_percent_usd', 20, 8);

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
        Schema::dropIfExists('account_summaries');
    }
}
