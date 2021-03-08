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
    components: {
        Navbar,
    },
    mounted() {
        //console.log('Component mounted.')

    }
}
</script>
