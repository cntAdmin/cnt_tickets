<template>
  <div class="card shadow">
    <div class="card-header">
      <div class="d-flex flex-wrap justify-content-between align-items-center">
        <div class="mr-auto align-items-center">
          <span class="text-uppercase font-weight-bold">{{
            ticketType.name
          }}</span>
        </div>
        <div class="ml-auto d-flex flex-wrap">
          <!-- MODAL DE FIRMA -->
          <signature-modal
            v-if="signatureModal && ticketType.id === 2"
            type="new"
            :ticket_id="ticket.id"
            @firmaEnviadaPorModal="pushSignature"
          />
          <button
            v-if="ticketType.id === 2"
            type="button"
            class="btn btn-sm btn-info text-white"
            title="Firma Digital"
            data-toggle="modal"
            data-target="#signatureModal"
            @click="signatureModal = true"
          >
            Firmar
          </button>

          <!-- MODAL REGISTRO DE TRABAJO -->
          <ticket-timeslots-modal
            v-if="ticketTimeslotModal && ticketType.id === 2"
            type="new"
            :ticket_id="ticket.id"
            @close="pushTimeslots"
          />
          <button
            v-if="ticketType.id === 2"
            type="button"
            class="btn btn-sm btn-warning ml-1"
            title="Registrar horas"
            data-toggle="modal"
            data-target="#ticketTimeslotsModal"
            @click="ticketTimeslotModal = true"
          >
            Registrar horas
          </button>

          <!-- VOLVER PAGINA PRINCIPAL -->
          <a href="/ticket" class="btn btn-sm btn-info text-white ml-1">
            Volver al listado
          </a>
        </div>
      </div>
    </div>

    <div class="card-body">
      <ticket-form
        v-if="ticketType.id === 1"
        buttonText="Crear Incidencia"
        type="new"
        :customer="customer ? customer : null"
        :ticket="ticket"
        :ticketType="ticketType"
        :editable="true"
        :userRole="userRole"
        @success="ticketCreated"
      />
      <work-report-form
        v-else-if="ticketType.id === 2"
        buttonText="Crear Parte de Trabajo"
        type="new"
        :customer="customer ? customer : null"
        :work-report="workReport"
        :ticket="ticket"
        :ticketType="ticketType"
        :timeslots="timeslots"
        :customerSign="ticketSignature"
        :editable="true"
        :userRole="userRole"
        @created="ticketCreated"
        @deleted="deleteTimeslots"
      />
    </div>
  </div>
</template>

<script>
import TicketForm from "./TicketForm.vue";
import TicketTimeslotsModal from "./TicketTimeslotsModal.vue";
import WorkReportForm from "./WorkReportForm.vue";
import SignatureModal from "../SignatureModal.vue";

export default {
  components: { TicketForm, WorkReportForm, TicketTimeslotsModal, SignatureModal },
  props: ["customer", "ticketType", "userRole"],
  data() {
    return {
      ticketTimeslotModal: false,
      signatureModal: false,
      timeslots: [],
      workReport: {
        customer: {},
        department_type: {},
        ticket_timeslots: {},
      },
      ticket: {
        customer: {},
        department_type: {},
        dates: [],
      },
      ticketSignature: null,
    };
  },
  mounted() {
    // console.log(this.ticketStatus);
  },
  methods: {
    pushTimeslots(data) {
      // console.log('TicketCreate.pushTimeslots.');
      this.timeslots.push({
        id: Math.random().toString(36).substring(7),
        start_date_time_picker: data.start_date_time,
        end_date_time_picker: null,
        work_time: data.work_time,
        inserted: 0,
      });
    },
    deleteTimeslots(data) {
      this.timeslots = this.timeslots.filter(
        (timeslot) => timeslot.id !== data.id
      );
    },
    ticketCreated() {
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