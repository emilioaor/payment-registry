@extends('layouts.app-menu')

@section('main')
    <payment-form
        :banks-available = "{{ json_encode($banksAvailable) }}"
        :user = "{{ json_encode(Auth::user()) }}"
    ></payment-form>
@endsection
