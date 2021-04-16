<template>
  <div class="d-flex flex-column justify-content-center pb-5">
    <div class="card shadow">
      <div class="card-header">
        <div class="d-flex justify-content-between flex-row align-items-center">
          <div class="mr-auto">
            <span class="font-weight-bold text-uppercase"
              ># {{ customer.id }}</span
            >
          </div>
          <div class="ml-auto">
            <a href="/customer" class="btn btn-sm btn-info text-white"
              >Volver al listado</a
            >
          </div>
        </div>
      </div>
      <div class="card-body">
        <customer-form
          buttonText="Actualizar Cliente"
          :customer="customer"
          :editable="true"
          @success="redirectToTicket"
        />
        <hr />
        <div class="d-flex flex-wrap justify-content-start mt-3">
            <user-form-card v-for="user in customer.users" :key="user.id" :user="user" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import UserFormCard from '../Users/UserFormCard.vue';
import CustomerForm from "./CustomerForm.vue";

export default {
  components: { CustomerForm, UserFormCard },
  props: ["customer"],
  methods: {
    redirectToTicket() {
      window.location = "/customer";
    },
  },
};
</script>

<style>
</style>