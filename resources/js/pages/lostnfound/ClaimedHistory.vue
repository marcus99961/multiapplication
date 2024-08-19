<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ title }}</h1>
                </div>
                <div class="col-sm-6">
                    <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <button @click="addItem" type="button" class="mb-2 btn btn-primary">
                        <i class="fa fa-plus-circle mr-1"></i>
                        Add Found Item
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
        <div class="container-fluid">
           
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th> 
                                <th>Location</th>                               
                                <th>ItemName</th>                              
                                <th>ClaimedBy</th>
                                <th>ClaimedDate</th>
                                <th>Remark</th>
                                <th>Attachment</th>
                         
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item,i) in items " :key="i" class="bg-transparent images" v-viewer>
                                <td>{{i + 1}}</td>

                                <td><div @dblclick="editItem(item)">{{item.date}}</div></td>
                                <td v-if="item.guest_name">{{item.location}}/{{ item.guest_name }}</td>   
                                <td v-else>{{item.location}}</td> 
                                <td>
                                    <div v-if="item.item_name.length<15" @dblclick="claimItem(item)">{{ item.item_name }}</div>
                                    <div class="myDIV" @dblclick="claimItem(item)" v-else >{{ item.item_name.substring(0,15)+".." }}</div>
                                    <span class="hide">{{ item.item_name }}</span>

                                </td>
                                <td>{{item.claimed_by}}</td>
                                <td>{{item.claimed_date}}</td>
                                <td>{{item.remark}}</td>
                                <td v-if="item.attachment"><div class="image"><img :src="'/'+ item.attachment" :class="getImageClass(i)" @click="onImageClick(item)"/></div></td>

                                <!-- <td>{{item.photo}}</td> -->
                                <!-- <td><div v-for="pay in payment"><div  v-if="item.id==pay.deposit_id" class="hover-text value"><button @click="print(pay)" class="btn btn-sm">{{ pay.amount }}</button><span class="tooltip-text" id="top">{{ pay.name }}</span></div></div></td> -->
                                <!-- <td><div v-for="pay in payment" style="text-align: right;"><div  v-if="item.id==pay.deposit_id" class="py-1 hover-text" style="text-align: right;">{{ format_date(pay.payment_date) }} {{ pay.currency }}<span class="tooltip-text" id="top">{{ pay.name }}</span> <button @click="print(pay)" class="btn btn-primary btn-sm button1">{{ pay.amount }}</button></div></div></td> -->

          
                            </tr>
                            </tbody>
                        </table>
                </div>
            </div>
            <!-- <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-hover">
                            <thead>
                            <tr class="cancel">
                                <th>#</th>
                                <th>ItemName (Cancel)</th>
                                <th>Date</th>                              
                                <th>Time</th>
                                <th style="text-align: right;">Amount</th>
                         
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in cancels " :key="index" class="bg-transparent cancel2">
                                <td>{{index + 1}}</td>

                                <td><div @dblclick="restorePayment(item)">{{item.name}}</div></td>
                                <td>{{item.item_date}}</td>                               
                                <td>{{item.item_time}}</td>
                                <td><div v-for="pay in payment" style="text-align: right;"><div  v-if="item.id==pay.deposit_id" class="py-1 hover-text" style="text-align: right;">{{ format_date(pay.payment_date) }} {{ pay.currency }}<span class="tooltip-text" id="top">{{ pay.name }}</span> <button @click="print(pay)" class="btn btn-primary btn-sm button1">{{ pay.amount }}</button></div></div></td>

          
                            </tr>
                            </tbody>
                        </table>
                </div>
            </div> -->
           
        </div>
    </div>

       <!-- Modal -->
       <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="bookModalLabel" v-show="!deleteMode && !claimMode"> {{!editMode ? 'Lost & Found Entry': 'Edit' }} </h5>
                    <h5 class="modal-title" id="bookModalLabel" v-show="deleteMode && !confirmMode && !unconfirmMode" > Delete Booking </h5>
                    <h5 class="modal-title" id="bookModalLabel" v-show="deleteMode && confirmMode && !unconfirmMode" > Confirm Booking </h5>
                    <h5 class="modal-title" id="bookModalLabel" v-show="deleteMode && !confirmMode && unconfirmMode" > It's not available </h5>
                  
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !claimMode">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title" >Date.</label>
                                <input type="hidden" v-model="itemData.id">
                                <input type="date" class="form-control" v-model="itemData.date">

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date" >Location</label>
                                <input type="text" class="form-control" v-model="itemData.location">

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="color" >Item Name</label>
                                <input type="text" class="form-control" v-model="itemData.item_name">

                            </div>
                        </div>
                    </div>

                    <div class="row" v-show="!deleteMode && !claimMode">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="color" >Finder Name/Department</label>
                                <input type="text" class="form-control" v-model="itemData.finder">

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="color" >Safebox Location</label>
                                <input type="text" class="form-control" v-model="itemData.safe_location">

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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="color" >Claimed Date</label>
                                <input type="date" class="form-control" v-model="itemData.claimed_date">

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="color" >Claimed By</label>
                                <input type="text" class="form-control" v-model="itemData.claimed_by">

                            </div>
                        </div>
                    
                   
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reg_no" >Remark</label>
                                <input type="text" class="form-control" v-model="itemData.remark">

                            </div>
                        </div>
                    </div>
                    <div class="row" v-show="!deleteMode && !claimMode">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color" >Attachment</label>
                                <input type="file" class="form-control" @change="selectedImage" >               
                               

                                
                            </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group image">
                                    <img :src="'/'+itemData.oldphoto" v-if="itemData.oldphoto && !itemData.photo"/>
                                    <img :src="itemData.photo" v-if="itemData.photo"/>
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
                   
                    <div class="row" v-show="deleteMode && confirmMode && !unconfirmMode">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="title" >Assign Driver</label>
                                <select class="form-control"  v-model="assign.driver_id">

                                    <option :value="driver.id"  v-for="driver in drivers">{{ driver.name }}</option>

                                </select>
                                <small class="text-danger" v-if="errors.driver_id"> {{ errors.driver_id[0] }} </small><br>
                            </div>
                        </div>

                    </div>




                        <h4 class="text-center" v-show="deleteMode && !confirmMode && !unconfirmMode">Are you sure want to delete!</h4>
                        <h4 class="text-center" v-show="deleteMode && confirmMode && !unconfirmMode">Are you sure want to change status as Confirm</h4>
                        <h4 class="text-center" v-show="deleteMode && !confirmMode && unconfirmMode">Are you sure want to change status as not available</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !claimMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeItem(): updateItem()" >{{!editMode ? 'Save': 'Save Changes' }}</button>
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
    export default {
    setup: () => ({
        title: 'Lost & Found History'
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

            payment: {},
            // report:{
            //     from_date: '',
            //     to_date: '',
            // },

            items: {},
            cancels: {},
            errors: {},
            current_user: {},
            drivers:{},
            assign:{},
            packages:{},
        }
    },
    watch: {
        keyword(after, before) {
            this.getItems();
        }
    },
    mounted(){
        this.getItems()
        this.getCancels()
    },
    created(){
       
        axios.get( '/api/getPayment/')
            .then(({data}) => (this.payment = data))
        axios.get( '/api/getPackages/')
            .then(({data}) => (this.packages = data))
    },
    methods: {
        format_date(value){
         if (value) {
           return moment(String(value)).format('DD-MM-YYYY')
          }
        },

        getItems(){

            axios.get('/api/getClaimedItems',{ params: { keyword: this.keyword } }).then(response=>{
                this.items = response.data
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
        confirmBook(book){
            this.deleteMode = true
            this.confirmMode = true
            this.unconfirmMode = false
            this.bookData.id = book.id
            $('#bookModal').modal('show')
        },
        unconfirmBook(book){
            this.deleteMode = true
            this.confirmMode = false
            this.unconfirmMode = true
            this.bookData.id = book.id
            $('#bookModal').modal('show')
        },
        confirmedBook(){
            axios.post(window.url + 'api/confirmBook/' + this.bookData.id,this.assign).then(() => {
                $('#bookModal').modal('hide');
                this.getBooks()
            }).catch(error =>this.errors = error.response.data.errors)

        },
        unconfirmedBook(){
            axios.post(window.url + 'api/unconfirmBook/' + this.bookData.id).then(response => {
                this.getBooks()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#bookModal').modal('hide')
            });
        },

       
        editItem(item){
            this.editMode = true
            this.deleteMode= false
            this.claimMode = false
            this.itemData= {
                id : item.id,
                date: item.date,
                location: item.location,
                item_name: item.item_name,
                finder: item.finder,
                safe_location: item.safe_location,
                oldphoto : item.photo,
                remark : item.remark,


            }

            
                $('#itemModal').modal('show')
            
        },

        claimItem(item){
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
            
        },
        depositPayment(item){
            this.editMode = true
            this.deleteMode= false
            this.itemData= {
                id : item.id,
                name :item.name,
                date : item.date,
                time: item.time,
               
            }
            this.paymentErrors= {
                name: false,
                phone: false,

            }
            $('#paymentModal').modal('show')
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
        updateDeposit(){

            axios.post('api/updateDeposit/' + this.itemData.id, this.itemData).then(response => {
                this.getItems()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#paymentModal').modal('hide')
            });


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
     getImageClass: function(i) {
        return {
            'fullWidthImage': this.fullWidthImageIndex === i
        };
    },
    onImageClick: function(item) {
        this.$viewerApi({
          items : item,
        })
      
    
    },
        updateItem(){



                axios.post('/api/updateItem', this.itemData).then(response=>{
                    this.getItems()
                    }).catch(errors=>{
                        console.log(errors)
                    }).finally(()=>{
                        $('#itemModal').modal('hide')
                });



                },

        claim(){



            axios.post('/api/claimItem', this.itemData).then(response=>{
                this.getItems()
                }).catch(errors=>{
                    console.log(errors)
                }).finally(()=>{
                    $('#itemModal').modal('hide')
            });



            },

        storeItem(){



                axios.post('/api/storeItem', this.itemData).then(response=>{
                    this.getItems()
                    }).catch(errors=>{
                        console.log(errors)
                    }).finally(()=>{
                        $('#itemModal').modal('hide')
                });



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

.image img {
  height: 50px;
  width: 50px;
}

</style>
