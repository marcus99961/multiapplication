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
                           
                            <div class="form-group">
                            <input type="radio" @change="toggleview" v-model="mode" value="user" class="mr-1"/>
                            <label class="form-check-label mr-2" for="flexSwitchCheckDefault">User</label>
                            <input type="radio" @change="toggleview" v-model="mode" value="service" class="mr-1"/>
                            <label class="form-check-label mr-2" for="flexSwitchCheckDefault">Service</label>
                            <input type="radio" @change="toggleview" v-model="mode" value="all" class="mr-1"/>
                            <label class="form-check-label mr-2" for="flexSwitchCheckDefault">All</label>
                           
                                <input class="form-check-input ml-2" type="checkbox" id="flexSwitchCheckDefault" @click="closestatus">
                                <label class="form-check-label ml-4" for="flexSwitchCheckDefault">Show Closed</label>
                           
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="text" v-model="keyword" placeholder="Search..." />                           
                        </div>
                        <div class="col-md-3" v-show="userMode && !serviceMode">
                            
                            <button @click="createComplaint"  class="mb-2 btn-sm btn-primary float-right"><i class="fa fa-plus-circle mr-1"></i>New Complaint</button>
                        </div>
                        <div class="col-md-3" v-show="!userMode && !serviceMode && authUserStore.user.department_id==4">
                            
                            <button @click="dailyReport"  class="mb-2 btn-sm btn-primary float-right"><i class="fa fa-plus-circle mr-1"></i>Send Report</button>
                        </div>
                        
                    </div>

                </div>
                <div class="card-body" v-show="!userMode && serviceMode">
                    <div class="row">
                    <div class="shadow p-3 mb-5 bg-body rounded col-md-12">
                        <table class="table table-bordered">
                        <thead>
                            <tr>                               
                                <th style="width: 10px">#</th>
                                <th>Room</th>
                                <th>Defect</th>
                                <th>Attached Photos</th>
                                <th>Status</th>
                                <th>Assigned</th>
                                <th>Action</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr v-for="memeber in members">
                                <td>{{ member.name }}</td>

                            </tr> -->
                                <tr v-for="(complaint, i) in complaints.data" :key="i" >
                                  
                                   
                                  
                                    <th v-if="pages > 0">{{(pages*15)-15+i+1}}</th> 
                                    <th v-else>{{i+1}}</th> 
                                    <td><text v-for="selectedroom in selectedrooms"><text  v-if="complaint.id==selectedroom.complaint_id">{{ selectedroom.name }}, </text></text></td>
                                    <td @dblclick="updateStatus(complaint)"> <text v-if="complaint.department_id == authUserStore.user.department_id" class="owner hover-text">{{ complaint.defect }}<span class="tooltip-text">{{ complaint.user_fullname }}</span></text>
                                        <text v-else>{{ complaint.defect }}</text>
                                    
                                        <!-- <div  v-if="event.id==pay.deposit_id" class="py-1 hover-text" style="text-align: right;">{{ format_date(pay.payment_date) }} {{ pay.currency }}<span class="tooltip-text" id="top">{{ pay.name }}</span> -->
                                    
                                    </td>
                                    <td @dblclick.prevent="attached(complaint)"><div v-for="attachment in attachments" class="images" v-viewer> <div v-if="attachment.complaint_id==complaint.id" class="image m-1"><i class="cross fa fa-trash text-danger" @click="removePhoto(attachment.id)"></i> <img :src="'/'+ attachment.name" @click="onImageClick(attachment)"/></div> </div></td>

                                    <td :class='complaint.status'>{{ complaint.status }}</td>
                                    <td><div v-for="complaintmember in complaintmembers"><div v-if="complaint.id==complaintmember.complaint_id" class="done">{{ complaintmember.name }}->{{ complaintmember.mobile }}</div> </div></td>
                                    <!-- <td>{{ complaint.remark }}</td> -->
                                    <td>{{ complaint.action }}</td>
                                    <td>
                                        
                                        <a href="#" @click.prevent="editComplaint(complaint)" v-show="complaint.user_id==authUserStore.user.id"><i class="fa fa-edit"></i></a>
                                       
                                        <a href="#" @click.prevent="deleteComplaint(complaint.id)" v-show="complaint.user_id==authUserStore.user.id"><i class="fa fa-trash text-danger ml-2"></i></a>
                                        <!-- <div v-for="count in messageCount"> -->
                                        <router-link :to="`/admin/messages/${ complaint.id }`" v-show="complaint.user_department_id==authUserStore.user.department_id || complaint.department_id == authUserStore.user.department_id"  class="btn-success btn-xs ml-2"><i class="fa-solid fa-message mr-1" ></i>{{ complaint.msg_status }}</router-link>


                                    </td>
                               

                                </tr>
                        </tbody>
                       
                    </table>
                        
                    </div>
                  
                    </div>
                    <Bootstrap4Pagination :data="complaints" @pagination-change-page="toggleview"></Bootstrap4Pagination>

                </div>
                <div class="card-body" v-show="userMode && !serviceMode">
                    <div class="row">
                    <div class="shadow p-3 mb-5 bg-body rounded col-md-12">
                        <table class="table table-bordered">
                        <thead>
                            <tr>                               
                                <th style="width: 10px">#</th>
                                <th>Room</th>
                                <th>Defect</th>
                                <th>Attached</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>Assigned</th>
                                <th>Atten:</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr v-for="memeber in members">
                                <td>{{ member.name }}</td>

                            </tr> -->
                                <tr v-for="(complaint, i) in complaints.data" :key="i" >
                                  
                                   
                                    <th v-if="pages > 0">{{(pages*15)-15+i+1}}</th> 
                                    <th v-else>{{i+1}}</th> 
                                    <td><text v-for="selectedroom in selectedrooms"><text  v-if="complaint.id==selectedroom.complaint_id">{{ selectedroom.name }}, </text></text></td>
                                    <td @dblclick="updateStatus(complaint)"> <text v-if="complaint.department_id == authUserStore.user.department_id" class="owner">{{ complaint.defect }}</text>
                                        <text v-else>{{ complaint.defect }}</text>
                                    </td>
                                    <td @dblclick.prevent="attached(complaint)"><div v-for="attachment in attachments" class="images" v-viewer> <div v-if="attachment.complaint_id==complaint.id" class="image"><i class="cross fa fa-trash text-danger ml-2" @click="removePhoto(attachment.id)"></i> <img :src="'/'+ attachment.name" @click="onImageClick(attachment)"/></div> </div></td>

                                    
                                    
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
                                        <a href="#" @click.prevent="closeComplaint(complaint.id)" v-show="complaint.status=='done' && complaint.user_department_id == authUserStore.user.department_id"><i class="fa-regular text-danger fa-copyright m-2"></i></a>



                                    </td>
                               

                                </tr>
                        </tbody>
                       
                    </table>
                        
                    </div>
                  
                    </div>
                    <Bootstrap4Pagination :data="complaints" @pagination-change-page="toggleview"></Bootstrap4Pagination>

                </div>
                <div class="card-body" v-show="!userMode && !serviceMode">
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
                                <th>Assigned</th>
                                <th>Atten:</th>
                                <th>Since</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr v-for="memeber in members">
                                <td>{{ member.name }}</td>

                            </tr> -->
                                <tr v-for="(complaint, i) in complaints.data" :key="i" >
                                  
                                   
                                    <th v-if="pages > 0">{{(pages*15)-15+i+1}}</th> 
                                    <th v-else>{{i+1}}</th> 
                                    <!-- <th>{{pages+i}}</th>  -->
                                    <td><text v-for="selectedroom in selectedrooms" class="hover-text"><text  v-if="complaint.id==selectedroom.complaint_id">{{ selectedroom.name }}, <span class="tooltip-text">{{ complaint.source }}</span></text></text></td>
                                    <td @dblclick="updateStatus(complaint)"> <text v-if="complaint.department_id == authUserStore.user.department_id" class="owner hover-text">{{ complaint.defect }}<span class="tooltip-text">{{ complaint.user_fullname }}</span></text>
                                        <text v-else class="hover-text">{{ complaint.defect }}<span class="tooltip-text">{{ complaint.user_fullname }}</span></text>
                                    
                                    
                                    
                                    </td>
                                    <td @dblclick.prevent="attached(complaint)">{{ complaint.action }}</td>
                                    <td :class='complaint.status'>{{ complaint.status }}</td>
                                    <td><div v-for="complaintmember in complaintmembers"><div v-if="complaint.id==complaintmember.complaint_id" class="done">{{ complaintmember.name }}->{{ complaintmember.mobile }}</div> </div></td>

                                   
                                    <td>{{ complaint.department_name }}</td>
                                    <td><text class="hover-text"><text>{{ format_date(complaint.created_at)  }} <span class="tooltip-text">{{ format_date(complaint.updated_at)  }}</span></text></text></td>

                                    <!-- <td>{{ format_date(complaint.created_at)  }}</td> -->
                                    <!-- <td>{{ complaint.remark }}</td> -->
                                  
                               

                                </tr>
                        </tbody>
                       
                    </table>
                        
                    </div>
                  
                    </div>
                    <Bootstrap4Pagination :data="complaints" @pagination-change-page="toggleview"></Bootstrap4Pagination>
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

                                <small class="text-danger" v-if="errors.action"> {{ errors.action[0] }} </small><br>
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
    <div class="modal fade" id="attachModal"  tabindex="-1" aria-labelledby="attachModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white" v-show="attachMode">
                    <h5 class="modal-title" id="attachModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Attach photo': 'Update Room' }} </h5>
               
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="attachMode">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color" >Attachment</label>
                                <input type="hidden" v-model="complaintData.id">
                                <input type="file" class="form-control" @change="selectedImage" >               
                               

                                
                            </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group image">
                                    <img :src="'/'+complaintData.oldphoto" v-if="complaintData.oldphoto && !complaintData.photo"/>
                                    <img :src="complaintData.photo" v-if="complaintData.photo"/>
                                </div>
                            
                        </div>

                    </div>
                  







                </div>
                <div class="modal-footer" v-show="attachMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storePhoto(): updateComplaint()" >{{!editMode ? 'Save': 'Save Changes' }}</button>
                </div>
              
            </div>
        </div>
    </div>


    <div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="complaintModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Add New Complaint': 'Update Complaint' }} </h5>
                    <h5 class="modal-title" id="complaintModalLabel" v-show="deleteMode" > Delete Room </h5>
                    <h5 class="modal-title" id="complaintModalLabel" v-show="renewMode" >Renew License </h5>
                    <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" @click="detail">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Detail</label>
                            </div>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="shadow p-3 mb-5 bg-body rounded col-md-12">
                        <div class="row">
                            <div class="col-md-6" v-show="!editMode">
                                <div class="form-group">
                                    <label for="title" @dblclick="createRoom">Room No.</label>
                                    <input type="text" v-show="addRoomMode" class="form-group input-sm col-xs-2" v-model="roomData.name" @keyup.enter="storeRoom">
                                    <small class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </small><br>
                                    <multiselect v-model="complaintData.selectedRoom" :options="rooms" :multiple="true"
                                        :close-on-select="true"> </multiselect>
                                        <small class="text-danger" v-if="errors.selectedRoom"> {{ errors.selectedRoom[0] }} </small><br>

                                </div>
                            </div>
                            <div class="col-md-6" v-show="detailMode">
                                <div class="form-group">
                                    <label for="title" >Guest Name (Optional)</label>
                                    <input type="text" class="form-control" v-model="complaintData.guest_name" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                           
                           <div class="col-md-6">
                                <div class="form-group">
                                    <div>Source: </div>
                                    <input type="radio" id="guest" value="guest" class="sm-2" v-model="complaintData.source" />
                                    <label for="guest">Guest</label>

                                    <input type="radio" id="admin" value="admin" class="sm-2" v-model="complaintData.source" />
                                    <label for="admin">Admin</label>
                                </div>
                           </div>
                           <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" >Case Priority</label>
                                <select v-model="complaintData.priority" class="form-control">
                                <option value="urgent">Urgent</option>
                                <option value="high">High</option>
                                <option value="normal">Normal</option>
                                <option value="low">Low</option>
                                </select>
                                <small class="text-danger" v-if="errors.priority"> {{ errors.priority[0] }} </small><br>
                            </div>
                           </div>
                       </div>
                        <div class="row" v-show="detailMode">
                           
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" >Arrival (Optional)</label>
                                    <input type="date" class="form-control" v-model="complaintData.arrival" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" >Departure (Optional)</label>
                                    <input type="date" class="form-control" v-model="complaintData.departure" >

                                </div>
                            </div>
                        </div>
                        <div class="row" v-show="!editMode">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" >Department</label>
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title" >Defect</label>
                                    <textarea class="form-control" v-model="complaintData.defect" name="w3review" rows="4" cols="50"/>
                                    <span class="text-danger" v-if="errors.defect"> {{ errors.defect[0] }} </span><br>

                                    
                                </div>
                            </div>
                          
                        </div>
                      
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title" >Remark (optional)</label>
                                    <input type="text" class="form-control" v-model="complaintData.remark" >
                                    <small class="text-danger" v-if="errors.remark"> {{ errors.priority[0] }} </small><br>

                                    <input type="hidden" class="form-control" v-model="complaintData.id" >
                                </div>
                            </div>
                          
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                                <label for="color" >Attachment</label>
                                <input type="file" class="form-control" @change="selectedImage" accept="image/png, image/jpeg, image/jpg, image/gif">               
                               
                                <span class="text-danger" v-if="errors.photo"> {{ errors.photo[0] }} </span><br>
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group image">
                                    <img :src="'/'+complaintData.oldphoto" v-if="complaintData.oldphoto && !complaintData.photo"/>
                                    <img :src="complaintData.photo" v-if="complaintData.photo"/>
                                </div>
                            
                        </div>
                        </div>
                       
                        
                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click.prevent="!editMode ? storeComplaint(): updateComplaint()" >{{!editMode ? 'Save': 'Save Changes' }}</button>
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

