<script setup>
    import {formatDate} from '../../utils/helper'
    import { ref } from 'vue'
    import {useToastr} from '../../utils/toaster'
    
    const usertoDelete = ref(null)

    defineProps({
        user:Object,
        index:Number
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

    const emit = defineEmits(['userDeleted','editUser'])

    const toastr = useToastr()

    const deleteUserPrompt = (user) =>{
        usertoDelete.value = user.id
        $('#deleteUserModal').modal('show') 
    }

    const deleteUser = () =>{
        axios.delete(`/api/users/${usertoDelete.value}`).then((response) =>{
            $('#deleteUserModal').modal('hide')
            getUsers()
            toastr.success('User deleted Successfully')
            emit('userDeleted',usertoDelete.value)
        }).catch((error) =>{
            console.log(error)
        })
    }

    // const editUser = (user) =>{
    //     emit('editUser',user)
    // }
</script>

<template>
    <tr>
        <td>{{ index +1 }}</td>
        <td>{{user.name}}</td>
        <td>
            <a href="mailto:{{user.email }}" target="_blank">{{ user.email }} </a>
        </td>
        <td>{{ formatDate(user.created_at) }}</td>
        <td> {{ user.role }}</td>
        <td>
            <a href="#"  @click.prevent="$emit('editUser',(user))">
                <i class="fa fa-edit"></i>
            </a>
            <a href="#" @click.prevent="deleteUserPrompt(user)">
                <i class="fa fa-trash text-danger ml-2"></i>
            </a>
        </td>

    </tr>


    <div class="modal fade" id="deleteUserModal" data-backdrop="static" tabindex="-2" role="dialog"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">
                                <span>Delete User</span>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h5>Are you sure you want to delete this user ?</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button @click.prevent="deleteUser" type="button" class="btn btn-primary">Delete User</button>
                        </div>
                    </div>
                </div>
            </div>
</template>