<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                
                <div class="card">
                    <div class="card-header">
                        <h1>Mongo DB</h1>
                    </div>
                    <div class="card-body">
                        <div class="redis-flex">
                            <div class="div1">
                                <h3>JSON DATA</h3>
                                <div class="pa" v-if="!isEmpty">
                                    {{ getData.finalData }}
                                </div>
                                <div class="btm-box" v-if="updatedAt!=''">
                                    <div>{{ updatedAt }}</div>
                                </div>
                            </div>
                            <div class="div1">
                                <h3>Update DATA</h3>
                                <textarea class="ta" rows="10" cols="50" required v-model="data.finalData"></textarea>
                                <div v-if="isEmpty">
                                    <button @click="add">Add</button>
                                </div>
                                <div v-else>
                                    <button @click="update">update</button>
                                </div>
                                

                                <div class="mt" v-if="error">
                                    <h5>{{ errorMsg }}</h5>
                                </div>
                                <div class="mt">    
                                    Tips: &nbsp; {
                                            "property" : "value"
                                          }
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .card-header{
        text-align:center;
    }
    .redis-flex{
        display: flex;
    }
    .div1{
        background:#efefef;
        height:400px;
        width:400px;
        margin:10px;
        padding:3px;
        text-align:center;
    }    
    .ta{
        border:none;
    }
    .ta:focus{
        border:none;
        outline:0px;
    }
    .mt{
    margin-top:10px;
    }
    .mt>h5{
        color:red;
    }
    .pa{
        padding:2%;
        height: 75%;
    }
    .btm-box{
        background:#fff;
    }
</style>
<script>
export default {
    data(){
        return {
            getData:{
                //_id:'',
                finalData:'',
            },
            data:{
                //_id:'',
                finalData:'',
            },
            updateIndex:'',
            error:false,
            errorMsg:'',
            isEmpty:false,
            updatedAt:'',
        }
    },
    mounted() {
        //console.log(window.Laravel.csrfToken)
    },
    async created(){
        const res = await this.callApi('get','/get');
        if(res.status==200){
            this.getData.finalData = res.data[0].finalData;
            this.data.finalData = res.data[0].finalData;
            this.updateIndex = res.data[0]._id;
            this.timeUpdate(res.data[0].updated_at);
        } else if(res.status==404) {
            console.log(res.data.msg);
            this.isEmpty = true;
        } else {
            console.log(res);
        }
    },
    methods:{
        async add(){
            if(this.data.finalData.trim()==''){
                this.setError('Data Required');
            } else {
                this.resetError();
                if(this.isJson(this.data.finalData.trim())){
                    
                    this.data.finalData = this.data.finalData.trim();
                    const res = await this.callApi('post','/add',this.data);
                    if(res.status==201){
                        this.getData.finalData = res.data.finalData;
                        this.updateIndex = res.data._id;
                        this.isEmpty = false;
                        this.timeUpdate(res.data.updated_at);
                    } else if(res.status==422) {
                            console.log(res.data.message);
                    } else {
                        console.log(res);
                    }
                } else {
                    this.setError('JSON format required');
                }   
            }
        },
        async update(){
            if(this.data.finalData.trim()=='' && this.updateIndex.trim()==''){
                this.setError('Data Required');
                console.log(`data:${this.data.finalData.trim()} & index:${this.updateIndex.trim()}`);
            } else {
                this.resetError();
                const updateData = {finalData:this.data.finalData.trim(), _id:this.updateIndex.trim()};
                if(this.isJson(this.data.finalData.trim())){
                    const res = await this.callApi('post','/update',updateData);
                    if(res.status==200){
                        this.getData.finalData = updateData.finalData;
                        this.timeUpdate(new Date());
                    } else if(res.status==422) {
                            console.log(res.data.message);
                    } else {
                        console.log(res);
                    }
                } else {
                    this.setError('JSON format required');
                }   
            }
        },
        setError(str){
            this.error=true;
            this.errorMsg=str;
        },
        resetError(){
            this.error=false;
            this.errorMsg='';
        },
        isJson(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        },
        toJson(str){
            return JSON.parse(str);
        },
        timeUpdate(updated_at){
            if(updated_at){
            var ts = new Date(updated_at);
            //console.log(ts.toDateString());
            //console.log(ts.toLocaleString());
            this.updatedAt = ts.toLocaleString();
            } else {
                this.updatedAt = '';
            }
        },
            
    },
}
</script>
