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
                        <a class="nav-link" @click="logout" style="cursor: pointer;">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</template>


<script>
export default{
    name:'Navbar',
    data(){
        return{
            access_token:false,
        }
    },
    methods: {
        logout(){
            let token=localStorage.getItem('access_token');
            const config = {
                headers: { Authorization: `Bearer ${token}` }
            }

            axios.delete('api/logout',config)
            .then((response)=>{
                console.log(response);
                localStorage.removeItem('access_token');
                localStorage.removeItem('user');
                this.$router.push({name:'login'});
                this.access_token=false;
            })
            .catch(()=>{

            })

            //bus.$emit('reloadData');
        }
    },
    mounted(){
        //check auth status
        if(localStorage.getItem('access_token')){
            this.access_token=true;
        }
        console.log(this.$root.$children[0].$children[1].$refs);
        //update auth status
        bus.$on('authStatus', (status) => {
            this.access_token=status;
        });

    }
}
</script>
