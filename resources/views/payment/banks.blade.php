@extends('layouts.app-menu')

@section('main')
    <payment-banks
        :banks = "{{ json_encode($banks) }}"
    ></payment-banks>
@endsection
