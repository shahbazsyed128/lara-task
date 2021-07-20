<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Employees Component 
                        <button class="btn btn-success float-right" @click="newModal">Add New <i class="fa fa-plus-square"></i></button>
                    </div>

                    <div class="card-body">
                       <table class="table table-hover">
                           <thead>
                                <tr>
                                     <th scope="col">#</th>
                                    <th scope="col"><i class="fas fa-image"></i> Logo</th>
                                    <th scope="col"><i class="fas fa-building"></i> Employee Name</th>
                                    <th scope="col"><i class="fas fa-at"></i> Email</th>
                                    <th scope="col"><i class="fas fa-link"></i> phone</th>
                                    <th scope="col"><i class="fas fa-history"></i> Registered At</th>
                                    <th scope="col"><i class="fas fa-cog"></i> Modify</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="employee in employees" :key="employee.id">
                                    <td>{{ employee.id}}</td>
                                    <td><img :src="'storage/'+employee.logo" height="50" width="50" alt=""></td>
                                    <td>{{ employee.name}}</td>
                                    <td>{{ employee.email}}</td>
                                    <td><a v-bind:href="`${employee.phone}`" >{{ employee.phone}}</a></td>
                                    <td>{{ employee.created_at | myDate}}</td>      
                                    
                                    <td>
                                        <a href="#" @click="editModal(employee)">
                                            <i class="fa fa-edit text-blue"></i>
                                        </a>
                                        /
                                        <a href="#" @click="deleteEmployee(employee.id)">
                                            <i class="fa fa-trash text-red"> </i>
                                        </a>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="employeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
            <h5 class="modal-title"  v-show="editmode"  id="employeeModalLabel">Update Employee's Info</h5>
            <h5 class="modal-title"  v-show="!editmode" id="employeeModalLabel">Add New Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form @submit.prevent="editmode ? updateEmployee() : createEmployee()" @keydown="form.onKeydown($event)">
      <div class="modal-body">
           
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input id="first_name" v-model="form.first_name" type="text" name="first_name" class="form-control">
                <HasError :form="form" field="first_name" />
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input id="last_name" v-model="form.last_name" type="text" name="last_name" class="form-control">
                <HasError :form="form" field="last_name" />
            </div>
            <div class="mb-3">
                <label for="company" class="form-label">Company</label>
                <select v-model="form.company_id" id="company"  name="company_id" class="form-control" >
                    <option value="">Select Company</option>
                    <option v-for="company in companies" :key="company.id" :value="company.id">{{company.name}}</option>
                </select>
                <HasError :form="form" field="company_id" />
            </div>    
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" v-model="form.email" type="email" name="email" class="form-control">
                <HasError :form="form" field="email" />
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input id="phone" v-model="form.phone" type="text" name="phone" class="form-control">
                <HasError :form="form" field="phone" />
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <Button :form="form" v-show="editmode" class="btn btn-success">Update</Button>
        <Button :form="form" v-show="!editmode" class="btn btn-primary">Create</Button>
      </div>
       </form>
    </div>
  </div>
</div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                editmode : false,
                employees:{},
                companies:{},
                form: new Form({
                    id:'',
                    first_name:'',
                    last_name:'',
                    company_id:'',
                    email:'',
                    phone:'',
                }),
            }
        },
        methods:{
            loadCompaines(){
                axios.get('api/company').then(({data}) =>  this.companies = data.data);
            },
            loadEmployees(){
                axios.get('api/employee').then(({data}) =>  this.employees = data.data);
            },
            createEmployee(){
                this.$Progress.start()
                this.form.post('/api/employee').then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#employeeModal').modal('hide')
                    Toast.fire({
                        icon:  'success',
                        title: 'Employee Created Successfully'
                    });
                    this.$Progress.finish()
                })
                .catch(()=>{
                     this.$Progress.fail()
                });
                
            },
            deleteEmployee(id){
                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.form.delete('api/employee/'+id).then(()=>{
                            Fire.$emit('AfterCreate');
                            Swal.fire(
                                'Deleted!',
                                'Your Employee has been deleted.',
                                'success'
                                )
                            
                        })
                        .catch(()=>{
                            Swal.fire(
                                'Failed!',
                                'There was something Wrong.',
                                'warning'
                            )
                        })
                    }
                })
            },
            updateEmployee(id){
                this.$Progress.start();
                this.form.put('api/employee/'+this.form.id)
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    Swal.fire(
                        'Updated!',
                        'Your User Info has been Updated.',
                        'success'
                    )
                    $('#employeeModal').modal('hide')
                    this.$Progress.finish();
                })
                .catch(()=>{
                    this.$Progress.fail();
                })
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#employeeModal').modal('show');  
            },
            editModal(employee){
                this.editmode = true;
                this.form.reset();
                $('#employeeModal').modal('show'); 
                this.form.fill(employee);
            },
           
        },
        created() {
            this.loadCompaines();
            this.loadEmployees();
           Fire.$on('AfterCreate',()=>{
                this.loadEmployees();
            })
        }
    }
</script>
