<template>
    <div  class="row justify-content-md-center">
        <div class="col-6 offsets-3 p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
            </div>

            <form @submit.prevent="registration">
                <div class="form-group">
                    <input v-model="user.name" type="text" class="form-control" name="name" placeholder="First Name">
                </div>
                <div class="form-group">
                    <input v-model="user.email" type="email" class="form-control" name="email"
                           placeholder="Email Address">
                </div>
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <input v-model="user.password" type="password" class="form-control" name="password"
                               placeholder="Password">
                    </div>
                    <div class="col-sm-6">
                        <input v-model="user.password_confirmation" type="password" class="form-control form-control-user" name="password_confirmation" placeholder="Repeat Password">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm btn-block">Submit
                </button>
            </form>
            <hr>
            <div class="text-center">
                <router-link :to="{name: 'login'}" class="small">Already have an account? Login!</router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name:'Registration',
    data(){
        return {
            user:{
                name:'',
                email:'',
                password:'',
                password_confirmation:''
            }
        }
    },
    methods: {
       registration(){
           this.$Progress.start();
           axios.post('api/register',this.user)
               .then((response)=>{
                   console.log(response);
                   this.successMsg("Registration success");
                   localStorage.setItem('access_token',response.data.access_token);
                   this.updateAuthStatus();
                   this.$Progress.finish();
                   this.$router.push({name:'home'});
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
