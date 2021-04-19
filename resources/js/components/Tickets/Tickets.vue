<template>
  <div class="d-flex flex-column justify-content-center w-100">
    <div class="d-flex flex-wrap justify-content-around">
      <counter
        v-for="ticket_status in statuses"
        :key="ticket_status.id"
        :color="ticket_status.color"
        :count="ticket_status.tickets_count"
        :title="ticket_status.name"
        :icon="ticket_status.icon"
      ></counter>
    </div>
    <a
      href="/ticket/crear"
      class="btn btn-sm btn-block btn-secondary font-weight-bold mt-3"
    >
      <i class="fa fa-plus"></i><span class="ml-2">Nuevo Ticket</span>
    </a>
    <ticket-search-form
      :page="page"
      :ticketDeleted="ticketDeleted"
      @searched="searched"
      @searching="searching"
    />
    <transition name="fade" mode="out-in" v-if="is_searching">
      <spinner />
    </transition>
    <transition
      name="fade"
      mode="out-in"
      v-else-if="(tickets.data && Object.keys(tickets.data).length > 0) || Object.keys(tickets).length > 0"
    >
      <div>
        <tickets-table
          v-if="tickets.data && Object.keys(tickets.data).length > 0"
          class="d-none d-lg-block"
          :tickets="tickets"
          @page="setPage"
          @ticketDeleted="ticketDeleted = true"
        ></tickets-table>
        <tickets-mobile-cards
        v-else
          class="d-block d-lg-none"
          :tickets="tickets"
          @page="setPage"
          @ticketDeleted="ticketDeleted = true"
        ></tickets-mobile-cards>
      </div>
    </transition>
    <transition
      name="fade"
      mode="out-in"
      v-else-if="tickets.data && Object.keys(tickets.data).length == 0"
    >
      <div
        class="alert alert-warning fade show mt-3 mx-3 text-center"
        role="alert"
      >
        <span class="font-weight-bold">
          No se han encontrado resultados, por favor, haga una nueva búsqueda
          <i class="fa fa-thumbs-up"></i>
        </span>
      </div>
    </transition>
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
  props: ["statuses"],
  data() {
    return {
      tickets: [],
      page: 1,
      is_searching: false,
      ticketDeleted: false,
    };
  },
  methods: {
    searching(data) {
      this.is_searching = data;
    },
    setPage(data) {
      this.page = data;
    },
    searched(data) {
      this.ticketDeleted = false;
      if(this.$screen.breakpoint == "xs") {
        this.tickets.push(...data)
      } else {
        this.tickets = data;
      }
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