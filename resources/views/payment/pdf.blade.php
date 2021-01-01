<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TECHLAND LLC</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <table class="w-100">
        <tr>
            <td class="text-right">
                <img width="250px" src="{{ asset('images/logo.jpeg') }}" alt="{{ config('app.name') }}">
            </td>
        </tr>
    </table>

    <table class="table table-bordered w-100 mt-3">
        @if(!empty($payment->confirmation_number))
            <tr>
                <td colspan="2">
                    <strong>
                        {{ __('validation.attributes.confirmation_number') }}:
                    </strong>
                    {{ $payment->confirmation_number }}
                </td>
            </tr>
        @endif
        <tr>
            <td>
                <strong>
                    {{ __('validation.attributes.created_at') }}:
                </strong>
                {{ $payment->created_at->format('m/d/Y') }}
            </td>
            <td>
                <strong>
                    {{ __('validation.attributes.status') }}:
                </strong>
                {{ __('status.' . $payment->status) }}
            </td>
        </tr>
        <tr>
            <td>
                <strong>{{ __('validation.attributes.payment_date') }}:</strong>
                {{ $payment->date->format('m/d/Y') }}
            </td>
            <td>
                <strong>{{ __('validation.attributes.account_holder_name') }}:</strong>
                {{ $payment->account_holder }}
            </td>
        </tr>
        <tr>
            <td>
                <strong>{{ __('validation.attributes.amount') }} (USD):</strong>
                {{ $payment->amount }}
            </td>
            <td>
                <strong>{{ __('validation.attributes.bank') }}:</strong>
                {{ $payment->bank->name }}
            </td>
        </tr>
        <tr>
            <td>
                <strong>{{ __('validation.attributes.transaction_number') }}:</strong>
                {{ $payment->transaction_number }}
            </td>
            <td>
                <strong>{{ __('validation.attributes.customer_name') }} Techland:</strong>
                {{ $payment->customer_name }}
            </td>
        </tr>
        <tr>
            <td>
                <strong>{{ __('validation.attributes.sales_order_or_invoice') }}:</strong>
                {{ $payment->sales_order }}
            </td>
            <td>
                <strong>{{ __('validation.attributes.payment_type') }}:</strong>
                {{ __('payment_type.' . $payment->type) }}
            </td>
        </tr>
        @if(!empty($payment->customer_comment))
            <tr>
                <td colspan="2">
                    <strong>{{ __('validation.attributes.comments') }}:</strong>
                    {{ $payment->customer_comment }}
                </td>
            </tr>
        @endif
        {{--@if(!empty($payment->capture))
            <tr>
                <td colspan="2" style="height: 250px;">
                    <div>
                        <strong>{{ __('validation.attributes.capture') }}:</strong>
                    </div>
                    <img
                        src="{{ asset('storage/' . $payment->capture) }}"
                        style="max-width: 250px;max-height: 250px;position: absolute;"
                    >
                </td>
            </tr>
        @endif--}}
    </table>
</body>
</html>
