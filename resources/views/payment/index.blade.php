@extends('layouts.app-menu')

@section('main')
    <payment-list
        :items = "{{ json_encode($payments->items()) }}"
        :total = "{{ $payments->total() }}"
    >
        <template v-slot:pagination>{{ $payments->links() }}</template>
    </payment-list>
@endsection
