<script setup>
    import {ref,onMounted,computed } from 'vue'
    import axios from 'axios'
    import {formatDate} from '../../utils/helper'
    import Swal from 'sweetalert2'



    const selectedStatus = ref()
    const appointmentStatus =ref([])
    const getAppointmentStatus = async () => {
        try {
            const response = await axios.get('/api/appointments-status');
            appointmentStatus.value = response.data;
        } catch (error) {
            console.error(error);
        }
    };

    const appointments = ref([]);
    const getAppointments = (status) =>{
        const params ={}
        if(status){
            params.status = status
        }
        axios.get('/api/appointments',{params})
        .then(response=> {
            appointments.value = response.data
            selectedStatus.value = status
        })
        .catch(error => {
            console.log(error)
        })
    }


    const deleteAppointment = (id) =>{
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton:true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) =>{
            if(result.isConfirmed){
                axios.delete(`/api/appointments/${id}`)
                .then(response => {
                    Swal.fire(
                        'Deleted!',
                        'Appointment has been deleted.',
                        'success'
                    )
                    getAppointments()
                })
                .catch(error => {
                    console.log(error)
                })
            }
        })
    }
    
    onMounted(() =>{
        getAppointments()
        getAppointmentStatus()
    }) 
</script>

<template >
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Appointments</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">List Appointment</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <router-link to="/admin/appointments/create">
                                <button class="btn btn-primary"><i class="fa fa-plus-circle mr-1"></i> Add New
                                    Appointment</button>
                            </router-link>
                        </div>
                        <div class="btn-group">
                            <button @click.prevent="getAppointments()" type="button" class="btn" :class="[typeof selectedStatus === 'undefined' ? 'btn-secondary' : 'btn-default']">
                                <span class="mr-1">All</span>
                                <span class="badge badge-pill badge-info"></span>
                            </button> 

                            <button v-for="status in appointmentStatus" @click="getAppointments(status.value)" type="button" class="btn " :class="[selectedStatus === status.value ? 'btn-secondary' :'btn-default']">
                                <span class="mr-1">{{status.name}}</span>
                                <span class="badge badge-pill" :class="`badge-${status.color}`">{{ status.count }}</span>
                            </button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Client Name</th>
                                        <th scope="col">Start Time </th>
                                        <th scope="col">End Time</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr  v-for="(appointment,index) in appointments.data" :key="appointment.id">
                                        <td>{{ index+1 }}</td>
                                        <td>{{appointment.client.firstname}} {{appointment.client.lastname}}</td>
                                        <td>{{formatDate(appointment.start)}}</td>
                                        <td>{{formatDate(appointment.end)}}</td>
                                        <td>
                                            <span class="badge" :class="`badge-${ appointment.status.color}`">{{appointment.status.name}}</span>
                                        </td>
                                        <td>
                                            <router-link :to="`/admin/appointments/${appointment.id}/edit`">
                                                <i class="fa fa-edit mr-2"></i>
                                            </router-link>

                                            <a @click.prevent="(deleteAppointment(appointment.id))" href="">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>