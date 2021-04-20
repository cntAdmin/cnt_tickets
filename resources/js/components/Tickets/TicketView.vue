<template>
  <div class="d-flex flex-column justify-content-center pb-5">
    <div class="card shadow">
      <div class="card-header">
        <div class="d-flex justify-content-between flex-row align-items-center">
          <div class="mr-auto">
            <span class="font-weight-bold text-uppercase">{{ ticket.custom_id }}</span>
          </div>
          <div class="ml-auto">
            <a 
              v-if="permissions.find(permission => permission.name == 'ticket.update')"
            :href="`/ticket/${ticket.id}/editar`" class="btn btn-sm btn-warning">Editar</a>
            <a 
              v-if="permissions.find(permission => permission.name == 'ticket.show')"
              href="/ticket" class="btn btn-sm btn-info text-white">Volver al listado</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <ticket-form :ticket="ticket" :editable="false" />
      </div>
    </div>
    <attachments :attachments="attachments" @attachmentDeleted="getTicketAttachments" />

    <comments :comments="comments" @commentDeleted="getTicketComments" />

    <new-comment :ticket_id="ticket.id" @submitted="getTicketComments" />
  </div>
</template>

<script>
import Comments from "../Comments/Comments.vue";
import NewComment from "../Comments/NewComment.vue";
import Attachments from "../Attachments/Attachments.vue";
import TicketForm from "./TicketForm.vue";

export default {
  components: { TicketForm, Attachments, Comments, NewComment },
  props: ["ticket", "permissions"],
  data() {
    return {
      comments: [],
      attachments: [],
    };
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