<script setup>
    import {formatDate} from '../../utils/helper'
    import { ref } from 'vue'
    import {useToastr} from '../../utils/toaster'
    


    const props = defineProps({
        user:Object,
        index:Number,
        selectAll:Boolean
    })

    const getUsers = () =>{
        axios.get('/api/users')
        .then(response => {
            users.value = response.data 
        })
        .catch(error => {
            console.log(error)
        })
    }

    const emit = defineEmits(['userDeleted','editUser','deleteUserPrompt'])

    const toastr = useToastr()

   

    // const editUser = (user) =>{
    //     emit('editUser',user)
    // }

    const roles = ref([
        {
            name:'ADMIN',
            value:1
        },
        {
            name:'USER',
            value:2
        }
    ])

    const changeRole =(user,role) =>{
        axios.put(`/api/users/${user.id}/role`,{role:role}).then((response) =>{
            toastr.success('User role updated Successfully')
        }).catch((error) =>{
            console.log(error)
        })
    }

    const toggleSelection = () => {
        emit('toggleSelection',props.user)
    }
</script>

<template>
    <tr>
        <td><input type="checkbox" :checked="selectAll" @change="toggleSelection"/></td>
        <td>{{ index +1 }}</td>
        <td>{{user.name}}</td>
        <td>
            <a :href="`mailto:${user.email}`" target="_blank">{{ user.email }} </a>
        </td>
        <td>{{ user.formatted_created_at }}</td>
        <td> 
            <select class="form-control" @change="changeRole(user,$event.target.value)">
                <option v-for="role in roles" :value="role.value" :selected="(user.role === role.name)">{{role.name}}</option>
            </select>
        </td>
        <td>
            <a href="#"  @click.prevent="$emit('editUser',(user))">
                <i class="fa fa-edit"></i>
            </a>
            <a href="#" @click.prevent="$emit('deleteUserPrompt',user.id)">
                <i class="fa fa-trash text-danger ml-2"></i>
            </a>
        </td>

    </tr>


    

            
</template>