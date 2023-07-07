<script setup>

    import { onMounted,ref } from 'vue'
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


             

        </div>
    </div>   
</template>