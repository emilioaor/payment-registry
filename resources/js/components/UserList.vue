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
                        <th>{{ t('validation.attributes.name') }}</th>
                        <th>{{ t('validation.attributes.email') }}</th>
                        <th>{{ t('validation.attributes.role') }}</th>
                        <th width="5%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items" :key="item.id">
                        <td>{{ item.name }}</td>
                        <td>{{ item.email }}</td>
                        <td>{{ t('role.' + item.role) }}</td>
                        <td>
                            <a :href="'/user/' + item.uuid + '/edit'" class="btn btn-secondary">
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
