@extends('layouts.app')

@section('content')
    <payment-form
        :banks-available = "{{ json_encode($banksAvailable) }}"
    ></payment-form>
@endsection
