<template>
  <div class="d-flex flex-column justify-content-center pb-5">
    <div class="card shadow">
      <div class="card-header">
        <div class="d-flex justify-content-between flex-row align-items-center">
          <div class="mr-auto">
            <span class="font-weight-bold text-uppercase"
              >{{ ticket.custom_id }} - {{ ticket.ticket_type.name }}</span
            >
          </div>
          <div class="ml-auto">
            <ticket-timeslots-modal
              v-if="ticketTimeslotModal && ticket.ticket_type.id === 2"
              :ticket_id="ticket.id"
              @close="pushTimeslots"
            />
            <button
              v-if="ticket.ticket_type.id === 2"
              type="button"
              class="btn btn-sm btn-warning"
              title="Borrar Ticket"
              data-toggle="modal"
              data-target="#ticketTimeslotsModal"
              @click="ticketTimeslotModal = true"
            >
              Añadir Fechas
            </button>
            <a :href="`/ticket/${ticket.id}`" class="btn btn-sm btn-success"
              >Ver Ticket</a
            >
            <a :href="`/ticket`" class="btn btn-sm btn-info text-white"
              >Volver al listado</a
            >
          </div>
        </div>
      </div>
      <div class="card-body">
        <ticket-form
          v-if="ticket.ticket_type_id === 1"
          buttonText="Actualizar Ticket"
          :ticket="ticket"
          :editable="true"
          :user-role="userRole"
          @updated="redirectToTicket"
        />
        <work-report-form
          v-if="ticket.ticket_type_id === 2"
          :buttonText="'Actualizar Parte'"
          :editable="true"
          :ticket="ticket"
          :ticketType="ticket.ticket_type"
          :timeslots="timeslots"
          :customer="ticket.customer"
          @deleted="deletedTimeslots"
        />
      </div>
    </div>
    <attachments
      :attachments="attachments"
      @attachmentDeleted="deleteAttachments"
    />
  </div>
</template>

<script>
import Attachments from "../Attachments/Attachments.vue";
import TicketForm from "./TicketForm.vue";
import TicketTimeslotsModal from "./TicketTimeslotsModal.vue";
import WorkReportForm from "./WorkReportForm.vue";
export default {
  components: { TicketForm, Attachments, TicketTimeslotsModal, WorkReportForm },
  props: ["ticket", "userRole"],
  data() {
    return {
      ticketTimeslotModal: false,
      timeslots: [],
      attachments: [],
    };
  },
  mounted() {
    this.timeslots = this.ticket.ticket_timeslots;
    this.attachments = this.ticket.attachments;
  },
  methods: {
    closeAll() {
      this.ticketTimeslotModal = false;
    },
    pushTimeslots(data) {
      this.closeAll();
      this.timeslots.push({
        start_date_time_picker: data.start_date_time,
        end_date_time_picker: data.end_date_time,
      });
    },
    deletedTimeslots(data) {
      this.timeslots = this.timeslots.filter(
        (timeslot) => timeslot.id !== data.id
      );
    },
    deleteAttachments(id) {
      this.closeAll();
      this.attachments = this.attachments.filter(
        (attachment) => attachment.id !== id
      );
    },
    reloadTicket(data) {
      this.closeAll();
      axios
        .get(`/api/ticket/${this.ticket}`)
        .then((res) => {
          this.ticket = res.data.ticket;
        })
        .catch((error) => console.log(error.response.data));
    },
    redirectToTicket() {
      window.location = `/ticket/${this.ticket.id}`;
    },
  },
};
</script>

<style>
</style>