<template>
  <div class="d-flex flex-column justify-content-center pb-5">
    <div class="card shadow">
      <div class="card-header">
        <div class="d-flex justify-content-between flex-row align-items-center">
          <div class="mr-auto">
          <span class="font-weight-bold text-uppercase">{{ ticket.custom_id }}</span>
          </div>
          <div class="ml-auto">
            <a :href="`/ticket`" class="btn btn-sm btn-info text-white">Volver al listado</a>
            <a :href="`/ticket/${ticket.id}`" class="btn btn-sm btn-success">Ver Ticket</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <ticket-form :ticket="ticket" :editable="true" @updated="redirectToTicket" buttonText="Actualizar Ticket"/>
      </div>
    </div>
    <attachments :attachments="ticket.attachments" @attachmentDeleted="reloadTicket" />
  </div>
</template>

<script>
import Attachments from '../Attachments/Attachments.vue';
import TicketForm from './TicketForm.vue'
export default {
  components: { TicketForm, Attachments },
    props: ["ticket"],
    methods: {
      reloadTicket() {
        axios.get(`/ticket/${this.ticket.id}`)
          .then( res => {
            this.ticket = res.data.ticket
          }).catch( err => console.log(err.response.data))
      },
      redirectToTicket(){
        window.location = `/ticket/${this.ticket.id}`;
      }
    }
}
</script>

<style>

</style>