<template>
    <div  class="row justify-content-md-center">
        <div class="col-6 offsets-3 p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Login an Account!</h1>
            </div>

            <form @submit.prevent="login">
                <div class="form-group">
                    <input v-model="user.email" type="email" class="form-control" name="email"
                           placeholder="Enter Email Address...">
                </div>
                <div class="form-group">
                    <input v-model="user.password" type="password" class="form-control" name="password"
                           placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary btn-sm btn-block">Submit
                </button>
            </form>
            <hr>
            <div class="text-center">
                <router-link :to="{name: 'register'}" class="small">Don't have an account? Register!</router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'login',
    data() {
        return {
            user:{
                email:'',
                password:'',
            }
        }
    },
    methods:{
        login(){
            this.$Progress.start();
            axios.post('api/login',this.user)
            .then((response)=>{
                if(!response.data.success){
                    iziToast.error({
                        title: 'Errors',
                        position: 'topRight',
                        message: response.data.message,
                    });
                    this.$Progress.fail();
                    return;
                }

                localStorage.setItem('access_token', response.data.access_token);
                localStorage.setItem('user', JSON.stringify(response.data.user));

                this.updateAuthStatus();
                this.$Progress.finish();
                this.$router.push({name:'home'});

                //if(response.sucess).
            })
            .catch((errors)=>{
                let error=errors.response.data.errors;
                this.errorMsg(error);
                this.$Progress.fail();
            })
        },
        updateAuthStatus() {
            bus.$emit('authStatus', true)
        },
        successMsg(msg){
            iziToast.success({
                title: 'Success',
                position: 'topRight',
                message: msg,
            });
        },
        errorMsg(msg){
            $.each(msg,function(index,value) {
                iziToast.error({
                    title: 'Error',
                    position: 'topRight',
                    message: value,
                });
            })
        },
    },
    mounted() {
        console.log('Component mounted.')
    }
}
</script>
