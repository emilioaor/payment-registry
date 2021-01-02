<?php

use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bank = new \App\Bank();
        $bank->name = 'Banismo';
        $bank->save();

        $bank = new \App\Bank();
        $bank->name = 'Synovus';
        $bank->save();

        $bank = new \App\Bank();
        $bank->name = 'PayPal';
        $bank->payment_type = \App\Payment::TYPE_PAYPAL;
        $bank->save();
    }
}
