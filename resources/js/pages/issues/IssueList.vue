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
                            <input class="form-control-sm rounded" type="text" v-model="keyword" placeholder="search issue..">
                        </div> -->
                        <div class="col-md-6">
                            <button @click="createIssue"  class="btn-info btn-sm float-right ml-1"><i class="fa fa-plus-circle mr-1"></i>New Issue</button>
                            <button @click="undoPost"  class="btn-info btn-sm float-right ml-1"><i class="fa fa-plus-circle mr-1"></i>Undo Post</button>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="shadow p-3 mb-5 bg-body rounded">
                        <!-- <h3>{{ current_issue.name }}</h3> -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>    
                                             
                                <th>Issue</th>
                                <th>Location</th> 
                                <th>Department</th>                         
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(issue, index) in issues " :key="index" class="bg-transparent">
                                <td>{{index + 1}}</td>
                                <!-- <td>{{issue.name}}</td> -->
                               <td> <router-link :to="`/admin/issueitems/${ issue.id }`" class="bg-transparent">{{ issue.name }}</router-link></td>
                               <td>{{ issue.location.name }}</td>
                                <td>{{issue.department.name}}</td>
                               
                               
                          
                            
                               


                                <td>
                                    <button @click="editIssue(issue)" class="ml-1" ><i class="fa fa-edit text-success mx-1"></i></button>
                                    <button @click="removeIssue(issue)" class="ml-1"><i class="fa fa-trash text-danger mx-1"></i></button>                               


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
    <div class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="issueModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="issueModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Add New Issue': 'Update Issue' }} </h5>
                    <h5 class="modal-title" id="issueModalLabel" v-show="deleteMode" > Delete Issue </h5>
                    <h5 class="modal-title" id="issueModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">                     
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Issue No.</label>
                                <input type="text" class="form-control" v-model="issueData.name" >
                                <small class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Location</label>
                                <multiselect v-model="issueData.location_id" :options='locations' :multiple="false" :show-labels="false"
                                :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.location_id"> {{ errors.location_id[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Department</label>
                                <multiselect v-model="issueData.department_id" :options='departments' :multiple="false" :show-labels="false"
                                :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.department_id"> {{ errors.department_id[0] }} </small><br>
                            </div>
                        </div>
                       
                    
                    
                      
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" >Issued</label>
                                <input type="date" class="form-control" v-model="issueData.issue_date" >
                                <small class="text-danger" v-if="errors.issue_date"> {{ errors.issue_date[0] }} </small><br>
                            </div>
                        </div>
                       
                    

                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeIssue(): updateIssue()" >{{!editMode ? 'Create Issue': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteIssue" >Delete</button>
                </div>
                <div class="modal-footer" v-show="renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="renewIssue" >Renew</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="undoModal" tabindex="-1" aria-labelledby="issueModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="issueModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Undo Posting': 'Update Issue' }} </h5>
                    <h5 class="modal-title" id="issueModalLabel" v-show="deleteMode" > Delete Issue </h5>
                    <h5 class="modal-title" id="issueModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                     
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="title" >Issue No.</label>
                                <multiselect v-model="postedData.issue_no" :options='posted' :multiple="false" :show-labels="false"
                                :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.issue_no"> {{ errors.issue_no[0] }} </small><br>
                            </div>
                        </div>
                       
                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? undoPosting(): updateIssue()" >{{!editMode ? 'Undo Posting': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteIssue" >Delete</button>
                </div>
                <div class="modal-footer" v-show="renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="renewIssue" >Renew</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import { useSettingStore } from '../../stores/SettingStore';
import Swal from 'sweetalert2';
export default {
    setup: () => ({
        title: 'All Issues'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            keyword: null,
        

            issueData: {
                received_date: '',
                name: '',
                supplier_id: '',
                location_id: '',
          

            },
        
            issues: {},
            imbalance: {},
            current_user: {},
            suppliers: [],
            authUserStore: {},
            settingStore: {},
            errors: {},
            posted: [],
            postedData: {},
            departments: [],
            locations: [],
            selectedlocations: {},
            selectedsuppliers: {},
           
        }
    },
    watch: {
        keyword(after, before) {
            this.getIssues();
        }
    },
    mounted(){
        this.getIssues()
            axios.get('/api/getSelecteddepartment').then(response=>{
                this.departments = response.data
            })
            axios.get('/api/getSelectedlocation').then(response=>{
                this.locations = response.data
            })
            axios.get('/api/getLocations').then(response=>{
                this.selectedlocations = response.data
            })
            axios.get('/api/getSuppliers').then(response=>{
                this.selectedsuppliers = response.data
            })
            axios.get('/api/getPostedissue').then(response=>{
                this.posted = response.data
            })
    },
    created(){
        console.log(window.user)
        this.current_user = window.user
        this.settingStore = useSettingStore();
    },
    methods: {
        getIssues(){

            axios.get('/api/getIssues').then(response=>{
                this.issues = response.data
            }).catch(errors=>{
                console.log(errors)
            });
        },

        removeIssue(issue){
            this.deleteMode = true
            this.issueData.id = issue.id
            $('#issueModal').modal('show')
        },
        deleteIssue(){
            axios.delete('/api/deleteIssue/' + this.issueData.id).then(response => {
                this.getIssues()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#issueModal').modal('hide')
            });
        },
        undoPost(){
            this.editMode = false
            this.deleteMode = false
            $('#undoModal').modal('show')
        },
        editIssue(issue){
            this.editMode = true
            this.deleteMode= false
            this.current_location = ''
            this.current_supplier = ''
            let arr = [];
            this.selectedlocations.forEach((value, index) => {
                arr.push(value);
                if(value.id==issue.location_id){
                    this.current_location = value.name
                }


            })
            let arr2 = [];
            this.selectedsuppliers.forEach((value, index) => {
                arr2.push(value);
                if(value.id==issue.supplier_id){
                    this.current_supplier = value.name
                }


            })
            this.issueData= {
                id: issue.id,
                name: issue.name,
                supplier_id: this.current_supplier,
                location_id: this.current_location,
                received_date: issue.received_date,
            
            }
        
            $('#issueModal').modal('show')
        },
        updateIssue(){



            axios.post('/api/updateIssue/' + this.issueData.id, this.issueData).then(response => {
                $('#issueModal').modal('hide');
                this.getIssues()
                }).catch(error =>this.errors = error.response.data.errors)



        },
     
        createIssue(){
            this.editMode = false
            this.deleteMode = false
            this.issueData= {
                id: '',
                name: '',
                supplier_id: '',
                location_id: '',
                received_date: '',
             

            }
            this.issueErrors= {
                name: false,

            }
            $('#issueModal').modal('show')
        },
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.issueData.img = reader.result;
            }
            reader.readAsDataURL(file);
        },
        storeIssue(){
         
                axios.post('/api/storeIssue', this.issueData).then(response=>{
                    $('#issueModal').modal('hide');
                this.getIssues()
                }).catch(error =>this.errors = error.response.data.errors)

        

            
        },
        undoPosting(){
            axios.post('/api/undoissuePosting', this.postedData).then(response=>{
              
                    $('#undoModal').modal('hide');
                  
                        Swal.fire({
                            title: "Posted items are reverted!",
                            text: "Congratulations!",
                            icon: "success"
                            });
                    
                this.getIssues()
                }).catch(error =>this.errors = error.response.data.errors)
        }
    }

}
</script>
