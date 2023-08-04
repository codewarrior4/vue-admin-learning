<script setup>
import axios from 'axios';
import {reactive,onMounted,ref} from 'vue'
import { useToastr } from '../../utils/toaster';
import {useRouter,useRoute} from 'vue-router'
import {Form } from 'vee-validate'
import flatpickr from 'flatpickr'
import 'flatpickr/dist/themes/light.css'



const editMode = ref(false)

const form = reactive({
    title:'',
    client_id:'',
    start_date:'',
    end_date:'',
    description:''
})


const router = useRouter()
const route = useRoute()

const toaster = useToastr()
const clients = ref()

const handleSubmit = (values, action) =>{
    if(editMode){
        editAppointment(values,action)
    } else{
        createAppointment(values,action)
    }
   
}

const createAppointment = (values, action) => {
    axios.post('/api/appointments/create',form)
    .then(response => {
        toaster.success(response.data.message)
        router.push('/admin/appointments')
    })
    .catch((error) =>{
        action.setErrors(error.response.data.errors)
    })
}

const editAppointment = (values,action) => {
    axios.put(`/api/appointments/${route.params.id}`,form)
    .then(response => {
        toaster.success(response.data.message)
        router.push('/admin/appointments')
    })
    .catch((error) =>{
        action.setErrors(error.response.data.errors)
    })
}

const getclients = () =>{
    axios.get('/api/clients')
    .then(response => {
        clients.value = response.data
    })
    .catch((error) =>{
        console.log(error.response.data)
    })
}

const getAppointment = () =>{
    axios.get(`/api/appointments/${route.params.id}/edit`)
    .then(response => {
        form.title = response.data.title
        form.client_id = response.data.client_id
        form.start_date = response.data.formattedStart
        form.end_date = response.data.formattedEnd
        form.description = response.data.description
    })
    .catch((error) =>{
        console.log(error.response.data)
    })

} 

onMounted(() =>{
    flatpickr(".flatpickr",{
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today", 
        disableMobile: "true",
        defaultHour:10, 
    })
    getclients()
    if(route.name === 'admin.appointments.edit'){
        editMode.value = true
        getAppointment()
    }
})
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <span v-if="editMode">Edit</span>
                        <span v-else>Create</span>
                        
                        Appointment</h1>
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
                                            <select id="client" v-model="form.client_id" class="form-control" :class="{'is-invalid':errors.client_id}"  >
                                                <option v-for="client in clients" :key="client.id" :value="client.id">{{client.firstname}} {{ client.lastname }}</option>
                                            </select>
                                            <span class="invalid-feedback">{{ errors.client_id}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date">Start Date</label>
                                            <input type="date" v-model="form.start_date" class="form-control flatpickr" id="date" :class="{'is-invalid' : errors.start_date}" >
                                            <span class="invalid-feedback">{{ errors.start_date }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="time">End Date</label>
                                            <input type="date" v-model="form.end_date" class="form-control flatpickr" id="time" :class="{'is-invalid' : errors.end_date}" >
                                            <span class="invalid-feedback">{{ errors.end_date }}</span>
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