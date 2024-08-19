<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2">
                            <h5 class="float-start">{{ title }}</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" @click="toggleview">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Toggle View User/Service</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <!-- <input type="text" v-model="keyword" placeholder="Search..." />      -->
                            <button @click="generatePdf"  class="mb-2 btn-sm btn-primary float-right"><i class="fa-solid fa-print m-1"></i>Generate PDF</button>
                      
                        </div>
                        <div class="col-md-2">
                            <!-- <input type="text" v-model="keyword" placeholder="Search..." />      -->
                            <button @click="findComplaint2"  class="mb-2 btn-sm btn-primary float-right"><i class="fa-solid fa-print m-1"></i>By Room/Date</button>
                      
                        </div>
                        <div class="col-md-2">
                            <button @click="findComplaint"  class="mb-2 btn-sm btn-primary float-right"><i class="fa-solid fa-magnifying-glass m-1"></i>Find Complaint</button>
                        </div>
                    </div>

                </div>
                <div class="card-body" v-show="!userMode">
                    <div class="row">
                    <div class="shadow p-3 mb-5 bg-body rounded col-md-12">
                        <table class="table table-bordered">
                        <thead>
                            <tr>                               
                                <th style="width: 10px">#</th>
                                <th>Date</th>
                                <th>Room</th>
                                <th>Defect</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>Atten:</th>
                                <th>Category</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody v-if="complaints.length > 0">
                            <!-- <tr v-for="memeber in members">
                                <td>{{ member.name }}</td>

                            </tr> -->
                                <tr v-for="(complaint, i) in complaints" :key="i" >
                                  
                                   
                                    <th>{{ i +1 }}</th> 
                                    <td>{{ complaint.date }}</td> 
                                    <td><text v-for="selectedroom in selectedrooms"><text  v-if="complaint.id==selectedroom.complaint_id">{{ selectedroom.name }}, </text></text></td>
                                    <td @dblclick="updateStatus(complaint)"> <text v-if="complaint.department_id == authUserStore.user.department_id" class="owner">{{ complaint.defect }}</text>
                                        <text v-else>{{ complaint.defect }}</text>
                                   
                                    </td>
                                    <td>{{ complaint.action }}</td>
                                    <td :class='complaint.status'>{{ complaint.status }}</td>
                                    <td>{{ complaint.department_name }}</td>
                                    <!-- <td>{{ complaint.remark }}</td> -->
                                    <td>
                                     {{ complaint.category_name }}


                                    </td>
                                    <td>{{ complaint.remark }}</td>
                               

                                </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="6" class="text-center">No results found...</td>
                            </tr>
                        </tbody>
                    </table>
                        
                    </div>
                  
                    </div>
                </div>
                <div class="card-body" v-show="userMode">
                    <div class="row">
                    <div class="shadow p-3 mb-5 bg-body rounded col-md-12">
                        <table class="table table-bordered">
                        <thead>
                            <tr>                               
                                <th style="width: 10px">#</th>
                                <th>Room</th>
                                <th>Defect</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>Asigned</th>
                                <th>Atten:</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody v-if="complaints.length > 0">
                            <!-- <tr v-for="memeber in members">
                                <td>{{ member.name }}</td>

                            </tr> -->
                                <tr v-for="(complaint, i) in complaints" :key="i" >
                                  
                                   
                                    <th>{{ i +1 }}</th> 
                                    <td><text v-for="selectedroom in selectedrooms"><text  v-if="complaint.id==selectedroom.complaint_id">{{ selectedroom.name }}, </text></text></td>
                                    <td @dblclick="updateStatus(complaint)"> <text v-if="complaint.department_id == authUserStore.user.department_id" class="owner">{{ complaint.defect }}</text>
                                        <text v-else>{{ complaint.defect }}</text>
                                    
                                    
                                    
                                    </td>
                                    <td @dblclick.prevent="attached(complaint)"> {{ complaint.action }}</td>
                                    <td :class='complaint.status'>{{ complaint.status }}</td>
                                    <td><div v-for="complaintmember in complaintmembers"><div v-if="complaint.id==complaintmember.complaint_id" class="done">{{ complaintmember.name }}->{{ complaintmember.mobile }}</div> </div></td>

                                   
                                    <td>{{ complaint.department_name }}</td>
                                    <!-- <td>{{ complaint.remark }}</td> -->
                                    <td>
                                        <a href="#" @click.prevent="editComplaint(complaint)" v-show="complaint.user_id==authUserStore.user.id"><i class="fa fa-edit"></i></a>
                                       
                                        <a href="#" @click.prevent="deleteComplaint(complaint.id)" v-show="complaint.user_id==authUserStore.user.id"><i class="fa fa-trash text-danger ml-2"></i></a>
                                        <!-- <div v-for="count in messageCount"> -->
                                        <router-link :to="`/admin/messages/${ complaint.id }`" v-show="complaint.user_department_id==authUserStore.user.department_id || complaint.department_id == authUserStore.user.department_id"  class="btn-success btn-xs ml-2"><i class="fa-solid fa-message mr-1" ></i>{{ complaint.msg_status }}</router-link>
                                        <a href="#" @click.prevent="closeComplaint(complaint.id)" v-show="complaint.status=='done' && complaint.user_department_id == authUserStore.user.department_id"><i class="fa-solid text-danger fa-circle-xmark ml-2"></i></a>



                                    </td>
                               

                                </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="6" class="text-center">No results found...</td>
                            </tr>
                        </tbody>
                    </table>
                        
                    </div>
                  
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="updateModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Update Status': 'Update Room' }} </h5>
                    <h5 class="modal-title" id="updateModalLabel" v-show="deleteMode" > Delete Room </h5>
                    <h5 class="modal-title" id="updateModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Case Status</label>
                                <select v-model="case_status" class="form-control">
                                <option value="assign">Assign</option>
                                <option value="inProgress">In Progress</option>
                                <option value="project">Project</option>
                                <option value="done">Done</option>
                                </select>
                                <small class="text-danger" v-if="errors.status"> {{ errors.status[0] }} </small><br>
                                <input type="hidden" v-model="complaintData.id">
                                <input type="hidden" v-model="complaintData.defect">
                                <input type="hidden" v-model="complaintData.user_id">
                            </div>
                        </div>
                        <div class="col-md-4" v-show="assignMode">
                            <div class="form-group" >
                                <label for="title" class="float-start">Assigned to:</label>
                                <select v-model="complaintData.member_id" class="form-control" >
                                    
                                <option v-for="member in members"  :value='member.id'>{{ member.name }}</option>
                                    
                            
                                </select>
                                <small class="text-danger" v-if="errors.status"> {{ errors.status[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-4" >
                            <div class="form-group" >
                                <label for="title" class="float-start">Currently assigned to:<div v-for="complaintmember in complaintmembers"><div v-if="complaintData.id==complaintmember.complaint_id" class="done form-control">{{ complaintmember.name }}</div> </div></label>
                              
                            </div>
                        </div>
                        <div class="col-md-8" v-show="actionMode">
                            <div class="form-group">
                                <!-- <label for="title" class="float-start">Assigned to:<div v-for="complaintmember in complaintmembers"><div v-if="complaintData.id==complaintmember.complaint_id" class="done form-control">{{ complaintmember.name }}</div> </div></label> -->

                                <label for="title" >Action</label>
                              <input type="text" class="form-control" v-model="complaintData.action">

                                <small class="text-danger" v-if="errors.status"> {{ errors.status[0] }} </small><br>
                            </div>
                        </div>
                     

                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeStatus(): updateRoom()" >{{!editMode ? 'Update': 'Save Changes' }}</button>
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
        <!-- Modal -->
    <div class="modal fade" id="byRoom"  tabindex="-1" aria-labelledby="attachModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="attachModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Generate Order By Room': 'Update Room' }} </h5>
               
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="row" v-show="!editMode">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" >From</label>
                                    <input type="date" class="form-control" v-model="complaintData.from" > 

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" >To</label>
                                    <input type="date" class="form-control" v-model="complaintData.to" > 

                                </div>
                            </div>
                        </div>
                       

                    </div>
                  







                </div>
                <div class="modal-footer" >
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? generatePdf2(): updateComplaint()" >{{!editMode ? 'Generate': 'Save Changes' }}</button>
                </div>
              
            </div>
        </div>
    </div>


    <div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" role="dialog" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="complaintModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Find Complaint': 'Update Complaint' }} </h5>
                    <h5 class="modal-title" id="complaintModalLabel" v-show="deleteMode" > Delete Room </h5>
                    <h5 class="modal-title" id="complaintModalLabel" v-show="renewMode" >Renew License </h5>
                    <!-- <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" @click="detail">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Detail</label>
                            </div> -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="shadow p-3 mb-5 bg-body rounded col-md-12">
                        <div class="row">
                           
                           <div class="col-md-6">
                                <div class="form-group">
                                    <div>Source: </div>
                                    <input type="radio" id="guest" value="guest" class="sm-2" v-model="complaintData.source" />
                                    <label for="guest">Guest</label>

                                    <input type="radio" id="admin" value="admin" class="sm-2" v-model="complaintData.source" />
                                    <label for="admin">Admin</label>
                                    <input type="radio" id="admin" value="both" class="sm-2" v-model="complaintData.source" />
                                    <label for="admin">Both</label>
                                </div>
                           </div>
                           <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" >Keywords</label>
                                    <input type="text" class="form-control" v-model="complaintData.queryin" > 

                                </div>
                            </div>
                       </div>
                        <div class="row" v-show="!editMode">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" >From</label>
                                    <input type="date" class="form-control" v-model="complaintData.from" > 

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" >To</label>
                                    <input type="date" class="form-control" v-model="complaintData.to" > 

                                </div>
                            </div>
                        </div>
                        
                      
                        <div class="row" v-show="!editMode">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" >Department (Atten)</label>
                                    <multiselect v-model="dept" :options="departments" :multiple="false"
                                        :close-on-select="true"> </multiselect>
                                        <span class="text-danger" v-if="errors.selectedDepartment"> {{ errors.selectedDepartment[0] }} </span><br>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" >Category</label>
                                    <multiselect v-model="complaintData.selectedCategory" :options="categories" :multiple="false"
                                        :close-on-select="true"> </multiselect>
                                        

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" >Case Status</label>
                                <select v-model="complaintData.status" class="form-control">
                                <option value="assign">Assign</option>
                                <option value="inProgress">In Progress</option>
                                <option value="project">Project</option>
                                <option value="done">Done</option>
                                <option value="closed">Closed</option>
                                <option value="pending">Pending</option>
                               
                                </select>
                              
                              
                            </div>
                        </div>
                          
                        </div>
                      
                       
                       
                        
                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="generatePdf" >Generate PDF</button>
                    <button type="button" class="btn btn-primary" @click.prevent="!editMode ? searchComplaint(): updateComplaint()" >{{!editMode ? 'Find': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="generatePdf" >Generate PDF</button>
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

import Swal from 'sweetalert2';
import { useAuthUserStore } from '../../stores/AuthUserStore';
import { getTransitionRawChildren } from 'vue';
//const authUserStore = useAuthUserStore();

export default {
   
    setup: () => ({
        title: 'Service Interface'
    }),
    
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            addRoomMode:false,
            assignMode: false,
            actionMode: false,
            attachMode: false,
            userMode: false,
            detailMode: false,
           
            keyword: null,
            dept: null,           
            case_status: null,
            fullWidthImageIndex: null,
            photoview: null,
           

            query: {},
            authUserStore: {},

            complaintData: {                
                selectedDepartment: '',
                selectedRoom: '',
                selectedCategory: '',
                arrival: '',
                action: '',
                result: '',
                remark: '',
                guest_name: '',
                status: '',
                priority: '',
                source: '',

          

            },
            roomData: {                
                name: '',
    
            },
            roomErrors: {
                name: false,
                

            },
            departments: [],
            rooms: [],
            selectedrooms: {},
            current_user: {},
            complaints: {},
            errors: {},
            members: {},
            attachments: {},
            messageCount:{},
            categories: [],
            complaintmembers: {},
        }
    },
    watch: {
        keyword(after, before) {
            this.getComplaints();
        },
       
        dept : function (value){
            axios.get('/api/getCategory2',{ params: { keyword: this.dept } }).then(response=>{
                this.categories = response.data
            })
           
        },
        case_status : function (value){
           
                if(this.case_status=='assign'){
                    this.assignMode = true
                    this.actionMode = false
                }else {
                    this.assignMode = false
                    this.actionMode = true
                    
                }
           
        }
    },
    mounted(){
        this.getRooms()
    //    this.getComplaints()
       
        this.getComplaintmembers()
    },
    created(){
        this.current_user = window.user
        this.authUserStore = useAuthUserStore();
        axios.get('/api/getDepartment2').then(response=>{
                this.departments = response.data})
        axios.get('/api/getRoom2').then(response=>{
                this.rooms = response.data
            })
       
        axios.get('/api/getAttachments').then(response=>{
                this.attachments = response.data
            })
        axios.get('/api/getMember2/'+this.authUserStore.user.department_id).then(response=>{
                this.members = response.data
            })
        // axios.get('/api/getCategory2',{ params: { keyword: this.complaintData.selectedDepartment } }).then(response=>{
        //         this.categories = response.data
        //     })
        axios.get('/api/selectedRooms').then(response=>{
                this.selectedrooms = response.data})
               
        
    },
    methods: {
        getRooms(){

            axios.get('/api/selectedRooms').then(response=>{
                this.selectedrooms = response.data
            }).catch(errors=>{
                console.log(errors)
            });
        },

        getComplaints(){
          
            axios.get('/api/getComplaints',{ params: { keyword: this.keyword }}).then(response=>{
                this.complaints = response.data               
            }).catch(errors=>{
                console.log(errors)
            });
            
            },

        getComplaintmembers(){
            axios.get('/api/getcomplaintmembers').then(response=>{
                this.complaintmembers = response.data
            })
        },

       
        resetInput(){
        this.roomData.name = '';
        this.complaintData.selectedRoom= '';
        this.complaintData.guest_name= '';
        this.complaintData.defect= '';
        this.complaintData.selectedDepartment= '';
        this.complaintData.action= '';
        this.complaintData.remark= '';
        this.complaintData.result= '';
        this.complaintData.arrival = '';
        this.complaintData.departure = '';
        this.complaintData.source = '';
        this.complaintData.priority = '';
        this.complaintData.member_id = '';
        this.case_status='';

     },
       
        editComplaint(complaint){
            this.editMode = true
            this.deleteMode= false
            this.complaintData= {
                id : complaint.id,
                guest_name : complaint.guest_name,
                action :complaint.action,
                remark : complaint.remark,
                defect : complaint.defect,
                arrival : complaint.arrival,
                departure : complaint.departure,
                source : complaint.source,
                priority : complaint.priority
            }
            $('#complaintModal').modal('show')
        },
       
        createRoom(){
            this.editMode = false
            this.deleteMode = false
            if(!this.addRoomMode){
                this.addRoomMode = true
            }else{
                this.addRoomMode = false
            }
            
            this.roomData= {
                id: '',
                name: '',
             

            }
          
          
        },
        findComplaint(){
            this.editMode = false
            this.deleteMode = false
            this.complaintData= {
                id: '',
                name: '',
               status: '',
               selectedCategory: '',
               source: '',
               from: '',
               to: '',
               query: ''
               
             

            }
          
            $('#complaintModal').modal('show')
        },
        findComplaint2(){
            this.editMode = false
            this.deleteMode = false
            this.complaintData= {
           
               from: '',
               to: '',
             
               
             

            }
          
            $('#byRoom').modal('show')
        },
        updateStatus(complaint){
            this.editMode = false
            this.deleteMode = false
            if(this.authUserStore.user.department_id==complaint.department_id){
                this.case_status = complaint.status
            this.complaintData= {
                id: complaint.id,
                action: complaint.action,
                defect: complaint.defect,
                user_id : complaint.user_id,
                // member_id: complaint.member_id,
                
               
             

            }
          
            $('#updateModal').modal('show')
            }else{
                Swal.fire(
                                'Not Authorized!',
                                'You are not authorized for this issue',
                                'warning'
                            )
            }
           
        },
        toggleview(){
            if(!this.userMode){
                this.userMode = true
                this.title = "User View"
                this.userinterface = true

            }else{
                this.userMode = false
                this.title = "Service View"
                this.userinterface = false
            }
        },
        detail(){
            if(!this.detailMode){
                this.detailMode = true

            }else{
                this.detailMode = false

            }
        },
        attached(complaint){
         
            if(this.authUserStore.user.department_id==complaint.user_department_id){
                this.case_status = complaint.status
                this.attachMode = true
            this.complaintData= {
                id: complaint.id,
                
                
               
             

            }
          
            $('#attachModal').modal('show')
            }else{
                Swal.fire(
                                'Not Authorized!',
                                'You are not authorized for this issue',
                                'warning'
                            )
            }
           
        },
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.complaintData.img = reader.result;
            }
            reader.readAsDataURL(file);
        },
        storePhoto(){

            axios.post('/api/storePhoto' ,this.complaintData).then(response=>{
                        $('#attachModal').modal('hide');
                this.getComplaints()
                this.attachMode=false
                axios.get('/api/getAttachments').then(response=>{
                this.attachments = response.data
            })
                }).catch(error =>this.errors = error.response.data.errors)


        },
        storeRoom(){


            
                axios.post('/api/storeRoom' ,this.roomData).then(response=>{
                    
                   this.resetInput()
                   this.addRoomMode = false
                   
                    axios.get('/api/getRoom2').then(response=>{
                this.rooms = response.data
            })
                }).catch(error =>this.errors = error.response.data.errors)
                
            
        }, 
        storeStatus(){


            this.complaintData.status= this.case_status;       
            axios.post('/api/storeStatus' ,this.complaintData).then(response=>{
                $('#updateModal').modal('hide')
            this.resetInput()
            this.getComplaints()
            this.getComplaintmembers()
            this.addRoomMode = false           
              
            }).catch(error =>this.errors = error.response.data.errors)


            }, 
        deleteComplaint(id) {          
        Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this Complaint!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/complaints/${id}`)
                        .then((response) => {                    
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            this.getComplaints()
                            this.getRooms()
                        });
                }
            })
        },

        closeComplaint(id) {          
        Swal.fire({
        title: 'Are you sure?',
        text: "You are going to close this Complaint!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, close it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/closecomplaints/${id}`)
                        .then((response) => {                    
                            Swal.fire(
                                'Done!',
                                'Your case has been closed.',
                                'success'
                            )
                            this.getComplaints()
                            this.getRooms()
                        });
                }
            })
        },

        removePhoto(id) {

            
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this image!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/api/attachment/${id}`)
                            .then((response) => {                    
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                )
                                this.getComplaints()
                                axios.get('/api/getAttachments').then(response=>{
                this.attachments = response.data
            })
                            });
                    }
                })
            },
        searchComplaint(){
            if(this.dept){
             this.complaintData.selectedDepartment= this.dept;           
            }else{
                this.complaintData.selectedDepartment= '';
            }
             axios.get('/api/findComplaints',{ params: { keyword: this.complaintData }}).then(response=>{
                this.complaints = response.data               
            }).catch(errors=>{
                console.log(errors)
            });
            $('#complaintModal').modal('hide')
            },
            searchComplaint2(){
           
             axios.get('/api/findComplaintByRoom',{ params: { keyword: this.complaintData }}).then(response=>{
                this.complaints = response.data               
            }).catch(errors=>{
                console.log(errors)
            });
            $('#byRoom').modal('hide')
            },

            generatePdf(){
                if(this.dept){
             this.complaintData.selectedDepartment= this.dept;           
            }else{
                this.complaintData.selectedDepartment= '';
            }
                axios({
                method:'post',
                url:'/api/reportComplaints',
                responseType:'arraybuffer',
                data: this.complaintData
                })
                .then(function(response) {
                    let blob = new Blob([response.data], { type:   'application/pdf' } );
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'complaintReport.pdf';
                    link.click();
                   //\ $('#reportModal').modal('hide')
                });
            },
            generatePdf2(){
              
                axios({
                method:'post',
                url:'/api/reportComplaintbyRoom',
                responseType:'arraybuffer',
                data: this.complaintData
                })
                .then(function(response) {
                    let blob = new Blob([response.data], { type:   'application/pdf' } );
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'complaintReport.pdf';
                    link.click();
                    $('#byRoom').modal('hide')
                });
            },
           
        updateComplaint(){           
                       
                axios.post('/api/updateComplaint' ,this.complaintData).then(response=>{
                    $('#complaintModal').modal('hide');
                this.getComplaints()
                this.getRooms()
                
                this.editMode = false
                }).catch(error =>this.errors = error.response.data.errors)
       
                   
               },
        
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.complaintData.photo = reader.result;
            }
            reader.readAsDataURL(file);
        },
    getImageClass: function(i) {
        return {
            'fullWidthImage': this.fullWidthImageIndex === i
        };
    },
    onImageClick: function(attachment) {
        // this.attachMode=false
        // this.photoview = attachment.name;
       
        // $('#attachModal').modal('show')

        
        this.$viewerApi({
          attachments : attachment,
        })
      
  
    },

            
        
    },
    view(attachment){
        this.attachMode=false
        this.photoview = attachment.name;
        $('#attachModal').modal('show')
    },
    

}
</script>
<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.ssr.css"/>
<style>
.owner {
    font-weight: bolder;
    
}
.pending {
    color: red;
}
.done {
    color: green;
}
.inProgress {
    color: orange;
}
.project {
    color: blue;

}
.assign {
    color: violet;

}
.fullWidthImage {
 /* width: 800px !important;
  height: 600px !important; */
  object-fit: cover !important;
width: 40% !important;
height: auto !important;
  display: flex;
  position: absolute;
}

.image {
    float: left;
    padding: 2px;
  height: 50px;
  width: 55px;
  position: relative;

 
}
.image .cross {
    color: black;
    position: absolute;
    top: 0;
    right: 0;
}



</style>