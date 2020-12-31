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
                <div class="row" v-if="editData">

                    <div class="col-12 text-right print-only">
                        <img class="logo" src="/images/logo.jpeg" alt="">
                    </div>

                    <div class="col-sm-6 col-lg-3 form-group">
                        <label>{{ t('validation.attributes.created_at') }}:</label>
                        {{ editData.created_at | date }}
                    </div>

                    <div class="col-sm-6 col-lg-3 form-group">
                        <label>{{ t('validation.attributes.status') }}:</label>
                        <span
                            class="text-white p-2 rounded status"
                            :class="{
                                    'bg-info': this.editData.status === 'pending',
                                    'bg-success': this.editData.status === 'approved',
                                    'bg-danger': this.editData.status === 'refused',
                                }"
                        >
                                {{ t('status.' + this.editData.status) }}
                            </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-lg-3 form-group">
                        <label for="date_picker">{{ t('validation.attributes.payment_date') }}</label>
                        <date-picker
                            name = "date_picker"
                            id = "date_picker"
                            language="en"
                            :input-class = "{'d-none': !form.date, 'form-control date-picker': true}"
                            format = "MM/dd/yyyy"
                            v-model="date"
                            @input="form.date = $event"
                            :disabled-picker="editData && editData.status !== 'pending'"
                            :disabled="{from: new Date()}"
                            ref="datePicker"
                        ></date-picker>

                        <input
                            type="text"
                            v-show="! form.date"
                            id="date"
                            name="date"
                            class="form-control"
                            :class="{'is-invalid': errors.has('date')}"
                            @click="$refs.datePicker.showCalendar()"
                            v-model="form.date"
                            readonly
                            v-validate
                            data-vv-rules="required"
                        >

                        <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('date', 'required')">
                            <strong>{{ t('validation.required', {attribute: 'date'}) }}</strong>
                        </span>
                    </div>

                    <div class="col-sm-6 col-lg-3 form-group">
                        <label for="account_holder">{{ t('validation.attributes.account_holder_name') }}</label>
                        <input
                            type="text"
                            id="account_holder"
                            name="account_holder"
                            class="form-control"
                            :class="{'is-invalid': errors.has('account_holder')}"
                            v-model="form.account_holder"
                            v-validate
                            data-vv-rules="required"
                            :readonly="editData && editData.status !== 'pending'"
                        >

                        <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('account_holder', 'required')">
                            <strong>{{ t('validation.required', {attribute: 'account_holder_name'}) }}</strong>
                        </span>
                    </div>

                    <div class="col-sm-6 col-lg-3 form-group">
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
                            :readonly="editData && editData.status !== 'pending'"
                        >

                        <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('amount', 'required')">
                            <strong>{{ t('validation.required', {attribute: 'amount'}) }}</strong>
                        </span>

                        <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('amount', 'regex')">
                            <strong>{{ t('validation.regex', {attribute: 'amount'}) }}</strong>
                        </span>
                    </div>

                    <div class="col-sm-6 col-lg-3 form-group">
                        <label for="bank_id">{{ t('validation.attributes.bank') }}</label>
                        <select
                            name="bank_id"
                            id="bank_id"
                            class="form-control"
                            v-model="form.bank_id"
                            :readonly="editData && editData.status !== 'pending'"
                        >
                            <option
                                v-for="bank in banksAvailable"
                                :key="bank.id"
                                :value="bank.id">
                                {{ bank.name }}
                            </option>
                        </select>
                    </div>

                    <div class="col-sm-6 col-lg-3 form-group">
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
                            data-vv-rules=""
                            :readonly="editData && editData.status !== 'pending'"
                        >

                        <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('transaction_number', 'required')">
                            <strong>{{ t('validation.required', {attribute: 'transaction_number'}) }}</strong>
                        </span>

                        <span class="invalid-feedback" role="alert" v-if="exists">
                            <strong>{{ t('validation.unique', {attribute: 'transaction_number'}) }}</strong>
                        </span>
                    </div>

                    <div class="col-sm-6 col-lg-3 form-group">
                        <label for="customer_name">{{ t('validation.attributes.customer_name') }} Techland</label>
                        <input
                            type="text"
                            id="customer_name"
                            name="customer_name"
                            class="form-control"
                            :class="{'is-invalid': errors.has('customer_name')}"
                            v-model="form.customer_name"
                            v-validate
                            data-vv-rules="required"
                            :readonly="editData && editData.status !== 'pending'"
                        >

                        <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('customer_name', 'required')">
                            <strong>{{ t('validation.required', {attribute: 'customer_name'}) }}</strong>
                        </span>
                    </div>

                    <div class="col-sm-6 col-lg-3 form-group">
                        <label for="sales_order">{{ t('validation.attributes.sales_order_or_invoice') }}</label>
                        <input
                            type="text"
                            id="sales_order"
                            name="sales_order"
                            class="form-control"
                            :class="{'is-invalid': errors.has('sales_order')}"
                            v-model="form.sales_order"
                            v-validate
                            data-vv-rules=""
                            :readonly="editData && editData.status !== 'pending'"
                        >

                        <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('sales_order', 'required')">
                            <strong>{{ t('validation.required', {attribute: 'sales_order_or_invoice'}) }}</strong>
                        </span>
                    </div>

                    <div class="col-sm-6 col-lg-3 form-group">
                        <label for="payment_type">{{ t('validation.attributes.payment_type') }}</label>
                        <select
                            name="payment_type"
                            id="payment_type"
                            class="form-control"
                            v-model="form.type"
                            :readonly="editData && editData.status !== 'pending'"
                        >
                            <option
                                v-for="(label, value) in paymentTypesAvailable"
                                :value="value">
                                {{ t('payment_type.' + value) }}
                            </option>
                        </select>
                    </div>

                    <div class="col-sm-6 col-lg-3 form-group">
                        <label for="customer_comment">{{ t('validation.attributes.comments') }}</label>
                        <textarea
                            name="customer_comment"
                            id="customer_comment"
                            cols="30"
                            rows="5"
                            class="form-control"
                            v-model="form.customer_comment"
                            :readonly="editData"
                        ></textarea>
                    </div>

                    <div class="col-sm-6 col-lg-3 form-group no-print">
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

                    <div class="col-sm-6 col-lg-3 form-group" v-if="editData">
                        <label for="techland_comment">{{ t('validation.attributes.comments') }} (Techland)</label>
                        <textarea
                            name="techland_comment"
                            id="techland_comment"
                            cols="30"
                            rows="5"
                            class="form-control"
                            v-model="form.techland_comment"
                            :readonly="editData && editData.status !== 'pending'"
                        ></textarea>
                    </div>

                    <div class="col-sm-6 col-lg-3 form-group" v-if="editData">
                        <label for="confirmation_number">{{ t('validation.attributes.confirmation_number') }}</label>
                        <input
                            type="text"
                            id="confirmation_number"
                            name="confirmation_number"
                            class="form-control"
                            :class="{'is-invalid': errors.has('confirmation_number', 'confirmation') || confirmationExists}"
                            v-model="form.confirmation_number"
                            @keypress="confirmationExists = false"
                            v-validate
                            data-vv-rules="required"
                            :readonly="editData && editData.status !== 'pending'"
                            data-vv-scope="confirmation"
                        >

                        <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('confirmation_number', 'required', 'confirmation')">
                            <strong>{{ t('validation.required', {attribute: 'confirmation_number'}) }}</strong>
                        </span>

                        <span class="invalid-feedback" role="alert" v-if="confirmationExists">
                            <strong>{{ t('validation.unique', {attribute: 'confirmation_number'}) }}</strong>
                        </span>
                    </div>

                    <div class="col-12 form-group print-only" v-if="editData && form.capture">
                        <label>{{ t('validation.attributes.capture') }}</label>
                        <div class="capture" @click="openImageExplorer()">
                            <img
                                :src="'/storage/' + form.capture"
                                :alt="t('validation.attributes.capture')"
                            >
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <button v-if="!loading && (! editData || editData.status === 'pending')" class="btn btn-success">
                    <i class="fa fa-save"></i>
                    {{ t('form.save') }}
                </button>

                <button-confirmation
                    :label="t('form.confirm')"
                    btn-class="btn btn-primary"
                    icon-class="fa fa-check"
                    v-if="!loading && editData && editData.status === 'pending'"
                    :confirmation="t('form.areYouSure')"
                    :buttons="[
                        {
                            label: t('form.yes'),
                            btnClass: 'btn btn-success',
                            code: 'yes'
                        },
                        {
                            label: t('form.no'),
                            btnClass: 'btn btn-danger',
                            code: 'no'
                        }
                    ]"
                    @confirmed="validateChangeStatus($event, 'approved')"
                ></button-confirmation>

                <button-confirmation
                    :label="t('form.refuse')"
                    btn-class="btn btn-danger"
                    icon-class="fa fa-remove"
                    v-if="!loading && editData && editData.status === 'pending'"
                    :confirmation="t('form.areYouSure')"
                    :buttons="[
                        {
                            label: t('form.yes'),
                            btnClass: 'btn btn-success',
                            code: 'yes'
                        },
                        {
                            label: t('form.no'),
                            btnClass: 'btn btn-danger',
                            code: 'no'
                        }
                    ]"
                    @confirmed="changeStatus($event, 'refused', null)"
                ></button-confirmation>

                <button type="button" v-if="!loading && editData" class="btn btn-info text-white" @click="print()">
                    <i class="fa fa-print"></i>
                    {{ t('form.print') }}
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
            paymentTypesAvailable: {
                type: Object,
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
                this.form = {
                    ...this.editData
                };

                this.date = new Date(this.form.date);
            }
        },

        data() {
            return {
                date: new Date(),
                loading: false,
                exists: false,
                confirmationExists: false,
                form: {
                    date: null,
                    account_holder: null,
                    customer_number: null,
                    customer_name: null,
                    sales_order: null,
                    bank_id: this.banksAvailable[0].id,
                    transaction_number: null,
                    amount: null,
                    capture: null,
                    type: 'ach',
                    customer_comment: null,
                    techland_comment: null,
                    confirmation_number: null
                }
            }
        },

        methods: {
            validateForm() {
                this.$validator.validateAll().then(res => res && this.checkIfPaymentExists());
            },

            checkIfPaymentExists() {
                this.loading = true;

                if (! this.form.transaction_number) {
                    return this.sendForm();
                }

                ApiService.post('/payment/exists', {
                    field: 'transaction_number',
                    value: this.form.transaction_number
                })
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

            validateChangeStatus(code, status) {
                if (code === 'yes') {
                    this.$validator.validateAll('confirmation').then(res => res && this.checkIfConfirmationExists(code, status));
                }
            },

            checkIfConfirmationExists(code, status) {
                this.loading = true;

                ApiService.post('/payment/exists', {
                    field: 'confirmation_number',
                    value: this.form.confirmation_number
                })
                    .then(res => {
                        if (!res.data.data) {
                            this.changeStatus(code, status, this.form.confirmation_number);
                        } else {
                            this.confirmationExists = true;
                            this.loading = false;
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                    })
            },

            changeStatus(code, status, confirmationNumber) {
                if (code === 'yes') {
                    this.loading = true;

                    ApiService.put('/payment/' + this.editData.uuid + '/change-status/' + status, {
                        confirmation_number: confirmationNumber
                    }).then(res => {
                        if (res.data.success) {
                            location.reload();
                        }
                    }).catch(err => {
                        this.loading = false;
                    })
                }
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

            print() {
                window.print();
            }
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
