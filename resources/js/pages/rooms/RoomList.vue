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
                            <input class="form-control-sm rounded" type="text" v-model="keyword" placeholder="search room..">
                        </div> -->
                        <div class="col-md-6">
                            <button @click="createRoom"  class="btn-info btn-sm float-right"><i class="fa fa-plus-circle mr-1"></i>New Location</button>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="shadow p-3 mb-5 bg-body rounded">
                        <!-- <h3>{{ current_room.name }}</h3> -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>                              
                                <th>Room No./Location</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(room, index) in rooms " :key="index" class="bg-transparent">
                                <td>{{index + 1}}</td>

                                <td>{{room.name}}</td>
                               
                                <td>
                                    {{room.createdHumanReadable}}
                                    <!-- {{room.reg_no.length <=15 ? room.reg_no : room.reg_no.substr(0,15) + '...'}} -->
                                </td>

                                <td>
                                    <button @click="editRoom(room)" class="btn-primary btn-sm mx-1"><i class="fa fa-edit"></i></button>
                                    <!-- <button @click="removeRoom(room)" class="btn btn-danger btn-sm mx-1">Delete</button>
                                    <button @click="renew(room)" class="btn btn-danger btn-sm mx-1">Renew</button> -->

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
    <div class="modal fade" id="roomModal" tabindex="-1" aria-labelledby="roomModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="roomModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Add New Room': 'Update Room' }} </h5>
                    <h5 class="modal-title" id="roomModalLabel" v-show="deleteMode" > Delete Room </h5>
                    <h5 class="modal-title" id="roomModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Room No.</label>
                                <input type="text" class="form-control" v-model="roomData.name" >
                                <small class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </small><br>

                            </div>
                        </div>

                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeRoom(): updateRoom()" >{{!editMode ? 'Create Room': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteRoom" >Delete</button>
                </div>
                <div class="modal-footer" v-show="renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="renewRoom" >Renew</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    setup: () => ({
        title: 'Locations'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            keyword: null,

            roomData: {
                room_no: '',
          

            },
            roomErrors: {
                room_no: false,
                

            },
            rooms: {},
            errors: {},
            current_user: {},
        }
    },
    watch: {
        keyword(after, before) {
            this.getRooms();
        }
    },
    mounted(){
        this.getRooms()
    },
    created(){
        console.log(window.user)
        this.current_user = window.user
    },
    methods: {
        getRooms(){

            axios.get('/api/getRooms').then(response=>{
                this.rooms = response.data
            }).catch(errors=>{
                console.log(errors)
            });
        },

        removeRoom(room){
            this.deleteMode = true
            this.roomData.id = room.id
            $('#roomModal').modal('show')
        },
        deleteRoom(){
            axios.post(window.url + 'api/deleteRoom/' + this.roomData.id).then(response => {
                this.getRooms()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#roomModal').modal('hide')
            });
        },
        renew(room){
            this.renewMode = true
            this.deleteMode = false
            this.editMode = false

            this.roomData= {
                id : room.id,
                renew: room.renew,

            }
            $('#roomModal').modal('show')

        },
        editRoom(room){
            this.editMode = true
            this.deleteMode= false
            this.roomData= {
                id : room.id,
                name :room.name,
                phone : room.phone,
                shift : room.shift,
            }
            this.roomErrors= {
                name: false,
                phone: false,

            }
            $('#roomModal').modal('show')
        },
        updateRoom(){



            axios.post('/api/updateRoom/' + this.roomData.id, this.roomData).then(response => {
                $('#roomModal').modal('hide');              
                this.getRooms()
                }).catch(error =>this.errors = error.response.data.errors)


        },
        renewRoom(){

            axios.post(window.url + 'api/renewRoom/' + this.roomData.id, this.roomData).then(response => {
                this.getRooms()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#roomModal').modal('hide')
            });

        },
        createRoom(){
            this.editMode = false
            this.deleteMode = false
            this.roomData= {
                id: '',
                room_no: '',
             

            }
            this.roomErrors= {
                room_no: false,

            }
            $('#roomModal').modal('show')
        },
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.roomData.img = reader.result;
            }
            reader.readAsDataURL(file);
        },
        storeRoom(){
           
                axios.post('/api/storeRoom', this.roomData).then(response=>{
                    $('#roomModal').modal('hide');              
                this.getRooms()
                }).catch(error =>this.errors = error.response.data.errors)


            
        }
    }

}
</script>
