<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_account_id')->nullable()->constrained('accounts');
            $table->foreignId('buyer_account_id')->nullable()->constrained('accounts');
            $table->bigInteger('quantity');
            $table->decimal('price', 20, 8);
            $table->decimal('transaction_fee', 20, 8)->default(0);
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
        Schema::dropIfExists('trade_orders');
    }
}
