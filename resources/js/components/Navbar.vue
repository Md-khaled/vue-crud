<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <router-link :to="{name: 'home'}" class="nav-link" href="#" >Home <span class="sr-only">(current)</span></router-link>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item" v-if="!access_token">
                        <router-link :to="{name: 'login'}" class="nav-link">Login <i class="fas fa-sign-in-alt"></i></router-link>
                    </li>
                    <li class="nav-item" v-else>
                        <a class="nav-link" href="#">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</template>


<script>
export default{
    data(){
        return{
            access_token:false,
        }
    },
    mounted(){

        let token=localStorage.getItem('access_token')
        if(token){
            console.log(token);
            this.access_token=token;
        }
        console.log(token);
    }
}
</script>
