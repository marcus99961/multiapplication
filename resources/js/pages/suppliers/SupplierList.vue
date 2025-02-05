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
                            <input class="form-control-sm rounded" type="text" v-model="keyword" placeholder="search supplier..">
                        </div> -->
                        <div class="col-md-6">
                            <button @click="createSupplier"  class="btn-info btn-sm float-right"><i class="fa fa-plus-circle mr-1"></i>New Supplier</button>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="shadow p-3 mb-5 bg-body rounded">
                        <!-- <h3>{{ current_supplier.name }}</h3> -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>    
                                             
                                <th>Supplier</th>
                                <th>Phone</th>                           
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(supplier, index) in suppliers " :key="index" class="bg-transparent">
                                <td>{{index + 1}}</td>
                            
                                <td>{{supplier.name}}</td>
                                <td>{{supplier.phone}}</td>
                            
                               


                                <td>
                                    <button @click="editSupplier(supplier)" class="ml-1" ><i class="fa fa-edit text-success mx-1"></i></button>
                                    <button @click="removeSupplier(supplier)" class="ml-1"><i class="fa fa-trash text-danger mx-1"></i></button>                               


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
    <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="supplierModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Add New Supplier': 'Update Supplier' }} </h5>
                    <h5 class="modal-title" id="supplierModalLabel" v-show="deleteMode" > Delete Supplier </h5>
                    <h5 class="modal-title" id="supplierModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                     
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Supplier Name</label>
                                <input type="text" class="form-control" v-model="supplierData.name" >
                                <small class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Address</label>
                                <input type="text" class="form-control" v-model="supplierData.address" >
                                <small class="text-danger" v-if="errors.address"> {{ errors.address[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Email</label>
                                <input type="text" class="form-control" v-model="supplierData.email" >
                                <small class="text-danger" v-if="errors.email"> {{ errors.email[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Phone</label>
                                <input type="text" class="form-control" v-model="supplierData.phone" >
                                <small class="text-danger" v-if="errors.phone"> {{ errors.phone[0] }} </small><br>
                            </div>
                        </div>

                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeSupplier(): updateSupplier()" >{{!editMode ? 'Create Supplier': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteSupplier" >Delete</button>
                </div>
                <div class="modal-footer" v-show="renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="renewSupplier" >Renew</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    setup: () => ({
        title: 'All Suppliers'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            keyword: null,

            supplierData: {
                address: '',
                name: '',
                email: '',
                phone: '',
          

            },
        
            suppliers: {},
            current_user: {},
            errors: {},
        }
    },
    watch: {
        keyword(after, before) {
            this.getSuppliers();
        }
    },
    mounted(){
        this.getSuppliers()
    },
    created(){
        console.log(window.user)
        this.current_user = window.user
    },
    methods: {
        getSuppliers(){

            axios.get('/api/getSuppliers').then(response=>{
                this.suppliers = response.data
            }).catch(errors=>{
                console.log(errors)
            });
        },

        removeSupplier(supplier){
            this.deleteMode = true
            this.supplierData.id = supplier.id
            $('#supplierModal').modal('show')
        },
        deleteSupplier(){
            axios.delete('/api/deleteSupplier/' + this.supplierData.id).then(response => {
                this.getSuppliers()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#supplierModal').modal('hide')
            });
        },
    
        editSupplier(supplier){
            this.editMode = true
            this.deleteMode= false
            this.supplierData= {
                id : supplier.id,
                name :supplier.name,
                email :supplier.email,
                address :supplier.address,
                phone :supplier.phone,
            
            }
        
            $('#supplierModal').modal('show')
        },
        updateSupplier(){



            axios.post('/api/updateSupplier/' + this.supplierData.id, this.supplierData).then(response => {
                $('#supplierModal').modal('hide');
                this.getSuppliers()
                }).catch(error =>this.errors = error.response.data.errors)



        },
     
        createSupplier(){
            this.editMode = false
            this.deleteMode = false
            this.supplierData= {
                id: '',
                name: '',
             

            }
            this.supplierErrors= {
                name: false,

            }
            $('#supplierModal').modal('show')
        },
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.supplierData.img = reader.result;
            }
            reader.readAsDataURL(file);
        },
        storeSupplier(){
         
                axios.post('/api/storeSupplier', this.supplierData).then(response=>{
                    $('#supplierModal').modal('hide');
                this.getSuppliers()
                }).catch(error =>this.errors = error.response.data.errors)

        

            
        }
    }

}
</script>
