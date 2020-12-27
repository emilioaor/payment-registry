<template>
    <form class="techland-form" @submit.prevent="validateForm()">
        <div class="card">
            <div class="card-header">
                <div v-if="editData">
                    <i class="fa fa-plus"></i> {{ t('form.edit') }} {{ t('form.payment') }}
                </div>
                <div v-else>
                    <i class="fa fa-plus"></i> {{ t('form.add') }} {{ t('form.payment') }}
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-7 col-lg-9">

                        <div class="row">
                            <div class="col-sm-6 col-lg-4 form-group">
                                <label for="date">{{ t('validation.attributes.payment_date') }}</label>
                                <date-picker
                                    name = "date"
                                    id = "date"
                                    language="en"
                                    input-class = "form-control date-picker"
                                    format = "dd/MM/yyyy"
                                    v-model="date"
                                    @input="form.date = $event"

                                ></date-picker>
                            </div>

                            <div class="col-sm-6 col-lg-4 form-group">
                                <label for="account_holder">{{ t('validation.attributes.account_holder') }}</label>
                                <input
                                    type="text"
                                    id="account_holder"
                                    name="account_holder"
                                    class="form-control"
                                    :class="{'is-invalid': errors.has('account_holder')}"
                                    v-model="form.account_holder"
                                    v-validate
                                    data-vv-rules="required"
                                >

                                <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('account_holder', 'required')">
                                    <strong>{{ t('validation.required', {attribute: 'account_holder'}) }}</strong>
                                </span>
                            </div>

                            <div class="col-sm-6 col-lg-4 form-group">
                                <label for="customer_number">{{ t('validation.attributes.customer_number') }}</label>
                                <input
                                    type="text"
                                    id="customer_number"
                                    name="customer_number"
                                    class="form-control"
                                    :class="{'is-invalid': errors.has('customer_number')}"
                                    v-model="form.customer_number"
                                    v-validate
                                    data-vv-rules="required"
                                >

                                <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('customer_number', 'required')">
                                    <strong>{{ t('validation.required', {attribute: 'customer_number'}) }}</strong>
                                </span>
                            </div>

                            <div class="col-sm-6 col-lg-4 form-group">
                                <label for="customer_name">{{ t('validation.attributes.customer_name') }}</label>
                                <input
                                    type="text"
                                    id="customer_name"
                                    name="customer_name"
                                    class="form-control"
                                    :class="{'is-invalid': errors.has('customer_name')}"
                                    v-model="form.customer_name"
                                    v-validate
                                    data-vv-rules="required"
                                >

                                <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('customer_name', 'required')">
                                    <strong>{{ t('validation.required', {attribute: 'customer_name'}) }}</strong>
                                </span>
                            </div>

                            <div class="col-sm-6 col-lg-4 form-group">
                                <label for="sales_order">{{ t('validation.attributes.sales_order') }}</label>
                                <input
                                    type="text"
                                    id="sales_order"
                                    name="sales_order"
                                    class="form-control"
                                    :class="{'is-invalid': errors.has('sales_order')}"
                                    v-model="form.sales_order"
                                    v-validate
                                    data-vv-rules="required"
                                >

                                <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('sales_order', 'required')">
                                    <strong>{{ t('validation.required', {attribute: 'sales_order'}) }}</strong>
                                </span>
                            </div>

                            <div class="col-sm-6 col-lg-4 form-group">
                                <label for="bank_id">{{ t('validation.attributes.bank') }}</label>
                                <select
                                    name="bank_id"
                                    id="bank_id"
                                    class="form-control"
                                    v-model="form.bank_id"
                                >
                                    <option
                                        v-for="bank in banksAvailable"
                                        :key="bank.id"
                                        :value="bank.id">
                                        {{ bank.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-sm-6 col-lg-4 form-group">
                                <label for="transaction_number">{{ t('validation.attributes.transaction_number') }}</label>
                                <input
                                    type="text"
                                    id="transaction_number"
                                    name="transaction_number"
                                    class="form-control"
                                    :class="{'is-invalid': errors.has('transaction_number') || exists}"
                                    @keypress="exists = false"
                                    v-model="form.transaction_number"
                                    v-validate
                                    data-vv-rules="required"
                                >

                                <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('transaction_number', 'required')">
                                    <strong>{{ t('validation.required', {attribute: 'transaction_number'}) }}</strong>
                                </span>

                                <span class="invalid-feedback" role="alert" v-if="exists">
                                    <strong>{{ t('validation.unique', {attribute: 'transaction_number'}) }}</strong>
                                </span>
                            </div>

                            <div class="col-sm-6 col-lg-4 form-group">
                                <label for="amount">{{ t('validation.attributes.amount') }} (USD)</label>
                                <input
                                    type="text"
                                    id="amount"
                                    name="amount"
                                    class="form-control"
                                    :class="{'is-invalid': errors.has('amount')}"
                                    v-model="form.amount"
                                    v-validate
                                    data-vv-rules="required|regex:^[0-9]+(\.[0-9]+)?$"
                                >

                                <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('amount', 'required')">
                                    <strong>{{ t('validation.required', {attribute: 'amount'}) }}</strong>
                                </span>

                                <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('amount', 'regex')">
                                    <strong>{{ t('validation.regex', {attribute: 'amount'}) }}</strong>
                                </span>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-5 col-lg-3 form-group">
                        <label>{{ t('validation.attributes.capture') }}</label>
                        <div class="capture" @click="openImageExplorer()">
                            <input type="file" id="capture" class="d-none" @change="changeImage">

                            <img
                                v-if="form.capture"
                                :src="editData ? '/storage/' + form.capture : form.capture"
                                :alt="t('validation.attributes.capture')"
                            >
                            <i v-else class="fa fa-camera"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button v-if="!loading" class="btn btn-success">
                    <i class="fa fa-save"></i>
                    {{ t('form.save') }}
                </button>

                <i v-if="loading" class="spinner-border spinner-border-sm"></i>
            </div>
        </div>
    </form>
</template>

<script>
    import ApiService from "../services/ApiService";

    export default {
        props: {
            banksAvailable: {
                type: Array,
                required: true
            },
            user: {
                type: Object|null,
                required: true
            },
            editData: {
                type: Object,
                required: false
            }
        },

        mounted() {
            if (this.editData) {
                this.form = {...this.editData};
            }
        },

        data() {
            return {
                date: new Date(),
                loading: false,
                exists: false,
                form: {
                    date: new Date(),
                    account_holder: null,
                    customer_number: null,
                    customer_name: null,
                    sales_order: null,
                    bank_id: this.banksAvailable[0].id,
                    transaction_number: null,
                    amount: null,
                    capture: null
                }
            }
        },

        methods: {
            validateForm() {
                this.$validator.validateAll().then(res => res && this.checkIfPaymentExists());
            },

            checkIfPaymentExists() {
                this.loading = true;

                ApiService.post('/payment/exists', {transaction_number: this.form.transaction_number})
                    .then(res => {
                        if (!res.data.data || (this.editData && this.editData.uuid === res.data.data.uuid)) {
                            this.sendForm();
                        } else {
                            this.exists = true;
                            this.loading = false;
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                    })
            },

            sendForm() {
                const apiService = this.editData ?
                    ApiService.put('/payment/' + this.editData.uuid, this.form) :
                    ApiService.post('/payment', this.form)
                ;

                apiService.then(res => {
                    if (res.data.success) {
                        location.reload();
                    }
                }).catch(err => {
                    this.loading = false;
                })
            },

            openImageExplorer() {
                if (this.editData) {
                    if (this.editData.capture) {
                        window.open('/storage/' + this.editData.capture)
                    }
                } else {
                    document.querySelector('#capture').click();
                }
            },

            changeImage(e) {
                const file = $('#capture')[0].files[0];

                if (!file || (file.type !== 'image/png' && file.type !== 'image/jpeg')) {
                    return false;
                }

                const reader = new FileReader();

                reader.addEventListener('load', () => {
                    if (reader.result) {
                        this.form.capture = reader.result;
                    }
                });

                reader.readAsDataURL(file);
            },
        }
    }
</script>

<style scoped lang="scss">
    .capture {
        background-color: rgba(0, 19, 29, 0.4);
        color: rgba(0, 19, 29, 1);
        width: 100%;
        font-size: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: calc(100% - 30px);
        max-height: 200px;
        cursor: pointer;
        border-radius: 3px;
        padding: 1rem;

        img {
            max-width: 100%;
            max-height: 100%;
        }
    }
</style>
