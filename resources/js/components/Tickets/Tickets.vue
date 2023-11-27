<template>
  <div class="d-flex flex-column justify-content-center w-100">
    
    <div class="row">
      <counter
        v-for="ticket_status in statuses"
        :key="ticket_status.id"
        :color="ticket_status.color"
        :count="ticket_status.tickets_count"
        :title="ticket_status.name"
        :icon="ticket_status.icon"
      ></counter>
    </div>

    <ticket-search-form
      :page="page"
      :ticketDeleted="ticketDeleted"
      :user="user"
      @searched="searched"
      @searching="searching"
    ></ticket-search-form>

    <div v-if="tickets.total > 0">
      <div class="d-none d-xl-block">
        <tickets-table v-if="tickets.total > 0"
          :tickets="tickets"
          :permissions="permissions"
          :user-role="user.roles[0].id"
          @page="setPage"
          @ticketDeleted="ticketDeleted = true"
        ></tickets-table>
      </div>
      <div class="d-xl-none d-block">
        <tickets-mobile-cards
          :tickets="tickets"
          :permissions="permissions"
          :formsearch="formsearch"
          @page="setPage"
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
    props: ["statuses", "permissions", "user", "userRole"],
    data() {
      return {
        tickets: [],
        formsearch: [],
        page: 1,
        is_searching: false,
        ticketDeleted: false,
        admins: [1, 2],
      };
    },
    methods: {
      mobileSearch(data) {
        this.formsearch = data;
      },
      searching(data) {
        this.is_searching = data;
      },
      setPage(data) {
        let a = Math.random().toString(36).substring(7);
        this.page = data ? data : a;
      },
      searched(data) {
        this.ticketDeleted = false;
        this.tickets = data;
      },
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