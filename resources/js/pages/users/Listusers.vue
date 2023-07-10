<script setup>

    import { onMounted,ref,reactive } from 'vue'
    import axios from 'axios' 
    import {Form,Field} from 'vee-validate'
    import * as yup from 'yup'


    const users =  ref([])
    const editing = ref(false)
    const formValues = ref()
    const form  = ref(null)

    const getUsers = () =>{
        axios.get('/api/users')
        .then(response => {
            users.value = response.data 
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

    

    const createuser = (values) =>{
        axios .post('/api/users',values).then((response) =>{
            
            $('#createUserModal').modal('hide')
            form.value.resetForm() 
            getUsers()
        }).catch((error) =>{
            console.log(error)
        })
    }
 
    const updateUser = (values) =>{
         
        axios.put('/api/users/'+formValues.value.id,values).then((response) =>{

            // short way with not hitting api again
            const index = users.value.findIndex(user =>user.id === response.data.id)
            users.value[index] = response.data

            $('#createUserModal').modal('hide')
            // getUsers()
        }).catch((error) =>{
            console.log(error)
        }).finally(() =>{
            editing.value = false
            form.value.resetForm()
        })
         
        form.value.resetForm()
        $('#createUserModal').modal('hide')
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

    const handleSubmit = (values) =>{
        if(editing.value){
            updateUser(values)
        }else{
            createuser(values )
        }
    }

    const closeModal= ()=>{
        editing.value = false
        $('#createUserModal').modal('hide')
        formValues.value = ''
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
             <button type="button" class="mb-4 btn btn-primary" @click="addUser">
                Add new User
            </button>
             <div class="card">
                <div class="card-body">
                    <table class="table table-light table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registered</th>
                                <th>Role</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users" :key="user.id">
                                <td>{{ index +1 }}</td>
                                <td>{{user.name}}</td>
                                <td>
                                    <a href="mailto:{{user.email }}" target="_blank">{{ user.email }} </a>
                                </td>
                                <td>{{ user.created_at }}</td>
                                <td>2020-01-01</td>
                                <td><a href="#"
                                    @click.prevent="editUser(user)"
                                    >
                                    <i class="fa fa-edit"></i>
                                </a></td>

                            </tr>
                        </tbody>
                        
                    </table>
                </div>
             </div>


             <div class="modal fade" id="createUserModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
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