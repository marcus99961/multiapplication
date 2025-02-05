<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="float-start">{{ title }}</h5>
                        </div>
                        <div class="col-md-4 float-center">
                            <input class="form-control-sm rounded" type="text" v-model="keyword" placeholder="search item..">
                        </div>
                        <div class="col-md-4">
                            <button @click="createItem"  class="btn-info btn-sm float-right"><i class="fa fa-plus-circle mr-1"></i>New Item</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="title" >ItemCode</label>
                                <input type="text" class="form-control" v-model="itemData.item_code" >
                                <small class="text-danger" v-if="errors.item_code"> {{ errors.item_code[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="title" >Item Name</label>
                                <input type="text" class="form-control" v-model="itemData.name" >
                                <small class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="title" >Spec</label>
                                <input type="text" class="form-control" v-model="itemData.spec" >
                                <small class="text-danger" v-if="errors.spec"> {{ errors.spec[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="title" >Unit</label>
                                <multiselect v-model="itemData.unit_measure" :options="units" :multiple="false" :show-labels="false"
                                        :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.unit_measure"> {{ errors.unit_measure[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="title" >Category</label>
                                <multiselect v-model="itemData.group_code" :options="categories" placeholder="Select one" :show-labels="false" :multiple="false"
                                        :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.group_code"> {{ errors.group_code[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="title" >Min</label>
                                <input type="text" class="form-control" v-model="itemData.min_qty" >
                                <small class="text-danger" v-if="errors.min_qty"> {{ errors.min_qty[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="title" >Max</label>
                                <input type="text" class="form-control" v-model="itemData.max_qty" >
                                <small class="text-danger" v-if="errors.max_qty"> {{ errors.max_qty[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="title" >Reorder</label>
                                <input type="text" class="form-control" v-model="itemData.reorder_level" @keyup.enter="storeItem">
                                <small class="text-danger" v-if="errors.reorder_level"> {{ errors.reorder_level[0] }} </small><br>
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
                                <th>Spec</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, i) in items.data " :key="i" class="bg-transparent">
                                <td>{{(pages*settingStore.setting.pagination_limit)-settingStore.setting.pagination_limit+i+1}}</td>
                                <td>{{item.item_code}}</td>
                                <td>{{item.name}}</td>
                                <td>{{item.spec}}</td>
                               


                                <td>
                                    <button @click="editItem(item)" class="ml-1" ><i class="fa fa-edit text-success mx-1"></i></button>
                                    <button @click="removeItem(item)" class="ml-1"><i class="fa fa-trash text-danger mx-1"></i></button>                               


                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>               
            </div>             
        </div>
        <Bootstrap4Pagination :data="items" @pagination-change-page="getItems"></Bootstrap4Pagination>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
        <div :class="`modal-dialog ${!deleteMode ? 'modal-lg': 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="itemModalLabel" v-show="!deleteMode && !renewMode"> {{!editMode ? 'Add New Item': 'Update Item' }} </h5>
                    <h5 class="modal-title" id="itemModalLabel" v-show="deleteMode" > Delete Item </h5>
                    <h5 class="modal-title" id="itemModalLabel" v-show="renewMode" >Renew License </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode && !renewMode">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Item Code</label>
                                <input type="text" class="form-control" v-model="itemData.item_code" >
                                <small class="text-danger" v-if="errors.item_code"> {{ errors.item_code[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Item Name</label>
                                <input type="text" class="form-control" v-model="itemData.name" >
                                <small class="text-danger" v-if="errors.name"> {{ errors.name[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Specifications</label>
                                <input type="text" class="form-control" v-model="itemData.spec" >
                                <small class="text-danger" v-if="errors.spec"> {{ errors.spec[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Unit Measure</label>
                                <multiselect v-model="itemData.unit_measure" :options="units" :multiple="false" :show-labels="false"
                                :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.unit_measure"> {{ errors.unit_measure[0] }} </small><br>
                            </div>
                        </div>

                    </div>
                    <div class="row" v-show="!deleteMode && !renewMode">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Category</label>
                                <!-- <input type="text" class="form-control" v-model="itemData.group_code" > -->
                                <multiselect v-model="itemData.group_code" :options="categories" :multiple="false" :show-labels="false"
                                :close-on-select="true"> </multiselect>
                                <small class="text-danger" v-if="errors.group_code"> {{ errors.group_code[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Min Qty</label>
                                <input type="text" class="form-control" v-model="itemData.min_qty" >
                                <small class="text-danger" v-if="errors.min_qty"> {{ errors.min_qty[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Max Qty</label>
                                <input type="text" class="form-control" v-model="itemData.max_qty" >
                                <small class="text-danger" v-if="errors.max_qty"> {{ errors.max_qty[0] }} </small><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" >Reorder Level</label>
                                <input type="text" class="form-control" v-model="itemData.reorder_level" >
                                <small class="text-danger" v-if="errors.reorder_level"> {{ errors.reorder_level[0] }} </small><br>
                            </div>
                        </div>

                    </div>




                    <h4 class="text-center" v-show="deleteMode">Are you sure want to delete!</h4>

                </div>
                <div class="modal-footer" v-show="!deleteMode && !renewMode">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

const authUserStore = useAuthUserStore;

export default {
    setup: () => ({
        title: 'All Items'
    }),
    data() {
        return {
            editMode: false,
            deleteMode: false,
            renewMode:false,
            keyword: null,
            pages: null,

            itemData: {
                item_code: '',
                name: '',
                spec: '',
                unit_measure: '',
                group_code: '',
                min_qty: '',
                max_qty: '',
                reorder_level: '',
          

            },
            units: [],
            items: {},
            categories: [],
            current_user: {},
            errors: {},
            selectedcategory: {},
            authUserStore: {},
            settingStore: {},
            searchStore: {},
        }
    },
    watch: {
        keyword(after, before) {
            this.getItems();
        }
    },
    mounted(){
        this.getItems()
        this.getUnits()
        this.getCategories()
        this.getSelectedcategory()
    },
    created(){
        console.log(window.user)
        this.current_user = window.user
        this.authUserStore = useAuthUserStore();
        this.settingStore = useSettingStore();
        this.searchStore = useSearchStore();
        
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
        removeItem(item){
            this.deleteMode = true
            this.itemData.id = item.id
            $('#itemModal').modal('show')
        },
        deleteItem(){
            axios.delete('/api/deleteItem/' + this.itemData.id).then(response => {
                this.getItems()
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                $('#itemModal').modal('hide')
            });
        },
    
        editItem(item){
            this.editMode = true
            this.deleteMode= false
            this.current_category = ''
            let arr = [];
            this.selectedcategory.forEach((value, index) => {
                arr.push(value);
                if(value.group_code==item.group_code){
                    this.current_category = value.name
                }


            })
            this.itemData= {
                id : item.id,
                name :item.name,
                item_code : item.item_code,
                spec : item.spec,
                unit_measure : item.unit_measure,
                
                group_code : this.current_category,
                min_qty : item.min_qty,
                max_qty : item.max_qty,
                reorder_level : item.reorder_level,
            }
        
            $('#itemModal').modal('show')
        },
        updateItem(){



            axios.post('/api/updateItem/' + this.itemData.id, this.itemData).then(response => {
                $('#itemModal').modal('hide');
                this.getItems()
                this.resetInput()
                }).catch(error =>this.errors = error.response.data.errors)



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
    
        this.itemData.group_code= '';
        this.itemData.item_code= '';
        this.itemData.name= '';
        this.itemData.spec= '';
        this.itemData.unit_measure= '';
        this.itemData.min_qty= '';
        this.itemData.max_qty= '';
        this.itemData.reorder_level = '';
        // this.itemData.departure = '';
        // this.itemData.source = '';
        // this.itemData.priority = '';
        // this.itemData.member_id = '';
      

     },
        storeItem(){
         
                axios.post('/api/storeItem', this.itemData).then(response=>{
                this.resetInput()
                this.getItems()
                }).catch(error =>this.errors = error.response.data.errors)

        

            
        }
    }

}
</script>
