<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2">
                            <h5 class="float-start text-success">Invoice No. {{ invoice_no.name }}</h5>
                          
                          
                        </div>
                        <div class="col-md-6 form-check form-switch">
                              
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" @click="expired">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Expired</label>
                            </div>
                        <!-- <div class="col-md-2 float-center">
                            <input class="form-control-sm rounded" type="text" v-model="keyword" placeholder="search item..">
                            
                        </div> -->
                        <div class="col-md-4">
                            <button @click="posting"  class="btn-info btn-sm float-right"><i class="fa fa-plus-circle mr-1"></i>Posting</button>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Item Name</label>
                                <multiselect v-model="itemData.item_id" :options="itemsoptions" :multiple="false" :show-labels="false"
                                        :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.item_id"> {{ errors.item_id[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-2" v-show="locationMode">
                            <div class="form-group">
                                <label for="title" >Location</label>
                                <multiselect v-model="itemData.location_id" :options='selectedlocations' :multiple="false" :show-labels="false"
                                :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.location_id"> {{ errors.location_id[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-2" v-show="expiredMode">
                           
                            <div class="form-group">
                                <label for="title" >Expired</label>
                                <input type="date" class="form-control" v-model="itemData.expired_date" >
                                <small class="text-danger" v-if="errors.expired_date"> {{ errors.expired_date[0] }} </small><br>
                            </div>
                           
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="title" >Qty</label>
                                <input type="text" class="form-control" v-model="itemData.qty" >
                                <small class="text-danger" v-if="errors.qty"> {{ errors.qty[0] }} </small><br>
                            </div>
                        </div>
                       
                      
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="title" >Price</label>
                                <input type="text" class="form-control" v-model="itemData.price" @keyup.enter="storeReceive">
                                <small class="text-danger" v-if="errors.price"> {{ errors.price[0] }} </small><br>
                            </div>
                        </div>
<!--                       
                      <div class="col-md-1">
                        <div class="form-group">
                          
                            <button @click=storeReceive() class="btn-success">Save</button>
                        </div>
                      </div>
                      -->
                        
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
                                <th>Currency</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Total </th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(receive, i) in receives " :key="i" class="bg-transparent">
                                <td>{{ i+1 }}</td>
                                <td>{{ receive.item.item_code }}</td>
                                <td>{{ receive.item.name }} x {{ receive.qty }}</td>
                                <td>{{ receive.currency }}</td>
                                <td class="text-right" v-if="receive.currency=='USD'">{{ receive.price_usd }}</td>                              
                                <td class="text-right" v-else>{{ receive.price_mmk }}</td>
                                <td class="text-right" v-if="receive.currency=='USD'">{{ receive.price_usd * receive.qty }}</td>
                                <td class="text-right" v-else>{{ receive.price_mmk * receive.qty }}</td>
                               
                               


                                <td>
                                    <button @click="editItem(receive)" class="ml-1" ><i class="fa fa-edit text-success mx-1"></i></button>
                                    <button @click="removeItem(receive)" class="ml-1"><i class="fa fa-trash text-danger mx-1"></i></button>                               


                                </td>

                            </tr>
                            <tr>
                                <th colspan="5">Grand Total </th>
                                <th class="text-right" v-if="invoice_no.currency=='USD'">{{ totals.total_usd }}</th>
                                <th class="text-right" v-else>{{ totals.total_mmk }}</th>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Expired</label>
                                <input type="date" class="form-control" v-model="itemData.expired_date" >
                                <small class="text-danger" v-if="errors.expired_date"> {{ errors.expired_date[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="title" >Qty</label>
                                <input type="text" class="form-control" v-model="itemData.qty" >
                                <small class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </small><br>
                            </div>
                        </div>
                      
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Price</label>
                                <input type="text" class="form-control" v-model="itemData.price" >
                                <small class="text-danger" v-if="errors.spec"> {{ errors.spec[0] }} </small><br>
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
            pages: null,
            
            form: { id : ''},
            itemData: {
                item_id: '',                   
                currency: '',
                expired_date: '',
                qty: '',
                price: '',
              
          

            },
            units: [],
            items: {},
            stocks: {},
            itemsoption: {},
            itemsoptions: [],
            categories: [],
            current_user: {},
            invoice_no : {},
            errors: {},
            receives: {},
            totals: {},
            selectedlocations: [],
            authUserStore: {},
            settingStore: {},
            searchStore: {},
            stockStore: {},
        }
    },
    watch: {
        keyword(after, before) {
            this.getItems();
        }
    },
    mounted(){
        this.getReceives()
        this.getTotals()
        this.getUnits()
        this.getCategories()
        this.getSelectedcategory()
        axios.get('/api/getInvoice_no',{ params: { keyword: this.form.id } }).then(response=>{
                this.invoice_no = response.data
            })
        axios.get('/api/getSelecteditems').then(response=>{
                this.itemsoption = response.data
            })
        axios.get('/api/getSelecteditem').then(response=>{
                this.itemsoptions = response.data
            })
        axios.get('/api/getSelectedlocation').then(response=>{
                this.selectedlocations = response.data
            })
            axios.get('/api/stockStore')
            .then((response) => {
                this.stocks = response.data
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
        getReceives(){

                axios.get('/api/getReceives',{ params: {keyword: this.form.id } } ).then(response=>{
                    this.receives = response.data
                }).catch(errors=>{
                    console.log(errors)
                });
                },
        getTotals(){

                axios.get('/api/getTotals',{ params: {keyword: this.form.id } } ).then(response=>{
                    this.totals = response.data
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
            axios.delete('/api/deleteReceive/' + this.itemData.id).then(response => {
                this.getReceives()
                this.getTotals()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#itemModal').modal('hide')
            });
        },
    
        editItem(receive){
            this.editMode = true
            this.deleteMode= false
            this.current_item = ''
            let arr = [];
            this.itemsoption.forEach((value, index) => {
                arr.push(value);
                if(value.id==receive.item_id){
                    this.current_item = value.name
                   
                }


            })
            if(this.invoice_no.currency=='MMK'){
                this.itemData= {
                id : receive.id,
                item_id : this.current_item,
                qty :receive.qty,
                location_id : receive.location_id,
                expired_date: receive.expired_date,
                currency : receive.currency,
                price : receive.price_mmk,
                          
             
            }
            }else{
                this.itemData= {
                id : receive.id,
                item_id : this.current_item,
                qty :receive.qty,
                location_id : receive.location_id,
                expired_date: receive.expired_date,
                currency : receive.currency,
                price : receive.price_usd,
                          
             
            }
            }
           
        
            $('#itemModal').modal('show')
        },
        updateItem(){



            axios.post('/api/updateReceive/' + this.itemData.id, this.itemData).then(response => {
                $('#itemModal').modal('hide');
                this.getReceives()
                this.resetInput()
                this.getTotals()
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
                text: `Posting for Invoice No."${this.invoice_no.name}"`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Post it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`/api/posting/${this.form.id}`)
                        .then((response) => {
                            Swal.fire(
                                'Posted!',
                                'Your Invoice has been posted.',
                                'success'
                            )
                            this.$router.push('/admin/invoices')
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
    
        this.itemData.item_id= '';
        this.itemData.price= '';
        this.itemData.qty= '';
        this.itemData.location_id= '';
        this.itemData.currency= '';
        this.itemData.expired_date= '';
       

     },
        storeReceive(){
         
                axios.post('/api/storeReceive/'+this.form.id, this.itemData).then(response=>{
                this.resetInput()
                this.getReceives()
                this.getTotals()
                
                }).catch(error =>this.errors = error.response.data.errors)

        

            
        }
    }

}
</script>
