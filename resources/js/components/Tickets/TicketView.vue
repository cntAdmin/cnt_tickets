<template>
  <div class="d-flex flex-column justify-content-center pb-5">
    <div class="card shadow">
      <div class="card-header">
        <div class="d-flex justify-content-between flex-row align-items-center">
          <div class="mr-auto">
            <span class=" text-uppercase">
              <span class="font-weight-bold">{{ ticket.id }}</span><span class="ml-2">({{ ticket.created_at | moment("DD-MM-YYYY HH:mm:ss") }})</span>
            </span>
          </div>
          <div class="ml-auto">
            <a v-if="permissions.find((permission) => permission.name == 'ticket.update')"
              :href="`/ticket/${ticket.id}/editar`"
              class="btn btn-sm btn-warning"
              >Editar</a
            >
            <a v-if="permissions.find((permission) => permission.name == 'ticket.show')"
              href="/ticket"
              class="btn btn-sm btn-info text-white"
              >Volver al listado</a
            >
          </div>
        </div>
      </div>
      <div class="card-body">
        <ticket-form
          v-if="ticket.ticket_type.id === 1"
          :customer="ticket.customer ? ticket.customer : null"
          :ticket="ticket"
          :ticketType="ticket.ticket_type"
          :editable="false"
          :userRole="userRole"
          :type="type ? type : ''"
        />
        <work-report-form
          v-else-if="ticket.ticket_type.id === 2"
          :customer="ticket.customer ? ticket.customer : null"
          :ticket="ticket"
          :ticketType="ticket.ticket_type"
          :timeslots="ticket.ticket_timeslots"
          :editable="false"
          :userRole="userRole"
        />
      </div>
    </div>
    <attachments
      :attachments="attachments"
      @attachmentDeleted="getTicketAttachments"
    />

    <comments :comments="comments" @commentDeleted="getTicketComments" :user-role="userRole" />

    <new-comment :ticket="ticket" @submitted="getTicketComments" :user-role="userRole" />
  </div>
</template>

<script>
import Comments from "../Comments/Comments.vue";
import NewComment from "../Comments/NewComment.vue";
import Attachments from "../Attachments/Attachments.vue";
import TicketForm from "./TicketForm.vue";
import WorkReportForm from './WorkReportForm.vue';
import Spinner from '../Spinner.vue';

export default {
  components: { TicketForm, Attachments, Comments, NewComment, WorkReportForm, Spinner },
  props: ["ticket", "permissions", "userRole", "type"],
  data() {
    return {
      comments: [],
      attachments: [],
    };
  },
  beforeMount() {
    if(!this.permissions) {
      this.permissions = [];
    }
  },
  mounted() {
    this.comments = this.ticket.comments;
    this.attachments = this.ticket.attachments;
  },
  methods: {
    getTicketAttachments() {
      axios
        .get(`/api/ticket/${this.ticket.id}/attachment`)
        .then((res) => {
          this.attachments = res.data.attachments;
        })
        .catch((err) => console.log(err.response.data));
    },
    getTicketComments() {
      axios
        .get(`/api/ticket/${this.ticket.id}/comment`)
        .then((res) => {
          this.comments = res.data.comments;
        })
        .catch((err) => console.log(err.response.data));
    },
  },
};
</script>