<template>
    <div class="d-flex" id="menu-item" @focusout="focusOut()">
        <div
            v-for="(item, i) in menu"
            :key="i"
            class="menu-item"
            :class="{active: dropVisible === i}"
            v-if="typeof item.show === 'undefined' || item.show"
        >
            <a href="" @click.prevent="openDropDown(i)">
                {{ item.label }}
                <i class="fa fa-caret-down ml-3"></i>
            </a>

            <div class="menu-drop d-flex" :class="{visible: dropVisible === i}">
                <div class="menu-item" v-for="child in item.children">
                    <a :href="child.route">
                        <i class="fa fa-caret-right"></i>
                        {{ child.label }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            menu: {
                type: Array,
                required: true
            }
        },

        data() {
            return {
                dropVisible: null
            }
        },

        methods: {
            openDropDown(index) {
                if (index === this.dropVisible) {
                    this.closeDropDown();
                } else {
                    this.dropVisible = index;
                }
            },

            closeDropDown() {
                this.dropVisible = null;
            },

            focusOut() {
                const currentVisible = this.dropVisible;

                window.setTimeout(() => {
                    if (this.dropVisible !== null && currentVisible === this.dropVisible) {
                        this.closeDropDown();
                    }
                }, 300);
            }
        }
    }
</script>
