<template>
    <div class="card shadow border-dark mt-2">
      <div class="card-body">
        <form @submit.prevent="handleSubmit" class="form-inline">
          <div class="col-12 col-md-12 col-lg-5 col-xl-5 mt-2">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text py-1">Cliente</div>
              </div>
              <vue-select
                class="col-8 px-0"
                transition="vs__fade"
                label="name"
                itemid="id"
                :options="customers"
                @input="setCustomer"
              >
                <div slot="no-options">No hay opciones con esta búsqueda</div>
                <template slot="option" slot-scope="option">
                  {{ option.name }}
                </template>
              </vue-select>
            </div>
          </div>
          <!-- <div class="col-12 col-md-12 col-lg-5 col-xl-5 mt-2" v-if="user.roles[0].id === 1">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text py-1">Agente</div>
              </div>
              <vue-select
                class="col-8 px-0"
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
          </div> -->
          <div class="col-12"></div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text py-1">Referencia Ticket</div>
              </div>
              <input type="text" class="form-control" placeholder="Ej: PRT2023-00001" id="text" v-model="search.custom_ticket_id"/>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text py-1">Titulo / Descripción</div>
              </div>
              <input type="text" class="form-control" id="text" v-model="search.text"/>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text py-1">Desde</div>
              </div>
              <input type="date" class="form-control" id="dateFrom" v-model="search.dateFrom"/>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text py-1">Hasta</div>
              </div>
              <input type="date" class="form-control" id="dateTo" v-model="search.dateTo"/>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text py-1">Prioridad</div>
              </div>
              <select v-model="search.priority_id" class="form-control">
                <option value="" selected>-- TODOS --</option>
                <option :value="priority.id" v-for="priority in priorities" :key="priority.id">
                  {{ priority.name }}
                </option>
              </select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text py-1">Estado</div>
              </div>
              <select v-model="search.ticket_status_id" class="form-control">
                <option value="" selected>-- TODOS --</option>
                <option :value="ticket_status.id" v-for="ticket_status in ticket_statuses" :key="ticket_status.id">
                  {{ ticket_status.name }}
                </option>
              </select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text py-1">Tipo</div>
              </div>
              <select v-model="search.ticket_type_id" class="form-control">
                <option value="" selected>-- TODOS --</option>
                <option :value="ticket_type.id" v-for="ticket_type in ticket_types" :key="ticket_type.id">
                  {{ ticket_type.name }}
                </option>
              </select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2" v-if="user.roles[0].id === 1">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text py-1">Asignado a</div>
              </div>
              <select v-model="search.agent_id" class="form-control">
                <option value="" selected>-- TODOS --</option>
                <option :value="user_asignable.id" v-for="user_asignable in users_asignables" :key="user_asignable.id">
                  {{ user_asignable.name }}
                </option>
              </select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text py-1">Estado factura</div>
              </div>
              <select v-model="search.invoiceable_type_id" class="form-control">
                <option value="" selected>-- TODOS --</option>
                <option :value="invoiceable_type.id" v-for="invoiceable_type in invoiceable_types" :key="invoiceable_type.id">
                  {{ invoiceable_type.name }}
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
</template>

<script>
import CustomersDropdownSelect from "../CustomersDropdownSelect.vue";

export default {
  components: { CustomersDropdownSelect },
  props: ["ticketDeleted", "user"],
  data() {
    return {
      agents: [],
      customers: [],
      priorities: [],
      ticket_statuses: [],
      ticket_types: [],
      invoiceable_types: [],
      users_asignables: [],
      stopLoading: false,
      search: {
        page: 1,
        custom_ticket_id: '',
        invoiceable_type_id: null,
        ticket_id: null,
        customer_id: null,
        agent_id: null,
        ticket_status_id: "",
        priority_id: "",
        ticket_type_id: "",
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
    this.get_all_ticket_types();
    this.get_all_invoiceable_types();
    this.get_all_users_asignables();
  },
  methods: {
    get_all_users_asignables() {
      axios.get('/api/get_all_users_asignables').then( res => {
        this.users_asignables = res.data.users_asignables;
      }).catch( error => console.log(error.response.data))
    },
    get_all_ticket_types() {
      axios.get("/api/get_all_ticket_types").then((res) => {
        this.ticket_types = res.data.ticket_types;
      });
    },
    get_all_invoiceable_types() {
      axios.get("/api/get_all_invoiceable_types").then((res) => {
        this.invoiceable_types = res.data.invoiceable_types;
      });
    },
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
    handleSubmit() {
      this.search.page = 1;
      this.$emit('submitted', this.search);

      // if (!this.stopLoading) {
      //   if (this.$screen.breakpoint !== "xs") {
      //     this.$emit("searching", true);
      //   }
        // if (e == undefined) {
          // this.search.page = this.page;
        // } else {
          // this.search.page = 1;
          // this.search.offset = 0;
        // }
        // this.$emit("mobileSearch", this.search);
        // this.$emit('submitted', this.search);

        // axios.get("/api/ticket", {params: {
        //     page: this.search.page,
        //     customer_id: this.search.customer_id,
        //     ticket_status_id: this.search.ticket_status_id,
        //     ticket_type_id: this.search.ticket_type_id,
        //     priority_id: this.search.priority_id,
        //     agent_id: this.search.agent_id,
        //     ticket_id: this.search.ticket_id,
        //     custom_ticket_id: this.search.custom_ticket_id,
        //     invoiceable_type_id: this.search.invoiceable_type_id,
        //     text: this.search.text,
        //     dateFrom: this.search.dateFrom,
        //     dateTo: this.search.dateTo,
        //     offset: this.search.offset,
        //   },
        // }).then((res) => {
        //   this.$emit("searched", res.data.tickets);
        // }).catch((err) => console.log(err));
      // }
    },
  },
  watch: {
    // page(val) {
    //   this.handleSubmit();
    // },
    ticketDeleted() {
      this.handleSubmit();
    },
  },
};
</script>