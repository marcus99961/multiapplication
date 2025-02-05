<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="float-start">{{ title }}</h5>
                        </div>
                        <!-- <div class="col-md-4 float-center">
                            <input class="form-control-sm rounded" type="text" v-model="keyword" placeholder="search category..">
                        </div> -->
                        <div class="col-md-6">
                            <button @click="createCategory"  class="btn-info btn-sm float-right"><i class="fa fa-plus-circle mr-1"></i>New Category</button>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="shadow p-3 mb-5 bg-body rounded">
                        <!-- <h3>{{ current_category.name }}</h3> -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>    
                                <th>Group Code</th>                          
                                <th>Category</th>
                                <th>Inventory Code</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(category, index) in categories " :key="index" class="bg-transparent">
                                <td>{{index + 1}}</td>
                                <td>{{category.group_code}}</td>
                                <td>{{category.name}}</td>
                                <td>{{category.inv_code}}</td>
                               


                                <td>
                                    <button @click="editCategory(category)" class="ml-1" ><i class="fa fa-edit text-success mx-1"></i></button>
                                    <button @click="removeCategory(category)" class="ml-1"><i class="fa fa-trash text-danger mx-1"></i></button>                               


                                </td>

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
                                <label for="title" >Group Code</label>
                                <input type="text" class="form-control" v-model="categoryData.group_code" >
                                <small class="text-danger" v-if="errors.group_code"> {{ errors.group_code[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Category Name</label>
                                <input type="text" class="form-control" v-model="categoryData.name" >
                                <small class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Inv Code</label>
                                <input type="text" class="form-control" v-model="categoryData.inv_code" >
                                <small class="text-danger" v-if="errors.inv_code"> {{ errors.inv_code[0] }} </small><br>
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

            categoryData: {
                group_code: '',
                name: '',
                inv_code: '',
          

            },
        
            categories: {},
            current_user: {},
            errors: {},
        }
    },
    watch: {
        keyword(after, before) {
            this.getCategories();
        }
    },
    mounted(){
        this.getCategories()
    },
    created(){
        console.log(window.user)
        this.current_user = window.user
    },
    methods: {
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
            axios.delete('/api/deleteCategory/' + this.categoryData.id).then(response => {
                this.getCategories()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#categoryModal').modal('hide')
            });
        },
    
        editCategory(category){
            this.editMode = true
            this.deleteMode= false
            this.categoryData= {
                id : category.id,
                name :category.name,
                group_code : category.group_code,
                inv_code : category.inv_code,
            }
        
            $('#categoryModal').modal('show')
        },
        updateCategory(){



            axios.post('/api/updateCategory/' + this.categoryData.id, this.categoryData).then(response => {
                $('#categoryModal').modal('hide');
                this.getCategories()
                }).catch(error =>this.errors = error.response.data.errors)



        },
     
        createCategory(){
            this.editMode = false
            this.deleteMode = false
            this.categoryData= {
                id: '',
                name: '',
             

            }
            this.categoryErrors= {
                name: false,

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
