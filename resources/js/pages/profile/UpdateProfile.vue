<script setup>

    import axios from 'axios';
    import {ref,onMounted} from 'vue'
    import { useToastr } from '../../utils/toaster';
    
    const errors = ref([])
    const toast = useToastr()
    const form = ref({
        name:'',
        email:'',
        role:''
    })

    const password = ref({
        current_password : '',
        new_password :'',
        confirm_password : ''
    })

    const getUser = () =>{
        axios.get('/api/profile')
        .then((response) => {
            form.value = response.data
        })

    }

    const updateProfile = () =>{
        errors.value = ''
        axios.put('/api/profile',form.value)
        .then((response)=>{
            toast.success(response.data.success);
        })
        .catch((error) => {
            if(error.response && error.response.status ===422){
                errors.value = error.response.data.errors
            }
        }).finally(()=>{
            password.value=[]
        })
    }

    const updatePassword = () =>{
        errors.value = ''
        axios.put('/api/password',password.value)
        .then((response)=>{
            toast.success(response.data.success);
        })
        .catch((error) => {
            if(error.response && error.response.status ===422){
                errors.value = error.response.data.errors
            }
        })
    }

    const fileInput = ref(null)
    const openFileInput = () =>{
        fileInput.value.click()
    }

    const profilepic = ref(null)

    const handleFileChange = (e) =>{
        const file = e.target.files[0]
        profilepic.value = URL.createObjectURL(file)


        const formData = new FormData();
        formData.append('profile_picture',file)
        axios.post('/api/profilepicture',formData)
        .then((response)=>{
            toast.success(response.data.success);
        }).catch((error)=>{
            toast.error(error.response.data.error);
        })
    }

    onMounted(() =>{
        getUser()
    })
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Update Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <input @change="handleFileChange" type="file" ref="fileInput"  class="d-none">
                                <img @click="openFileInput" class="profile-user-img img-circle" :src="profilepic ? profilepic : form.avatar" alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">{{ form.name }}</h3>

                            <p class="text-muted text-center">{{ form.role }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab"><i class="fa fa-user mr-1"></i> Edit Profile</a></li>
                                <li class="nav-item"><a class="nav-link" href="#changePassword" data-toggle="tab"><i class="fa fa-key mr-1"></i> Change
                                        Password</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="profile">
                                    <form  class="form-horizontal" @submit.prevent="updateProfile()">
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="inputName" v-model="form.name" placeholder="Name">

                                                <span class="text-sm text-danger" v-if="errors && errors.name">{{ errors.name[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control " id="inputEmail" v-model="form.email" placeholder="Email">

                                                <span class="text-sm text-danger" v-if="errors && errors.email">{{ errors.email[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-save mr-1"></i> Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane" id="changePassword">
                                    <form @submit.prevent="updatePassword()" class="form-horizontal">
                                        <div class="form-group row">
                                            <label for="currentPassword" class="col-sm-3 col-form-label">Current
                                                Password</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control " v-model="password.current_password" id="currentPassword" placeholder="Current Password">
                                                <span class="text-sm text-danger" v-if="errors && errors.current_password">{{ errors.current_password[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="newPassword" class="col-sm-3 col-form-label">New
                                                Password</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control " id="newPassword" v-model="password.new_password" placeholder="New Password">
                                                <span class="text-sm text-danger" v-if="errors && errors.new_password">{{ errors.new_password[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="passwordConfirmation" class="col-sm-3 col-form-label">Confirm
                                                New Password</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control " id="passwordConfirmation"  v-model="password.confirm_password" placeholder="Confirm New Password">
                                                <span class="text-sm text-danger" v-if="errors && errors.confirm_password">{{ errors.confirm_password[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-3 col-sm-9">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-save mr-1"></i> Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .profile-user-img {
       cursor: pointer;
       background-color: blue;
    }
</style>