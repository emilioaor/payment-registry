<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
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
            $table->string('uuid', 15)->unique();
            $table->timestamp('date');
            $table->string('account_holder');
            $table->float('amount');
            $table->string('customer_name');
            $table->string('sales_order');
            $table->foreignId('bank_id')->constrained('banks');
            $table->string('transaction_number', 50)->unique();
            $table->enum('status', ['pending', 'refused', 'approved']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
