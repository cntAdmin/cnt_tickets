<template>
  <div class="card shadow mt-3 border-dark">
    <div class="card-body">
      <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="success.status">
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
      <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="error.status">
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
              <th scope="col">Agente</th>
              <th scope="col">Cliente</th>
              <th scope="col">Titulo</th>
              <th scope="col">Tipo</th>
              <th scope="col">Fecha creación</th>
              <th scope="col">Tiempo trabajado</th>
              <th scope="col">Info adicional</th>
              <th scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="ticket in tickets.data" :key="ticket.id">
              <td>{{ ticket.agent !== null ? ticket.agent.name : 'Los partes no tienen agente'  }}</td>
              <td>{{ ticket.customer ? ticket.customer.name : "" }}</td>
              <td>{{ ticket.title }}</td>
              <td>{{ ticket.ticket_type.name }}</td>
              <td>{{ ticket.created_at | moment("DD-MM-YYYY") }}</td>
              <td>
                <div v-if="ticket.ticket_type_id === 2">
                  <span>{{ get_total_work_time(ticket.ticket_timeslots) }}</span>
                </div>
                <div v-else>
                  <span> - </span>
                </div>
              </td>
              <td>
                <div class="d-flex justify-content-center">
                  <span class="btn btn-sm btn-link" :title="ticket.ticket_status.name">
                    <i :class="`fas fa-${ticket.ticket_status.icon} text-${ticket.ticket_status.color}`"></i>
                  </span>
                  <span class="btn btn-sm btn-link" title="Adjuntos" v-if="Object.keys(ticket.comment_attachments).length +
                      Object.keys(ticket.attachments).length > 0">
                    <i class="text-secondary fas fa-paperclip"></i>
                  </span>
                  <span class="btn btn-sm btn-link" title="Comentarios" v-if="Object.keys(ticket.comments).length > 0">
                    <i class="text-secondary fas fa-comment-dots"></i>
                  </span>
                  <span class="btn btn-sm btn-link" :title="ticket.priority.name" v-if="ticket.priority_id !== null">
                    <i :class="'fas fa-exclamation text-' + checkColor(ticket) "></i>
                  </span>
                  <span class="btn btn-sm btn-link" v-if="ticket.invoiceable_type_id !== null" :title="ticket.invoiceable_type ? ticket.invoiceable_type.name : ''">
                    <!-- NO FACTURAR -->
                    <i class="fab fa-creative-commons-nc-eu" v-if="ticket.invoiceable_type_id === 1"></i> 
                    <!-- A FACTURAR  -->
                    <i class="fas fa-coins" v-if="ticket.invoiceable_type_id === 2"></i>
                    <!-- FACTURADO  -->
                    <i class="fas fa-euro-sign" v-if="ticket.invoiceable_type_id === 3"></i>
                  </span>
                  <span class="btn btn-sm btn-link" title="Descargar PDF" v-if="ticket.ticket_type_id == 2 && ticket.is_signed" @click="descargarParte(ticket)">
                    <i class="text-danger fas fa-file-pdf"></i>
                  </span>
                </div>
              </td>
              <td>
                <div class="d-flex fkex-wrap justify-content-center" style="gap: 0.5rem" v-if="ticket.ticket_type_id === 2">
                  <ticket-confirm-edit-modal v-if="ticketConfirmEditModal && ticket.is_signed === 1" :ticket_id="ticket.id"/>
                  
                  <a v-if="permissions.find((permission) => permission.name == 'ticket.show')"
                    class="btn btn-sm btn-success"
                    :href="`/ticket/${ticket.id}`"
                    title="Ver Parte"
                  >
                    <i class="fa fa-eye"></i>
                  </a>

                  <!-- Editar parte si no está firmado ni facturado -->
                  <a v-if="permissions.find((permission) => permission.name == 'ticket.update') && ticket.is_signed === 0 && ticket.invoiceable_type_id !== 3"
                    class="btn btn-sm btn-info text-white"
                    title="Editar Parte"
                    :href="`/ticket/${ticket.id}/editar`"
                  >
                    <i class="fa fa-edit"></i>
                  </a>
                  <!-- Editar parte si está firmado y no facturado -->
                  <button v-else-if="ticket.is_signed === 1 && ticket.invoiceable_type_id !== 3"
                    type="button"
                    class="btn btn-sm btn-info text-white"
                    title="Editar Parte Cerrado"
                    data-toggle="modal"
                    data-target="#ticketConfirmEditModal"
                    @click="ticketConfirmEditModal = true"
                  >
                    <i class="fa fa-edit"></i>
                  </button>
                  <!-- No permite editar si ya está facturadoi -->
                  <button v-else-if="ticket.invoiceable_type_id === 3"
                    type="button"
                    class="btn btn-sm btn-info text-white"
                    title="Parte Facturado. No se puede editar"
                    disabled
                  >
                    <i class="fa fa-edit"></i>
                  </button>

                  <button v-if="permissions.find((permission) => permission.name == 'ticket.destroy')"
                    type="button"
                    class="btn btn-sm btn-danger"
                    title="Borrar Ticket"
                    data-toggle="modal"
                    data-target="#deleteModal"
                    @click="deleteModal(ticket)"
                  >
                    <i class="fa fa-trash-alt"></i>
                  </button>


                  <button v-if="permissions.find((permission) => permission.name == 'ticket.update')"
                    type="button"
                    class="btn btn-sm btn-secondary text-white dropdown-toggle"
                    title="Editar Estado"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="fa fa-question-circle"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <button
                      v-if="ticket.invoiceable_type_id == 1"
                      class="dropdown-item"
                      @click="changeStatusFacturado(ticket.id, 2)"
                    >
                      Marcar como facturable
                    </button>
                    <button
                      v-if="ticket.invoiceable_type_id == 2"
                      class="dropdown-item"
                      @click="changeStatusFacturado(ticket.id, 3)"
                    >
                      Marcar como facturado
                    </button>
                  </div>
                </div>

                <div v-else class="d-flex flex-wrap justify-content-center" style="gap: 0.5rem">
                  <a v-if="permissions.find((permission) => permission.name == 'ticket.show')"
                    class="btn btn-sm btn-success"
                    :href="`/ticket/${ticket.id}`"
                    title="Ver Ticket"
                  >
                    <i class="fa fa-eye"></i>
                  </a>

                  <a v-if="permissions.find((permission) => permission.name == 'ticket.update')"
                    class="btn btn-sm btn-info text-white"
                    title="Editar Ticket"
                    :href="`/ticket/${ticket.id}/editar`"
                  >
                    <i class="fa fa-edit"></i>
                  </a>
                  
                  <button v-if="permissions.find((permission) => permission.name == 'ticket.destroy')"
                    type="button"
                    class="btn btn-sm btn-danger"
                    title="Borrar Ticket"
                    data-toggle="modal"
                    data-target="#deleteModal"
                    @click="deleteModal(ticket)"
                  >
                    <i class="fa fa-trash-alt"></i>
                  </button>

                  <button v-if="permissions.find((permission) => permission.name == 'ticket.update')"
                    type="button"
                    class="btn btn-sm btn-secondary text-white dropdown-toggle"
                    title="Editar Estado"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="fa fa-question-circle"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <button 
                      class="dropdown-item"
                      @click="changeStatus(ticket.id, ticket_status.id)"
                      v-for="ticket_status in ticket_statuses"
                      :key="ticket_status.id"
                    >
                      {{ ticket_status.name }}
                    </button>
                  </div>
                  
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
import TicketConfirmEditModal from '../TicketConfirmEditModal.vue'

