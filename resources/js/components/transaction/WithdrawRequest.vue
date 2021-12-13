<template>
    <div class="row">
        <h4>{{$route.meta.title}}</h4>
        <div class="card">
            <div class="card-header">
                
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="mb-3">
                            <label for="from" class="form-label">From</label>
                            <input type="date" class="form-control" id="from" aria-describedby="fromHelp" v-model="from">
                            <div id="fromHelp" class="form-text text-error">{{fromError}}</div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="mb-3">
                            <label for="to" class="form-label">To</label>
                            <input type="date" class="form-control" id="to" aria-describedby="toHelp" v-model="to">
                            <div id="toHelp" class="form-text text-error">{{toError}}</div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="d-grid gap-2" style="paddingTop:31px">
                            <button class="btn btn-primary" type="button" @click="loadData">Search</button>
                        </div>
                    </div>
                </div>            
            </div>
            <div v-if="generalError" class="alert alert-danger" role="alert">
                    {{generalError}}
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
                        <th>Action</th>
                        <th>Account Number</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Request Date</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data" :key="index">
                            <td>{{index+1}}</td>
                            <td>
                                <button type="button" @click="acceptRequest(item.transaction_id)" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#topUpModal"><i class="fa fa-check"/> Accept
                                </button>
                            </td>
                            <td>{{item.account.account_number}}</td>
                            <td>{{item.account.users.name}}</td>
                            <td>{{moneyFormat(item.amount)}}</td>
                            <td>{{dateLong(item.created_date)}}</td>
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
            list_type:[],
            type:'',
            from:'',
            to:'',
            typeError:"",
            amountError:"",
            generalError:""
        }
    },
    created(){
    },
    methods:{
        searchAccount(){
            HttpSend({
                to:'api/account/search_to_transfer/'+this.typeTo,
                method:'GET'
            }).then(result=>{
                if(result.error_code === 0){
                    if(result.data){
                        this.name = result.data.users.name
                    }
                    else
                        this.name = ''
                }
            })
        },
        loadData(){
            HttpSend({
                to:'api/transaction/get_withdraw_to_approve',
                formData:{
                    type:this.type,
                    from:this.from,
                    to:this.to
                }
            }).then(result=>{
                if(result.error_code === 0)
                    this.data = result.data
            })
        },
        acceptRequest(transaction_id){
            Swal.fire({
                title: 'Withdraw Request',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: `Yes`,
                cancelButtonText: `Cancel`,
                html:'Are you sure to accept this withdraw request?',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.resetError()
                    HttpSend({
                        to:'api/transaction/accept_withdraw/'+transaction_id,
                        method:'PUT'
                    }).then(result=>{
                        switch(result.error_code){
                            case 0:
                                Swal.fire("Success", result.message, "success").then(()=>{
                                    this.loadData()
                                })
                                break
                            case 1:
                                let error = result.errors
                                break
                            case 2:
                                Swal.fire("Something wrong", result.message, "error")
                                break;
                            default:
                                break
                        }
                    })
                }
            })
        },
        resetError(){
            this.generalError = ""
        },
    }
}
</script>