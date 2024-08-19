<template>
     <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-light">
                    <div class="row">
                        <div class="col-md-12" >
                                <h5 style="text-align: center;">TEst</h5>
                                
                                </div>
                        </div>
                    </div>
                        <div class="card-body">
                    <div class="shadow p-3 mb-5 bg-body rounded">

                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>                              
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(category, index) in departmentcategories " :key="index" class="bg-transparent">
                                <td>{{index + 1}}</td>                                
                                <td>{{category.name}}</td>
                              
                               
                                <td><button @click="removeLink(category.id)" class="btn-danger"><i class="fa-solid fa-trash"></i></button></td>
          
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-light">
                    <div class="row">
                       
                        <div class="col-md-12">
                                                     
                            <button @click="createCategory"  class="btn-info btn-sm float-end mx-2">Add New Category</button>
                        
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="shadow p-3 mb-5 bg-body rounded">

                        <table class="table">
                            <thead>
                            <tr>                               
                                <th>Category</th>                              
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(category, index) in categories " :key="index" class="bg-transparent">                                                     
                                <td>{{category.name}}</td>
                              
                               
                                <td><button @click="AddCategory(category)" class="btn btn-success btn-sm mx-1"><i class="fa-solid fa-plus"></i></button>
                                <button @click="editCategory(category)" class="btn btn-warning btn-sm mx-1"><i class="fa-solid fa-pencil"></i></button></td>
          
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="categoryModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Add New Category': 'Update Category' }} </h5>
                    <h5 class="modal-title" id="categoryModalLabel" v-show="deleteMode" > Delete Category </h5>
                    <h5 class="modal-title" id="categoryModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Category Name.</label>
                                <input type="text" class="form-control" v-model="categoryData.name" >
                                <input type="hidden" v-model="categoryData.id">
                                <small class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </small><br>

                            </div>
                        </div>

                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeCategory(): updateCategory()" >{{!editMode ? 'Create Category': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteCategory" >Delete</button>
                </div>
                <div class="modal-footer" v-show="renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="renewCategory" >Renew</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import Swal from 'sweetalert2';
export default {
    setup: () => ({
        title: 'All Categories'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            keyword: null,
            form: { id : ''},

            categoryData: {
                name: '',
          

            },
           
            categoryErrors: {
                name: false,
                

            },
            categories: {},
            current_user: {},
            errors: {},
            departmentcategories: {},
            
        }
    },
    watch: {
        keyword(after, before) {
            this.getCategories();
        }
    },
    mounted(){
        this.getCategories()
        this.getDepartmentcategories()
    },
    created(){
        
        this.form.id = this.$route.params.id
    },
    methods: {
        getDepartmentcategories(){

            axios.get('/api/getDepartmentcategories/'+this.form.id,{ params: { keyword: this.keyword } }).then(response=>{
                this.departmentcategories = response.data
            }).catch(errors=>{
                console.log(errors)
            });
            
            },


            
        getCategories(){

            axios.get('/api/getCategories').then(response=>{
                this.categories = response.data
            }).catch(errors=>{
                console.log(errors)
            });
        },

        removeCategory(category){
            this.deleteMode = true
            this.categoryData.id = category.id
            $('#categoryModal').modal('show')
        },
        deleteCategory(){
            axios.post(window.url + 'api/deleteCategory/' + this.categoryData.id).then(response => {
                this.getCategories()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#categoryModal').modal('hide')
            });
        },
        renew(category){
            this.renewMode = true
            this.deleteMode = false
            this.editMode = false

            this.categoryData= {
                id : category.id,
                renew: category.renew,

            }
            $('#categoryModal').modal('show')

        },
        editCategory(category){
            this.editMode = true
            this.deleteMode= false
            this.categoryData= {
                id : category.id,
                name :category.name,
       
            }
            this.categoryErrors= {
                name: false,
                phone: false,

            }
            $('#categoryModal').modal('show')
        },
        updateCategory(){



            axios.post('/api/updateCategory', this.categoryData).then(response => {
                $('#categoryModal').modal('hide');
                    this.getCategories()
                }).catch(error =>this.errors = error.response.data.errors)


        },
        
        removeLink(id) {
        Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/departmentcategory/${id}`)
                .then((response) => {
                   
                    Swal.fire(
                        'Deleted!',
                        'Your category has been deleted.',
                        'success'
                    )
                });
                this.getDepartmentcategories()
        }
    })
    },
        AddCategory(category){

            this.categoryData = { id : category.id }
                axios.post('/api/departmentCategory/'+this.form.id,this.categoryData).then(response => {
                    this.getCategories()
                    this.getDepartmentcategories()
                }).catch(errors => {
                    console.log(errors)
                });

            },
        createCategory(){
            this.editMode = false
            this.deleteMode = false
            this.categoryData= {
                id: '',
                category_no: '',
             

            }
            this.categoryErrors= {
                category_no: false,

            }
            $('#categoryModal').modal('show')
        },
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.categoryData.img = reader.result;
            }
            reader.readAsDataURL(file);
        },
        storeCategory(){


           
                axios.post('/api/storeCategory', this.categoryData).then(response=>{
                    $('#categoryModal').modal('hide');
                    this.getCategories()
                }).catch(error =>this.errors = error.response.data.errors)


            
        }
    }

}
</script>
