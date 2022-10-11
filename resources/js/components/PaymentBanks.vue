<template>
    <form class="techland-form" @submit.prevent="validateForm()">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-list-alt"></i> {{ t('menu.banks') }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 form-group" v-for="bank in banks">
                        <input type="text" class="form-control" :value="bank.name" :readonly="true">
                    </div>

                    <div class="col-12 form-group" v-if="addNew">
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control"
                            :placeholder="t('validation.attributes.bank')"
                            :class="{'is-invalid': errors.has('name')}"
                            v-model="bank.name"
                            v-validate
                            data-vv-rules="required"
                        >

                        <span class="invalid-feedback" role="alert" v-if="errors.firstByRule('name', 'required')">
                            <strong>{{ t('validation.required', {attribute: 'bank'}) }}</strong>
                        </span>
                    </div>

                    <div class="col-12 form-group" v-if="! addNew">
                        <button class="btn btn-secondary" @click="addNewBank()">
                            <i class="fa fa-plus"></i>
                            Agregar nuevo
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-footer" v-if="addNew">
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
        name: 'payment-banks',
        props: {
            banks: {
                type: Array,
                required: true
            }
        },

        data() {
            return {
                loading: false,
                addNew: false,
                bank: {
                    name: null
                }
            }
        },

        methods: {
            addNewBank() {
                this.addNew = true;
                window.setTimeout(() => {
                    document.querySelector('#name').focus();
                }, 100);
            },

            validateForm() {
                this.$validator.validateAll().then(res => res && this.sendForm());
            },

            sendForm() {
                this.loading = true;

                ApiService.post('/payment/bank', this.bank)
                    .then(res => {
                        if (res.data.success) {
                            location.reload();
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        this.loading = false;
                    })
                ;
            }
        }
    }
</script>
