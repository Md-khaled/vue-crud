
<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <form @submit.prevent="searchProduct()" class="card-header">
                        <div class="form-row justify-content-between">
                            <div class="col-md-2">
                                <input v-model="search.title" type="text" name="title" placeholder="Product Title" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Price Range</span>
                                    </div>
                                    <input type="text" v-model="search.price_from" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                                    <input type="text" v-model="search.price_to" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input type="date" v-model="search.date" name="date" placeholder="Date" class="form-control">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="card-header">
                        <h3 class="card-title">Product List</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click.prevent="showModal()" >Add New <i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th>Registered At</th>
                                <th width="15%">Modify</th>
                            </tr>
                            <tr v-for="(product, index) in productList.data" :key="product.id">
                                <td>{{ product.id }}</td>
                                <td>{{ product.title | upperText }}</td>
                                <td>{{ product.price }}</td>
                                <td>{{ product.details }}</td>
                                <td><span :class="product.status?'badge badge-primary':'badge badge-danger'">{{ product.status?'Active':'In Active' }}</span></td>
                                <td>{{ product.created_at |formateDate}}</td>
                                <td>
                                    <button @click.prevent="editModal(product)"   class="btn btn-success mr-2"><i class="fa fa-edit"></i></button>
                                    <button @click.prevent="deleteProduct(product.id)" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr v-if="productList==''">
                                <td colspan="7"><h2 class="text-center">Product Info Not Found</h2></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        <pagination :data="productList" @pagination-change-page="getResults" align="right"></pagination>
                    </div>
                </div>

            </div>
        </div>


        <!--    modal-->

        <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{editMode?'Update Product':'Add New Product'}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode?updateProduct():addProduct()" id="clearForm">

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="product-title" class="col-form-label">Title:</label>
                                <input v-model="products.title" type="text" class="form-control" id="product-title">
                            </div>
                            <div class="form-group">
                                <label for="product-price" class="col-form-label">Price:</label>
                                <input v-model="products.price" type="text" class="form-control" id="product-price">
                            </div>
                            <div class="form-group">
                                <label for="product-details" class="col-form-label">Decription:</label>
                                <textarea v-model="products.details" class="form-control" id="product-details"></textarea>
                            </div>
                            <div class="form-group">
                                <label  class="col-form-label">Status:</label>
                                <select  v-model="products.status" class="form-control">
                                    <option value=''>Choose option</option>
                                    <option value="1" >Active</option>
                                    <option value="0" >InActive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button  type="submit" class="btn btn-primary">{{editMode?'Update':'Add New'}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
// vue2 dropzone
export default {
    data(){
        return {
            editMode:false,
            productList:{},
            search:{
                title:'',
                price_from:'',
                price_to:'',
                date:'',
            },
            products:{
                id: '',
                title: '',
                price:'',
                details: '',
                status: '',
                created_at: '',
            }
        }

    },
    components: {

    },
    methods:{
        loadProduct(){
            axios.get('api/products')
                .then(response =>{
                    console.log(response);
                    // handle success
                    this.productList = response.data;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
        },
        getResults(page = 1) {
            axios.get('api/products?page=' + page)
                .then(response => {
                    this.productList = response.data;
                });
        },
        searchProduct(){
            this.$Progress.start();
            axios.post('api/search-product',this.search)
                .then((data ) => {
                    this.productList=data.data;
                    //this.successMsg("Record insert successfully");
                    this.$Progress.finish();
                })
                .catch((error )=> {
                    let errors=error.response.data.errors;
                    this.errorMsg(errors);
                    this.$Progress.fail();
                })
        },
        addProduct(){
            this.$Progress.start();
            axios.post('api/products',this.products)
                .then((data ) => {
                    this.loadProduct();
                    $('#productModal').modal('hide');
                    this.successMsg("Record insert successfully");
                    this.$Progress.finish();
                })
                .catch((error )=> {
                     let errors=error.response.data.errors;
                     this.errorMsg(errors);
                     this.$Progress.fail();
                })
        },
        updateProduct(){
            axios.put('api/products/'+this.products.id, this.products).then(response => {
                this.loadProduct();
                $('#productModal').modal('hide');
                this.successMsg("Record updated successfully");
            }).catch((error )=> {
                let errors=error.response.data.errors;
                this.errorMsg(errors);
                this.$Progress.fail();
            })
        },
        deleteProduct(product_id){
            let ref=this;
            iziToast.question({
                timeout: 20000,
                close: false,
                overlay: true,
                displayMode:'once',
                id:'question',
                zindex:999,
                title:'Hey',
                message:'Are you sure to delete that?',
                position:'center',
                buttons:[
                    ['<button><b>Yes</b></button>',function(instance,toast) {
                        instance.hide({transitionOut:'fadeOut'},toast,'button');
                        axios.delete("api/products/"+product_id)
                            .then(response =>{
                                console.log(response);
                                ref.get_all_product();
                                iziToast.success({
                                    title: 'Success',
                                    message: response.data.success,
                                });
                            })
                            .catch(err=>{console.log(err);})
                    },true],
                    ['<button><b>Cancel</b></button>',function(instance,toast) {
                        instance.hide({transitionOut:'fadeOut'},toast,'button');
                    }],
                ],
            });
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
        showModal(){
            this.editMode = false;
            this.resetModal();
        },
        editModal(product){
            this.editMode = true;
            this.products=product;
            this.resetModal();
        },
        resetModal(){
            $('#clearForm')[0].reset();
            if(this.products){
                console.log('exist');
            }
            $('#productModal').modal('show');
        },
    },
    created() {
        this.loadProduct();
    },
}
</script>
