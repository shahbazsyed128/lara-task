<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Employee View Component 
                    </div>
                    <div class="card-body">
                        <div v-if="employee_id">
                            
                            <table style="border-spacing: 5px; border-collapse: unset;">
                                <tr>
                                    <th><i class="fa fa-user text-blue"></i> First Name:</th>
                                    <td>{{employee.first_name}}</td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-user text-blue"></i> Last Name:</th>
                                    <td>{{employee.last_name}}</td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-building text-blue"></i> Company:</th>
                                    <td>{{employee.company.name}}</td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-at text-blue"></i> Email:</th>
                                    <td>{{employee.email}}</td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-phone text-blue"></i> Phone:</th>
                                    <td><a :href="employee.phone">{{employee.phone}}</a></td>
                                </tr>
                            </table>
                        </div>
                        <div v-else >
                                <h1 class="text-danger text-center">No employee ID Defined</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
          props: ['employee_id'],
        data(){
            return {
                employee:{},
            }
        },
        methods:{
            
            loadEmployee(){
                if(this.employee_id){
                    axios.get('api/employee/'+this.employee_id).then(({data}) =>  this.employee = data);
                }  
              
            },
         
        },
        created() {
           this.loadEmployee();
        }
    }
</script>
