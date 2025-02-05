<template>
    
    <div class="row justify-content-center">
    
      <div class="col-md-12">
        <div class="container mt-3">
        <div class="row">
            <div class="col-md-6">
                <h5 class="float-start">{{ locations.name }}</h5>
            </div>
            <div class="col-md-4 float-center">
                            <input class="form-control-sm rounded" type="text" v-model="keyword" placeholder="search item..">
                        </div>
            <div class="col-md-6">
                
                <!-- <button @click="reportSummary"  class="btn-info btn-sm float-right"><i class="fa fa-plus-circle mr-1"></i>Stock Summary</button> -->
            </div>
        </div>
        </div>
      </div>
      <div class="cards">
        
        <div class="card" v-for="stock in stockbylocations">
          <h6 class="text-center">{{stock.item.name}}</h6>
          <p>x {{stock.quantity}}</p>
        </div>
    </div>
  </div>

    <!-- Modal -->
    <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="supplierModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Add New Supplier': 'Update Supplier' }} </h5>
                    <h5 class="modal-title" id="supplierModalLabel" v-show="deleteMode" > Delete Supplier </h5>
                    <h5 class="modal-title" id="supplierModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                     

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Address</label>
                                <input type="text" class="form-control" v-model="supplierData.address" >
                                <small class="text-danger" v-if="errors.address"> {{ errors.address[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Email</label>
                                <input type="text" class="form-control" v-model="supplierData.email" >
                                <small class="text-danger" v-if="errors.email"> {{ errors.email[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Phone</label>
                                <input type="text" class="form-control" v-model="supplierData.phone" >
                                <small class="text-danger" v-if="errors.phone"> {{ errors.phone[0] }} </small><br>
                            </div>
                        </div>

                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeSupplier(): updateSupplier()" >{{!editMode ? 'Create Supplier': 'Save Changes' }}</button>
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
                    <h5 class="modal-title" id="supplierModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Add New Supplier': 'Update Supplier' }} </h5>
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
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
export default {
    setup: () => ({
        title: 'Stocks by Location'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            keyword: null,

            supplierData: {
                address: '',
                name: '',
                email: '',
                phone: '',
          

            },
            form: { id : ''},
            stockbylocations: {},
            suppliers: {},
            current_user: {},
            errors: {},
            locations: {},
            stocksSummary: {},
            reportData: { date: '',},
        }
    },
    watch: {
        keyword(after, before) {
            this.getStockbylocations();
        }
    },
    mounted(){
        this.getLocations()
        this.getStockbylocations();
        
    },
    created(){
        this.form.id = this.$route.params.id
        console.log(window.user)
        this.current_user = window.user
    },
    methods: {
        getStockbylocations(){

            axios.get('/api/getStockbylocations',{ params: { location_id : this.form.id, keyword: this.keyword } }).then(response=>{
                this.stockbylocations = response.data
            }).catch(errors=>{
                console.log(errors)
            });
            },
        getLocations(){

            axios.get('/api/getLocation',{ params: { keyword: this.form.id } }).then(response=>{
                this.locations = response.data
            }).catch(errors=>{
                console.log(errors)
            });
            },
        reportSummary(){
            this.editMode = false
            this.deleteMode = false
           
            $('#reportModal').modal('show')
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
                   //\ $('#reportModal').modal('hide')
                });
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
  margin-top: 55px;
  justify-content: center;
}

.card {
  box-shadow: 1px 1px 10px rgba(235, 179, 179, 0.207);
  padding: 6px;
  width: 250px;
  margin-right: 15px;
  cursor: pointer;
  margin-bottom: 15px;

}
p {
    color: green;
}

.links {
  padding: 20px
}

.links a {
  margin: 0 5px
}
</style>
