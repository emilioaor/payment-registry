<template>
    <div class="d-flex w-100" id="menu-item" @focusout="focusOut()">
        <!-- Logo -->
        <div class="menu-item logo">
            <a href="/">
                <img class="logo" src="/images/logo.png" alt="logo">
            </a>
        </div>

        <!-- Button mobile -->
        <button type="button" class="button-mobile" @click="showMobile = !showMobile">
            <i v-if="showMobile" class="fa fa-caret-up"></i>
            <i v-else class="fa fa-caret-down"></i>
        </button>

        <!-- General menu -->
        <div
            v-for="(item, i) in menu"
            :key="i"
            class="menu-item menu-mobile"
            :class="{active: dropVisible === i, 'show-mobile': showMobile}"
            v-if="typeof item.show === 'undefined' || item.show"
        >
            <a href="" @click.prevent="openDropDown(i)">
                {{ t(item.label) }}

                <i v-if="menuLoading === i" class="spinner-border spinner-border-sm ml-3"></i>
                <i v-else-if="dropVisible === i" class="fa fa-caret-up ml-3"></i>
                <i v-else class="fa fa-caret-down ml-3"></i>
            </a>

            <div class="menu-drop d-flex" :class="{visible: dropVisible === i}">
                <div class="menu-item" v-for="child in item.children" v-if="typeof child.show === 'undefined' || child.show">
                    <a :href="child.route" @click="loading(i)">
                        <i class="fa fa-caret-right"></i>
                        {{ t(child.label) }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Session -->
        <div class="menu-item menu-mobile" :class="{active: dropVisible === 'session', 'show-mobile': showMobile}">
            <a href="" @click.prevent="openDropDown('session')">
                {{ user.name }}
                <i v-if="menuLoading === 'session'" class="spinner-border spinner-border-sm ml-3"></i>
                <i v-else-if="dropVisible === 'session'" class="fa fa-caret-up ml-3"></i>
                <i v-else class="fa fa-caret-down ml-3"></i>
            </a>

            <div class="menu-drop d-flex" :class="{visible: dropVisible === 'session'}">
                <div class="menu-item">
                    <a :href="'/user/config'"  @click="loading('session')">
                        <i class="fa fa-caret-right"></i>
                        {{ t('menu.security') }}
                    </a>
                </div>

                <div class="menu-item">
                    <a href="" @click.prevent="logout()">
                        <i class="fa fa-caret-right"></i>
                        {{ t('menu.logout') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Language -->
        <div class="menu-item menu-mobile" :class="{'show-mobile': showMobile}">

            <a href="" @click.prevent="">
                <i v-if="menuLoading === 'lang'" class="spinner-border spinner-border-sm"></i>
                <template v-else>
                    <img src="/images/en.png" :class="{selected: lang === 'en'}" @click="changeLanguage('en')" >
                    <img src="/images/es.png" :class="{selected: lang === 'es'}" @click="changeLanguage('es')" >
                </template>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            menu: {
                type: Array,
                required: true
            },

            user: {
                type: Object,
                required: true
            }
        },

        mounted() {
            this.lang = localStorage.getItem('lang') ? localStorage.getItem('lang') : 'en';
        },

        data() {
            return {
                dropVisible: null,
                menuLoading: null,
                showMobile: false,
                lang: null
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
            },

            loading(index) {
                this.menuLoading = index;
            },

            logout() {
                this.loading('session');
                document.getElementById('logout-form').submit();
            },

            changeLanguage(lang) {
                if (this.lang !== lang) {
                    this.menuLoading = 'lang';
                    localStorage.setItem('lang', lang);
                    location.reload();
                }
            }
        }
    }
</script>
