<template>
  <div class="d-flex flex-column justify-content-center pb-5">
    <div class="card shadow">
      <div class="card-header">
        <div class="d-flex justify-content-between flex-row align-items-center">
          <div class="mr-auto">
            <span class="font-weight-bold text-uppercase"># {{ ticketStatus.id }}</span>
          </div>
          <div class="ml-auto">
            <a :href="`/ticket-status`" class="btn btn-sm btn-info text-white">Volver al listado</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <ticket-status-form
          buttonText="Actualizar Estado"
          :ticketStatus="ticketStatus"
          :editable="true"
          @updated="redirectToTicketStatus"
        />
      </div>
    </div>
  </div>
</template>

<script>
import TicketStatusForm from "./TicketStatusForm.vue";
export default {
  components: { TicketStatusForm },
  props: ["ticketStatus"],
  methods: {
    redirectToTicketStatus() {
      window.location = "/ticket-status";
    },
  },
};
</script>