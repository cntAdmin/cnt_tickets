<template>
  <div class="flex-row justify-content-center">
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert" v-if="success.status">
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

    <div class="card mt-3 shadow" v-for="ticket in tickets.data" :key="ticket.id">
      <div class="card-header">
        <span>Creado {{ ticket.created_at | moment("DD-MM-YYYY HH:mm:ss") }}</span>
        <span :class="`float-right text-${ticket.ticket_status.color} ${ticket.ticket_status.color == 'info' ? 'text-white' : ''}`">
          <i :class="`fas fa-${ticket.ticket_status.icon}`"></i> {{ ticket.ticket_status.name }}
        </span>
      </div>
      <div class="card-body">
        <p class="text-truncate">{{ ticket.title }}</p>
      </div>
      <div class="card-footer">
        <div class="form-inline d-flex justify-content-center" v-if="ticket.ticket_type_id === 2">
          <ticket-confirm-edit-modal v-if="ticketConfirmEditModal && ticket.is_signed === 1" :ticket_id="ticket.id"/>

          <span class="btn btn-sm btn-link">
            <i class="text-secondary fas fa-paperclip mr-2"></i
            ><span class="badge badge-dark mr-2">
              {{ Object.keys(ticket.attachments).length }}
            </span>Adjuntos
          </span>

          <a v-if="permissions.find((permission) => permission.name == 'ticket.show')"
            class="btn btn-sm btn-link" 
            :href="`/ticket/${ticket.id}`"
          >
            <i class="text-success fa fa-eye mr-1"></i>Ver parte
          </a>

          <!-- Editar parte si no está firmado ni facturado -->
          <a v-if="permissions.find((permission) => permission.name == 'ticket.update') 
                  && ticket.is_signed === 0 && ticket.invoiceable_type_id !== 3"
            class="btn btn-sm btn-link"
            :href="`/ticket/${ticket.id}/editar`"
          >
            <i class="fa fa-edit"></i>Editar
          </a>
          <!-- Editar parte si está firmado y no facturado -->
          <button v-else-if="ticket.is_signed === 1 && ticket.invoiceable_type_id !== 3"
            type="button"
            class="btn btn-sm btn-link"
            data-toggle="modal"
            data-target="#ticketConfirmEditModal"
            @click="ticketConfirmEditModal = true"
          >
            <i class="fa fa-edit"></i>Editar
          </button>
          <!-- No permite editar si ya está facturadoi -->
          <button v-else-if="ticket.invoiceable_type_id === 3"
            type="button"
            class="btn btn-sm btn-link"
            title="Parte Facturado. No se puede editar"
            disabled
          >
            <i class="fa fa-edit"></i>Editar (Ya facturado)
          </button>

          <!-- <span v-if="permissions.find((permission) => permission.name == 'ticket.destroy')"
            class="btn btn-sm btn-link" 
            data-toggle="modal" data-target=".custom-modal-lg" 
            @click="openDeleteModal(ticket)"
          >
            <i class="text-danger fa fa-trash mr-1"></i>
            Borrar
          </span> -->

          <div class="btn-group dropup" v-if="ticket.invoiceable_type_id != 3">
            <button 
              type="button" 
              class="btn btn-sm btn-secondary dropdown-toggle" 
              data-toggle="dropdown" 
              aria-haspopup="true" 
              aria-expanded="false"
            >
              Acciones ...
            </button>
            <div class="dropdown-menu">
              <button v-if="ticket.invoiceable_type_id == 1" class="dropdown-item" @click="changeStatusFacturado(ticket.id, 2)">
                Pasar a facturable
              </button>
              <button v-if="ticket.invoiceable_type_id == 2" class="dropdown-item" @click="changeStatusFacturado(ticket.id, 3)">
                Marcar como facturado
              </button>
            </div>
          </div>
        </div>

        <div class="form-inline d-flex justify-content-center" v-else>

          <span class="btn btn-sm btn-link">
            <i class="text-secondary fas fa-paperclip mr-2"></i>
            <span class="badge badge-dark mr-2">{{ Object.keys(ticket.attachments).length }}</span>Adjuntos
          </span>

          <a v-if="permissions.find((permission) => permission.name == 'ticket.show')" 
            class="btn btn-sm btn-link" 
            :href="`/ticket/${ticket.id}`"
          >
            <i class="text-success fa fa-eye mr-1"></i>Ver ticket
          </a>

          <a v-if="permissions.find((permission) => permission.name == 'ticket.update')"
            class="btn btn-sm btn-link"
            :href="`/ticket/${ticket.id}/editar`"
          >
            <i class="fa fa-edit"></i>Editar
          </a>

          <div class="btn-group dropup" v-if="ticket.invoiceable_type_id != 3">
            <button 
              type="button" 
              class="btn btn-sm btn-secondary dropdown-toggle" 
              data-toggle="dropdown" 
              aria-haspopup="true" 
              aria-expanded="false"
            >
              Acciones ...
            </button>
            <div class="dropdown-menu">
              <button 
                class="dropdown-item"
                @click="changeStatus(ticket.id, ticket_status.id)"
                v-for="ticket_status in ticketStatuses"
                :key="ticket_status.id"
              >
                {{ ticket_status.name }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TicketConfirmEditModal from '../TicketConfirmEditModal.vue'

export default {
  components: {TicketConfirmEditModal},
  props: ["tickets", "permissions", "formsearch"],
  data() {
    return {
      ticketStatuses: [],
      stopLoading: false,
      page: 0,
      success: {
        status: false,
        msg: "",
      },
      ticketConfirmEditModal: false,
    };
  },
  mounted() {
    this.get_ticket_statuses();
    window.onscroll = () => {
      this.scroll();
    };
      this.formsearch.offset = 10;
  },
  methods: {
    get_mobile_tickets() {
      axios
        .get("/api/ticket", {
          params: {
            type: "infinite",
            customer_id: this.formsearch.customer_id,
            ticket_status_id: this.formsearch.ticket_status_id,
            priority_id: this.formsearch.priority_id,
            agent_id: this.formsearch.agent_id,
            ticket_id: this.formsearch.ticket_id,
            text: this.formsearch.text,
            dateFrom: this.formsearch.dateFrom,
            dateTo: this.formsearch.dateTo,
            offset: this.formsearch.offset,
          },
        })
        .then((res) => {
          if (res.data.tickets.length <= 10) {
            return (this.stopLoading = true);
          }

          this.tickets.push(...res.data.tickets);
          this.formsearch.offset += 10;
        });
    },
    setStatus(ticket, status_id) {
      axios.put(`/api/ticket/${ticket.id}/ticket-status/${status_id}`)
        .then((res) => {
          $("html, body").animate({ scrollTop: 0 }, "slow");
          this.success = {
            status: true,
            msg: res.data.msg,
          };
          setTimeout(() => {
            this.success = {
              status: false,
              msg: "",
            };
            window.location.reload();
          }, 2000);
        })
        .catch((err) => console.log(err.response.data));
    },
    scroll() {
      let bottomOfWindow =
        Number(
          (
            Math.max(
              window.pageYOffset,
              document.documentElement.scrollTop,
              document.body.scrollTop
            ) + window.innerHeight
          ).toFixed(0)
        ) === document.documentElement.offsetHeight;

      if (bottomOfWindow) {
        this.get_mobile_tickets(); // replace it with your code
      }
    },
    get_ticket_statuses() {
      axios.get("/api/get_all_ticket_statuses").then((res) => {
        this.ticketStatuses = res.data.ticket_statuses;
      });
    },
  },
};
</script>