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
                            <input class="form-control-sm rounded" type="text" v-model="keyword" placeholder="search unit..">
                        </div> -->
                        <div class="col-md-6">
                            <button @click="createUnit"  class="btn-info btn-sm float-right"><i class="fa fa-plus-circle mr-1"></i>New Unit</button>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="shadow p-3 mb-5 bg-body rounded">
                        <!-- <h3>{{ current_unit.name }}</h3> -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>    
                                             
                                <th>UnitName</th>
                           
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(unit, index) in units " :key="index" class="bg-transparent">
                                <td>{{index + 1}}</td>
                            
                                <td>{{unit.name}}</td>
                            
                               


                                <td>
                                    <button @click="editUnit(unit)" class="ml-1" ><i class="fa fa-edit text-success mx-1"></i></button>
                                    <button @click="removeUnit(unit)" class="ml-1"><i class="fa fa-trash text-danger mx-1"></i></button>                               


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
    <div class="modal fade" id="unitModal" tabindex="-1" aria-labelledby="unitModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="unitModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Add New Unit': 'Update Unit' }} </h5>
                    <h5 class="modal-title" id="unitModalLabel" v-show="deleteMode" > Delete Unit </h5>
                    <h5 class="modal-title" id="unitModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                     
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Unit Name</label>
                                <input type="text" class="form-control" v-model="unitData.name" >
                                <small class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </small><br>
                            </div>
                        </div>
                   

                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeUnit(): updateUnit()" >{{!editMode ? 'Create Unit': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteUnit" >Delete</button>
                </div>
                <div class="modal-footer" v-show="renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="renewUnit" >Renew</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    setup: () => ({
        title: 'All Units'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            keyword: null,

            unitData: {
                group_code: '',
                name: '',
                inv_code: '',
          

            },
        
            units: {},
            current_user: {},
            errors: {},
        }
    },
    watch: {
        keyword(after, before) {
            this.getUnits();
        }
    },
    mounted(){
        this.getUnits()
    },
    created(){
        console.log(window.user)
        this.current_user = window.user
    },
    methods: {
        getUnits(){

            axios.get('/api/getUnits').then(response=>{
                this.units = response.data
            }).catch(errors=>{
                console.log(errors)
            });
        },

        removeUnit(unit){
            this.deleteMode = true
            this.unitData.id = unit.id
            $('#unitModal').modal('show')
        },
        deleteUnit(){
            axios.delete('/api/deleteUnit/' + this.unitData.id).then(response => {
                this.getUnits()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#unitModal').modal('hide')
            });
        },
    
        editUnit(unit){
            this.editMode = true
            this.deleteMode= false
            this.unitData= {
                id : unit.id,
                name :unit.name,
            
            }
        
            $('#unitModal').modal('show')
        },
        updateUnit(){



            axios.post('/api/updateUnit/' + this.unitData.id, this.unitData).then(response => {
                $('#unitModal').modal('hide');
                this.getUnits()
                }).catch(error =>this.errors = error.response.data.errors)



        },
     
        createUnit(){
            this.editMode = false
            this.deleteMode = false
            this.unitData= {
                id: '',
                name: '',
             

            }
            this.unitErrors= {
                name: false,

            }
            $('#unitModal').modal('show')
        },
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.unitData.img = reader.result;
            }
            reader.readAsDataURL(file);
        },
        storeUnit(){
         
                axios.post('/api/storeUnit', this.unitData).then(response=>{
                    $('#unitModal').modal('hide');
                this.getUnits()
                }).catch(error =>this.errors = error.response.data.errors)

        

            
        }
    }

}
</script>
