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
            $table->string('customer_number')->nullable();
            $table->string('customer_name');
            $table->string('sales_order')->nullable();
            $table->foreignId('bank_id')->constrained('banks');
            $table->string('transaction_number', 50)->nullable()->unique();
            $table->enum('status', ['pending', 'refused', 'approved']);
            $table->enum('type', ['ach', 'deposit', 'check', 'transfer', 'paypal']);
            $table->string('capture')->nullable();
            $table->text('customer_comment')->nullable();
            $table->text('techland_comment')->nullable();
            $table->string('confirmation_number', 50)->nullable()->unique();
            $table->foreignId('status_changed_by')->nullable()->constrained('users');
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