import Swal from 'sweetalert2';
import { useAuthUserStore } from '../../stores/AuthUserStore';
import moment from 'moment';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
import { useSettingStore } from '../../stores/SettingStore';


const authUserStore = useAuthUserStore;

export default {
   
    setup: () => ({
        title: 'Complaints'
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
            serviceMode: false,
            detailMode: false,
            guestMode: false,
           
            keyword: null,
            dept: null,           
            case_status: null,
            fullWidthImageIndex: null,           
            mode: null,
            current_member_id : null,
            pages : null,
            close_status: 'closed',
            query: {},
            authUserStore: {},
            settingStore: {},


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
            complaints: { },
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
            this.toggleview();
        },
        close_status(after, before) {
            this.toggleview();
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
                }else if(this.case_status== 'done') {
                    this.assignMode = true
                    this.actionMode = true
                    
                }else if(this.case_status== 'inProgress') {
                    this.assignMode = true
                    this.actionMode = true
                }else if(this.case_status== 'project') {
                    this.assignMode = true
                    this.actionMode = true
                }else {
                    this.assignMode = false
                    this.actionMode = true
                }
           
        }
    },
     
    mounted(){
        this.getRooms()        
        this.getComplaintmembers()
        
    },
    created(){
        this.current_user = window.user
        this.authUserStore = useAuthUserStore();
        this.settingStore = useSettingStore();
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

        axios.get('/api/selectedRooms').then(response=>{
                this.selectedrooms = response.data})
               
        
    },
    methods: {
        format_date(value){
         if (value) {
           return moment(String(value)).fromNow()
          }
        },
        getRooms(){

            axios.get('/api/selectedRooms').then(response=>{
                this.selectedrooms = response.data
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
        this.dept='';

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
        createComplaint(){
            this.editMode = false
            this.deleteMode = false
            this.complaintData= {
                id: '',
                name: '',
                remark : '',

             

            }
          
            $('#complaintModal').modal('show')
        },
        updateStatus(complaint){
            this.editMode = false
            this.deleteMode = false
            this.current_member_id = ''
            let arr = [];
            this.complaintmembers.forEach((value, index) => {
                arr.push(value);
                if(value.complaint_id==complaint.id){
                    this.current_member_id = value.member_id
                }


            });
            if(this.authUserStore.user.department_id==complaint.department_id || this.authUserStore.user.department_id==4){
                this.case_status = complaint.status
            this.complaintData= {
                id: complaint.id,
                action: complaint.action,
                defect: complaint.defect,
                user_id : complaint.user_id,
                member_id: this.current_member_id,
                
               
             

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
        closestatus(){
            if(this.close_status){
                this.close_status = null

            }else{
                this.close_status = 'closed'
            }
           
        },
        dailyReport(){

            Swal.fire({
        title: 'Are you sure?',
        text: "You are going to send daily report!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, please send it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.get(`/api/sendDailyreport`)
                        .then((response) => {                    
                            Swal.fire(
                                'Done!',
                                'Daily Report Sent Already',
                                'success'
                            )
                            this.toggleview()
                            this.getRooms()
                        });
                }
            })
        },




        //     axios.get('/api/sendDailyreport').then(response=>{
        //         this.complaints = response.data               
        //     }).catch(errors=>{
        //         console.log(errors)
        //     });
        // },
        toggleview(page=1){
            if(this.mode==='user'){
                this.userMode = true
                this.serviceMode= false
                this.title = "User View"
                axios.get('/api/getComplaints/user?page=' + page,{ params: { keyword: this.keyword, close_status: this.close_status } }).then(response=>{
                this.complaints = response.data
                this.pages = page               
                }).catch(errors=>{
                console.log(errors)
                });
                // this.title = "User View"
             //   this.$router.push('/admin/usercomplaint');
               

            }else if(this.mode === 'service') {
                this.userMode = false
                this.serviceMode = true                                                
                this.title = "Service View"
                axios.get('/api/getComplaints/service?page=' + page,{ params: { keyword: this.keyword, close_status: this.close_status }}).then(response=>{
                this.complaints = response.data     
                this.pages = page           
            }).catch(errors=>{
                console.log(errors)
            });
               
            }else{
                this.userMode = false
                this.serviceMode = false
                this.title = "All"
                axios.get('/api/getComplaints?page=' + page,{ params: { keyword: this.keyword, close_status: this.close_status }}).then(response=>{
                this.complaints = response.data       
                this.pages = page         
            }).catch(errors=>{
                console.log(errors)
            });
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
         
            if(this.authUserStore.user.department_id==complaint.user_department_id || this.authUserStore.user.department_id==complaint.department_id){
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
                this.toggleview()
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
            this.toggleview()
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
                           this.toggleview()
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
                            this.toggleview()
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
                                this.toggleview()
                                axios.get('/api/getAttachments').then(response=>{
                this.attachments = response.data
            })
                            });
                    }
                })
            },
        storeComplaint(){
             this.complaintData.selectedDepartment= this.dept;           
                       
                       axios.post('/api/storeComplaint' ,this.complaintData).then(response=>{
                        $('#complaintModal').modal('hide');
                this.toggleview()
                this.getRooms()
                this.resetInput()
                axios.get('/api/getAttachments').then(response=>{
                this.attachments = response.data
                })
                }).catch(error =>this.errors = error.response.data.errors)
       
                
               },
           
        updateComplaint(){           
                       
                axios.post('/api/updateComplaint' ,this.complaintData).then(response=>{
                    $('#complaintModal').modal('hide');
                this.toggleview()
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
      
        this.$viewerApi({
          attachments : attachment,
        })
      
    },

            
        
    },

    

}
</script>
<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.ssr.css"/>
<style>
.tooltip-text {
  visibility: hidden;
  position: absolute;
  top: 0;
  right: 105%;
  z-index: 1;

  
  color: white;
  font-size: 11px;  
  background-color: #192733;
  opacity: 70%;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;
}

.hover-text:hover .tooltip-text {
   
  visibility: visible;
  
}
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
.closed {
    color:dimgrey;
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