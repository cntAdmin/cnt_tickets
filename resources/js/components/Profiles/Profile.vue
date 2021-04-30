<template>
  <div class="mb-5 pb-5">
    <div
      class="card shadow mt-3"
      v-if="
        [1, 3].includes(user.roles[0].id) &&
        permissions.find(
          (permission) => permission.name == 'customer.update'
        ) &&
        user.customer_id
      "
    >
      <div class="card-header">
        <span class="text-uppercase font-weight-bold">{{
          user.customer ? user.customer.name : null
        }}</span>
      </div>
      <div class="card-body">
        <customer-form
          :customer="user.customer"
          :editable="true"
          buttonText="Actualizar Cliente"
        />
      </div>
    </div>
    <div
      class="card shadow mt-3"
      v-if="permissions.find((permission) => permission.name == 'user.update')"
    >
      <div class="card-header">
        <span
          class="text-uppercase font-weight-bold"
          v-if="user.customer.users.length > 1"
          >Usuarios</span
        >
        <span class="text-uppercase font-weight-bold" v-else>Usuario</span>
      </div>
      <div class="card-body">
        <user-form
          v-if="![1, 3].includes(user.roles[0].id)"
          buttonText="Actualizar usuario"
          :user-role="userRole"
          :cardTemplate="false"
          :editable="true"
          :user="user"
        />
        <div class="d-flex flex-wrap" v-else>
          <user-form-card
            v-for="u in user.customer.users"
            buttonText="Actualizar usuario"
            :key="u.id"
            :user="u"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CustomerForm from "../Customers/CustomerForm.vue";
import UserForm from "../Users/UserForm.vue";
import UserFormCard from "../Users/UserFormCard.vue";

export default {
  components: { CustomerForm, UserForm, UserFormCard },
  props: ["user", "permissions", "userRole"],
  beforeMount() {
    if (!this.user.customer) {
      this.user.customer = {
        users: [this.user],
      };
    }
  },
};
</script>

<style>
</style>