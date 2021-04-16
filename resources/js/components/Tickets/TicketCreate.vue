<template>
  <div class="card shadow">
    <div class="card-header">
      <div class="d-flex flex-wrap justify-content-between">
        <div class="mr-auto"></div>
        <div class="ml-auto">
          <a href="/ticket" class="btn btn-sm btn-info text-white">Volver al listado</a>
        </div>
      </div>
    </div>

    <div class="card-body">
      <ticket-form
        :customer="customer ? customer : null"
        :ticket="ticket"
        :editable="true"
        buttonText="Crear Ticket"
        type="new"
        @created="ticketCreated"
      />
    </div>
  </div>
</template>

<script>
import TicketForm from "./TicketForm.vue";
export default {
  components: { TicketForm },
  props: ["customer"],
  data() {
    return {
      ticket: {
        customer: {},
        department_type: {},
      },
    };
  },
  methods: {
    ticketCreated() {
      window.location = "/ticket";
    },
  },
};
</script>

<style>
</style>