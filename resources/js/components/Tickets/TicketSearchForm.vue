<template>
  <div class="flex-row justify-content-center container-fluid mt-3">
    <div class="card w-100 shadow border-dark">
      <div class="card-body">
        <form @submit.prevent="handleSubmit" class="form-inline">
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <label class="sr-only" for="ticket_id">ID</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text d-none d-lg-block">ID</div>
                <div class="input-group-text d-block d-lg-none">
                  <i class="fa fa-hashtag"></i>
                </div>
              </div>
              <input
                type="text"
                class="form-control"
                id="ticket_id"
                placeholder="ID Ticket"
                v-model="search.ticket_id"
              />
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2" v-if="user.roles[0].id === 1">
            <label class="sr-only" for="ticket_id">Cliente</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text d-none d-lg-block py-1">
                  Cliente
                </div>
                <div class="input-group-text d-block d-lg-none py-1">
                  <i class="fa fa-building"></i>
                </div>
              </div>
              <vue-select
                class="col-10 col-lg-8 col-xl-9 px-0"
                transition="vs__fade"
                label="alias"
                itemid="id"
                :options="customers"
                @input="setCustomer"
              >
                <div slot="no-options">No hay opciones con esta búsqueda</div>
                <template slot="option" slot-scope="option">
                  {{ option.id }} -
                  {{ option.alias !== "" ? option.alias : option.name }}
                </template>
              </vue-select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2" v-if="user.roles[0].id === 1">
            <label class="sr-only" for="ticket_id">Agente</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text d-none d-lg-block py-1">
                  Agente
                </div>
                <div class="input-group-text d-block d-lg-none py-1">
                  <i class="fa fa-user-tie"></i>
                </div>
              </div>
              <vue-select
                class="col-10 col-lg-8 col-xl-9 px-0"
                transition="vs__fade"
                label="name"
                itemid="id"
                :options="agents"
                @input="setAgent"
              >
                <div slot="no-options">No hay opciones con esta búsqueda</div>
                <template slot="option" slot-scope="option">
                  {{ option.name }}
                </template>
              </vue-select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <label class="sr-only" for="text">Titulo / Descripción</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text d-none d-lg-block">
                  Titulo / Descripción
                </div>
                <div class="input-group-text d-block d-lg-none">
                  <i class="fa fa-spell-check"></i>
                </div>
              </div>
              <input
                type="text"
                class="form-control"
                id="text"
                placeholder="Buscar este texto..."
                v-model="search.text"
              />
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <label class="sr-only" for="dateFrom">Desde</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text d-none d-lg-block">Desde</div>
                <div class="input-group-text d-block d-lg-none">
                  <i class="fa fa-greater-than"></i>
                  <i class="fa fa-calendar-day"></i>
                </div>
              </div>
              <input
                type="date"
                class="form-control"
                id="dateFrom"
                placeholder="Buscar este texto..."
                v-model="search.dateFrom"
              />
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <label class="sr-only" for="dateTo">Hasta</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text d-none d-lg-block">Hasta</div>
                <div class="input-group-text d-block d-lg-none">
                  <i class="fa fa-less-than"></i>
                  <i class="fa fa-calendar-day"></i>
                </div>
              </div>
              <input
                type="date"
                class="form-control"
                id="dateTo"
                placeholder="Buscar este texto..."
                v-model="search.dateTo"
              />
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <label class="sr-only" for="dateTo">Prioridad</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text d-none d-lg-block">Prioridad</div>
                <div class="input-group-text d-block d-lg-none">
                  <i class="fa fa-exclamation"></i>
                </div>
              </div>
              <select v-model="search.priority_id" class="form-control">
                <option value="" selected>-- TODOS --</option>
                <option
                  :value="priority.id"
                  v-for="priority in priorities"
                  :key="priority.id"
                >
                  {{ priority.name }}
                </option>
              </select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <label class="sr-only" for="dateTo">Estado</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text d-none d-lg-block">Estado</div>
                <div class="input-group-text d-block d-lg-none">
                  <i
                    :class="`fa fa-${ticket_status.icon}`"
                    v-for="ticket_status in ticket_statuses"
                    :key="ticket_status.id"
                  ></i>
                </div>
              </div>
              <select v-model="search.ticket_status_id" class="form-control">
                <option value="" selected>-- TODOS --</option>
                <option
                  :value="ticket_status.id"
                  v-for="ticket_status in ticket_statuses"
                  :key="ticket_status.id"
                >
                  {{ ticket_status.name }}
                </option>
              </select>
            </div>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-sm btn-success btn-block mt-3">
              <i class="fa fa-search"></i><span class="ml-2">Buscar</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["page", "ticketDeleted", "user"],
  data() {
    return {
      agents: [],
      customers: [],
      priorities: [],
      ticket_statuses: [],
      stopLoading: false,
      search: {
        page: 1,
        ticket_id: null,
        customer_id: null,
        agent_id: null,
        ticket_status_id: "",
        priority_id: "",
        text: null,
        offset: 0,
        dateFrom: this.$moment().subtract(30, "days").format("YYYY-MM-DD"),
        dateTo: this.$moment().format("YYYY-MM-DD"),
      },
    };
  },
  mounted() {
    // RECOGEMOS PARAMETROS DE LA URL
    const urlParams = new URLSearchParams(window.location.search);
    const urlTicketStatus = urlParams.get("ticket-status");
    // SI EXISTE TICKET STATUS LO SETEAMOS PARA SU BÚSQUEDA
    if (typeof urlTicketStatus !== "undefined") {
      this.search.ticket_status_id = urlTicketStatus;
    }

    this.handleSubmit();
    this.getCustomers();
    this.getAgents();
    this.get_all_priorities();
    this.get_all_ticket_statuses();
  },
  methods: {
    get_all_ticket_statuses() {
      axios.get("/api/get_all_ticket_statuses").then((res) => {
        this.ticket_statuses = res.data.ticket_statuses;
      });
    },
    get_all_priorities() {
      axios.get("/api/get_all_priorities").then((res) => {
        this.priorities = res.data.priorities;
      });
    },
    setAgent(value) {
      this.search.agent_id = value ? value.id : null;
    },
    setCustomer(value) {
      this.search.customer_id = value ? value.id : null;
    },
    getAgents() {
      axios.get("/api/get_all_agents").then((res) => {
        this.agents = res.data.agents;
      });
    },
    getCustomers() {
      axios.get("/api/get_all_customers").then((res) => {
        this.customers = res.data.customers;
      });
    },
    handleSubmit(e) {
      if (!this.stopLoading) {
        if (this.$screen.breakpoint !== "xs") {
          this.$emit("searching", true);
        }
        if (e == undefined) {
          this.search.page = this.page;
        } else {
          this.search.page = 1;
          this.search.offset = 0;
        }
        this.$emit('mobileSearch', this.search);

        axios
          .get("/api/ticket", {
            params: {
              type: this.$screen.breakpoint == "xs" ? "infinite" : "paginate",
              page: this.search.page,
              customer_id: this.search.customer_id,
              ticket_status_id: this.search.ticket_status_id,
              priority_id: this.search.priority_id,
              agent_id: this.search.agent_id,
              ticket_id: this.search.ticket_id,
              text: this.search.text,
              dateFrom: this.search.dateFrom,
              dateTo: this.search.dateTo,
              offset: this.search.offset,
            },
          })
          .then((res) => {
            if (res.data.tickets.length == 0) {
              return (this.stopLoading = true);
            }
            //   console.log(res.data);
            setTimeout(() => {
              if (this.$screen.breakpoint !== "xs") {
                this.$emit("searching", false);
              }
              this.$emit("searched", res.data.tickets);
            }, 1000);
          })
          .catch((err) => console.log(err));
      }
    },
  },
  watch: {
    page(val) {
      this.handleSubmit();
    },
    ticketDeleted() {
      this.handleSubmit();
    },
  },
};
</script>