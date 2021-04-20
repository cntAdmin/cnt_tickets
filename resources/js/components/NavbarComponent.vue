<template>
  <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark px-5">
    <a class="navbar-brand" href="/dashboard">
      <img src="/cnt_logo.png" width="150" height="48" />
    </a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- {{-- LEFT PART --}} -->
      <ul class="navbar-nav mr-auto align-items-center">
        <li class="nav-item dropdown" v-if="user.roles[0].id === 1">
          <a
            class="nav-link"
            href="!#"
            id="adminDropdown"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <button
              class="btn btn-dark border border-light rounded font-weight-bold text-uppercase dropdown-toggle"
            >
              <i class="fa fa-user-cog"></i>
              <span class="ml-2">Admin</span>
            </button>
          </a>
          <div class="dropdown-menu" aria-labelledby="adminDropdown">
            <div class="col-12 align-items-center">
              <a
                class="dropdown-item d-inline-flex"
                href="/customer"
                v-if="permissions.find((permission) => permission.name == 'customer.show')">
                <div class="col-2 text-right">
                  <i class="fa fa-building"></i>
                </div>
                <div class="col-10 text-left">Clientes</div>
              </a>
            </div>
            <div class="col-12 align-items-center">
              <a
                class="dropdown-item d-inline-flex"
                href="/user"
                v-if="
                  permissions.find(
                    (permission) => permission.name == 'user.show'
                  )
                "
              >
                <div class="col-2 text-right">
                  <i class="fa fa-users"></i>
                </div>
                <div class="col-10 text-left">Usuarios</div>
              </a>
            </div>
            <div class="col-12 align-items-center">
              <a
                class="dropdown-item d-inline-flex"
                href="/ticket-status"
                v-if="
                  permissions.find(
                    (permission) => permission.name == 'ticket_status.show'
                  )
                "
              >
                <div class="col-2 text-right">
                  <i class="fa fa-check"></i>
                </div>
                <div class="col-10 text-left">Estados</div>
              </a>
            </div>
            <div class="col-12 align-items-center">
              <a
                class="dropdown-item d-inline-flex"
                href="/department"
                v-if="
                  permissions.find(
                    (permission) => permission.name == 'department.show'
                  )
                "
              >
                <div class="col-2 text-right">
                  <i class="fa fa-plus-circle"></i>
                </div>
                <div class="col-10 text-left">Departamentos</div>
              </a>
            </div>
            <div class="col-12 align-items-center">
              <a
                class="dropdown-item d-inline-flex"
                href="/department-type"
                v-if="
                  permissions.find(
                    (permission) => permission.name == 'department_type.show'
                  )
                "
              >
                <div class="col-2 text-right">
                  <i class="fa fa-plus"></i>
                </div>
                <div class="col-10 text-left">Sub Departamentos</div>
              </a>
            </div>
            <div class="col-12 align-items-center">
              <a
                class="dropdown-item d-inline-flex"
                href="/origin-type"
                v-if="
                  permissions.find(
                    (permission) => permission.name == 'origin_type.show'
                  )
                "
              >
                <div class="col-2 text-right">
                  <i class="fa fa-ticket-alt"></i>
                </div>
                <div class="col-10 text-left">Procedencias</div>
              </a>
            </div>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a
            class="nav-link"
            href="!#"
            id="ticketsDropdown"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <button
              class="btn btn-dark border border-light rounded font-weight-bold text-uppercase dropdown-toggle"
            >
              <i class="fa fa-clipboard-list"></i>
              <span class="ml-2">Tickets</span>
            </button>
          </a>
          <div class="dropdown-menu" aria-labelledby="ticketsDropdown">
            <div class="col-12 align-items-center">
              <a
                class="dropdown-item d-inline-flex"
                href="/ticket"
                v-if="
                  permissions.find(
                    (permission) => permission.name == 'ticket.show'
                  )
                "
              >
                <div class="col-2 text-right">
                  <i class="fa fa-ticket-alt"></i>
                </div>
                <div class="col-10 text-left">Todos los tickets</div>
              </a>
              <a
                class="dropdown-item d-inline-flex"
                :href="`/ticket?ticket-status=${ticketStatus.id}`"
                v-for="ticketStatus in ticketStatuses"
                :key="ticketStatus.id"
              >
                <div class="col-2 text-right">
                  <i :class="`fa fa-${ticketStatus.icon}`"></i>
                </div>
                <div class="col-10 text-left" v-if="ticketStatus.id === 1">
                  {{ ticketStatus.name }} / No leído
                </div>
                <div class="col-10 text-left" v-else>
                  {{ ticketStatus.name }}
                </div>
              </a>
            </div>
          </div>
        </li>
      </ul>
      <!-- {{-- RIGHT PART --}} -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a
            id="usernameDropdown"
            class="nav-link dropdown-toggle"
            href="#"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <i class="fa faw fa-user-circle"></i>
            <span class="text-lowercase ml-2">{{ user.username }}</span>
          </a>

          <div
            class="dropdown-menu dropdown-menu-right"
            aria-labelledby="usernameDropdown"
          >
            <a
              class="dropdown-item d-inline-flex"
              :href="`/profile/${user.id}`"
              v-if="permissions.find((permission) => permission.name == 'user.show')">
              <div class="col-2 text-right">
                <i class="fa fa-user"></i>
              </div>
              <div class="col-10 text-left">Mi Perfil</div>
            </a>
            <button
              class="dropdown-item btn-link"
              onclick="event.preventDefault();  
                                document.getElementById('logout-form').submit();"
            >
              Logout
            </button>

            <form
              id="logout-form"
              action="/logout"
              method="POST"
              class="d-none"
            >
              <input type="hidden" name="_token" :value="csrf" />
            </form>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
export default {
  props: ["user", "permissions"],
  data() {
    return {
      ticketStatuses: [],
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
    };
  },
  mounted() {
    this.get_ticket_statuses();
  },
  methods: {
    get_ticket_statuses() {
      axios
        .get("/api/get_all_ticket_statuses")
        .then((res) => {
          this.ticketStatuses = res.data.ticket_statuses;
        })
        .catch((err) => console.log(err.response.data));
    },
  },
};
</script>

<style>
</style>