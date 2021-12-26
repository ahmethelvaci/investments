<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_summaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asset_id');
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

            $table->decimal('daily_price_tl', 20, 8);
            $table->decimal('daily_price_usd', 20, 8);
            $table->decimal('daily_profit_tl', 20, 8);
            $table->decimal('daily_profit_usd', 20, 8);
            $table->decimal('daily_profit_percent_tl', 20, 8);
            $table->decimal('daily_profit_percent_usd', 20, 8);

            $table->decimal('weekly_price_tl', 20, 8);
            $table->decimal('weekly_price_usd', 20, 8);
            $table->decimal('weekly_profit_tl', 20, 8);
            $table->decimal('weekly_profit_usd', 20, 8);
            $table->decimal('weekly_profit_percent_tl', 20, 8);
            $table->decimal('weekly_profit_percent_usd', 20, 8);

            $table->decimal('monthly_price_tl', 20, 8);
            $table->decimal('monthly_price_usd', 20, 8);
            $table->decimal('monthly_profit_tl', 20, 8);
            $table->decimal('monthly_profit_usd', 20, 8);
            $table->decimal('monthly_profit_percent_tl', 20, 8);
            $table->decimal('monthly_profit_percent_usd', 20, 8);

            $table->decimal('quarterly_price_tl', 20, 8);
            $table->decimal('quarterly_price_usd', 20, 8);
            $table->decimal('quarterly_profit_tl', 20, 8);
            $table->decimal('quarterly_profit_usd', 20, 8);
            $table->decimal('quarterly_profit_percent_tl', 20, 8);
            $table->decimal('quarterly_profit_percent_usd', 20, 8);

            $table->decimal('semiannually_price_tl', 20, 8);
            $table->decimal('semiannually_price_usd', 20, 8);
            $table->decimal('semiannually_profit_tl', 20, 8);
            $table->decimal('semiannually_profit_usd', 20, 8);
            $table->decimal('semiannually_profit_percent_tl', 20, 8);
            $table->decimal('semiannually_profit_percent_usd', 20, 8);

            $table->decimal('yearly_price_tl', 20, 8);
            $table->decimal('yearly_price_usd', 20, 8);
            $table->decimal('yearly_profit_tl', 20, 8);
            $table->decimal('yearly_profit_usd', 20, 8);
            $table->decimal('yearly_profit_percent_tl', 20, 8);
            $table->decimal('yearly_profit_percent_usd', 20, 8);

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
        Schema::dropIfExists('asset_summaries');
    }
}
