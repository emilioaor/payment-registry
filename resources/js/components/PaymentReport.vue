<template>
    <form class="techland-form" @submit.prevent="validateForm()">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-list-alt"></i> {{ t('menu.report') }} {{ t('menu.payments') }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 form-group">
                        <label for="start">{{ t('validation.attributes.start_date') }}</label>
                        <date-picker
                            name = "start"
                            id = "start"
                            language="en"
                            input-class = "form-control date-picker"
                            format = "dd/MM/yyyy"
                            v-model="start"
                            @input="form.start = changeDate($event, 0, 0, 0)"
                        ></date-picker>
                    </div>

                    <div class="col-sm-6 col-lg-4 form-group">
                        <label for="end">{{ t('validation.attributes.end_date') }}</label>
                        <date-picker
                            name = "end"
                            id = "end"
                            language="en"
                            input-class = "form-control date-picker"
                            format = "dd/MM/yyyy"
                            v-model="end"
                            @input="form.end = changeDate($event, 23, 59, 59)"
                        ></date-picker>
                    </div>

                    <div class="col-sm-6 col-lg-4 form-group">
                        <label for="status">{{ t('validation.attributes.status') }}</label>
                        <select
                            name="status"
                            id="status"
                            class="form-control"
                            v-model="form.status"
                        >
                            <option :value="null">{{ t('form.all') }}</option>
                            <option
                                v-for="(label, value) in statusAvailable"
                                :value="value">
                                {{ label }}
                            </option>
                        </select>
                    </div>

                    <div class="col-sm-6 col-lg-4 form-group">
                        <label for="bank_id">{{ t('validation.attributes.bank') }}</label>
                        <select
                            name="bank_id"
                            id="bank_id"
                            class="form-control"
                            v-model="form.bank_id"
                        >
                            <option :value="null">{{ t('form.all') }}</option>
                            <option
                                v-for="bank in banksAvailable"
                                :value="bank.id">
                                {{ bank.name }}
                            </option>
                        </select>
                    </div>

                    <div class="col-sm-6 col-lg-4 form-group">
                        <label for="sales_order">{{ t('validation.attributes.sales_order') }}</label>
                        <input
                            type="text"
                            name="sales_order"
                            id="sales_order"
                            class="form-control"
                            v-model="form.sales_order"
                        >
                    </div>

                    <div class="col-sm-6 col-lg-4 form-group">
                        <label for="transaction_number">{{ t('validation.attributes.transaction_number') }}</label>
                        <input
                            type="text"
                            name="transaction_number"
                            id="transaction_number"
                            class="form-control"
                            v-model="form.transaction_number"
                        >
                    </div>

                    <div class="col-sm-6 col-lg-4 form-group">
                        <label for="account_holder">{{ t('validation.attributes.account_holder') }}</label>
                        <input
                            type="text"
                            name="account_holder"
                            id="account_holder"
                            class="form-control"
                            v-model="form.account_holder"
                        >
                    </div>

                    <div class="col-sm-6 col-lg-4 form-group">
                        <label for="customer_name">{{ t('validation.attributes.customer_name') }}</label>
                        <input
                            type="text"
                            name="customer_name"
                            id="customer_name"
                            class="form-control"
                            v-model="form.customer_name"
                        >
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button v-if="!loading" class="btn btn-success">
                    <i class="fa fa-list-alt"></i>
                    {{ t('form.report') }}
                </button>

                <i v-if="loading" class="spinner-border spinner-border-sm"></i>
            </div>
            <div class="card-body" v-if="results.length">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>{{ t('validation.attributes.payment_date') }}</th>
                            <th>{{ t('validation.attributes.bank') }}</th>
                            <th>{{ t('validation.attributes.account_holder') }}</th>
                            <th>{{ t('validation.attributes.customer_name') }}</th>
                            <th class="text-center">{{ t('validation.attributes.sales_order') }}</th>
                            <th class="text-center">{{ t('validation.attributes.amount') }}</th>
                            <th class="text-center" v-if="user.role === 'administrator'">{{ t('validation.attributes.status_changed_by') }}</th>
                            <th class="text-center">{{ t('validation.attributes.status') }}</th>
                            <th width="5%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="result in results" :key="result.id">
                            <td>{{ result.date | date }}</td>
                            <td>{{ result.bank.name }}</td>
                            <td>{{ result.account_holder }}</td>
                            <td>{{ result.customer_name }}</td>
                            <td class="text-center">{{ result.sales_order }}</td>
                            <td class="text-center">{{ result.amount }}</td>
                            <td class="text-center" v-if="user.role === 'administrator'">
                                {{ result.status_changed_by ? result.status_changed_by.name : '' }}
                            </td>
                            <td class="text-center">
                                <span
                                    class="text-white p-2 rounded"
                                    :class="{
                                            'bg-info': result.status === 'pending',
                                            'bg-success': result.status === 'approved',
                                            'bg-danger': result.status === 'refused',
                                        }"
                                >
                                        {{ t('status.' + result.status) }}
                                    </span>
                            </td>
                            <td>
                                <a :href="'/payment/' + result.uuid + '/edit'" target="_blank" class="btn btn-secondary">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</template>

<script>
    import ApiService from "../services/ApiService";

    export default {
        props: {
            statusAvailable: {
                type: Object,
                required: true
            },
            banksAvailable: {
                type: Array,
                required: true
            },
            user: {
                type: Object,
                required: true
            }
        },

        mounted() {
            this.form.start = this.changeDate(this.start, 0, 0, 0);
            this.form.end = this.changeDate(this.end, 23, 59, 59);
        },

        data() {
            return {
                loading: false,
                start: new Date(),
                end: new Date(),
                form: {
                    start: null,
                    end: null,
                    status: null,
                    bank_id: null,
                    sales_order: null,
                    transaction_number: null,
                    account_holder: null,
                    customer_name: null
                },
                results: []
            }
        },

        methods: {
            validateForm() {
                this.$validator.validateAll().then(res => res && this.sendForm());
            },

            sendForm() {
                this.loading = true;
                this.results = [];

                ApiService.post('/payment/report', this.form).then(res => {
                    this.results = res.data.data;
                    this.loading = false;
                }).catch(err => {
                    this.loading = false;
                })
            },

            changeDate(date, h, i, s) {
                date.setHours(h, i, s);

                return date;
            }
        }
    }
</script>
