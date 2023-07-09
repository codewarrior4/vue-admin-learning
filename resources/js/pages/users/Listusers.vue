<script setup>

    import { onMounted,ref,reactive } from 'vue'
    import axios from 'axios' 


    const users =  ref([])

    const getUsers = () =>{
        axios.get('/api/users')
        .then(response => {
            users.value = response.data
            console.log(users.value);
        })
        .catch(error => {
            console.log(error)
        })
    }

    const form  = reactive({
        name:'',
        email:'',
        password:''
    })

    const createuser =()=>{
        axios .post('/api/users',form).then((response) =>{
            
            $('#createUserModal').modal('hide')
            form.name = ''
            form.email = ''
            form.password = ''
            getUsers()
        }).catch((error) =>{
            console.log(error)
        })
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
             <button type="button" class="mb-4 btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                Add New User
            </button>
             <div class="card">
                <div class="card-body">
                    <table class="table table-light table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users" :key="user.id">
                                <td>{{ index +1 }}</td>
                                <td>{{user.name}}</td>
                                <td>
                                    <a href="mailto:{{user.email }}" target="_blank">{{ user.email }} </a>
                                </td>
                                <td>2020-01-01</td>
                                <td>2020-01-01</td>

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
                    <h5 class="modal-title" id="staticBackdropLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form autocomplete="off">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" v-model="form.name" class="form-control " id="name"
                                aria-describedby="nameHelp" placeholder="Enter full name">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" v-model="form.email" class="form-control " id="email"
                                aria-describedby="nameHelp" placeholder="Enter full name">
                        </div>
                    </form>

                    <div class="form-group">
                        <label for="email">Password</label>
                        <input type="password" v-model="form.password" class="form-control " id="password"
                            aria-describedby="nameHelp" placeholder="Enter password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" @click="createuser" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

             

        </div>
    </div>   
</template>