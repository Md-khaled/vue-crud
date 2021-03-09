<template>
    <div class="container">
        <Navbar></Navbar>
        <router-view></router-view>
        <!-- set progressbar -->
        <vue-progress-bar></vue-progress-bar>
    </div>
</template>

<script>
import Navbar from './components/Navbar'
export default {
    name:'App-master',
    components: {
        Navbar,
    },
    mounted() {
        //console.log('Component mounted.')

    }
}
</script>
<style>
#navbarNav a.router-link-exact-active {
    color: #42b983;
}
</style>
