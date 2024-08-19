<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-3">
                    <h1 class="m-0">{{ title }}</h1>
                </div>
                <div class="col-sm-3">
                            <!-- <input type="text" v-model="keyword" placeholder="Search..." />      -->
                            <!-- <button @click="generatePdf"  class="mb-2 btn-sm btn-primary float-right"><i class="fa-solid fa-print m-1"></i>Generate PDF</button> -->
                      
                        </div>
                <div class="col-sm-6">
                    <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <button @click="addTv" type="button" class="mb-2 btn btn-primary" v-show="authUserStore.user.department_id==1 || authUserStore.user.department_id==3">
                        <i class="fa fa-plus-circle mr-1"></i>
                        Add TV
                    </button>
                    <div>
                       
                    </div>
                </div>
                <div>
                    <input type="text" v-model="keyword" class="form-control" placeholder="Search..." />
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid"><div v-if="selectedItems.length > 0"><button @click="DeleteAllItem" class="btn-xs btn-danger m-1">Delete</button>
            <button @click="UpdateAllItem" class="btn-xs btn-info m-1">Update</button>
            <button @click="generatePdf"  class="btn-xs btn-success m-1"><i class="fa-solid fa-print m-1"></i>Generate PDF</button></div>
            
        
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                            <thead>
                            <tr>
                                <!-- <td><input type="checkbox" v-model="selectAll" @change="selectallitem()"/></td> -->
                                <th>#</th>
                                <th>Room</th>
                                <th>Brand</th>
                                <th>Size</th>                              
                                <th>Model</th>
                                <th>TunerType</th>
                                <th>Setup</th>
                                <th>Options</th>
                         
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item,i) in tvs.data " :key="i" class="images" v-viewer>
                                <!-- <td><input type="checkbox" v-model="selectedItems" @change="toggleSelection()" :value="item.id"/></td> -->
                                <td>{{(pages*settingStore.setting.pagination_limit)-settingStore.setting.pagination_limit+i+1}}</td>

                                <td><div @dblclick="editItem(item)">{{item.room_no}}</div></td>
                             
                                <td>{{item.brand}}</td>                         
                                  
                                <td>{{item.size}}</td>
                                <td>{{item.model}}</td>
                                <td>{{item.tuner}}</td>
                                <td>{{item.type}}</td>
                                
                                <td> <a href="#" @click.prevent="editTv(item)"><i class="fa fa-edit text-info mx-2"></i></a>
                                    <a href="#" @click.prevent="deleteTv(item.id,item)"><i class="fa fa-trash text-danger"></i></a></td>
                           
          
                            </tr>
                            </tbody>
                        </table>
                </div>
            </div>
            <Bootstrap4Pagination :data="tvs" @pagination-change-page="getTvs"></Bootstrap4Pagination>
       
           
        </div>
    </div>

       <!-- Modal -->
       <div class="modal fade" id="tvModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="bookModalLabel" v-show="!deleteMode && !claimMode"> {{!editMode ? 'In-room TV': 'Edit' }} </h5>
                    <h5 class="modal-title" id="bookModalLabel" v-show="deleteMode && !confirmMode && !unconfirmMode" > Delete Booking </h5>
                    <h5 class="modal-title" id="bookModalLabel" v-show="deleteMode && confirmMode && !unconfirmMode" > Confirm Booking </h5>
                    <h5 class="modal-title" id="bookModalLabel" v-show="deleteMode && !confirmMode && unconfirmMode" > It's not available </h5>
      
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !claimMode">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Room No.</label>
                                <input type="hidden" v-model="tvData.id">
                                <input type="text" class="form-control" v-model="tvData.room_no">
                                <small class="text-danger" v-if="errors.room_no"> {{ errors.room_no[0] }} </small><br>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Brand</label>
                                <select v-model="tvData.brand" class="form-control">
                                <option value="Panasonic">Panasonic</option>
                                <option value="Samsung">Samsung</option>
                                <option value="LG">LG</option>    
                                <option value="Sharp">Sharp</option>  
                                <option value="Sony">Sony</option>                             
                                </select>
                                <small class="text-danger" v-if="errors.brand"> {{ errors.brand[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Size</label>
                                <select v-model="tvData.size" class="form-control">
                                <option value="32 inches">32 inches</option>
                                <option value="40-42 inches">40-42 inches</option>
                                <option value="50-52 inches">50-52 inches</option>                               
                                </select>
                                <small class="text-danger" v-if="errors.size"> {{ errors.size[0] }} </small><br>
                            </div>
                        </div>
                    </div>    
                    <div class="row" v-show="!deleteMode && !claimMode">
                        <div class="col-md-4">
                            <div class="form-group">
                                    <label for="title" >TunerType</label>
                                    <select v-model="tvData.tuner" class="form-control">
                                    <option value="Built-in">Built-in</option>
                                    <option value="STB">STB</option>                                                      
                                    </select>
                                    <small class="text-danger" v-if="errors.tuner"> {{ errors.tuner[0] }} </small><br>
                                </div>
                            </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Model</label>
                                <select v-model="tvData.model" class="form-control">
                                <option value="non-smart">non-smart</option>
                                <option value="smart">smart</option>
                                                 
                                </select>
                                <small class="text-danger" v-if="errors.model"> {{ errors.model[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Setup-Type</label>
                                <select v-model="tvData.type" class="form-control">
                                <option value="Wall-mount">Wall-mount</option>
                                <option value="Table">Table</option>
                                               
                                </select>
                                <small class="text-danger" v-if="errors.type"> {{ errors.type[0] }} </small><br>
                            </div>
                        </div>
                    </div>

                    <div class="row" v-show="!deleteMode && !claimMode">
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="color" >Note</label>
                                <input type="text" class="form-control" v-model="tvData.note">
                                <small class="text-danger" v-if="errors.note"> {{ errors.note[0] }} </small><br>

                            </div>
                        </div>
                    
                   
                    </div>
                  
                 
                   
                   
                    


               


                        <h4 class="text-center" v-show="deleteMode && !confirmMode && !unconfirmMode">Are you sure want to delete!</h4>
                        <h4 class="text-center" v-show="deleteMode && confirmMode && !unconfirmMode">Are you sure want to change status as Confirm</h4>
                        <h4 class="text-center" v-show="deleteMode && !confirmMode && unconfirmMode">Are you sure want to change status as not available</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !claimMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeTv(): updateTv()" >{{!editMode ? 'Save': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode && !confirmMode && !unconfirmMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteBook" >Delete</button>
                </div>
                <div class="modal-footer" v-show="deleteMode && confirmMode && !unconfirmMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" @click="confirmedBook" >Yes!!!</button>
                </div>
                <div class="modal-footer" v-show="deleteMode && !confirmMode && unconfirmMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="unconfirmedBook" >Yes!!!</button>
                </div>
                <div class="modal-footer" v-show="claimMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" @click="claim" >Claim Item</button>
                </div>
               
            </div>
        </div>
           
    </div>
    <div class="modal fade" id="claimModel" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="bookModalLabel"> Claim Items</h5>
                
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    

                    <div class="row" v-show="claimMode">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="color" >Claimed Date</label>
                                <input type="date" class="form-control" v-model="itemData.claimed_date">
                                <input type="hidden" v-model="itemData.ids">
                                <small class="text-danger" v-if="errors.claimed_date"> {{ errors.claimed_date[0] }} </small><br>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="color" >Claimed By</label>
                                <input type="text" class="form-control" v-model="itemData.claimed_by">
                                <small class="text-danger" v-if="errors.claimed_by"> {{ errors.claimed_by[0] }} </small><br>

                            </div>
                        </div>
                    
                   
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reg_no" >Remark</label>
                                <input type="text" class="form-control" v-model="itemData.remark">

                            </div>
                        </div>
                    </div>
                    
                    <div class="row" v-show="claimMode">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color" >Attachment</label>
                                <input type="file" class="form-control" @change="selectedAttachment" >               
                               

                                
                            </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group image">
                                    <img :src="'/'+itemData.oldattachment" v-if="itemData.oldattachment && !itemData.attachment"/>
                                    <img :src="itemData.attachment" v-if="itemData.attachment"/>
                                </div>
                            
                        </div>
                     
                    </div>
                   
                

                </div>
             
                <div class="modal-footer" v-show="claimMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" @click="claimItems" >Claim Items</button>
                </div>
               
            </div>
        </div>
           
    </div>
             <!-- Modal -->
             <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Report by Date range</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                <label for="title" >Report From</label>
                <input type="date" class="form-control" v-model="report.from_date">
                <label for="title" >To</label>
                <input type="date" class="form-control" v-model="report.to_date">



               
            </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="reportBooking()">Generate</button>
                </div>
                </div>
            </div>
            </div>
    

</template>

<script>
    import Swal from 'sweetalert2';
    import moment from 'moment';
    import { useAuthUserStore } from '../../stores/AuthUserStore';
    import { Bootstrap4Pagination } from 'laravel-vue-pagination';
    import { useSettingStore } from '../../stores/SettingStore';
   // Vue.component('pagination', require('laravel-vue-pagination'));

    export default {
    setup: () => ({
        title: 'In-room Televisions'
    }),
    data() {
        return {
            foMode: false,
            editMode: false,
            deleteMode: false,
            claimMode:false,
            confirmMode: false,
            unconfirmMode: false,
            keyword: null,
            pages: null,
            fullWidthImageIndex: null,
            bookData: {
                destination: '',
                duation: '',
                date: '',
                time: '',
                confirm: '',

            },
            itemData: {
                

            },
            tvData: {
                

            },

            payment: {},
            selectedItems: [],
            selectAll: false,

            items: {},
            tvs: {},
            cancels: {},
            errors: {},
            current_user: {},
            drivers:{},
            assign:{},
            packages:{},
            authUserStore: {},
            settingStore: {},
        }
    },
    watch: {
        keyword(after, before) {
            this.getTvs();
        }
    },
    mounted(){
        this.getItems()
        this.getTvs()
        
    },
    created(){
        this.settingStore = useSettingStore();
       // console.log(this.settingStore);
        this.authUserStore = useAuthUserStore();
     
    },
    methods: {
        generatePdf(){
              
                axios({
                method:'post',
                url:'/api/reportItems',
                responseType:'arraybuffer',
                data: this.selectedItems
                })
                .then(function(response) {
                    let blob = new Blob([response.data], { type:   'application/pdf' } );
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'itemtReport.pdf';
                    link.click();
                   // $('#reportModal').modal('hide')
                });
            },
        format_date(value){
         if (value) {
           return moment(String(value)).format('DD-MM-YYYY')
          }
        },
        toggleSelection(){
            if(this.items.data.length==this.selectedItems.length){
                this.selectAll=true
            }else{
                this.selectAll=false
            }
        },
        selectallitem(){

            //  console.log(this.events[i]['id'])
            if(this.selectAll==true){
                this.selectedItems=[]
                for(var i=0; i < this.items.data.length; i++){
                //   console.log(this.events[i]['id'])
                    this.selectedItems.push(this.items.data[i]['id'])
                }
                }else{
                this.selectedItems=[]
            }

            },
        DeleteAllItem(){
        if(this.selectedItems.length!==0){
            if(window.confirm('Delete this records')){
                 var fd = new FormData()
                fd.append('ids',this.selectedItems)
                axios({
                    url: '/api/bulkDelete',
                    method: 'post',
                    data: fd
                })
                    .then(res=>{
                        console.log(res)
                    })
                    .catch(err=>{
                        console.log(err)
                    })
            }
        }else{
            alert('empty')
        }
        },
        UpdateAllItem(){
        if(this.selectedItems.length!==0){
           
                //  var fd = new FormData()
                // fd.append('ids',this.selectedItems)


                if(this.authUserStore.user.department_id==3){
            this.claimMode = true
            this.editMode = false
            this.deleteMode= false
            this.itemData= {
                ids : this.selectedItems,
                claimed_date: '',
                claimed_by: '',
                oldattachment: '',
                finder: '',
                oldphoto : '',
                remark : '',


            }

            
                $('#claimModel').modal('show')
            }else{
                Swal.fire(
                                'Sorry',
                                'You are not authorized to claim this item!',
                                'error'
                            )
            }
           
        }else{
            alert('empty')
        }
        },

        getItems(page=1){

            axios.get('/api/getItems?page=' + page,{ params: { keyword: this.keyword } }).then(response=>{
                //console.log(response.data)
                this.items = response.data
                this.pages = page
            }).catch(errors=>{
                console.log(errors)
            });
             },
        getTvs(page=1){

                axios.get('/api/getTvs?page=' + page,{ params: { keyword: this.keyword } }).then(response=>{                  
                    this.tvs = response.data
                    this.pages = page
                }).catch(errors=>{
                    console.log(errors)
                });
                },
        getCancels(){

            axios.get('/api/getCancels',{ params: { keyword: this.keyword } }).then(response=>{
                this.cancels = response.data
            }).catch(errors=>{
                console.log(errors)
            });



            },

        removeBook(book){
            this.deleteMode = true
            this.confirmMode = false
            this.confirmMode = false
            this.bookData.id = book.id
            if(this.current_user.id==book.user_id){
                $('#bookModal').modal('show')
            }

        },

        deleteBook(){
            axios.post(window.url + 'api/deleteBook/' + this.bookData.id).then(response => {
                this.getBooks()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#bookModal').modal('hide')
            });
        },


        
        editTv(item){
            if(this.authUserStore.user.id==item.user_id){
            this.editMode = true
            this.deleteMode= false
            this.claimMode = false
            this.tvData= {
                id : item.id,
                room_no: item.room_no,
                brand: item.brand,
                size: item.size,
                model: item.model,
                type: item.type,
                note : item.note,
                tuner : item.tuner,


            }

            
                $('#tvModal').modal('show')
            }else{
                Swal.fire(
                                'Sorry',
                                'You are not authorized to edit this record!',
                                'error'
                            )
            }
           
            
        },

        claimItem(item){
            if(this.authUserStore.user.department_id==3){
            this.claimMode = true
            this.editMode = false
            this.deleteMode= false
            this.itemData= {
                id : item.id,
                claimed_date: item.claimed_date,
                claimed_by: item.claimed_by,
                oldattachment: item.attachment,
                finder: item.finder,
                oldphoto : item.photo,
                remark : item.remark,


            }

            
                $('#itemModal').modal('show')
            }else{
                Swal.fire(
                                'Sorry',
                                'You are not authorized to claim this item!',
                                'error'
                            )
            }
            
        },
         claimItems(){



            axios.post('/api/claimItems', this.itemData).then(response=>{
                $('#claimModal').modal('hide');
                this.getItems()
                }).catch(error =>this.errors = error.response.data.errors)

                $('#claimModal').modal('hide')
            },
       
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.itemData.photo = reader.result;
            }
            reader.readAsDataURL(file);
        },
        selectedAttachment(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.itemData.attachment = reader.result;
            }
            reader.readAsDataURL(file);
        },
        
        print(pay){
            this.itemData.id = pay.id;
         
            axios({
                method:'post',
                url:'/api/receiptForm/' + this.itemData.id,
                responseType:'arraybuffer',

            })
                .then(function(response) {
                    let blob = new Blob([response.data], { type:   'application/pdf' } );
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download ="receipt.pdf";
                    link.click();
                });

        },
        cancelPayment(item) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Cancelling Payment for "${item.name}"`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`/api/cancelPayment/${item.id}`)
                        .then((response) => {
                            Swal.fire(
                                'Cancel!',
                                'Your payment has been canceled.',
                                'success'
                            )
                            this.getItems()
                            this.getCancels()

                        });
                }
            })
        },
   
      
        restorePayment(item) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Restore Payment for "${item.name}"`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Restore it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`/api/restorePayment/${item.id}`)
                        .then((response) => {
                            Swal.fire(
                                'Restore!',
                                'Your payment has been restored.',
                                'success'
                            )
                            this.getItems()
                            this.getCancels()

                        });
                }
            })
        },
        updateBook(){

                axios.post(window.url + 'api/updateBook/' + this.bookData.id, this.bookData).then(response => {
                    this.getBooks()
                }).catch(errors => {
                    console.log(errors)
                }).finally(() => {
                    $('#bookModal').modal('hide')
                });

        },
        renewCar(){

                axios.post(window.url + 'api/renewCar/' + this.bookData.id, this.bookData).then(response => {
                    this.getBooks()
                }).catch(errors => {
                    console.log(errors)
                }).finally(() => {
                    $('#bookModal').modal('hide')
                });

        },
        addItem(){
            this.editMode = false
            this.deleteMode = false
            this.claimMode = false
            this.itemData= {
                date: '',
                sr_no: '',
                item_name: '',
                founder: '',
                location: '',
                safe_location: '',
                remark: '',
                photo: ''

            }

            $('#itemModal').modal('show')
        },
        addTv(){
            this.editMode = false
            this.deleteMode = false
            this.claimMode = false
            this.tvData= {


            }

            $('#tvModal').modal('show')
        },
        report(){
            this.report={
                from_date:'',
                to_date:'',
            }
           

            $('#reportModal').modal('show')
        },
        reportBooking(){
      
                   axios({
                method:'post',
                url:'/api/reportBooking',
                responseType:'arraybuffer',
                data: this.report
                })
                .then(function(response) {
                    let blob = new Blob([response.data], { type:   'application/pdf' } );
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'Report.pdf';
                    link.click();
                    $('#reportModal').modal('hide')
                });
     },
    
            onImageClick: function(item) {
               
            this.$viewerApi({
                items : item,
            })
            

        },
        updateTv(){



                axios.post('/api/updateTv', this.tvData).then(response=>{
                    $('#tvModal').modal('hide');
            this.getTvs()
            }).catch(error =>this.errors = error.response.data.errors)



            },

        claim(){



            axios.post('/api/claimItem', this.itemData).then(response=>{
                $('#itemModal').modal('hide');
                this.getItems()
                }).catch(error =>this.errors = error.response.data.errors)



            },
        deleteItem(id,item) {
        if(this.authUserStore.user.id==item.user_id){            
        Swal.fire({
        title: 'Are you sure?',
        text: "You are going to delete this item!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, pls delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/deleteItem/${id}`)
                        .then((response) => {                    
                            Swal.fire(
                                'Done!',
                                'Your item has been deleted.',
                                'success'
                            )
                           
                            this.getItems()
                        });
                }
            })
        }else{
            Swal.fire(
                                'Sorry',
                                'You are not authorized to delete this item!',
                                'error'
                            )
        }
        },
        deleteTv(id,item) {
        if(this.authUserStore.user.id==item.user_id){            
        Swal.fire({
        title: 'Are you sure?',
        text: "You are going to delete this record!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, pls delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/deleteTv/${id}`)
                        .then((response) => {                    
                            Swal.fire(
                                'Done!',
                                'Your record has been deleted.',
                                'success'
                            )
                           
                            this.getTvs()
                        });
                }
            })
        }else{
            Swal.fire(
                                'Sorry',
                                'You are not authorized to delete this record!',
                                'error'
                            )
        }
        },
        


        storeItem(){



                axios.post('/api/storeItem', this.itemData).then(response=>{
                    $('#itemModal').modal('hide');
                this.getItems()
                }).catch(error =>this.errors = error.response.data.errors)



        },
        storeTv(){



            axios.post('/api/storeTv', this.tvData).then(response=>{
                $('#tvModal').modal('hide');
            this.getTvs()
            }).catch(error =>this.errors = error.response.data.errors)



            }
    }

}
</script>

<style>
.hide {
    display: none;
    font-size: 11px;
}

.myDIV:hover + .hide {
    display: block;
    color: blue;
}
.owner {
    font-weight: bold;
}
.tooltip-text {
  visibility: hidden;
  position: absolute;
  z-index: 1;
  
  color: white;
  font-size: 12px;
  background-color: #192733;
  border-radius: 10px;
  padding: 10px 15px 10px 15px;
}

.hover-text:hover .tooltip-text {
   
  visibility: visible;
}
.value {
    text-align: right;
}
#top {
  top: -40px;
  left: -50%;
}
.button1 {
    width: 95px;
    text-align: right;
    
}

#bottom {
  top: 25px;
  left: -50%;
}

#left {
  top: -8px;
  right: 120%;
}

#right {
  top: -8px;
  left: 120%;
}

.hover-text {
  position: relative;
  display: inline-block;
  
 
  text-align: center;
}
.cancel {
    color: red;
}
.cancel2 {
    color: lightgray;
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

</style>
