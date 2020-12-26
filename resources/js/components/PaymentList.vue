<template>
    <div class="card techland-list">
        <div class="card-body">
            <table-filter>
                <template v-slot:total v-if="total">
                    <div class="text-right">
                        <strong>{{ t('form.total') }}:</strong> {{ total }}
                    </div>
                </template>
            </table-filter>

            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>{{ t('validation.attributes.created_at') }}</th>
                        <th>{{ t('validation.attributes.payment_date') }}</th>
                        <th>{{ t('validation.attributes.bank') }}</th>
                        <th>{{ t('validation.attributes.account_holder') }}</th>
                        <th>{{ t('validation.attributes.customer_name') }}</th>
                        <th class="text-center">{{ t('validation.attributes.sales_order') }}</th>
                        <th class="text-center">{{ t('validation.attributes.transaction_number') }}</th>
                        <th width="1%" class="text-center">{{ t('validation.attributes.amount') }}</th>
                        <th width="5%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="payment in payments" :key="payment.id">
                        <td>{{ new Date(payment.created_at) | date }}</td>
                        <td>{{ new Date(payment.date) | date }}</td>
                        <td>{{ payment.bank.name }}</td>
                        <td>{{ payment.account_holder }}</td>
                        <td>{{ payment.customer_name }}</td>
                        <td class="text-center">{{ payment.sales_order }}</td>
                        <td class="text-center">{{ payment.transaction_number }}</td>
                        <td class="text-center">{{ payment.amount }}</td>
                        <td>
                            <a class="btn btn-secondary">
                                <i class="fa fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div>
                <slot name="pagination"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            payments: {
                type: Array,
                required: true
            },

            total: {
                type: Number,
                required: false
            }
        }
    }
</script>
