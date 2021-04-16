<template>
  <div class="card shadow">
    <div class="card-header">
      <div class="d-flex flex-wrap justify-content-between">
        <div class="mr-auto"></div>
        <div class="ml-auto">
          <a href="/customer" class="btn btn-sm btn-info text-white"
            >Volver al listado</a
          >
        </div>
      </div>
    </div>

    <div class="card-body">
      <customer-form
        :customer="customer"
        buttonText="Crear Cliente"
        :editable="true"
        type="new"
        @success="redirectToCustomers"
      />
    </div>
  </div>
</template>

<script>
import CustomerForm from "./CustomerForm.vue";
export default {
  components: { CustomerForm },
  data() {
    return {
      customer: {},
    };
  },
  methods: {
    redirectToCustomers() {
      window.location = "/customer";
    },
  },
};
</script>

<style>
</style>
CustomerForm