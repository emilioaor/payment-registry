@extends('layouts.app-menu')

@section('main')
    <payment-form
        :banks-available = "{{ json_encode($banksAvailable) }}"
        :edit-data = "{{ isset($payment) ? json_encode($payment) : 'null' }}"
        :user = "{{ json_encode(Auth::user()) }}"
    ></payment-form>
@endsection
