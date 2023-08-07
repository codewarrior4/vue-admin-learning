<script setup>
    import axios from 'axios';
    import {Form } from 'vee-validate'
    import {reactive} from 'vue'

    const form = reactive({
        email: '',
        password: ''
    })

    const handleSubmit = (value,actions) =>{
        axios.post('/login',form).
        then(() => {
            window.location.href = '/admin/dashboard'
        }).catch((error) =>{
        actions.setErrors(error.response.data.errors)
    })

    }
</script>

<template>

<div class="login-box">

<div class="card card-outline card-primary">
    <div class="card-header text-center">
        <a href="#" class="h1"><b>Admin</b>Login</a>
    </div>
    <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <Form  @submit="handleSubmit" v-slot:default="{errors}">
            <div class="input-group mb-3">
                <input v-model="form.email" type="email" class="form-control" placeholder="Email" :class="{'is-invalid' : errors.email}" >
                
                <span class="invalid-feedback">{{ errors.email }}</span>
            </div>
            <div class="input-group mb-3">
                <input v-model="form.password" type="password" class="form-control" placeholder="Password" :class="{'is-invalid' : errors.password}" > 
                <span class="invalid-feedback">{{ errors.password }}</span>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                        <input type="checkbox" id="remember">
                        <label for="remember">
                            Remember Me
                        </label>
                    </div>
                </div>

                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </div>

            </div>
        </Form>

        <p class="mb-1">
            <a href="forgot-password.html">I forgot my password</a>
        </p>
    </div>

</div>
</div>
</template>