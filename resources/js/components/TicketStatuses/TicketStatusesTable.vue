<template>
  <div class="card mx-3 shadow mt-3 border-dark">
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
        <table class="table table-striped text-center">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Nombre</th>
              <th scope="col">Color</th>
              <th scope="col">Icono</th>
              <th scope="col">Fecha Creación</th>
              <th scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="ticketStatus in ticketStatuses.data" :key="ticketStatus.id">
              <th>{{ ticketStatus.name }}</th>
              <td>
                <button type="button" :class="`btn btn-sm btn-${ticketStatus.color}`"></button>
              </td>
              <td>
                <span class="btn btn-sm disable">
                  <i :class="`fas fa-${ticketStatus.icon} text-${ticketStatus.color}`"></i>
                </span>
              </td>
              <td>{{ ticketStatus.created_at | moment("DD-MM-YYYY") }}</td>
              <td>
                <div class="d-flex flex-wrap justify-content-center" style="gap: 0.5rem">
                  <a v-if="permissions.find((permission) => permission.name == 'ticket_status.update')"
                    :href="`/ticket-status/${ticketStatus.id}/editar`"
                    class="btn btn-sm btn-info text-white" title="editar"
                  >
                    <i class="fa fa-edit"></i>
                  </a>
                  <button 
                    v-if="permissions.find((permission) => permission.name == 'ticket_status.destroy')"
                    type="button"
                    class="btn btn-sm btn-danger"
                    title="borrar"
                    data-toggle="modal"
                    data-target="#deleteModal"
                    @click="deleteModal(ticketStatus)"
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
          :data="ticketStatus.id"
          title="estado"
          toDelete="ticket-status"
          @success="deleted"
          @error="notDeleted"
        />
      </div>
    </div>
    <pagination
      size="small"
      align="center"
      :data="ticketStatuses"
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
  props: ["ticketStatuses", "permissions"],
  data() {
    return {
      ticketStatus: {},
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
    deleted(data) {
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
        this.$emit("deleted");
      }, 2000);
    },
    notDeleted(data) {
      this.closeAll();
      $("html, body").animate({ scrollTop: 0 }, "slow");
      this.error = {
        status: true,
        msg: data,
      };
    },
    deleteModal(ticketStatus) {
      this.showDelete = true;
      this.ticketStatus = ticketStatus;
    },
  },
};
</script>

<style>
</style>