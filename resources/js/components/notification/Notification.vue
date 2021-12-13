<template>
    <div class="row">
        <h4>{{$route.meta.title}}</h4>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                <div v-if="data.length == 0">
                    <p class="text-center">No result found</p>
                </div>
                <ul class="list-group">
                    <li :class="item.is_read == 0?'text-alert list-group-item':'list-group-item'" v-for="(item, index) in data" :key="index">
                        <div class="notification">
                            <div class="date-notification">{{dateLong(item.created_date)}}</div>
                            <div class="desc-notification">{{item.description}}</div>
                        </div>
                    </li>
                </ul>
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
        this.loadData()
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
                to:'api/notification',
                method:'GET',
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