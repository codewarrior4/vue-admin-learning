import Dashboard from './components/Dashboard.vue'
import Appointments from './pages/appointments/Listappointments.vue'
import AppointmentsCreate from './pages/appointments/AppointmentsCreate.vue'
import Users from './pages/users/Listusers.vue'
import UpdateSettings from './pages/settings/UpdateSettings.vue'
import UpdateProfile from './pages/profile/UpdateProfile.vue'
import Login from './pages/auth/Login.vue'


export default [
    {
        path:'/login',
        name:'admin.login',
        component:Login
    },
    {
        path:'/admin/dashboard',
        name:'admin.dashboard',
        component:Dashboard
    },
    {
        path:'/admin/appointments',
        name:'admin.appointments',
        component:Appointments
    },
    {
        path:'/admin/appointments/create',
        name:'admin.appointments.create',
        component:AppointmentsCreate
    },
    {
        path:'/admin/appointments/:id/edit',
        name:'admin.appointments.edit',
        component:AppointmentsCreate
    },
    {
        path:'/admin/users',
        name:'admin.users',
        component:Users
    },
    {
        path:'/admin/settings',
        name:'admin.settings',
        component:UpdateSettings
    },
    {
        path:'/admin/profile',
        name:'admin.profile',
        component:UpdateProfile
    },

]