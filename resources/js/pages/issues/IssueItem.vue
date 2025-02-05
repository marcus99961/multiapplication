<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2">
                            <h5 class="float-start text-success">Issue No. {{ issue_no.name }}</h5>
                          
                          
                        </div>
                        <!-- <div class="col-md-6 form-check form-switch">
                              
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" @click="expired">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Expired</label>
                            </div> -->
                        <!-- <div class="col-md-2 float-center">
                            <input class="form-control-sm rounded" type="text" v-model="keyword" placeholder="search item..">
                            
                        </div> -->
                        <div class="col-md-4">
                            <button @click="posting"  class="btn-info btn-sm float-right"><i class="fa fa-plus-circle mr-1"></i>Posting</button>
                        </div>
                    </div>
                    <div class="row">
                        <!-- <div class="col-md-2">
                            <div class="form-group">
                                <label for="title" >Date</label>
                                <input type="date" class="form-control" v-model="itemData.issue_date">
                                <small class="text-danger" v-if="errors.issue_date"> {{ errors.issue_date[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-3" >
                            <div class="form-group">
                                <label for="title" >Location</label>
                                <multiselect v-model="issuelocation" :options='selectedlocations' :multiple="false" :show-labels="false"
                                :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.location_id"> {{ errors.location_id[0] }} </small><br>
                            </div>
                        </div>
                      
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Department</label>
                                <multiselect v-model="itemData.department_name" :options="departments" :multiple="false" :show-labels="false"
                                        :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.department_name"> {{ errors.department_name[0] }} </small><br>
                            </div>
                        </div>
                        -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" >Item Name</label>
                                <multiselect v-model="choseitem" :options="itemsoptions" :multiple="false" :show-labels="false"
                                        :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.item_id"> {{ errors.item_id[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="title" >Qty x {{ qtyoptions.total_qty }}</label>
                                <input type="text" class="form-control" v-model="itemData.qty" @keyup.enter="storeIssue">
                                <span class="text-danger" v-show="qtyerror">Insufficient Qty</span>
                            </div>
                        </div>
                       
                      
                    

                        
                    </div>
                </div>
                <div class="card-body">
                    <div class="shadow p-3 mb-5 bg-body rounded">
                        
                        <!-- <h3>{{ current_item.name }}</h3> -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>    
                                <th>ItemCode</th>                          
                                <th>ItemName</th>
                               
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(issue, i) in issues " :key="i" class="bg-transparent">
                                <td>{{ i+1 }}</td>
                                <td>{{ issue.item.item_code }}</td>
                                <td>{{ issue.item.name }} x {{ issue.qty }}</td>
                                <!-- <td>{{ issue.currency }}</td>
                                <td class="text-right" v-if="issue.currency=='USD'">{{ issue.price_usd }}</td>
                                <td class="text-right" v-else>{{ issue.price_mmk }}</td> -->
                               
                               


                                <td>
                                    <button @click="editItem(issue)" class="ml-1" ><i class="fa fa-edit text-success mx-1"></i></button>
                                    <button @click="removeItem(issue)" class="ml-1"><i class="fa fa-trash text-danger mx-1"></i></button>                               


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
    <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="itemModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Add New Item': 'Update Item' }} </h5>
                    <h5 class="modal-title" id="itemModalLabel" v-show="deleteMode" > Delete Item </h5>
                    <h5 class="modal-title" id="itemModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"  @click='resetInput()'>x</button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Item Name</label>
                                <input type="text" class="form-control" v-model="itemData.item_id" disabled>
                                <small class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </small><br>
                            </div>
                        </div>
                       
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="title" >Qty</label>
                                <input type="text" class="form-control" v-model="itemData.qty" >
                                <small class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </small><br>
                            </div>
                        </div>
                     
                     

                    </div>
                  




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"  @click='resetInput()'>Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeItem(): updateItem()" >{{!editMode ? 'Create Item': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteItem" >Delete</button>
                </div>
                <div class="modal-footer" v-show="renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="renewItem" >Renew</button>
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
import { useSearchStore } from '../../stores/SearchStore';
import { useRouter, useRoute } from 'vue-router';
import { useStockStore } from '../../stores/StockStore';
import { useItemStore } from '../../stores/ItemStore';



const authUserStore = useAuthUserStore;
const router = useRouter();
export default {
    setup: () => ({
        title: 'All Items'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            locationMode: false,
            expiredMode: false,
            keyword: null,
            issuelocation: null,
            choseitem: null,
            pages: null,
            qtyerror: false,
            
            form: { id : ''},
            itemData: {
                item_id: '',                   
                location: '',
                department_name: '',
                qty: '',
                issue_date: '',
               
              
          

            },
            qtyoptions: {},
            units: [],
            items: {},
            stocks: {},
            itemsoption: {},
            itemsoptions: [],
            departments: [],
            categories: [],
            current_user: {},
            issue_no : { name: '', location_id: ''},
            errors: {},
            receives: {},
            issues: {},
            selectedlocations: [],
            authUserStore: {},
            settingStore: {},
            searchStore: {},
            stockStore: {},
            itemStore: {},
        }
    },
    watch: {
        keyword(after, before) {
            this.getItems();
        },
        // issuelocation : function (value){
        //     axios.get('/api/getSourceitem',{params: { keyword: this.issuelocation }}).then(response=>{
        //     this.itemsoptions = response.data
        //     })
           
        // },
        choseitem : function (value){
            axios.get('/api/getSourceitemqtys',{params: { item: this.choseitem, location: this.form.id}}).then(response=>{
            this.qtyoptions = response.data
            })
           
        },
    },
    mounted(){
        this.getIssueitems()
        this.getUnits()
        this.getCategories()
        this.getSelectedcategory()
        
        axios.get('/api/getSelecteddepartment').then(response=>{
                this.departments = response.data
            })
            axios.get('/api/getSourceitems',{params: { keyword: this.form.id }}).then(response=>{
            this.itemsoptions = response.data
            })
        axios.get('/api/getSelectedlocation').then(response=>{
                this.selectedlocations = response.data
            })
            axios.get('/api/stockStore')
            .then((response) => {
                this.stocks = response.data
            })
            axios.get('/api/getItem').then(response=>{
                this.itemStore = response.data
            })
            axios.get('/api/getSelecteditems').then(response=>{
                this.itemsoption = response.data
            })
    },
    created(){
        console.log(window.user)
        this.form.id = this.$route.params.id
        this.current_user = window.user
        this.authUserStore = useAuthUserStore();
        this.settingStore = useSettingStore();
        this.searchStore = useSearchStore();
        this.stockStore = useStockStore();
       
        axios.get('/api/getIssue_no',{ params: { keyword: this.form.id } }).then(response=>{
                this.issue_no = response.data
            })
        
    },
    methods: {
        getItems(page=1){

            axios.get('/api/getItems?page='+page, { params: { keyword: this.keyword } } ).then(response=>{
                this.items = response.data
                this.pages = page
            }).catch(errors=>{
                console.log(errors)
            });
        },
        getIssueitems(){

                axios.get('/api/getIssueitems',{ params: {keyword: this.form.id } } ).then(response=>{
                    this.issues = response.data
                }).catch(errors=>{
                    console.log(errors)
                });
                },
        getUnits(){

            axios.get('/api/getSelectedunit').then(response=>{
                this.units = response.data
            }).catch(errors=>{
                console.log(errors)
            });
            },
            getCategories(){

                axios.get('/api/getSelectedcategory').then(response=>{
                    this.categories = response.data
                }).catch(errors=>{
                    console.log(errors)
                });
                },
                getSelectedcategory(){

                axios.get('/api/getCategories').then(response=>{
                    this.selectedcategory = response.data
                }).catch(errors=>{
                    console.log(errors)
                });
                },
        removeItem(receive){
            this.deleteMode = true
            this.itemData.id = receive.id
            $('#itemModal').modal('show')
        },
        deleteItem(){
            axios.delete('/api/deleteIssueitem/' + this.itemData.id).then(response => {
                this.getIssueitems()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#itemModal').modal('hide')
            });
        },
    
        editItem(issue){
            this.editMode = true
            this.deleteMode= false
            this.current_item = ''
            let arr = [];
            this.itemsoption.forEach((value, index) => {
                arr.push(value);
                if(value.id==issue.item_id){
                    this.current_item = value.name
                }


            })
            this.itemData= {
                id : issue.id,
                item_id : this.current_item,
                qty :issue.qty,
               
             
             
            }
        
            $('#itemModal').modal('show')
        },
        updateItem(){



            axios.post('/api/updateIssue/' + this.itemData.id, this.itemData).then(response => {
                $('#itemModal').modal('hide');
                this.getIssueitems()
                this.resetInput()
                }).catch(error =>this.errors = error.response.data.errors)



        },
        expired(){
            if(!this.expiredMode){
                this.expiredMode = true

            }else{
                this.expiredMode = false

            }
        },

        detail(){
            if(!this.locationMode){
                this.locationMode = true

            }else{
                this.locationMode = false

            }
        },
        posting() {
            Swal.fire({
                title: 'Are you sure?',
                text: `Posting for Invoice No."${this.issue_no.name}"`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Post it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`/api/issueposting/${this.form.id}`)
                        .then((response) => {
                            Swal.fire(
                                'Posted!',
                                'Your Invoice has been posted.',
                                'success'
                            )
                            this.$router.push('/admin/issues')
                            // this.getCancels()

                        });
                }
            })

        },
        createItem(){
            this.editMode = false
            this.deleteMode = false
            this.itemData= {
                id: '',
                name: '',
             

            }
            this.itemErrors= {
                name: false,

            }
            $('#itemModal').modal('show')
        },
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.itemData.img = reader.result;
            }
            reader.readAsDataURL(file);
        },
        resetInput(){
    
        this.itemData.issue_date= '';
        this.itemData.department_name= '';
        this.itemData.qty= '';
        this.choseitem= '';
        this.issuelocation= '';
        this.qtyoptions= '';
      
       

     },
        storeIssue(){
            this.itemData.qty > this.qtyoptions.total_qty ? this.qtyerror = true: this.qtyerror = false
            if(!this.qtyerror){
                this.itemData.location = this.issuelocation;   
              
                this.current_chose_item = ''

            let arr = [];
            this.itemStore.forEach((value, index) => {
                arr.push(value);
                if(value.name==this.choseitem){
                    this.itemData.item_id = value.id
                }


            })
                axios.post('/api/storeIssueitems/'+this.form.id, this.itemData).then(response=>{
                this.resetInput()
                this.getIssueitems()
                
                }).catch(error =>this.errors = error.response.data.errors)

        
            }
            
        }
    }

}
</script>
