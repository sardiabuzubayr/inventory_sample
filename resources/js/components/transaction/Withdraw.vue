<template>
    <div class="row">
        <h4>{{$route.meta.title}}</h4>
        <div class="card">
            <form @submit="saveWithdraw">
                <div class="card-header">
                    Fill below to withdraw
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="accountNumber" class="form-label">Choose account number to Withdraw</label>
                        <select class="form-select" id="accountNumber" aria-describedby="accountNumberHelp"  v-model="accountNumber">
                            <option v-for="item in account_list" :key="item.account_number" :value="item.account_number">
                                {{item.account_number}}
                            </option>
                        </select>
                        <div id="accountNumberHelp" class="form-text text-error">{{accountNumberError}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount to Withdraw</label>
                        <input type="number" class="form-control" id="amount" aria-describedby="amountHelp" v-model="amount">
                        <div id="amountHelp" class="form-text text-error">{{amountError}}</div>
                    </div>                
                </div>
                <div v-if="generalError" class="alert alert-danger" role="alert">
                        {{generalError}}
                </div>
                <div class="card-footer">
                    <div class="d-grid gap-2 d-md-block">
                        <button class="btn btn-primary" type="submit">Withdraw</button>
                    </div>
                </div>
            </form>
        </div>  
        <div class="clearfix" style="padding-top:10px"/>
        <div class="card">
            <div class="card-header">
                List of your withdraw request
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Account Number</th>
                        <th>Amount</th>
                        <th>Request On</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data" :key="index">
                            <td>{{index+1}}</td>
                            <td>{{item.account_number}}</td>
                            <td>{{moneyFormat(item.amount)}}</td>
                            <td>{{dateLong(item.created_date)}}</td>
                            <td>{{item.is_approve===1?'Aprove':'Pending'}}</td>
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
            account_list:[],
            accountNumber:'',
            amount:0,
            accountNumberError:"",
            amountError:"",
            generalError:""
        }
    },
    created(){
        this.getAccount()
        this.loadData()
    },
    methods:{
        getAccount(){
            HttpSend({
                to:'api/account/get_my_account',
                method:'GET'
            }).then(result=>{
                if(result.error_code === 0)
                    this.account_list = result.data
            })
        },
        loadData(){
            HttpSend({
                to:'api/transaction/list_request_withdraw',
                method:'GET'
            }).then(result=>{
                if(result.error_code === 0)
                    this.data = result.data
            })
        },
        saveWithdraw(e){
            e.preventDefault()
            Swal.fire({
                title: 'Withdraw',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: `Yes`,
                cancelButtonText: `Cancel`,
                html:'Are you sure want to withdraw?',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.resetError()
                    HttpSend({
                        to:'api/transaction/request_withdraw',
                        formData:{
                            account_number:this.accountNumber,
                            amount:this.amount
                        }
                    }).then(result=>{
                        switch(result.error_code){
                            case 0:
                                Swal.fire("Success", result.message, "success").then(()=>{
                                    this.accountNumber = ""
                                    this.amount = 0
                                    this.loadData()
                                })
                                break
                            case 1:
                                let error = result.errors
                                this.accountNumberError = error.account_number?error.account_number[0]:""
                                this.amountError = error.amount?error.amount[0]:""
                                break
                            case 2:
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
            this.amountError = ""
            this.accountNumberError = ""
            this.generalError = ""
        },
        
    }
}
</script>