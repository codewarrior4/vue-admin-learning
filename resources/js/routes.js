import Dashboard from './components/Dashboard.vue'
import Appointments from './pages/appointments/Listappointments.vue'
import Users from './pages/users/Listusers.vue'
import UpdateSettings from './pages/settings/UpdateSettings.vue'
import UpdateProfile from './pages/profile/UpdateProfile.vue'


export default [
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