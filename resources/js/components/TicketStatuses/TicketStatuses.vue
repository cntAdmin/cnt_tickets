<template>
  <div class="d-flex flex-column justify-content-center w-100">
    <div class="d-flex flex-wrap justify-content-around">
      <counter
        color="primary"
        :count="ticket_statuses_count"
        title="Estados"
        icon="check-circle"
      />
    </div>
    <a
      href="/ticket-status/crear"
      class="btn btn-sm btn-block btn-secondary font-weight-bold mt-3"
    >
      <i class="fa fa-plus"></i><span class="ml-2">Crear Estado</span>
    </a>
    <ticket-status-search-form
      :page="page"
      :deleted="deleted"
      @searched="searched"
      @searching="searching"
    />
    <transition name="fade" mode="out-in" v-if="is_searching">
      <spinner />
    </transition>

    <transition
      name="fade"
      mode="out-in"
      v-else-if="
        ticketStatuses.data && Object.keys(ticketStatuses.data).length > 0
      "
    >
      <ticket-statuses-table
        class="d-none d-lg-block"
        :ticketStatuses="ticketStatuses"
        @page="setPage"
        @deleted="deleted = true"
      />
    </transition>
    <transition name="fade" mode="out-in" v-else>
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
import Spinner from '../Spinner.vue';
import TicketStatusesTable from './TicketStatusesTable.vue';
import TicketStatusSearchForm from "./TicketStatusSearchForm.vue";
export default {
  components: { TicketStatusSearchForm, Spinner, TicketStatusesTable },
  props: ["ticket_statuses_count"],
  data() {
    return {
      ticketStatuses: [],
      page: 1,
      is_searching: false,
      deleted: false,
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
      this.deleted = false;
      this.ticketStatuses = data;
    },
  },
};
</script>

<style>
</style>