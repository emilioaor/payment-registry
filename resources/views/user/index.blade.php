@extends('layouts.app-menu')

@section('main')
    <user-list
        :items = "{{ json_encode($users->items()) }}"
        :total = "{{ $users->total() }}"
    ></user-list>
@endsection
