<template>
    
    <div class="row justify-content-center">
      <!-- <h1>Our Cars</h1>
      <label for="">Make:</label>
      <select @change="handleChange" v-model="make">
        <option value="All">All</option>
        <option value="Chevrolet">Chevy</option>
        <option value="Porsche">Porsche</option>
        <option value="Audi">Audi</option>
      </select>
      <label for="">Price:</label>
      <select @change="handleChange" v-model="price">
        <option value="any">Any</option>
        <option value="htl">High to Low</option>
        <option value="lth">Low to High</option>
      </select> -->
      <div class="col-md-12">
        <div class="container mt-3">
        <div class="row">
            <div class="col-md-6">
                <h5 class="float-start">{{ title }}</h5>
            </div>
            <div class="col-md-6">
                <button @click="reportSummary"  class="btn-info btn-sm float-center"><i class="fa fa-plus-circle mr-1"></i>Stock Summary</button>
                <button @click="stockTransfer"  class="btn-info btn-sm float-right"><i class="fa fa-plus-circle mr-1"></i>Stock Transfer</button>
            </div>
           
        </div>
        </div>
      </div>
      <div class="cards">
        
        <div class="card" v-for="location in locations">
       
          <h4 class="text-center"><router-link :to="`/admin/stocklists/${ location.id }`" class="bg-transparent">   {{location.name}} <text v-for="qty in locationitems"><span v-if="location.id == qty.location_id">x{{ qty.quantity }}</span> </text> </router-link></h4>
          <!-- <p>${{car.phone}}</p> -->
        </div>
    </div>
  </div>

    <!-- Modal -->
    <div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="supplierModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Transfer': 'Update Supplier' }} </h5>
                    <h5 class="modal-title" id="supplierModalLabel" v-show="deleteMode" > Delete Supplier </h5>
                    <h5 class="modal-title" id="supplierModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                     
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" >Source</label>
                                <multiselect v-model="choselocation" :options='sourcelocations' :multiple="false" :show-labels="false"
                                :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.location_id"> {{ errors.location_id[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" >Destination</label>
                                <multiselect v-model="transferData.destination" :options='destination' :multiple="false" :show-labels="false"
                                :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.location_id"> {{ errors.location_id[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" >Item Name</label>
                                <multiselect v-model="choseitem" :options="itemsoptions" :multiple="false" :show-labels="false"
                                        :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.item_id"> {{ errors.item_id[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Invoice</label>
                                <multiselect v-model="choseinvoice" :options="sourceinvoices" :multiple="false" :show-labels="false"
                                        :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.item_id"> {{ errors.item_id[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Quantity x {{ qtyoptions.total_qty }}</label>
                                <input type="text" class="form-control" v-model="transferData.qty">
                                <span class="text-danger" v-show="qtyerror">Insufficient Qty</span>
                                <small class="text-danger" v-if="errors.phone"> {{ errors.phone[0] }} </small><br>
                            </div>
                        </div>

                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? transferItem(): updateSupplier()" >{{!editMode ? 'Submit': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteSupplier" >Delete</button>
                </div>
                <div class="modal-footer" v-show="renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="renewSupplier" >Renew</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="supplierModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Summary by Date': 'Update Supplier' }} </h5>
                    <h5 class="modal-title" id="supplierModalLabel" v-show="deleteMode" > Delete Supplier </h5>
                    <h5 class="modal-title" id="supplierModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                     
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="title" >Report Date</label>
                                <input type="date" class="form-control" v-model="reportData.date" >
                                <small class="text-danger" v-if="errors.date"> {{ errors.date[0] }} </small><br>
                            </div>
                        </div>
                  

                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? stockSummary(): updateSupplier()" >{{!editMode ? 'Submit': 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteSupplier" >Delete</button>
                </div>
                <div class="modal-footer" v-show="renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="renewSupplier" >Renew</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    setup: () => ({
        title: 'Stocks By Locations'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            keyword: null,
            choselocation: null,
            choseitem: null,
            choseinvoice: null,
            qtyerror: false,

            supplierData: {
                address: '',
                name: '',
                email: '',
                phone: '',
          

            },
            transferData: {
                source: '',
                destination: '',
                item_name: '',
                qty: '',
                
            },
            suppliers: {},           
            current_user: {},
            errors: {},
            locations: {},
            locationitems: {},
            itemsoptions: [],
            sourcelocations: [],
            sourceinvoices: [],
            qtyoptions: {},
            destination: [],
            stocksSummary: {},
            reportData: { date: '',},
        }
    },
    watch: {
        keyword(after, before) {
            this.getSourceitems();
        },
        choselocation : function (value){
            axios.get('/api/getSourceitem',{params: { keyword: this.choselocation }}).then(response=>{
            this.itemsoptions = response.data
            })
           
        },
        choseitem : function (value){
            axios.get('/api/getSourceinvoices',{params: { location: this.choselocation, item: this.choseitem }}).then(response=>{
            this.sourceinvoices = response.data
            })
           
        },

        choseinvoice : function (value){
            axios.get('/api/getSourceitemqty',{params: { item: this.choseitem, location: this.choselocation, invoice: this.choseinvoice }}).then(response=>{
            this.qtyoptions = response.data
            })
           
        },
    },
    mounted(){
        this.getLocations()
        this.getLocationitems()
        // this.getSourceitems()
       this.getSourcelocations()
        axios.get('/api/getSelectedlocation').then(response=>{
                this.destination = response.data
            })
        
    },
    created(){
        console.log(window.user)
        this.current_user = window.user
    },
    methods: {



        resetInput(){
    
    this.choselocation= '';
    this.choseinvoice= '';
    this.choseitem= '';
    this.transferData.destination= '';
    this.transferData.qty= '';
   
   

 },
        getLocations(){

            axios.get('/api/getLocations').then(response=>{
                this.locations = response.data
            }).catch(errors=>{
                console.log(errors)
            });
            },
        getSourcelocations(){
            axios.get('/api/getSourcelocations').then(response=>{
                this.sourcelocations = response.data
            }).catch(errors=>{
                console.log(errors)
            });
            },
        

            getLocationitems(){

            axios.get('/api/getLocationitems').then(response=>{
            this.locationitems = response.data
            }).catch(errors=>{
                console.log(errors)
            });
            },
        reportSummary(){
            this.editMode = false
            this.deleteMode = false
           
            $('#reportModal').modal('show')
        },
        stockTransfer(){
            this.editMode = false
            this.deleteMode = false
           
            $('#transferModal').modal('show')
        },
        stockSummary(){

          
            axios({
                method:'post',
                url:'/api/getStocksummary',
                responseType:'arraybuffer',
                data: this.reportData
                })
                .then(function(response) {
                    let blob = new Blob([response.data], { type:   'application/pdf' } );
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'stock_summary.pdf';
                    link.click();
                    
                });
                $('#reportModal').modal('hide')
            },
        getSuppliers(){

            axios.get('/api/getSuppliers').then(response=>{
                this.suppliers = response.data
            }).catch(errors=>{
                console.log(errors)
            });
        },

        removeSupplier(supplier){
            this.deleteMode = true
            this.supplierData.id = supplier.id
            $('#supplierModal').modal('show')
        },
        deleteSupplier(){
            axios.delete('/api/deleteSupplier/' + this.supplierData.id).then(response => {
                this.getSuppliers()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#supplierModal').modal('hide')
            });
        },
        transferItem(){
            
            this.transferData.qty > this.qtyoptions.total_qty ? this.qtyerror = true: this.qtyerror = false
            if(!this.qtyerror){
                this.transferData.source = this.choselocation;   
                this.transferData.item_name = this.choseitem;   
                axios.post('/api/transferItem', this.transferData).then(response=>{
                    $('#transferModal').modal('hide');
              //  this.getSuppliers()
              this.resetInput()
              this.getLocationitems()
              this.getSourcelocations()
                }).catch(error =>this.errors = error.response.data.errors)
            }


        },
    
        editSupplier(supplier){
            this.editMode = true
            this.deleteMode= false
            this.supplierData= {
                id : supplier.id,
                name :supplier.name,
                email :supplier.email,
                address :supplier.address,
                phone :supplier.phone,
            
            }
        
            $('#supplierModal').modal('show')
        },
        updateSupplier(){



            axios.post('/api/updateSupplier/' + this.supplierData.id, this.supplierData).then(response => {
                $('#supplierModal').modal('hide');
                this.getSuppliers()
                }).catch(error =>this.errors = error.response.data.errors)



        },
     
        createSupplier(){
            this.editMode = false
            this.deleteMode = false
            this.supplierData= {
                id: '',
                name: '',
             

            }
            this.supplierErrors= {
                name: false,

            }
            $('#supplierModal').modal('show')
        },
        selectedImage(e){
            console.log(e)
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend= () => {
                this.supplierData.img = reader.result;
            }
            reader.readAsDataURL(file);
        },
        storeSupplier(){
         
                axios.post('/api/storeSupplier', this.supplierData).then(response=>{
                    $('#supplierModal').modal('hide');
                this.getSuppliers()
                }).catch(error =>this.errors = error.response.data.errors)

        

            
        }
    }

}
</script>
<style scoped>
.cards {
  display: flex;
  /* width: 1200px; */
  flex-wrap: wrap;
  margin-top: 60px;
  justify-content: center;
}
span{
    color: red;
    font-size: x-small;
}
.card {
  box-shadow: 1px 1px 10px rgba(235, 179, 179, 0.207);
  padding: 15px;
  width: 250px;
  margin-right: 15px;
  cursor: pointer;
  margin-bottom: 20px;

}

.links {
  padding: 20px
}

.links a {
  margin: 0 5px
}
</style>
