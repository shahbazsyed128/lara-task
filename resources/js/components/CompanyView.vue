<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Company View Component 
                    </div>
                    <div class="card-body">
                        <div v-if="company_id">
                            <div class="row">
                                <div class="col-sm-2">
                                    <img :src="'storage/'+company.logo" class="rounded-circle" height="100" width="100" alt="">
                                </div>
                            </div>
                            <table style="border-spacing: 5px; border-collapse: unset;">
                                <tr>
                                    <th><i class="fa fa-user text-blue"></i> Name:</th>
                                    <td>{{company.name}}</td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-at text-blue"></i> Email:</th>
                                    <td>{{company.email}}</td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-link text-blue"></i> Website:</th>
                                    <td><a :href="company.website">{{company.website}}</a></td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-users text-blue"></i> Employess:</th>
                                    <td> <span v-for="employee in company.employee" :key="employee.id"><i class="fa fa-user text-blue"> </i> : {{ employee.first_name }} {{ employee.last_name }} , </span></td>
                                </tr>
                            </table>
                        </div>
                        <div v-else >
                                <h1 class="text-danger text-center">No Company ID Defined</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
          props: ['company_id'],
        data(){
            return {
                company:{},
            }
        },
        methods:{
            
            loadCompay(){
                if(this.company_id){
                    axios.get('api/company/'+this.company_id).then(({data}) =>  this.company = data);
                }  
              
            },
         
        },
        created() {
           this.loadCompay();
        }
    }
</script>
