<template>
    <div class='container'>
        <div id="main-content">
            <Navigation v-if="!$route.meta.hideNavbar"/>
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
    import Navigation from './Navigation.vue'
    export default {
        components: { Navigation},
        data(){
            return {
                showNavigation:false
            }
        },
        mounted(){
            let isLoggedIn = localStorage.getItem('isLoggedIn')
            let path = window.location.pathname
            if(isLoggedIn == 'true' && (path !== "/login" && path !== "/register"))
                this.showNavigation = true
        },
        updated(){
            let isLoggedIn = localStorage.getItem('isLoggedIn')
            let path = window.location.pathname
            if(isLoggedIn == 'true' && (path !== "/login" && path !== "/register"))
                this.showNavigation = true
        },
        computed: {
            hide () {
                return this.$route.path === '/login' || this.$route.path === '/register'; 
            }
        }
    }
</script>