<script setup>
import axios from 'axios';
import {reactive} from 'vue'
import { useToastr } from '../../utils/toaster';
import {useRouter} from 'vue-router'
import {Form } from 'vee-validate'


const form = reactive({
    title:'',
    client_id:'',
    start:'',
    end:'',
    description:''
})

const router = useRouter()

const toaster = useToastr()

const handleSubmit = (values, action) =>{
    axios.post('/api/appointments/create',form)
    .then(response => {
        toaster.success(response.data.message)
        router.push('/admin/appointments')
    })
    .catch((error) =>{
        action.setErrors(error.response.data.errors)
    })
}
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Appointment</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/appointments">Appointments</router-link>
                        </li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <Form @submit="handleSubmit" v-slot:default="{errors}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" v-model="form.title" class="form-control" id="title" :class="{'is-invalid' : errors.title}" placeholder="Enter Title">
                                            <span class="invalid-feedback">{{ errors.title }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="client">Client Name</label>
                                            <select id="client" v-model="form.client_id" class="form-control">
                                                <option>Client One</option>
                                                <option>Client Two</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date">Appointment Date</label>
                                            <input type="start" class="form-control" id="date">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="time">Appointment Time</label>
                                            <input type="time" class="form-control" id="time">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea v-model="form.description" class="form-control" id="description" rows="3"
                                        placeholder="Enter Description" :class="{'is-invalid' : errors.description}"></textarea>
                                        <span class="invalid-feedback">{{ errors.description }}</span>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>