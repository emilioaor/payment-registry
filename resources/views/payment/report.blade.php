@extends('layouts.app-menu')

@section('main')
    <payment-report
        :banks-available = "{{ json_encode($banksAvailable) }}"
        :status-available = "{{ json_encode(\App\Payment::statusAvailable()) }}"
        :user = "{{ json_encode(Auth::user()) }}"
    ></payment-report>
@endsection
