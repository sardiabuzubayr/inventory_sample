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
                            <label for="type" class="form-label">Type of Transaction</label>
                            <select class="form-select" id="type" aria-describedby="typeHelp"  v-model="type">
                                <option value="" selected>All</option>
                                <option v-for="item in list_type" :key="item.value" :value="item.value">
                                    {{item.label}}
                                </option>
                            </select>
                            <div id="typeHelp" class="form-text text-error">{{typeError}}</div>
                        </div>
                    </div>
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
                        <th>Type</th>
                        <th>Account Number</th>
                        <th>Amount</th>
                        <th>Transaction Date</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data" :key="index">
                            <td>{{index+1}}</td>
                            <td>{{item.type}}</td>
                            <td>{{item.account_number}}</td>
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
        this.getTypeTransaction()
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
        getTypeTransaction(){
            HttpSend({
                to:'api/transaction/list_type',
                method:'GET'
            }).then(result=>{
                if(result.error_code === 0)
                    this.list_type = result.data
            })
        },
        loadData(){
            HttpSend({
                to:'api/transaction/mutation',
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
        resetError(){
            this.amountError = ""
            this.typeError = ""
            this.generalError = ""
        },
    }
}
</script>