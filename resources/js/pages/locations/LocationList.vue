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
                            <input class="form-control-sm rounded" type="text" v-model="keyword" placeholder="search location..">
                        </div> -->
                        <div class="col-md-6">
                            <button @click="createLocation"  class="btn-info btn-sm float-right"><i class="fa fa-plus-circle mr-1"></i>New Location</button>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="shadow p-3 mb-5 bg-body rounded">
                        <!-- <h3>{{ current_location.name }}</h3> -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>    
                                             
                                <th>Location</th>
                           
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(location, index) in locations " :key="index" class="bg-transparent">
                                <td>{{index + 1}}</td>
                            
                                <td>{{location.name}}</td>
                            
                               


                                <td>
                                    <button @click="editLocation(location)" class="ml-1" ><i class="fa fa-edit text-success mx-1"></i></button>
                                    <button @click="removeLocation(location)" class="ml-1"><i class="fa fa-trash text-danger mx-1"></i></button>                               


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
    <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="locationModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Add New Location': 'Update Location' }} </h5>
                    <h5 class="modal-title" id="locationModalLabel" v-show="deleteMode" > Delete Location </h5>
                    <h5 class="modal-title" id="locationModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                     
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Location Name</label>
                                <input type="text" class="form-control" v-model="locationData.name" >
                                <small class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </small><br>
                            </div>
                        </div>
                   

                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeLocation(): updateLocation()" >{{!editMode ? 'Create Location': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteLocation" >Delete</button>
                </div>
                <div class="modal-footer" v-show="renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="renewLocation" >Renew</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    setup: () => ({
        title: 'All Locations'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            keyword: null,

            locationData: {
                group_code: '',
                name: '',
                inv_code: '',
          

            },
        
            locations: {},
            current_user: {},
            errors: {},
        }
    },
    watch: {
        keyword(after, before) {
            this.getLocations();
        }
    },
    mounted(){
        this.getLocations()
    },
    created(){
        console.log(window.user)
        this.current_user = window.user
    },
    methods: {
        getLocations(){

            axios.get('/api/getLocations').then(response=>{
                this.locations = response.data
            }).catch(errors=>{
                console.log(errors)
            });
        },

        removeLocation(location){
            this.deleteMode = true
            this.locationData.id = location.id
            $('#locationModal').modal('show')
        },
        deleteLocation(){
            axios.delete('/api/deleteLocation/' + this.locationData.id).then(response => {
                this.getLocations()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#locationModal').modal('hide')
            });
        },
    
        editLocation(location){
            this.editMode = true
            this.deleteMode= false
            this.locationData= {
                id : location.id,
                name :location.name,
            
            }
        
            $('#locationModal').modal('show')
        },
        updateLocation(){



            axios.post('/api/updateLocation/' + this.locationData.id, this.locationData).then(response => {
                $('#locationModal').modal('hide');
                this.getLocations()
                }).catch(error =>this.errors = error.response.data.errors)



        },
     
        createLocation(){
            this.editMode = false
            this.deleteMode = false
            this.locationData= {
                id: '',
                name: '',
             

            }
            this.locationErrors= {
                name: false,

            }
            $('#locationModal').modal('show')
        },
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.locationData.img = reader.result;
            }
            reader.readAsDataURL(file);
        },
        storeLocation(){
         
                axios.post('/api/storeLocation', this.locationData).then(response=>{
                    $('#locationModal').modal('hide');
                this.getLocations()
                }).catch(error =>this.errors = error.response.data.errors)

        

            
        }
    }

}
</script>
