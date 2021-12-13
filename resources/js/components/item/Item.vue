<template>
    <div class="row">
        <h4>{{$route.meta.title}}</h4>
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="button-action small-padding">
                            <button class="btn btn-info margin-right-action" type="button" data-bs-toggle="modal" data-bs-target="#form" @click="setAksi('create')">New</button>
                            <button class="btn btn-secondary" type="button" @click="loadData">Refresh</button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <form @submit="loadData">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Cari ..." aria-describedby="button-addon2" maxlength="8" v-model="keywords">
                                <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
                            </div>
                        </form>
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
                        <th>Nama Item</th>
                        <th>Unit</th>
                        <th>Stok</th>
                        <th>Harga Satuan</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data" :key="index">
                            <td>{{index+1}}</td>
                            <td>
                                <div class="button-action">
                                <button class="btn btn-success btn-sm margin-right-action" type="button" data-bs-toggle="modal" data-bs-target="#form" @click="getOne(item.id_item)">Edit</button>
                                <button class="btn btn-danger btn-sm" type="button" @click="deleteItem(item.id_item)">Delete</button>
                                </div>
                            </td>
                            <td>{{item.nama_item}}</td>
                            <td>{{item.unit}}</td>
                            <td>{{item.stok}}</td>
                            <td>{{moneyFormat(item.harga_satuan)}}</td>
                            <td><img class="image-table" :src="'/image/item/'+(item.barang !== '' && item.barang?item.barang:'noimage.png')"/></td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="data.length == 0">
                    <p class="text-center">No result found</p>
                </div>
                </div>
            </div>
        </div>   
    </div>

    <div class="modal fade" id="form" tabindex="-1" aria-labelledby="formLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formLabel">{{aksi=='create'?'Tambah Item':'Ubah Item'}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form @submit="do_crud">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="namaItem" class="form-label">Nama Item</label>
                        <input type="text" class="form-control" id="namaItem" aria-describedby="namaItemHelp" maxlength="50" v-model="namaItem">
                        <div id="namaItemHelp" class="form-text text-error">{{namaItemError}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="unit" class="form-label">Unit</label>
                        <select v-model = "unit" id="unit" class="form-control">
                            <option value="" disabled>Pilih Unit</option>
                            <option value="kg">Kg</option>
                            <option value="pcs">Pcs</option>
                        </select>
                        <div id="unitHelp" class="form-text text-error">{{unitError}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stok" aria-describedby="stokHelp" maxlength="50" v-model="stok">
                        <div id="stokHelp" class="form-text text-error">{{stokError}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="hargaSatuan" class="form-label">Harga Satuan</label>
                        <input type="number" class="form-control" id="hargaSatuan" aria-describedby="hargaSatuanHelp" maxlength="50" v-model="hargaSatuan"/>
                        <div id="hargaSatuanHelp" class="form-text text-error">{{hargaSatuanError}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="barang" class="form-label">Barang</label>
                        <input type="file" class="form-control" id="barang" aria-describedby="barangHelp"  @change="onFileChange" accept="image/*">
                        <div id="barangHelp" class="form-text text-error">{{barangError}}</div>
                    </div>
                </div>
                <div v-if="generalError" class="alert alert-danger" role="alert">
                        {{generalError}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
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
            idItem:-1,
            namaItem:'',
            unit:'',
            stok:0,
            hargaSatuan:0,
            barang:null,
            namaItemError:'',
            unitError:'',
            stokError:'',
            hargaSatuanError:'',
            generalError:'',
            keywords:'',
            aksi:'create',
            page:0,
            limit:10
        }
    },
    created(){
        this.loadData()
    },
    methods:{
        setAksi(aksi){
            this.aksi = aksi
            if(aksi === 'create')
                this.emptyForm()
        },
        onFileChange(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.barang = files[0];
            console.log(this.barang)
        },
        loadData(e){
            if(e)
                e.preventDefault()
            HttpSend({
                to:'api/item/get_all/'+this.limit+"/"+this.page,
                formData:{
                    keywords:this.keywords
                }
            }).then(result=>{
                if(result.error_code === 0){
                    this.data = result.data.data
                }
            })
        },
        do_crud(e){
            e.preventDefault()
            if(this.aksi === 'create')
                this.saveNew()
            else
                this.saveEdit()
        },
        saveNew(){
            this.resetError()
            HttpSend({
                to:'api/item/new_item',
                formData:{
                    nama_item:this.namaItem,
                    unit:this.unit,
                    stok:this.stok,
                    harga_satuan:this.hargaSatuan,
                    barang:this.barang
                }
            }).then(result=>{
                switch(result.error_code){
                    case 0:
                        Swal.fire("Sukses", result.message, "success")
                        this.loadData()
                        this.emptyForm()
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
        saveEdit(){
            this.resetError()
            HttpSend({
                to:'api/item/edit_item/'+this.idItem,
                formData:{
                    nama_item:this.namaItem,
                    unit:this.unit,
                    stok:this.stok,
                    harga_satuan:this.hargaSatuan
                },
                method:'PUT'
            }).then(result=>{
                switch(result.error_code){
                    case 0:
                        Swal.fire("Sukses", result.message, "success").then(()=>{
                            if(this.barang)
                                this.updateImage(this.idItem)
                            this.loadData()
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
        updateImage(idItem){
            HttpSend({
                to:'api/item/update_gambar',
                formData:{
                    id_item:idItem,
                    barang:this.barang
                }
            }).then(result=>{
                console.log(result)
            })
        },
        deleteItem(idItem){
            Swal.fire({
                title: 'Hapus',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: `Ya`,
                cancelButtonText: `Batal`,
                html:'Yakin ingin menghapus item ini?',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.resetError()
                    HttpSend({
                        to:'api/item/delete_item/'+idItem,
                        method:'DELETE'
                    }).then(result=>{
                        switch(result.error_code){
                            case 0:
                                Swal.fire("Sukses", result.message, "success").then(()=>{
                                    this.loadData()
                                })
                                break
                            case 1:
                                this.generalError = result.message
                                break;
                            default:
                                break
                        }
                    })
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
        emptyForm(){
            this.namaItem = ""
            this.unit = ""
            this.stok = 0
            this.hargaSatuan = 0
            this.gambar = []
        }
    }
}
</script>