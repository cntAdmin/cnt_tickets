<template>
  <div class="card shadow">
    <div class="card-header">
      <div class="d-flex flex-wrap justify-content-between">
        <div class="mr-auto"></div>
        <div class="ml-auto">
          <a href="/ticket-status" class="btn btn-sm btn-info text-white"
            >Volver al listado</a
          >
        </div>
      </div>
    </div>

    <div class="card-body">
      <ticket-status-form
        :ticketStatus="ticketStatus"
        :editable="true"
        buttonText="Crear Estado"
        type="new"
        @created="created"
      />
    </div>
  </div>
</template>

<script>
import TicketStatusForm from "./TicketStatusForm.vue";
export default {
  components: { TicketStatusForm },
  data() {
    return {
      ticketStatus: {},
    };
  },
  methods: {
    created() {
      window.location = "/ticket-status";
    },
  },
};
</script>

<style>
</style>