<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Companies Component 
                        <button class="btn btn-success float-right" @click="newModal">Add New <i class="fa fa-plus-square"></i></button>
                    </div>
                    <div class="card-body">
                       <table class="table table-hover">
                           <thead>
                                <tr>
                                     <th scope="col">#</th>
                                    <th scope="col"><i class="fas fa-image"></i> Logo</th>
                                    <th scope="col"><i class="fas fa-building"></i> Company Name</th>
                                    <th scope="col"><i class="fas fa-at"></i> Email</th>
                                    <th scope="col"><i class="fas fa-link"></i> Website</th>
                                    <th scope="col"><i class="fas fa-history"></i> Registered At</th>
                                    <th scope="col"><i class="fas fa-cog"></i> Modify</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="company in companies.data" :key="company.id">
                                    <td>{{ company.id}}</td>
                                    <td><img :src="'storage/'+company.logo" class="rounded-circle" height="30" width="30" alt=""></td>
                                    <td>{{ company.name}}</td>
                                    <td>{{ company.email}}</td>
                                    <td><a v-bind:href="`${company.website}`" >{{ company.website}}</a></td>
                                    <td>{{ company.created_at | myDate}}</td>      
                                    
                                    <td>
                                        <router-link :to="{ name: 'company-view', params: { company_id: company.id } }" data-toggle="tooltip" data-placement="top" title="Show"><i class="fa fa-eye text-green"></i></router-link>
                                         / 
                                        <a href="#" @click="editModal(company)" data-toggle="tooltip" data-placement="top" title="Edit">
                                            <i class="fa fa-edit text-blue"></i>
                                        </a>
                                        /
                                        <a href="#" @click="deleteCompany(company.id)" data-toggle="tooltip" data-placement="top" title="Delete">
                                            <i class="fa fa-trash text-red"> </i>
                                        </a>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <pagination :data="companies" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal -->
<div class="modal fade" id="companyModal" tabindex="-1" role="dialog" aria-labelledby="companyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
            <h5 class="modal-title"  v-show="editmode"  id="companyModalLabel">Update Company's Info</h5>
            <h5 class="modal-title"  v-show="!editmode" id="companyModalLabel">Add New Company</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form @submit.prevent="editmode ? updateCompany() : createCompany()" @keydown="form.onKeydown($event)">
      <div class="modal-body">
           
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input id="name" v-model="form.name" type="text" name="name" class="form-control">
                <HasError :form="form" field="name" />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" v-model="form.email" type="email" name="email" class="form-control">
                <HasError :form="form" field="email" />
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" name="photo" @change="uploadFile" >
                        <input type="hidden" name="logo" v-model="form.logo">
                        <HasError :form="form" field="photo" />

                        <div v-if="form.progress">Progress: {{ form.progress.percentage }}%</div>
                    </div>
                    <div class="col-sm-6">
                        <img v-show="editmode" :src="logo" alt="logo.png" height="80" width="80">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="website" class="form-label">Website</label>
                <input id="website" v-model="form.website" type="text" name="website" class="form-control">
                <HasError :form="form" field="website" />
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
                companies:{},
                form: new Form({
                    id:'',
                    name:'',
                    email:'',
                    logo:'',
                    website:'',
                }),
                logo:'',
            }
        },
        methods:{
            getResults(page = 1) {
                axios.get('api/company?page=' + page)
                    .then(response => {
                        this.companies = response.data;
                    });
            },
            loadCompaines(){
                axios.get('api/company').then(({data}) =>  this.companies = data);
            },
            createCompany(){
                this.$Progress.start()
                this.form.post('/api/company').then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#companyModal').modal('hide')
                    Toast.fire({
                        icon:  'success',
                        title: 'Company Created Successfully'
                    });
                    this.$Progress.finish()
                })
                .catch(()=>{
                     this.$Progress.fail()
                });
                
            },
            deleteCompany(id){
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
                        this.form.delete('api/company/'+id).then(()=>{
                            Fire.$emit('AfterCreate');
                            Swal.fire(
                                'Deleted!',
                                'Your Company has been deleted.',
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
            updateCompany(id){
                this.$Progress.start();
                this.form.put('api/company/'+this.form.id)
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    Swal.fire(
                        'Updated!',
                        'Your Company Info has been Updated.',
                        'success'
                    )
                    $('#companyModal').modal('hide')
                    this.$Progress.finish();
                })
                .catch(()=>{
                    this.$Progress.fail();
                })
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#companyModal').modal('show');  
            },
            editModal(company){
                this.editmode = true;
                this.form.reset();
                $('#companyModal').modal('show'); 
                this.form.fill(company);
                this.logo = "storage/"+company.logo;
            },
            uploadFile(e){
                 if (! e.target.files.length) return;
                let file = e.target.files[0];
                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = evt => {
                    let img = new Image();
                    img.onload = () => {
                        if( img.width > 99 && img.height > 99 ){
                            this.form.logo = reader.result;
                            this.logo = reader.result;
                        }
                        else{
                            Swal.fire(
                                'Oops!',
                                'File must be minimum 100 x 100.',
                                'error'
                            )
                        }
                    }
                    img.src = evt.target.result;
                   
                }
            }
        },
        created() {
           this.loadCompaines();
           Fire.$on('AfterCreate',()=>{
                this.loadCompaines();
            })
        }
    }
</script>
