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
                            <input class="form-control-sm rounded" type="text" v-model="keyword" placeholder="search invoice..">
                        </div> -->
                        <div class="col-md-6">
                            <button @click="createInvoice"  class="btn-info btn-sm float-right"><i class="fa fa-plus-circle mr-1"></i>New Invoice</button>
                            <button @click="undoPost"  class="btn-info btn-sm float-right"><i class="fa fa-plus-circle mr-1"></i>Undo Post</button>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="shadow p-3 mb-5 bg-body rounded">
                        <!-- <h3>{{ current_invoice.name }}</h3> -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>    
                                             
                                <th>Invoice</th>
                                <th>Supplier</th> 
                                <th>Location</th>                         
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(invoice, index) in invoices " :key="index" class="bg-transparent">
                                <td>{{index + 1}}</td>
                                <!-- <td>{{invoice.name}}</td> -->
                               <td> <router-link :to="`/admin/receives/${ invoice.id }`" class="bg-transparent">{{ invoice.name }}</router-link></td>

                                <td>{{invoice.supplier.name}}</td>
                                <td v-if="invoice.location_id">{{ invoice.location.name }}</td>
                                <td v-else>Not Specified</td>
                          
                            
                               


                                <td>
                                    <button @click="editInvoice(invoice)" class="ml-1" ><i class="fa fa-edit text-success mx-1"></i></button>
                                    <button @click="removeInvoice(invoice)" class="ml-1"><i class="fa fa-trash text-danger mx-1"></i></button>                               


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
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="invoiceModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Add New Invoice': 'Update Invoice' }} </h5>
                    <h5 class="modal-title" id="invoiceModalLabel" v-show="deleteMode" > Delete Invoice </h5>
                    <h5 class="modal-title" id="invoiceModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">                     
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Invoice No.</label>
                                <input type="text" class="form-control" v-model="invoiceData.name" >
                                <small class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" >Supplier</label>
                                <multiselect v-model="invoiceData.supplier_id" :options='suppliers' :multiple="false" :show-labels="false"
                                :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.supplier_id"> {{ errors.supplier_id[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Currency</label>
                               <select name="" v-model="invoiceData.currency" class="form-control">
                                <option value="MMK">MMK</option>
                                <option value="USD">USD</option>
                               </select>
                                <small class="text-danger" v-if="errors.currency"> {{ errors.currency[0] }} </small><br>
                            </div>
                        </div>
                    
                    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" >Location</label>
                                <multiselect v-model="invoiceData.location_id" :options='locations' :multiple="false" :show-labels="false"
                                :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.location_id"> {{ errors.location_id[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" >Received</label>
                                <input type="date" class="form-control" v-model="invoiceData.received_date" >
                                <small class="text-danger" v-if="errors.received_date"> {{ errors.received_date[0] }} </small><br>
                            </div>
                        </div>
                       
                    

                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeInvoice(): updateInvoice()" >{{!editMode ? 'Create Invoice': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteInvoice" >Delete</button>
                </div>
                <div class="modal-footer" v-show="renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="renewInvoice" >Renew</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="undoModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="invoiceModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Undo Posting': 'Update Invoice' }} </h5>
                    <h5 class="modal-title" id="invoiceModalLabel" v-show="deleteMode" > Delete Invoice </h5>
                    <h5 class="modal-title" id="invoiceModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                     
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="title" >Invoice No.</label>
                                <multiselect v-model="postedData.invoice_no" :options='posted' :multiple="false" :show-labels="false"
                                :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.invoice_no"> {{ errors.invoice_no[0] }} </small><br>
                            </div>
                        </div>
                       
                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? undoPosting(): updateInvoice()" >{{!editMode ? 'Undo Posting': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteInvoice" >Delete</button>
                </div>
                <div class="modal-footer" v-show="renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="renewInvoice" >Renew</button>
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
        title: 'All Invoices'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            keyword: null,
        

            invoiceData: {
                received_date: '',
                name: '',
                supplier_id: '',
                location_id: '',
          

            },
        
            invoices: {},
            imbalance: {},
            current_user: {},
            suppliers: [],
            authUserStore: {},
            settingStore: {},
            errors: {},
            posted: [],
            postedData: {},
            units: [],
            locations: [],
            selectedlocations: {},
            selectedsuppliers: {},
           
        }
    },
    watch: {
        keyword(after, before) {
            this.getInvoices();
        }
    },
    mounted(){
        this.getInvoices()
        axios.get('/api/getSelectedsupplier').then(response=>{
                this.suppliers = response.data
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
            axios.get('/api/getPosted').then(response=>{
                this.posted = response.data
            })
    },
    created(){
        console.log(window.user)
        this.current_user = window.user
        this.settingStore = useSettingStore();
    },
    methods: {
        getInvoices(){

            axios.get('/api/getInvoices').then(response=>{
                this.invoices = response.data
            }).catch(errors=>{
                console.log(errors)
            });
        },

        removeInvoice(invoice){
            this.deleteMode = true
            this.invoiceData.id = invoice.id
            $('#invoiceModal').modal('show')
        },
        deleteInvoice(){
            axios.delete('/api/deleteInvoice/' + this.invoiceData.id).then(response => {
                this.getInvoices()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#invoiceModal').modal('hide')
            });
        },
        undoPost(){
            this.editMode = false
            this.deleteMode = false
            $('#undoModal').modal('show')
        },
        editInvoice(invoice){
            this.editMode = true
            this.deleteMode= false
            this.current_location = ''
            this.current_supplier = ''
            let arr = [];
            this.selectedlocations.forEach((value, index) => {
                arr.push(value);
                if(value.id==invoice.location_id){
                    this.current_location = value.name
                }


            })
            let arr2 = [];
            this.selectedsuppliers.forEach((value, index) => {
                arr2.push(value);
                if(value.id==invoice.supplier_id){
                    this.current_supplier = value.name
                }


            })
            this.invoiceData= {
                id: invoice.id,
                name: invoice.name,
                supplier_id: this.current_supplier,
                location_id: this.current_location,
                received_date: invoice.received_date,
            
            }
        
            $('#invoiceModal').modal('show')
        },
        updateInvoice(){



            axios.post('/api/updateInvoice/' + this.invoiceData.id, this.invoiceData).then(response => {
                $('#invoiceModal').modal('hide');
                this.getInvoices()
                }).catch(error =>this.errors = error.response.data.errors)



        },
     
        createInvoice(){
            this.editMode = false
            this.deleteMode = false
            this.invoiceData= {
                id: '',
                name: '',
                supplier_id: '',
                location_id: '',
                received_date: '',
             

            }
            this.invoiceErrors= {
                name: false,

            }
            $('#invoiceModal').modal('show')
        },
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.invoiceData.img = reader.result;
            }
            reader.readAsDataURL(file);
        },
        storeInvoice(){
         
                axios.post('/api/storeInvoice', this.invoiceData).then(response=>{
                    $('#invoiceModal').modal('hide');
                this.getInvoices()
                }).catch(error =>this.errors = error.response.data.errors)

        

            
        },
        undoPosting(){
            axios.post('/api/undoPosting', this.postedData).then(response=>{
                this.imbalance = response.data
                    $('#undoModal').modal('hide');
                    if(this.imbalance){
                        Swal.fire({
                            title: "Item Qty is imbalance!",
                            text:  `You need to fix qty or location for -"${this.imbalance}"`,
                            icon: "error"
                            });
                    }else{
                        Swal.fire({
                            title: "Posted items are reverted!",
                            text: "Congratulations!",
                            icon: "success"
                            });
                    }
                this.getInvoices()
                }).catch(error =>this.errors = error.response.data.errors)
        }
    }

}
</script>
