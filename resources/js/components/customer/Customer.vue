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
                        <th>Id Customer</th>
                        <th>Nama</th>
                        <th>Contact</th>
                        <th>Alamat</th>
                        <th>Diskon</th>
                        <th>Tipe Diskon</th>
                        <th>Ktp</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data" :key="index">
                            <td>{{index+1}}</td>
                            <td>
                                <div class="button-action">
                                <button class="btn btn-success btn-sm margin-right-action" type="button" data-bs-toggle="modal" data-bs-target="#form" @click="getOne(item.id_customer)">Edit</button>
                                <button class="btn btn-danger btn-sm" type="button" @click="deleteItem(item.id_customer)">Delete</button>
                                </div>
                            </td>
                            <td>{{item.id_customer}}</td>
                            <td>{{item.nama}}</td>
                            <td>{{item.contact}}</td>
                            <td>{{item.alamat}}</td>
                            <td>{{item.tipe_diskon==='fix'?moneyFormat(item.diskon):(item.diskon?item.diskon+"%":"")}}</td>
                            <td>{{item.tipe_diskon}}</td>
                            <td><img class="image-table" :src="'/image/customer/'+(item.ktp !== '' && item.ktp?item.ktp:'noimage.png')"/></td>
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
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" aria-describedby="namaHelp" maxlength="50" v-model="nama">
                        <div id="namaHelp" class="form-text text-error">{{namaError}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="contact" class="form-label">Kontak</label>
                        <input type="number" class="form-control" id="contact" aria-describedby="numberHelp" v-model="contact">
                        <div id="numberHelp" class="form-text text-error">{{contactError}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" aria-describedby="alamatHelp" v-model="alamat"/>
                        <div id="alamatHelp" class="form-text text-error">{{alamatError}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="diskon" class="form-label">Diskon</label>
                        <input type="number" class="form-control" id="diskon" aria-describedby="diskonHelp" v-model="diskon"/>
                        <div id="diskonHelp" class="form-text text-error">{{diskonError}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="tipeDiskon" class="form-label">Tipe Diskon</label>
                        <select v-model = "tipeDiskon" id="tipeDiskon" class="form-control">
                            <option value="" disabled>Pilih Tipe Diskon</option>
                            <option value="fix">Fix</option>
                            <option value="persen">Persentase</option>
                        </select>
                        <div id="tipeDiskonHelp" class="form-text text-error">{{tipeDiskonError}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="ktp" class="form-label">Ktp</label>
                        <input type="file" class="form-control" id="ktp" aria-describedby="ktpHelp"  @change="onFileChange" accept="image/*">
                        <div id="ktpHelp" class="form-text text-error">{{ktpError}}</div>
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
            idCustomer:-1,
            nama:'',
            contact:'',
            alamat:'',
            diskon:'',
            tipeDiskon:'',
            ktp:null,
            namaError:'',
            contactError:'',
            alamatError:'',
            tipeDiskonError:'',
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
            this.ktp = files[0];
            console.log(this.ktp)
        },
        loadData(e){
            if(e)
                e.preventDefault()
            HttpSend({
                to:'api/customer/get_all/'+this.limit+"/"+this.page,
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
                to:'api/customer/new_item',
                formData:{
                    nama:this.nama,
                    contact:this.contact,
                    alamat:this.alamat,
                    diskon:this.diskon,
                    tipeDiskon:this.tipeDiskon,
                    ktp:this.ktp
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
                        this.namaError = error.nama?error.nama[0]:""
                        this.contactError = error.contact?error.contact[0]:""
                        this.alamatError = error.alamat?error.alamat[0]:""
                        this.tipeDiskonError = error.harga_satuan?error.harga_satuan[0]:""
                        break;
                    default:
                        break;
                }
            })
        },
        getOne(idCustomer){
            this.setAksi('update')
            this.idCustomer = idCustomer
            HttpSend({
                to:'api/customer/get_one/'+idCustomer,
                method:'GET'
            }).then(result=>{
                if(result.error_code === 0){
                    this.idCustomer = result.data.id_customer
                    this.nama = result.data.nama
                    this.contact = result.data.contact
                    this.alamat = result.data.alamat
                    this.diskon = result.data.diskon
                    this.tipeDiskon = result.data.tipe_diskon
                }
            })
        },
        saveEdit(){
            this.resetError()
            HttpSend({
                to:'api/customer/edit_item/'+this.idCustomer,
                formData:{
                    nama:this.nama,
                    contact:this.contact,
                    alamat:this.alamat,
                    diskon:this.diskon,
                    tipe_diskon:this.tipeDiskon
                },
                method:'PUT'
            }).then(result=>{
                switch(result.error_code){
                    case 0:
                        Swal.fire("Sukses", result.message, "success").then(()=>{
                            if(this.ktp)
                                this.updateImage(this.idCustomer)
                            this.loadData()
                        })
                        break;
                    case 1:
                        this.generalError = result.message
                        break;
                    case 2:
                        let error = result.errors
                        this.namaError = error.nama?error.nama[0]:""
                        this.contactError = error.contact?error.contact[0]:""
                        this.alamatError = error.alamat?error.alamat[0]:""
                        this.tipeDiskonError = error.harga_satuan?error.harga_satuan[0]:""
                        break;
                    default:
                        break;
                }
            })
        },
        updateImage(idCustomer){
            HttpSend({
                to:'api/customer/update_gambar',
                formData:{
                    id_customer:idCustomer,
                    ktp:this.ktp
                }
            }).then(result=>{
                console.log(result)
            })
        },
        deleteItem(idCustomer){
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
                        to:'api/customer/delete_item/'+idCustomer,
                        method:'DELETE'
                    }).then(result=>{
                        switch(result.error_code){
                            case 0:
                                Swal.fire("Sukses", result.message, "success").then(()=>{
                                    this.loadData()
                                })
                                break
                            case 1:
                                Swal.fire("Terjadi kesalahan", result.message, "error")
                                break;
                            default:
                                break
                        }
                    })
                }
            })
        },
        resetError(){
            this.namaError = ""
            this.contactError = ""
            this.alamatError = ""
            this.tipeDiskonError = ""
            this.generalError = ""
        },
        emptyForm(){
            this.nama = ""
            this.contact = ""
            this.alamat = ""
            this.diskon = ""
            this.tipeDiskon = ""
            this.gambar = []
        }
    }
}
</script>