export default {
  components: {TicketConfirmEditModal},
  props: ["tickets", "permissions", "userRole"],
  data() {
    return {
      ticket: {},
      ticket_statuses: [],
      showDelete: false,
      success: {
        status: false,
        msg: "",
      },
      error: {
        status: false,
        msg: "",
      },
      ticketConfirmEditModal: false,
    };
  },
  mounted() {
    this.get_all_ticket_status();
  },
  methods: {
    checkColor(thisTicket) {
      switch (thisTicket.priority.id) {
        case 1:
            return "success";
          break;
        case 2:
            return "warning";
          break;
        case 3:
            return "danger";
          break;
      
        default:
          return "primary"
          break;
      }
    },
    changeStatus(ticket_id, ticket_status_id) {
      axios.put(`/api/ticket/${ticket_id}/ticket-status/${ticket_status_id}`).then((res) => {
        this.success = {
          status: true,
          msg: res.data.msg,
        };
        setTimeout(() => {
          this.$emit("page");
        }, 1500);
      });
    },
    changeStatusFacturado(ticket_id, ticket_invoiceable_type_id) {
      axios.put(`/api/ticket/${ticket_id}/ticket-invoiceable-type/${ticket_invoiceable_type_id}`).then((res) => {
        this.success = {
          status: true,
          msg: res.data.msg,
        };
        setTimeout(() => {
          this.$emit("page");
        }, 1500);
      });
    },
    get_all_ticket_status() {
      axios.get("/api/get_all_ticket_statuses").then((res) => {
        this.ticket_statuses = res.data.ticket_statuses;
      }).catch((error) => console.log(error));
    },
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
        this.$emit("ticketDeleted");
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
    get_total_work_time(timeslots){
      let cadena = [];
      let totalminutes = 0;

      if(timeslots.length>0){
        for(var element=0; element<timeslots.length; element++){ 
          cadena = timeslots[element].work_time.split(":");
          totalminutes = totalminutes + (parseInt(cadena[0])*60) + (parseInt(cadena[1]));
        }
      }

      return this.timeConvert(totalminutes);
    },
    timeConvert(timeInMinutes){
      let num = timeInMinutes;
      let hours = (num / 60);
      let rhours = Math.floor(hours);
      let minutes = (hours - rhours) * 60;
      let rminutes = Math.round(minutes);

      if(rhours > 0 && rminutes > 0){
        if(rhours < 10){
          return "0" + rhours + ":" + rminutes + ":00";
        }
        else{
          return rhours + ":" + rminutes + ":00";
        }
      }
      if(rhours > 0 && rminutes === 0){
        if(rhours < 10){
          return "0" + rhours + ":00:00";
        }
        else{
          return rhours + ":00:00";
        }
      }
      if(rhours === 0 && rminutes > 0){
        return "00:" + rminutes + ":00";
      }
      if(rhours === 0 && rminutes === 0){
        return "00:00:00";
      }
    },
    descargarParte(ticket)
    {
      axios.get("/api/descargar_parte_trabajo/" + ticket.id, {responseType: 'blob'}).then(res => {
        window.open(URL.createObjectURL(res.data))
      }).catch(err => {
          console.log(err);
      });
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