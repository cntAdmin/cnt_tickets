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
              <th scope="col">Tipo</th>
              <th scope="col" class="text-center">Fecha creación</th>
              <th scope="col" class="text-center">Tiempo trabajado</th>
              <th scope="col" class="text-center">Estados</th>
              <th scope="col" class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="ticket in tickets.data"
              :key="ticket.id"
              :class="checkTicketRow(ticket)"
            >
              <th scope="row" class="text-center">
                <a
                  :href="`/ticket/${ticket.id}`"
                  class="btn btn-sm btn-link text-dark font-weight-bold"
                  >{{ ticket.custom_id }}</a
                >
              </th>
              <td>
                {{ ticket.agent !== null ? ticket.agent.name : (ticket.created_by_user ? ticket.created_by_user.name : '')  }}
              </td>
              <td>{{ ticket.customer ? ticket.customer.name : "" }}</td>
              <td>{{ ticket.title }}</td>
              <td>{{ ticket.ticket_type.name }}</td>
              <td class="text-center">
                {{ ticket.created_at | moment("DD-MM-YYYY") }}
              </td>
              <td class="text-center">
                <span>{{ get_total_work_time(ticket.ticket_timeslots) }}</span>
              </td>
              <td>
                <div class="d-flex flex-wrap justify-content-start">
                  <button
                    :class="`btn btn-sm btn-${ticket.ticket_status.color} ${
                      ticket.ticket_status.color == 'info' ? ' text-white' : ''
                    }`"
                    :title="ticket.ticket_status.name"
                    disabled
                  >
                    <i :class="`fa fa-${ticket.ticket_status.icon}`"></i>
                  </button>
                  <span class="btn btn-sm btn-link" v-if="Object.keys(ticket.comment_attachments).length +
                        Object.keys(ticket.attachments).length > 0">
                    <i class="text-secondary fas fa-paperclip"></i
                    >
                  </span>
                  <span class="btn btn-sm btn-link" v-if="Object.keys(ticket.comments).length > 0">
                    <i class="text-secondary fas fa-comment-dots"></i
                    >
                  </span>
                  <span class="btn btn-sm btn-link" v-if="ticket.priority_id !== null">
                    <i :class="'fas fa-exclamation text-' + checkColor(ticket) "></i>
                  </span>
                  <span class="btn btn-sm btn-link" v-if="ticket.ticket_type.id !== null" :title="ticket.invoiceable_type ? ticket.invoiceable_type.name : ''">
                    <!-- NO FACTURAR -->
                    <i class="fab fa-creative-commons-nc-eu" v-if="ticket.invoiceable_type_id === 1" 
                      ></i> 
                    <!-- A FACTURAR  -->
                    <i class="fas fa-coins" v-if="ticket.invoiceable_type_id === 2"></i>
                    <!-- FACTURADO  -->
                    <i class="fas fa-euro-sign" v-if="ticket.invoiceable_type_id === 3"></i>
                  </span>
                </div>
              </td>
              <td>
                <div
                  class="d-flex flex-wrap justify-content-around align-items-center"
                >
                  <!-- MODAL EDITAR INCIDENCIA TicketConfirmEditModal.vue -->
                  <ticket-confirm-edit-modal
                    v-if="ticketConfirmEditModal && ticket.is_signed === 1"
                    :ticket_id="ticket.id"
                  />


                  <!-- BOTON VER TICKET -->
                  <a
                    v-if="
                      permissions.find(
                        (permission) => permission.name == 'ticket.show'
                      )
                    "
                    class="btn btn-sm btn-success"
                    :href="`/ticket/${ticket.id}`"
                    title="Ver Ticket"
                  >
                    <i class="fa fa-eye"></i>
                  </a>

                  <!-- BOTON EDITAR TICKET -->
                  <a
                    v-if="
                      permissions.find(
                        (permission) => permission.name == 'ticket.update'
                      )
                      && ticket.is_signed === 0
                    "
                    class="btn btn-sm btn-info text-white"
                    title="Editar Ticket"
                    :href="`/ticket/${ticket.id}/editar`"
                  >
                    <i class="fa fa-edit"></i>
                  </a>

                  <button
                    v-if="ticket.is_signed === 1"
                    type="button"
                    class="btn btn-sm btn-info text-white"
                    title="Editar Ticket Cerrado"
                    data-toggle="modal"
                    data-target="#ticketConfirmEditModal"
                    @click="ticketConfirmEditModal = true"
                  >
                    <i class="fa fa-edit"></i>
                  </button>

                  <!-- BOTON ACTUALIZAR ESTADO DE TICKET -->
                  <button
                    v-if="
                      permissions.find(
                        (permission) => permission.name == 'ticket.update'
                      )
                    "
                    type="button"
                    class="btn btn-sm btn-secondary text-white dropdown-toggle"
                    title="Editar Ticket"
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
                  <button
                    v-if="
                      permissions.find(
                        (permission) => permission.name == 'ticket.destroy'
                      )
                    "
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
    checkTicketRow(ticket) {
      if (ticket.ticket_status.id > 2) {
        return "border-left border-success";
      } else {
        if (this.userRole < 3) {
          if (ticket.read_by_admin) {
            return "border-left border-success";
          } else {
            return "border-left border-danger text-danger font-weight-bold";
          }
        } else {
          if (ticket.read_by_admin) {
            return "border-left border-danger text-danger font-weight-bold";
          } else {
            return "border-left border-success";
          }
        }
      }
    },
    changeStatus(ticket_id, ticket_status_id) {
      axios
        .put(`/api/ticket/${ticket_id}/ticket-status/${ticket_status_id}`)
        .then((res) => {
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
      axios
        .get("/api/get_all_ticket_statuses")
        .then((res) => {
          // console.log(res.data);
          this.ticket_statuses = res.data.ticket_statuses;
        })
        .catch((error) => console.log(error.response.data));
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

      for(var element=0; element<timeslots.length; element++){ 
        cadena = timeslots[element].work_time.split(":");
        totalminutes = totalminutes + (parseInt(cadena[0])*60) + (parseInt(cadena[1]));
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
        return rhours + " hora(s) y " + rminutes+ " minutos";
      }
      if(rhours > 0 && rminutes === 0){
        return rhours + " hora(s)";
      }
      if(rhours === 0 && rminutes > 0){
        return rminutes+ " minutos";
      }
      if(rhours === 0 && rminutes === 0){
        return "Sin definir";
      }
    }
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