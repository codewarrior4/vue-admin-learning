<script setup>

    import { onMounted,ref,watch } from 'vue'
    import axios from 'axios' 
    import {Form,Field} from 'vee-validate'
    import * as yup from 'yup'
    import {useToastr} from '../../utils/toaster'
    import ListusersItem from './ListusersItem.vue'
    import { debounce } from 'lodash';
    import { Bootstrap4Pagination } from 'laravel-vue-pagination';

    const users =  ref({'data':[]})
    const editing = ref(false)
    const formValues = ref()
    const form  = ref(null)
    const toastr = useToastr()

    const getUsers = (page = 1) =>{
        axios.get(`/api/users?page=${page}`)
        .then(response => {
            users.value = response.data 
            selectAll.value = false
            selectedUsers.value= []
        })
        .catch(error => {
            console.log(error)
        })
    }
 

    const createUserSchema = yup.object({
        name: yup.string().required(),
        email: yup.string().required().email(),
        password: yup.string().required().min(8)
    })

    const editUserSchema = yup.object({
        name: yup.string().required(),
        email: yup.string().required().email(), 
        password: yup.string().when((password,schema)=>{
            if(password.length > 0){
                return schema.min(8)
            }else{
                return schema
            }
        })
    })

    

    const createuser = (values,{resetForm,setFieldError}) =>{
        axios .post('/api/users',values).then((response) =>{
            
            $('#createUserModal').modal('hide')
            // form.value.resetForm() 
            resetForm()
            getUsers()
            toastr.success('User created Successfully')
        }).catch((error) =>{
            if(error.response.data.errors){
                setFieldError('email',error.response.data.errors.email[0])
            }
        })
    }
 
    const updateUser = (values,{resetForm,setErrors}) =>{
         
        axios.put('/api/users/'+formValues.value.id,values).then((response) =>{

            // short way with not hitting api again
            const index = users.value.findIndex(user =>user.id === response.data.id)
            users.value[index] = response.data

            $('#createUserModal').modal('hide')
            resetForm()
            toastr.success('User updated Successfully')

            // getUsers()
        }).catch((error) =>{
            console.log(error)
            if(error.response.data.errors){
                setErrors(error.response.data.errors)
            } 
        }) 
    }

    const editUser = (user) => {
        editing.value = true
        form.value.resetForm() 
        $('#createUserModal').modal('show')
        formValues.value= {
            id:user.id,
            name:user.name,
            email:user.email
        } 
    }

    const addUser = () =>{
        editing.value = false
        $('#createUserModal').modal('show')
        
    }

    const handleSubmit = (values,actions) =>{
        console.log(actions);
        if(editing.value){
            updateUser(values,actions)
        }else{
            createuser(values,actions )
        }
    }

    const closeModal= ()=>{
        editing.value = false
        $('#createUserModal').modal('hide')
        formValues.value = ''
    }

    const searchQuery = ref(null)

    const search = () =>{
        axios.get('/api/users/search?query='+searchQuery.value)
        .then(response => {
            users.value = response.data 
        })
        .catch(error => {
            console.log(error)
        })
    }

    watch(searchQuery, debounce(() => {
        search();
    }, 300));

    
    const userDeleted = (userId) =>{
        const index = users.value.findIndex(user =>user.id === userId)
        users.value.splice(index,1)
    } 

    const selectedUsers = ref([])
    const toggleSelection = (user) =>{
        const index = selectedUsers.value.indexOf(user.id)
        if(index === -1){
            selectedUsers.value.push(user.id)
        } else{
            selectedUsers.value.splice(index,1)
        }
        console.log(selectedUsers.value)
    }

    const bulkDelete = () =>{
        axios.delete(`/api/users`,{
            data:{
                ids:selectedUsers.value
            }
        }).then((response) =>{
            getUsers()
            toastr.success(response.data.message)
            selectedUsers.value =[]
            selectAll.value=false
        }).catch((error)=>{
            console.log(error)
        })
    }

    const selectAll = ref(false)
    const selectAllUsers = () =>{
        if(selectAll.value){
            selectedUsers.value = users.value.data.map(user => user.id)

        } else{
            selectedUsers.value = []
        }
        console.log(selectedUsers.value)
         
    }

    const toggleAll = () =>{
        selectAll.value = !selectAll.value
        selectAllUsers()
    }
    onMounted(() => {
        getUsers()
    })
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">List User</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
             <!-- creeate a bootstrap datatable  -->
            
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                   
                    <button type="button" class="mb-4 btn btn-primary" @click="addUser">
                        <i class="fa fa-plus-circle mr-2"></i>
                        Add new User
                    </button>
                    <div class="ml-2" v-if="selectedUsers.length >0">
                        <button  type="button" class="mb-4 btn btn-danger mr-4" @click.prevent="bulkDelete">
                            <i class="fa fa-trash mr-2"></i>
                            Delete Selected 
                        </button>
                        <span class="ml-2">Selected {{selectedUsers.length}} users</span>
                    </div>
                </div>
                <div>
                    <input type="text" v-model="searchQuery" class="form-control" placeholder="Search..." />
                </div>
            </div>
             <div class="card">
                <div class="card-body">
                    <table class="table table-light table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th><input type="checkbox" @click.prevent="toggleAll" v-model="selectAll" @change="selectAllUsers"  /></th>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registered</th>
                                <th>Role</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody v-if="users.data.length > 0">
                            <ListusersItem v-for="(user,index) in users.data" :key="user.id" :user="user" :index="index" @user-deleted="userDeleted" @edit-user="editUser" @toggleSelection="toggleSelection" :select-all="selectAll" />
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="6" class="text-center">No users found</td>
                            </tr>
                        </tbody>
                        
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <Bootstrap4Pagination :data="users" :limit="10" :show-disabled="true" :align="'center'" @pagination-change-page="getUsers" />
                <!-- {{ pagination }} -->

            </div>


            <div class="modal fade" id="createUserModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">
                                <span v-if="editing">Edit User</span>
                                <span v-else>Add New User</span>
                            </h5>
                            <button type="button" class="close" @click="closeModal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <Form  ref="form" @submit="handleSubmit" :validation-schema="editing ? editUserSchema : createUserSchema" v-slot="{errors}" :initial-values="formValues">
                            <div class="modal-body"> 
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <Field type="text" name ="name" class="form-control " id="name"
                                        aria-describedby="nameHelp" placeholder="Enter full name" :class="{'is-invalid' :errors.name }" />
                                        <span class="invalid-feedback">{{ errors.name }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <Field type="email" name ="email" class="form-control " id="email"
                                        aria-describedby="nameHelp" placeholder="Enter full name" :class="{'is-invalid' :errors.email }" />
                                        <span class="invalid-feedback">{{ errors.email }}</span>
                                </div> 

                                <div class="form-group">
                                    <label for="email">Password</label>
                                    <Field type="password" name ="password" class="form-control " id="password"
                                        aria-describedby="nameHelp" placeholder="Enter password" :class="{'is-invalid' :errors.password }" />
                                        <span class="invalid-feedback">{{ errors.password }}</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </Form>
                    </div>
                </div>        
            </div>

             
            

        </div>
    </div>   
</template>