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
                            <router-link class="btn btn-info margin-right-action" type="button" to="/new_sales">New</router-link>
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
                        <th>Kode Transaksi</th>
                        <th>Customer</th>
                        <th>Tanggal Transaksi</th>
                        <th>Total Bayar</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data" :key="index">
                            <td>{{index+1}}</td>
                            <td>
                                <div class="button-action">
                                    <button class="btn btn-info btn-sm margin-right-action" type="button" data-bs-toggle="modal" data-bs-target="#form" @click="detailItem(item.kode_transaksi)" >Detail</button>
                                    <button class="btn btn-danger btn-sm" type="button" @click="deleteItem(item.kode_transaksi)">Delete</button>
                                </div>
                            </td>
                            <td>{{item.kode_transaksi}}</td>
                            <td>{{item.customer.nama}}</td>
                            <td>{{dateLong(item.tanggal_transaksi)}}</td>
                            <td>{{moneyFormat(item.total_bayar)}}</td>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formLabel">Detail Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Id Item</th>
                            <th>Nama Item</th>
                            <th>Qty</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in detail" :key="index">
                                <td>{{index+1}}</td>
                                <td>{{item.id_item}}</td>
                                <td>{{item.item.nama_item}}</td>
                                <td>{{item.qty}}</td>
                                <td>{{moneyFormat(item.item.harga_satuan)}}</td>
                                <td>{{moneyFormat(item.qty*item.item.harga_satuan)}}</td>
                            </tr>
                        </tbody>
                    </table>
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
            detail:[],
            kodeTransaksi:-1,
            keywords:'',
            page:0,
            limit:10
        }
    },
    created(){
        this.loadData()
    },
    methods:{
        loadData(e){
            if(e)
                e.preventDefault()
            HttpSend({
                to:'api/sales/get_all/'+this.limit+"/"+this.page,
                formData:{
                    keywords:this.keywords
                }
            }).then(result=>{
                if(result.error_code === 0){
                    this.data = result.data.data
                }
            })
        },
        deleteItem(kodeTransaksi){
            Swal.fire({
                title: 'Hapus',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: `Ya`,
                cancelButtonText: `Batal`,
                html:'Yakin ingin menghapus item ini?',
            }).then((result) => {
                if (result.isConfirmed) {
                    HttpSend({
                        to:'api/sales/delete_item/'+kodeTransaksi,
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
        detailItem(kodeTransaksi){
            HttpSend({
                to:'api/sales/get_detail/'+kodeTransaksi,
                method:'GET'
            }).then(result=>{
                if(result.error_code === 0){
                    this.detail = result.data
                }
            })
        }
    }
}
</script>