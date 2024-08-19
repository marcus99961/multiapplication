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
                            <input class="form-control-sm rounded" type="text" v-model="keyword" placeholder="search member..">
                        </div> -->
                        <div class="col-md-6">
                            <button @click="createMember"  class="btn-info btn-sm float-right">New Member</button>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="shadow p-3 mb-5 bg-body rounded">
                        <!-- <h3>{{ current_user.name }}</h3> -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>                              
                                <th>Member</th>                              
                                <th>Mobile</th>                              
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(member, index) in members " :key="index" class="bg-transparent">
                                <td>{{index + 1}}</td>

                                <td>{{member.name}}</td>
                               
                              
                                <td>{{member.mobile}}</td>
                                <td>
                                    <button @click="editMember(member)" class="btn-sm btn-info mx-1"><i class="fa fa-edit ml-2"></i></button>
                                    <!-- <button @click="removeMember(member)" class="btn btn-danger btn-sm mx-1">Delete</button>
                                    <button @click="renew(member)" class="btn btn-danger btn-sm mx-1">Renew</button> -->

                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="float-start">Inactive Members</h5>
                        </div>
                 
                     
                    </div>

                </div>
                <div class="card-body">
                    <div class="shadow p-3 mb-5 bg-body rounded">
                        <!-- <h3>{{ current_user.name }}</h3> -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>                              
                                <th>Member</th>                              
                                <th>Mobile</th>                              
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(member, index) in inactivemembers " :key="index" class="bg-transparent" :style="'color: red'">
                                <td>{{index + 1}}</td>

                                <td class="inactive">{{member.name}}</td>
                               
                              
                                <td>{{member.mobile}}</td>
                                <td>
                                    <button @click="editMember(member)" class="btn-sm btn-info mx-1"><i class="fa fa-edit ml-2"></i></button>
                                    <!-- <button @click="removeMember(member)" class="btn btn-danger btn-sm mx-1">Delete</button>
                                    <button @click="renew(member)" class="btn btn-danger btn-sm mx-1">Renew</button> -->

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
    <div class="modal fade" id="memberModal" tabindex="-1" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="memberModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Add New Member': 'Update Member' }} </h5>
                    <h5 class="modal-title" id="memberModalLabel" v-show="deleteMode" > Delete Member </h5>
                    <h5 class="modal-title" id="memberModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Member Name.</label>
                                <input type="text" class="form-control" v-model="memberData.name" >
                                <small class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </small><br>

                            </div>
                        </div>
                      
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Mobile No.</label>
                                <input type="text" class="form-control" v-model="memberData.mobile" >
                                <small class="text-danger" v-if="errors.mobile"> {{ errors.mobile[0] }} </small><br>

                            </div>
                        </div>
                        <div class="col-md-4" v-show="!deleteMode && editMode">
                            <div class="form-group">
                                <label for="title" >Status</label>
                                <select v-model="memberData.status" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                                 
                                </select>
                                <!-- <input type="text" class="form-control" v-model="memberData.status" > -->
                                <small class="text-danger" v-if="errors.status"> {{ errors.status[0] }} </small><br>

                            </div>
                        </div>


                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeMember(): updateMember()" >{{!editMode ? 'Create Member': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteMember" >Delete</button>
                </div>
                <div class="modal-footer" v-show="renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="renewMember" >Renew</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    setup: () => ({
        title: 'All Members'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            keyword: null,
            dept: null,

            memberData: {
                member_no: '',
                selectedDepartment: '',

            },
            memberErrors: {
                member_no: false,
                

            },
            departments: [],
            members: {},
            inactivemembers: {},
            errors: {},
            
            current_user: {},
        }
    },
    watch: {
        keyword(after, before) {
            this.getMembers();
        }
    },
    mounted(){
        this.getMembers()
        this.getInactiveMembers()
    },
    created(){
        console.log(window.user)
        this.current_user = window.user
        axios.get('/api/getDepartment2').then(response=>{
                this.departments = response.data})
    },
    methods: {
        getMembers(){

            axios.get('/api/getMembers').then(response=>{
                this.members = response.data
            }).catch(errors=>{
                console.log(errors)
            });
        },
        getInactiveMembers(){

                axios.get('/api/getInactiveMembers').then(response=>{
                    this.inactivemembers = response.data
                }).catch(errors=>{
                    console.log(errors)
                });
                },

        removeMember(member){
            this.deleteMode = true
            this.memberData.id = member.id
            $('#memberModal').modal('show')
        },
        deleteMember(){
            axios.post(window.url + 'api/deleteMember/' + this.memberData.id).then(response => {
                this.getMembers()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#memberModal').modal('hide')
            });
        },
        renew(member){
            this.renewMode = true
            this.deleteMode = false
            this.editMode = false

            this.memberData= {
                id : member.id,
                renew: member.renew,

            }
            $('#memberModal').modal('show')

        },
        editMember(member){
            this.editMode = true
            this.deleteMode= false
            this.memberData= {
                id : member.id,
                name :member.name,
                mobile : member.mobile,
                status: member.status,
            }
          
            $('#memberModal').modal('show')
        },
        updateMember(){



            axios.post('/api/updateMember/' + this.memberData.id, this.memberData).then(response => {
                $('#memberModal').modal('hide');              
                this.getMembers()
                this.getInactiveMembers()
                }).catch(error =>this.errors = error.response.data.errors)


        },
        renewMember(){

            axios.post(window.url + 'api/renewMember/' + this.memberData.id, this.memberData).then(response => {
                this.getMembers()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#memberModal').modal('hide')
            });

        },
        createMember(){
            this.editMode = false
            this.deleteMode = false
            this.memberData= {
                id: '',
                name: '',
             

            }
           
            $('#memberModal').modal('show')
        },
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.memberData.img = reader.result;
            }
            reader.readAsDataURL(file);
        },
        storeMember(){
           
                axios.post('/api/storeMemberdepartment', this.memberData).then(response=>{
                    $('#memberModal').modal('hide');              
                this.getMembers()
                }).catch(error =>this.errors = error.response.data.errors)


            
        }
    }

}
</script>
<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.ssr.css">

.owner {
    font-weight: bolder;
    
}




</style>
