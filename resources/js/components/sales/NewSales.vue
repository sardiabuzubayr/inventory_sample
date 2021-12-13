<template>
    <div class="row">
        <h4>{{$route.meta.title}}</h4>
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">                    
                        <div class="mb-3">
                            <label for="idCustomer" class="form-label">Customer</label>
                            <select class="form-select" id="idCustomer" v-model="idCustomer" @change="changeCustomer">
                                <option value="" selected disabled>Pilih Customer</option>
                                <option v-for="item in listCustomer" :key="item.id_customer" :value="item.id_customer">
                                    {{item.nama}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Type id_item + qty : Then Enter" aria-describedby="button-addon2" maxlength="8" v-model="keywords" @keyup="addItem">
                            <button class="btn btn-primary" type="button" id="button-addon2" data-bs-toggle="modal" data-bs-target="#form" @click="setAksi('search')">Search</button>
                            <button class="btn btn-success" type="button" id="button-addon2" data-bs-toggle="modal" data-bs-target="#form" @click="setAksi('bayar')" :disabled="listItem.length === 0 || listCustomerSelect.length === 0">Buy</button>
                        </div>
                    </div>
                </div>            
            </div>
        </div>  
        <div class="clearfix" style="padding-top:10px"/>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Aksi</th>
                        <th>Id Item</th>
                        <th>Nama Item</th>
                        <th width="10%">Qty</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in listItem" :key="index">
                            <td>{{index+1}}</td>
                            <td>
                                <div class="button-action">
                                <button class="btn btn-danger btn-sm" type="button" @click="deleteItem(item.id_item)">Delete</button>
                                </div>
                            </td>
                            <td>{{item.id_item}}</td>
                            <td>{{item.nama_item}}</td>
                            <td><input type="number" :value="item.qty" @keyup="changeQty($event, item.id_item)"/></td>
                            <td>{{moneyFormat(item.qty*item.harga_satuan)}}</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>   
    </div>

    <div class="modal fade" id="form" tabindex="-1" aria-labelledby="formLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formLabel">{{aksi==='search'?'Cari Item':'Bayar'}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" v-if="aksi=='search'">
                    <input type="text" class="form-control" placeholder="Cari ..." aria-describedby="button-addon2" v-model="keywordsSearch" @keyup="searchItem">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Aksi</th>
                            <th>Id Item</th>
                            <th>Nama Item</th>
                            <th>Harga Satuan</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in listItemSearch" :key="index">
                                <td>{{index+1}}</td>
                                <td>
                                    <div class="button-action">
                                    <button class="btn btn-info btn-sm" type="button" @click="pilihItem(item.id_item)">Pilih</button>
                                    </div>
                                </td>
                                <td>{{item.id_item}}</td>
                                <td>{{item.nama_item}}</td>
                                <td>{{item.qty}}</td>
                                <td>{{moneyFormat(item.harga_satuan)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else >
                    <form @submit="saveNew">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="customerPrev" class="form-label">Customer</label>
                            <input type="text" class="form-control" id="customerPrev" aria-describedby="namaItemHelp" v-model="namaCustomer" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="total" class="form-label">Total</label>
                            <input type="text" disabled class="form-control" id="total" aria-describedby="totalHelp" :value="moneyFormat(total)">
                            <div id="totalHelp" class="form-text text-error">{{totalError}}</div>
                        </div>
                        <div class="mb-3">
                            <label for="diskon" class="form-label">Diskon</label>
                            <input type="text" disabled class="form-control" id="diskon" aria-describedby="diskonHelp" :value="listCustomerSelect.tipe_diskon === 'fix'?moneyFormat(diskon):(diskon?diskon+'%':'')">
                        </div>
                        <div class="mb-3">
                            <label for="totalBayar" class="form-label">Total Bayar</label>
                            <input type="text" disabled class="form-control" id="totalBayar" aria-describedby="totalBayarHelp" :value="moneyFormat(totalBayar)">
                        </div>
                        <div class="mb-3">
                            <label for="bayar" class="form-label">Bayar</label>
                            <input type="number" class="form-control" id="bayar" aria-describedby="bayarHelp" v-model="bayar" ref="bayar_ref" @keyup="bayarKasir" autofocus>
                            <div id="bayarHelp" class="form-text text-error">{{bayarError}}</div>
                        </div>
                        <div class="mb-3">
                            <label for="kembali" class="form-label">Kembali</label>
                            <input type="text" disabled class="form-control" id="kembali" aria-describedby="diskonHelp" :value="moneyFormat(kembali)">
                        </div>
                    </div>
                    <div v-if="generalError" class="alert alert-danger" role="alert">
                            {{generalError}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Swal from 'sweetalert2'
import { HttpSend } from '../../Network'
import func from '../../Util'

export default {
    mixins:[func],
    data(){
        return {
            data:[],
            listCustomer:[],
            listCustomerSelect:[],
            idCustomer:'',
            listItem:[],
            listItemSearch:[],
            namaCustomer:'',
            total:0,
            diskon:0,
            kembali:0,
            bayar:0,
            totalBayar:0,
            namaItemError:'',
            unitError:'',
            stokError:'',
            hargaSatuanError:'',
            generalError:'',
            keywords:'',
            keywordsSearch:'',
            aksi:'search',
            page:0,
            limit:10
        }
    },
    created(){
        this.loadListCustomer()
    },
    methods:{
        changeCustomer(){
            const item = this.listCustomer.find(element => {
                if (element.id_customer === this.idCustomer) {
                    return true;
                }
            });
            if(item){
                this.namaCustomer = item.nama
                this.listCustomerSelect = item
            }
            else
                this.listCustomerSelect = []
        },
        setAksi(aksi){
            this.aksi = aksi
            if(aksi === 'bayar')
                this.openBayar()
        },
        loadListCustomer(){
            HttpSend({
                to:'api/customer/list_customer',
                method:'GET'
            }).then(result=>{
                if(result.error_code === 0){
                    this.listCustomer = result.data
                }
            })
        },
        pilihItem(id_item){
            const item = this.listItem.find(element => {
                if (element.id_item === id_item) {
                    return true;
                }
            });
            if(item){
                item.qty = item.qty+1
                const index = this.listItem.findIndex(element => {
                    if (element.id_item === item.id_item) {
                        return true;
                    }
                });
                this.listItem[index] = item
            }
            else{
                const data = this.listItemSearch.find(element => {
                    if (element.id_item === id_item) {
                        return true;
                    }
                });

                this.listItem.push({
                    id_item:data.id_item,
                    nama_item:data.nama_item,
                    qty:1,
                    unit:data.unit,
                    harga_satuan:data.harga_satuan
                })
            }
        },
        addItem(e){
            if(e.keyCode === 13){                    
                let kode = this.keywords.split('+')
                let idItem = 0
                let qty = 1
                if(kode.length == 2){
                    idItem = kode[0]
                    qty = kode[1]
                }
                else
                    idItem = this.keywords

                HttpSend({
                    to:'api/item/get_one/'+idItem,
                    method:'GET'
                }).then(result=>{
                    if(result.error_code === 0){
                        if(result.data === null)
                            Swal.fire("Error", "Item tidak ada", "error")
                        else{
                            let data = result.data
                            if(this.listItem.length > 0){
                                const item = this.listItem.find(element => {
                                    if (element.id_item === data.id_item) {
                                        return true;
                                    }
                                });
                                if(item){
                                    item.qty = item.qty+parseInt(qty)
                                    const index = this.listItem.findIndex(element => {
                                        if (element.id_item === data.id_item) {
                                            return true;
                                        }
                                    });
                                    this.listItem[index] = item
                                    console.log(this.listItem)
                                }
                                else{
                                    this.listItem.push({
                                        id_item:data.id_item,
                                        nama_item:data.nama_item,
                                        qty:parseInt(qty),
                                        unit:data.unit,
                                        harga_satuan:data.harga_satuan
                                    })
                                    console.log(this.listItem)
                                }
                            }     
                            else{
                                this.listItem.push({
                                    id_item:data.id_item,
                                    nama_item:data.nama_item,
                                    qty:parseInt(qty),
                                    unit:data.unit,
                                    harga_satuan:data.harga_satuan
                                })
                                console.log(this.listItem)
                            }  
                            this.keywords = ''                     
                        }
                    }
                })
            }
        },
        deleteItem(id_item){
            const index = this.listItem.findIndex(element => {
                if (element.id_item === id_item) {
                    return true;
                }
            });
            if(id_item){
                this.listItem.splice(index, 1)
            }
        },
        changeQty(e, id_item){
            const item = this.listItem.find(element => {
                if (element.id_item === id_item) {
                    return true;
                }
            });
            if(item){
                if(e.target.value !== ''){
                    item.qty = parseInt(e.target.value)
                    const index = this.listItem.findIndex(element => {
                        if (element.id_item === item.id_item) {
                            return true;
                        }
                    });
                    this.listItem[index] = item
                }
            }
        },
        searchItem(e){
            if(e.keyCode === 13){
                HttpSend({
                    to:'api/item/search/'+this.keywordsSearch,
                    method:'GET'
                }).then(result=>{
                    if(result.error_code === 0){
                        this.listItemSearch = result.data
                    }
                })
            }            
        },
        saveNew(e){
            e.preventDefault()
            
            HttpSend({
                to:'api/sales/save_transaction',
                formData:{
                    id_customer:this.idCustomer,
                    total_diskon:this.diskon,
                    total_bayar:this.totalBayar,
                    list_item:JSON.stringify(this.listItem)
                }
            }).then(result=>{
                switch(result.error_code){
                    case 0:
                        Swal.fire("Sukses", result.message, "success").then(()=>{
                            this.listItem = []
                            document.getElementById('close').click()
                            this.total = 0
                            this.diskon = 0
                            this.totalBayar = 0
                            this.bayar = 0
                            this.kembali = 0
                        })                       
                        break;
                    case 1:
                        this.generalError = result.message
                        break;
                    case 2:
                        let error = result.errors
                        this.namaItemError = error.nama_item?error.nama_item[0]:""
                        this.unitError = error.unit?error.unit[0]:""
                        this.stokError = error.stok?error.stok[0]:""
                        this.hargaSatuanError = error.harga_satuan?error.harga_satuan[0]:""
                        break;
                    default:
                        break;
                }
            })
        },
        getOne(idItem){
            this.setAksi('update')
            this.idItem = idItem
            HttpSend({
                to:'api/item/get_one/'+idItem,
                method:'GET'
            }).then(result=>{
                if(result.error_code === 0){
                    this.namaItem = result.data.nama_item
                    this.unit = result.data.unit
                    this.stok = result.data.stok
                    this.hargaSatuan = result.data.harga_satuan
                }
            })
        },
        resetError(){
            this.namaItemError = ""
            this.unitError = ""
            this.stokError = ""
            this.hargaSatuanError = ""
            this.generalError = ""
        },
        openBayar(){
            let total = 0
            // this.$refs.bayar_ref.focus()
            // console.log(this.$refs.bayar_ref)
            for(let i = 0; i<this.listItem.length; i++){
                let item = this.listItem[i]
                total+=(item.qty*item.harga_satuan)
            }
            this.total = total
            if(this.listCustomerSelect.length !== 0){
                if(this.listCustomerSelect.tipe_diskon === 'fix'){
                    this.diskon = this.listCustomerSelect.diskon
                    this.totalBayar = total - this.diskon
                }
                else if(this.listCustomerSelect.tipe_diskon === 'persen'){
                    this.diskon = this.listCustomerSelect.diskon
                    this.totalBayar = total - (total*(this.listCustomerSelect.diskon/100))
                }
                else{
                    this.diskon = 0
                    this.totalBayar = total
                }
            }
        },
        bayarKasir(){
            let kembali = this.bayar - this.totalBayar
            if(kembali > 0)
                this.kembali = kembali
            else
                this.kembali = 0
        }
    }
}
</script>