<template>
  <div class="d-flex flex-column justify-content-center w-100">
    
    <div class="row">
      <!-- <counter
        v-for="ticket_status in statuses"
        :key="ticket_status.id"
        :color="ticket_status.color"
        :count="ticket_status.tickets_count"
        :title="ticket_status.name"
        :icon="ticket_status.icon"
      ></counter> -->
      <counter
        title="NUEVO"
        icon="newspaper"
        color="primary"
        :count="nuevo"
      ></counter>
      <counter
        title="ABIERTO"
        icon="envelope-open"
        color="secondary"
        :count="abierto"
      ></counter>
      <counter
        title="RESUELTO"
        icon="check-square"
        color="success"
        :count="resuelto"
      ></counter>
      <counter
        title="CANCELADO"
        icon="window-close"
        color="danger"
        :count="cancelado"
      ></counter>
    </div>

    <div class="d-none d-lg-block">
      <div class="row d-flex justify-content-center mt-2 mb-2">
        <a class="btn btn-secondary text-white mr-2" href="/ticket-type/1/ticket/crear">
          <i class="fa fa-ticket-alt mr-2"></i>
            <span>Nuevo ticket</span>
        </a>
        <a class="btn btn-primary text-white" href="/ticket-type/2/ticket/crear" v-if="admins.includes(userRole)">
          <i class="fa fa-tools mr-2"></i>
            <span>Nuevo parte de trabajo</span>
        </a>
      </div>
    </div>

    <!-- <ticket-search-form
      :page="page"
      :ticketDeleted="ticketDeleted"
      :user="user"
      @searched="searched"
      @searching="searching"
    ></ticket-search-form> -->

    <ticket-search-form
      :ticketDeleted="ticketDeleted"
      :user="user"
      @searching="searching"
      @submitted="get_tickets"
    ></ticket-search-form>

    <div v-if="tickets.total > 0">
      <div class="d-none d-xl-block">
        <tickets-table v-if="tickets.total > 0"
          :tickets="tickets"
          :permissions="permissions"
          :user-role="user.roles[0].id"
          :searched="searched"
          @page="get_tickets"
          @ticketDeleted="ticketDeleted = true"
        ></tickets-table>
      </div>
      <div class="d-xl-none d-block">
        <tickets-mobile-cards
          :tickets="tickets"
          :permissions="permissions"
          :searched="searched"
          @page="get_tickets"
          @ticketDeleted="ticketDeleted = true"
        ></tickets-mobile-cards>
      </div>
    </div>
    <div v-else-if="tickets.total == 0">
      <div class="w-100">
        <div class="alert alert-info fade show mt-3 text-center" role="alert">
            No se han encontrado resultados, por favor, haga una nueva búsqueda
            <i class="fa fa-thumbs-up"></i>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Counter from "../Counter.vue";
  import Spinner from "../Spinner.vue";
  import TicketSearchForm from "./TicketSearchForm.vue";
  import TicketsMobileCards from "./TicketsMobileCards.vue";
  import TicketsTable from "./TicketsTable.vue";

  export default {
    components: {
      TicketSearchForm,
      TicketsTable,
      Counter,
      Spinner,
      TicketsMobileCards,
    },
    props: ["permissions", "user", "userRole"],
    data() {
      return {
        tickets: [],
        // formsearch: [],
        searched: [],
        // page: 1,
        is_searching: false,
        ticketDeleted: false,
        admins: [1, 2],
        nuevo: 0,
        abierto: 0,
        resuelto: 0,
        cancelado: 0,
      };
    },
    methods: {
      // mobileSearch(data) {
      //   this.formsearch = data;
      // },
      searching(data) {
        this.is_searching = data;
      },
      // setPage(data) {
      //   let a = Math.random().toString(36).substring(7);
      //   this.page = data ? data : a;
      // },
      // searched(data) {
      //   this.ticketDeleted = false;
      //   this.tickets = data;
      // },
      get_tickets(data) {

        if (!data) return;
        this.searched = data;
        this.ticketDeleted = false;
        axios.get("/api/ticket", {params: {
            page: data.page,
            customer_id: data.customer_id,
            ticket_status_id: data.ticket_status_id,
            ticket_type_id: data.ticket_type_id,
            priority_id: data.priority_id,
            agent_id: data.agent_id,
            ticket_id: data.ticket_id,
            custom_ticket_id: data.custom_ticket_id,
            invoiceable_type_id: data.invoiceable_type_id,
            text: data.text,
            dateFrom: data.dateFrom,
            dateTo: data.dateTo,
            offset: data.offset,
          },
        }).then((res) => {
          this.get_counters(data);
          this.tickets = res.data.tickets;
        }).catch((err) => console.log(err));
      },
      get_counters(data)
      {
        axios.get("/api/get_ticket_counters", {
          params: {
            page: data.page,
            customer_id: data.customer_id,
            ticket_status_id: data.ticket_status_id,
            ticket_type_id: data.ticket_type_id,
            priority_id: data.priority_id,
            agent_id: data.agent_id,
            ticket_id: data.ticket_id,
            custom_ticket_id: data.custom_ticket_id,
            invoiceable_type_id: data.invoiceable_type_id,
            text: data.text,
            dateFrom: data.dateFrom,
            dateTo: data.dateTo,
            offset: data.offset,
          },
        }).then((res) => {
          this.nuevo = res.data.nuevo;
          this.abierto = res.data.abierto;
          this.resuelto = res.data.resuelto;
          this.cancelado = res.data.cancelado;
        }).catch((err) => {
          console.log(err);
        });
      }
    },
  };
</script>
<style>
  .fade-enter-active,
  .fade-leave-active {
    transition-duration: 0.3s;
    transition-property: opacity;
    transition-timing-function: ease;
  }

  .fade-enter,
  .fade-leave-active {
    opacity: 0;
  }
</style>