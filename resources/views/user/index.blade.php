@extends('layouts.app-menu')

@section('main')
    <user-list
        :items = "{{ json_encode($users->items()) }}"
        :total = "{{ $users->total() }}"
    >
        <template v-slot:pagination>{{ $users->links() }}</template>
    </user-list>
@endsection
