@extends('layouts.app-menu')

@section('main')
    <payment-form
        :banks-available = "{{ json_encode($banksAvailable) }}"
        :payment-types-available = "{{ json_encode(App\Payment::paymentTypesAvailable()) }}"
        :edit-data = "{{ isset($payment) ? json_encode($payment) : 'null' }}"
        :user = "{{ json_encode(Auth::user()) }}"
    ></payment-form>
@endsection
