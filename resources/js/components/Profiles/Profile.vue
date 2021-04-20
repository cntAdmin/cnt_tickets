<template>
    <div class="mb-5 pb-5">
        <div class="card shadow mt-3" v-if="[1, 3].includes(user.roles[0].id) && permissions.find((permission) => permission.name == 'customer.update')">
            <div class="card-header">
                <span class="text-uppercase font-weight-bold">{{ user.customer.name }}</span>
            </div>
            <div class="card-body">
                <customer-form :customer="user.customer" :editable="true" buttonText="Actualizar Cliente" />
            </div>
        </div>
        <div class="card shadow mt-3" v-if="permissions.find((permission) => permission.name == 'user.update')">
            <div class="card-header">
                <span class="text-uppercase font-weight-bold" v-if="[1, 3].includes(userrole)">Usuarios</span>
                <span class="text-uppercase font-weight-bold" v-else >Usuario</span>
            </div>
            <div class="card-body">
                <user-form :userrole="userrole" :cardTemplate="false" :editable="true" :user="user" v-if="![1, 3].includes(user.roles[0].id)" />
                <div class="d-flex flex-wrap" v-else>
                    <user-form-card v-for="u in user.customer.users" :key="u.id" :user="u"/>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import CustomerForm from "../Customers/CustomerForm.vue"
import UserForm from '../Users/UserForm.vue'
import UserFormCard from '../Users/UserFormCard.vue'

export default {
  components: { CustomerForm, UserForm, UserFormCard },
  props: ["user", "permissions", "userrole"]
}
</script>

<style>

</style>