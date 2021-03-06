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
    <div class="dropdown mt-3">
      <button
        class="btn btn-secondary dropdown-toggle btn-sm btn-block"
        type="button"
        id="dropdownMenuButton"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
      >
        <i class="fa fa-plus"></i><span class="ml-2">Crear</span>
      </button>
      <div class="dropdown-menu w-100 text-center" aria-labelledby="dropdownMenuButton">
        <a href="/ticket-type/1/ticket/crear" class="dropdown-item">
          <i class="fa fa-ticket-alt"></i><span class="ml-2">Nuevo Ticket</span>
        </a>
        <a href="/ticket-type/2/ticket/crear" class="dropdown-item" v-if="[1, 2].includes(user.roles[0].id)">
          <i class="fa fa-tools"></i><span class="ml-2">Nuevo Parte de Trabajo</span>
        </a>
      </div>
    </div>

    <!-- PARTE MOBILE -->
    <div
      class="d-flex d-lg-none flex-wrap mt-3"
      v-if="this.$screen.breakpoint == 'xs'"
    >
      <button
        class="btn btn-primary btn-block"
        type="button"
        data-toggle="collapse"
        data-target="#searchform"
        aria-expanded="false"
        aria-controls="searchform"
      >
        Búsqueda
      </button>
      <div class="collapse col-12 w-100" id="searchform">
        <ticket-search-form
          :page="page"
          :ticketDeleted="ticketDeleted"
          :user="user"
          @searched="searched"
          @searching="searching"
          @mobileSearch="mobileSearch"
        />
      </div>
      <transition name="fade" mode="out-in" v-if="is_searching">
        <spinner />
      </transition>
      <transition
        name="fade"
        mode="out-in"
        v-else-if="Object.keys(tickets).length > 0"
      >
        <tickets-mobile-cards
          class="col-12"
          :tickets="tickets"
          :formsearch="formsearch"
          @page="setPage"
          @ticketDeleted="ticketDeleted = true"
        ></tickets-mobile-cards>
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
    <!-- PARTE WEB -->
    <div v-else>
      <ticket-search-form
        :page="page"
        :ticketDeleted="ticketDeleted"
        :user="user"
        @searched="searched"
        @searching="searching"
      />
      <transition name="fade" mode="out-in" v-if="is_searching">
        <spinner />
      </transition>
      <transition
        name="fade"
        mode="out-in"
        v-else-if="tickets.data && Object.keys(tickets.data).length > 0"
      >
        <tickets-table
          v-if="tickets.data && Object.keys(tickets.data).length > 0"
          class="d-none d-lg-block"
          :tickets="tickets"
          :permissions="permissions"
          :user-role="user.roles[0].id"
          @page="setPage"
          @ticketDeleted="ticketDeleted = true"
        ></tickets-table>
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
  props: ["statuses", "permissions", "user"],
  data() {
    return {
      tickets: [],
      formsearch: [],
      page: 1,
      is_searching: false,
      ticketDeleted: false,
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