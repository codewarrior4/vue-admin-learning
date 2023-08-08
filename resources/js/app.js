import './bootstrap';
import 'admin-lte/plugins/jquery/jquery.min.js'
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js'
import 'admin-lte/dist/js/adminlte.min.js'
import { createApp } from 'vue/dist/vue.esm-bundler';
import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routes'
import Login from './pages/auth/Login.vue'
import BarChart from './components/BarChart.vue'

const app = createApp({})

const router = createRouter({
    routes: Routes,
    history:createWebHistory()
})

app.use(router)

app.component('Login', Login)
app.component('BarChart', BarChart)

app.mount('#app')