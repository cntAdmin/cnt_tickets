<template>
  <div class="d-flex flex-column justify-content-center w-100">
    <!-- <div class="d-flex flex-wrap justify-content-around"> -->
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

    <!-- PARTE MOBILE -->
    <div class="d-flex d-lg-none flex-wrap mt-3" 
      v-if="this.$screen.breakpoint == 'xs' || this.$screen.breakpoint == 'sm' || this.$screen.breakpoint == 'md'"
    >
      <ticket-search-form
        :esmovil=true
        :page="page"
        :ticketDeleted="ticketDeleted"
        :user="user"
        @searched="searched"
        @searching="searching"
      />
      <transition name="fade" mode="out-in" v-if="is_searching">
        <spinner />
      </transition>
      <transition name="fade" mode="out-in" v-else-if="tickets.total > 0">
        <tickets-mobile-cards
          class="w-100"
          :tickets="tickets"
          :formsearch="formsearch"
          @page="setPage"
          @ticketDeleted="ticketDeleted = true"
        ></tickets-mobile-cards>
      </transition>
      <transition name="fade" mode="out-in" v-else-if="tickets.total == 0">
        <div class="w-100">
          <div class="alert alert-info fade show mt-3 text-center" role="alert">
              No se han encontrado resultados, por favor, haga una nueva búsqueda
              <i class="fa fa-thumbs-up"></i>
          </div>
        </div>
      </transition>
    </div>

    <!-- PARTE WEB -->
    <div v-else>
      <div class="row d-flex justify-content-center mt-4">
        <a class="btn btn-secondary text-white shadow-lg" href="/ticket-type/1/ticket/crear">
          <i class="fa fa-ticket-alt"></i><span class="ml-2">Nuevo ticket</span>
        </a>

        <a class="btn btn-primary text-white shadow-lg ml-2" href="/ticket-type/2/ticket/crear" v-if="admins.includes(userRole)">
          <i class="fa fa-tools"></i><span class="ml-2">Nuevo parte de trabajo</span>
        </a>
      </div>
      <ticket-search-form
        :esmovil=false
        :page="page"
        :ticketDeleted="ticketDeleted"
        :user="user"
        @searched="searched"
        @searching="searching"
      />
      <transition name="fade" mode="out-in" v-if="is_searching">
        <spinner />
      </transition>
      <!-- <transition name="fade" mode="out-in" v-else-if="tickets.total > 0"> -->
      <transition name="fade" mode="out-in" v-else-if="tickets.data && Object.keys(tickets.data).length > 0">
        <tickets-table v-if="tickets.total > 0"
          class="d-none d-lg-block"
          :tickets="tickets"
          :permissions="permissions"
          :user-role="user.roles[0].id"
          @page="setPage"
          @ticketDeleted="ticketDeleted = true"
        ></tickets-table>
      </transition>
      <transition name="fade" mode="out-in" v-else>
        <div class="alert alert-info fade show mt-3 mx-3 text-center" role="alert">
          No se han encontrado resultados, por favor, haga una nueva búsqueda
          <i class="fa fa-thumbs-up"></i>
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