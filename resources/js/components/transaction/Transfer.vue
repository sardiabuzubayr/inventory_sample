<template>
    <div class="row">
        <h4>{{$route.meta.title}}</h4>
        <div class="card">
            <form @submit="saveTransfer">
                <div class="card-header">
                    Fill below to transfer your money
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="accountNumber" class="form-label">Transfer from</label>
                        <select class="form-select" id="accountNumber" aria-describedby="accountNumberHelp"  v-model="accountNumber">
                            <option v-for="item in account_list" :key="item.account_number" :value="item.account_number">
                                {{item.account_number}}
                            </option>
                        </select>
                        <div id="accountNumberHelp" class="form-text text-error">{{accountNumberError}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="accountNumberTo" class="form-label">Transfer to</label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" placeholder="Type account number" aria-label="Recipient's username" aria-describedby="button-addon2" maxlength="8" v-model="accountNumberTo">
                            <button class="btn btn-success" type="button" id="button-addon2" @click="searchAccount">Search Account</button>
                        </div>
                        <div id="accountNumberHelp" class="form-text text-error">{{accountNumberToError}}</div>

                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" disabled class="form-control" id="name" v-model="name">
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount to Transfer</label>
                        <input type="number" class="form-control" id="amount" aria-describedby="amountHelp" v-model="amount">
                        <div id="amountHelp" class="form-text text-error">{{amountError}}</div>
                    </div>             
                </div>
                <div v-if="generalError" class="alert alert-danger" role="alert">
                        {{generalError}}
                </div>
                <div class="card-footer">
                    <div class="d-grid gap-2 d-md-block">
                        <button class="btn btn-primary" type="submit" :disabled="name==''">Transfer</button>
                    </div>
                </div>
            </form>
        </div>  
        <div class="clearfix" style="padding-top:10px"/>
        <div class="card">
            <div class="card-header">
                Transfer History
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Account Number</th>
                        <th>To</th>
                        <th>Amount</th>
                        <th>Transfer Date</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data" :key="index">
                            <td>{{index+1}}</td>
                            <td>{{item.account_number}}</td>
                            <td>{{item.account_number_to}}</td>
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
            account_list:[],
            accountNumber:'',
            accountNumberTo:'',
            name:'',
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
        searchAccount(){
            HttpSend({
                to:'api/account/search_to_transfer/'+this.accountNumberTo,
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
                to:'api/transaction/list_transfer',
                method:'GET'
            }).then(result=>{
                if(result.error_code === 0)
                    this.data = result.data
            })
        },
        saveTransfer(e){
            e.preventDefault()
            Swal.fire({
                title: 'Transfer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: `Yes`,
                cancelButtonText: `Cancel`,
                html:'Are you sure want to transfer your money to '+this.accountNumberTo,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.resetError()
                    HttpSend({
                        to:'api/transaction/transfer',
                        formData:{
                            account_number:this.accountNumber,
                            account_number_to:this.accountNumberTo,
                            amount:this.amount
                        }
                    }).then(result=>{
                        switch(result.error_code){
                            case 0:
                                Swal.fire("Success", result.message, "success").then(()=>{
                                    this.accountNumber = ""
                                    this.accountNumberTo = ""
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