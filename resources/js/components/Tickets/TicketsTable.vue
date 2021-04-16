<template>
  <div class="card mx-3 shadow mt-3">
    <div class="card-body">
      <div
        class="alert alert-success alert-dismissible fade show"
        role="alert"
        v-if="success.status"
      >
        <span>{{ success.msg }}</span>
        <button
          type="button"
          class="close"
          data-dismiss="alert"
          aria-label="Close"
          @click="success.status = false"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div
        class="alert alert-danger alert-dismissible fade show"
        role="alert"
        v-if="error.status"
      >
        <span>{{ error.msg }}</span>
        <button
          type="button"
          class="close"
          data-dismiss="alert"
          aria-label="Close"
          @click="error.status = false"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="table-responsive">
        <table class="table table-hover table-striped shadow">
          <thead class="thead-dark">
            <tr>
              <th scope="col" class="text-center"># ID</th>
              <th scope="col">Agente</th>
              <th scope="col">Cliente</th>
              <th scope="col">Titulo</th>
              <th scope="col" class="text-center">Fecha</th>
              <th scope="col" class="text-center">Estado</th>
              <th scope="col" class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="ticket in tickets.data" :key="ticket.id">
              <th scope="row" class="text-center">
                <a :href="`/ticket/${ticket.id}`" class="btn btn-sm btn-link text-dark font-weight-bold">{{ ticket.custom_id }}</a>
              </th>
              <td>{{ ticket.agent ? ticket.agent.name : ticket.createdBy.name }}</td>
              <td>{{ ticket.customer.alias !== '' ? ticket.customer.alias : ticket.customer.name }}</td>
              <td>{{ ticket.title }}</td>
              <td class="text-center">{{ ticket.created_at | moment("DD-MM-YYYY") }}</td>
              <td>
                <div class="d-flex flex-wrap justify-content-center">
                  <button :class="`btn btn-sm btn-${ticket.ticket_status.color} ${ticket.ticket_status.color == 'info' ? ' text-white' : ''}`"
                    :title="ticket.ticket_status.name"
                    disabled>
                    <i :class="`fa fa-${ticket.ticket_status.icon}`"></i>
                  </button>
                </div>
              </td>
              <td>
                <div class="d-flex flex-wrap justify-content-around align-items-center">
                  <a
                    type="button"
                    class="btn btn-sm btn-success"
                    :href="`/ticket/${ticket.id}`"
                    title="Ver Ticket"
                  >
                    <i class="fa fa-eye"></i>
                  </a>
                  <a
                    type="button"
                    class="btn btn-sm btn-info text-white"
                    title="Editar Ticket"
                    :href="`/ticket/${ticket.id}/editar`"
                  >
                    <i class="fa fa-edit"></i>
                  </a>
                  <button
                    type="button"
                    class="btn btn-sm btn-danger"
                    title="Borrar Ticket"
                    data-toggle="modal"
                    data-target="#deleteModal"
                    @click="deleteModal(ticket)"
                  >
                    <i class="fa fa-trash-alt"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <delete-modal
          v-if="showDelete"
          :data="ticket.id"
          :title="'ticket'"
          :toDelete="'ticket'"
          @success="ticketDeleted"
          @error="ticketNotDeleted"
        />
      </div>
    </div>
      <pagination
        size="small"
        align="center"
        :data="tickets"
        :limit="3"
        @pagination-change-page="emitPagination"
      >
        <span slot="prev-nav">&lt; Anterior</span>
        <span slot="next-nav">Siguiente &gt;</span>
      </pagination>
  </div>
</template>

<script>
export default {
  props: ["tickets"],
  data() {
    return {
      ticket: {},
      showDelete: false,
      success: {
        status: false,
        msg: "",
      },
      error: {
        status: false,
        msg: "",
      },
    };
  },
  mounted() {

  },
  methods: {
    emitPagination(page) {
      this.$emit("page", page);
    },
    closeAll() {
      this.success = {
        status: false,
        msg: "",
      };
      this.error = {
        status: false,
        msg: "",
      };
      this.showDelete = false;
    },
    ticketDeleted(data) {
      this.closeAll();
      $("html, body").animate({ scrollTop: 0 }, "slow");
      this.success = {
        status: true,
        msg: data,
      };
      setTimeout(() => {
      this.success = {
        status: false,
        msg: "",
      };
      this.$emit('ticketDeleted');
      }, 2000);
    },
    ticketNotDeleted(data) {
      this.closeAll();
      $("html, body").animate({ scrollTop: 0 }, "slow");
      this.error = {
        status: true,
        msg: data,
      };
    },
    deleteModal(ticket) {
      this.showDelete = true;
      this.ticket = ticket;
    },
  },
};
</script>
<style>
/* Enter and leave animations can use different */
/* durations and timing functions.              */
.slide-fade-enter-active {
  transition: all 0.3s ease;
}
.slide-fade-leave-active {
  transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}
</style>