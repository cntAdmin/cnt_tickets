<template>
  <nav class="navbar navbar-dark bg-dark justify-content-center fixed-bottom">
    <ul class="navbar-nav d-flex flex-row justify-content-around w-100">

      <div class="btn-group dropup">
        <button 
          type="button" 
          class="btn btn-dark" 
          data-toggle="dropdown" 
          aria-haspopup="true" 
          aria-expanded="false"
        >
          <i class="far fa-ticket-alt fa-2x"></i><br/>
          <span>Ticket</span>
        </button>
        <div 
          class="dropdown-menu" 
          style="background-color: #343a40 !important; position: absolute !important;  margin-bottom: 7px;"
        >
          <a href="/ticket" class="btn btn-dark text-white dropdown-item">
            <span class="text-decoration-none">Todos</span>
          </a>
          <a href="/ticket?ticket-status=1" class="btn btn-dark text-white dropdown-item">
            <span class="text-decoration-none">No leídos</span>
          </a>
        </div>
      </div>
      
      <div class="btn-group dropup">
        <button 
          type="button" 
          class="btn btn-dark" 
          data-toggle="dropdown" 
          aria-haspopup="true" 
          aria-expanded="false"
        >
          <i class="far fa-plus fa-2x"></i><br/>
          <span>Nuevo</span>
        </button>
        <div 
          class="dropdown-menu" 
          style="background-color: #343a40 !important; position: absolute !important;  margin-bottom: 7px;"
        >
          <a href="/ticket-type/1/ticket/crear" class="btn btn-dark text-white dropdown-item">
            <span class="text-decoration-none">Ticket</span>
          </a>
          <a v-if="admins.includes(userRole)" href="/ticket-type/2/ticket/crear" class="btn btn-dark text-white dropdown-item">
            <span class="text-decoration-none">Parte</span>
          </a>
        </div>
      </div>

      <li class="nav-item text-white align-items-center">
        <a :href="`/profile/${user.id}`" class="btn btn-dark text-white">
          <i class="far fa-user fa-2x"></i><br/>
          <span class="text-decoration-none">Perfil</span>
        </a>
      </li>

      <li class="nav-item text-white align-items-center">
        <button type="button" class="btn btn-dark text-white" 
          onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
        >
          <i class="far fa-sign-out-alt fa-2x"></i><br/>
          <span class="text-decoration-none">SALIR</span>
        </button>
        <form id="logout-form-mobile" action="/logout" method="POST" class="d-none">
          <input type="hidden" name="_token" :value="csrf" />
        </form>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      admins: [1, 2],
      ticketStatuses: [],
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
    };
  },
};
</script>

<style>
</style>