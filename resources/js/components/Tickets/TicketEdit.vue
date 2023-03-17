<template>
  <div class="d-flex flex-column justify-content-center pb-5">
    <div class="card shadow">
      <div class="card-header">
        <div class="d-flex justify-content-between flex-row align-items-center">
          <div class="mr-auto">
            <span class="font-weight-bold text-uppercase">{{ ticket.id }} - {{ ticket.ticket_type.name }}</span>
          </div>
          <div class="ml-auto">

            <!-- MODAL DE FIRMA -->
            <signature-modal
              v-if="signatureModal && ticket.ticket_type.id === 2"
              :ticket_id="ticket.id"
              @firmaEnviadaPorModal="pushSignature"
            />
            <button
              v-if="ticket.ticket_type.id === 2"
              type="button"
              class="btn btn-sm btn-info text-white"
              title="Firma Digital"
              data-toggle="modal"
              data-target="#signatureModal"
              @click="signatureModal = true"
            >
              Firmar
            </button>

            <!-- MODAL REGISTRO DE HORAS TRABAJO -->
            <ticket-timeslots-modal
              v-if="ticketTimeslotModal && ticket.ticket_type.id === 2"
              :ticket_id="ticket.id"
              @close="pushTimeslots"
            />
            <button
              v-if="ticket.ticket_type.id === 2"
              type="button"
              class="btn btn-sm btn-warning"
              title="Registrar horas"
              data-toggle="modal"
              data-target="#ticketTimeslotsModal"
              @click="ticketTimeslotModal = true"
            >
              Registrar horas
            </button>
            <!-- VER INCIDENCIA -->
            <a :href="`/ticket/${ticket.id}`" class="btn btn-sm btn-success">Ver Incidencia</a>
            <!-- VOLVER LISTADO DE INCIDENCIAS -->
            <a :href="`/ticket`" class="btn btn-sm btn-info text-white">Volver al listado</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <ticket-form
          v-if="ticket.ticket_type_id === 1"
          buttonText="Actualizar Incidencia"
          type="edit"
          :ticket="ticket"
          :editable="true"
          :user-role="userRole"
          :ticketType="ticket.ticket_type"
          @success="redirectToTicket"
        />
        <work-report-form
          v-if="ticket.ticket_type_id === 2"
          :buttonText="'Actualizar Parte de trabajo'"
          type="edit"
          :editable="true"
          :ticket="ticket"
          :ticketType="ticket.ticket_type"
          :timeslots="timeslots"
          :customerSign="ticketSignature"
          :customer="ticket.customer"
          :user-role="userRole"
          @deleted="deletedTimeslots"
          @updated="ticketUpdated"
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
import SignatureModal from "../SignatureModal.vue";

export default {
  components: { TicketForm, Attachments, TicketTimeslotsModal, WorkReportForm, SignatureModal },
  props: ["ticket", "userRole"],
  data() {
    return {
      ticketTimeslotModal: false,
      timeslots: [],
      attachments: [],
      signatureModal: false,
      ticketSignature: null,
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
        end_date_time_picker: null,
        work_time: data.work_time,
        inserted: 0,
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
    ticketUpdated() {
      window.location = "/ticket";
    },
    pushSignature(data){ 
      this.ticketSignature = data;
    } 
  },
};
</script>

<style>
</style>