<template>
    <div class="card techland-list">
        <div class="card-header">
            <i class="fa fa-list"></i> {{ t('menu.payments') }}
        </div>
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
                        <th>{{ t('validation.attributes.payment_date') }}</th>
                        <th>{{ t('validation.attributes.account_holder_name') }}</th>
                        <th>{{ t('validation.attributes.bank') }}</th>
                        <th width="1%" class="text-center">{{ t('validation.attributes.amount') }}</th>
                        <th>{{ t('validation.attributes.customer_name') }}</th>
                        <th class="text-center">{{ t('validation.attributes.sales_order_or_invoice') }}</th>
                        <th width="5%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items" :key="item.id">
                        <td>{{ new Date(item.date) | date }}</td>
                        <td>{{ item.account_holder }}</td>
                        <td>{{ item.bank.name }}</td>
                        <td class="text-center">{{ item.amount }}</td>
                        <td>{{ item.customer_name }}</td>
                        <td class="text-center">{{ item.sales_order }}</td>
                        <td>
                            <a :href="'/payment/' + item.uuid + '/edit'" class="btn btn-secondary">
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
            items: {
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
