<script setup>
import {ref,onMounted} from 'vue'
import BarChart from './BarChart.vue';

const selectedAppointmentStatus = ref('all');

const totalAppointmentCount = ref(0)

const getAppoitnmentsCount = () =>{
    axios.get('/api/stats/appointments',{
        params :{
            status : selectedAppointmentStatus.value,
        }
    }).then((response) =>{
        totalAppointmentCount.value = response.data.totalAppointmentCount
    })
}

const totalUsersCount = ref(0)
const selectedUsersStatus = ref('all');

const getUsersCount = () =>{
    axios.get('/api/stats/users',{
        params:{
            duration:selectedUsersStatus.value
        },
    }).then((response)=>{
        console.log("user count",response);
        totalUsersCount.value=response.data.totalUsersCount;
    });
}



onMounted(()=>{
    getAppoitnmentsCount()
    getUsersCount();
})
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Starter Page 123</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <h1>Charts Display</h1>
                <BarChart></BarChart>
            </div>
            <div class="row mt-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <div class="d-flex justify-content-between">
                                <h3>{{totalAppointmentCount}}</h3>
                                <select
                                v-model="selectedAppointmentStatus"
                                @change="getAppoitnmentsCount()"
                                    style="height: 2rem; outline: 2px solid transparent;" class="px-1 rounded border-0">
                                    <option value="">All</option>
                                    <option value="scheduled">Scheduled</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <p>Appointments</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <router-link to="/admin/appointments" class="small-box-footer">
                            View Appointments
                            <i class="fas fa-arrow-circle-right"></i>
                        </router-link>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <div class="d-flex justify-content-between">
                                <h3>{{ totalUsersCount }}</h3>
                                <select
                                @change="getUsersCount()"
                                v-model="selectedUsersStatus"
                                    style="height: 2rem; outline: 2px solid transparent;" class="px-1 rounded border-0">
                                    <option value="TODAY">Today</option>
                                    <option value="30">30 days</option>
                                    <option value="60">60 days</option>
                                    <option value="360">360 days</option>
                                    <option value="MTD">Month to Date</option>
                                    <option value="YTD">Year to Date</option>
                                </select>
                            </div>
                            <p>Users</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <router-link to="/admin/users" class="small-box-footer">
                            View Users
                            <i class="fas fa-arrow-circle-right"></i>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</template>