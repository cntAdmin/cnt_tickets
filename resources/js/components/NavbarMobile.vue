<template>
  <!-- <nav class="navbar navbar-dark bg-dark justify-content-center shadow-lg fixed-bottom">
    <ul class="navbar-nav d-flex flex-row justify-content-around w-100 pb-2">

        <div class="btn-group dropup">
          <button 
            type="button" 
            class="btn btn-dark dropdown-toggle" 
            data-toggle="dropdown" 
            aria-haspopup="true" 
            aria-expanded="false"
          >
            <i class="far fa-ticket-alt fa-2x"></i>
            <p>Ticket</p>
          </button>
          <div class="dropdown-menu" style="background-color: #343a40 !important;">
            <a href="/ticket" class="btn btn-dark text-white dropdown-item">
              <p class="text-decoration-none">Todos</p>
            </a>
            <a href="/ticket?ticket-status=1" class="btn btn-dark text-white dropdown-item">
              <p class="text-decoration-none">No leídos</p>
            </a>
          </div>
        </div>

        <div class="btn-group dropup">
          <button 
            type="button" 
            class="btn btn-dark dropdown-toggle" 
            data-toggle="dropdown" 
            aria-haspopup="true" 
            aria-expanded="false"
          >
            <i class="far fa-plus fa-2x"></i>
            <p>Nuevo</p>
          </button>
          <div class="dropdown-menu" style="background-color: #343a40 !important;">
            <a href="/ticket-type/1/ticket/crear" class="btn btn-dark text-white dropdown-item">
              <p class="text-decoration-none">Ticket</p>
            </a>
            <a href="/ticket-type/2/ticket/crear" class="btn btn-dark text-white dropdown-item">
              <p class="text-decoration-none">Parte</p>
            </a>
          </div>
        </div>

      <li class="nav-item text-white align-items-center">
        <a :href="`/profile/${user.id}`" class="btn btn-link text-white">
          <i class="far fa-user fa-2x"></i>
          <p class="text-decoration-none">Perfil</p>
        </a>
      </li>
      <li class="nav-item text-white align-items-center">
        <button
          type="button"
          class="btn btn-link text-white"
          onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
        >
          <i class="far fa-sign-out-alt fa-2x"></i>
          <p class="text-decoration-none">SALIR</p>
        </button>
        <form
          id="logout-form-mobile"
          action="/logout"
          method="POST"
          class="d-none"
        >
          <input type="hidden" name="_token" :value="csrf" />
        </form>
      </li>
    </ul>
  </nav> -->
  <nav class="navbar navbar-dark bg-dark justify-content-center fixed-bottom" style="margin-bottom: 2rem;">
    <ul class="navbar-nav d-flex flex-row justify-content-around w-100">

      <div class="btn-group dropup">
        <button 
          type="button" 
          class="btn btn-dark dropdown-toggle" 
          data-toggle="dropdown" 
          aria-haspopup="true" 
          aria-expanded="false"
        >
          <i class="far fa-ticket-alt fa-2x"></i>
          <p>Ticket</p>
        </button>
        <div class="dropdown-menu" style="background-color: #343a40 !important; position: absolute;">
          <a href="/ticket" class="btn btn-dark text-white dropdown-item">
            <p class="text-decoration-none">Todos</p>
          </a>
          <a href="/ticket?ticket-status=1" class="btn btn-dark text-white dropdown-item">
            <p class="text-decoration-none">No leídos</p>
          </a>
        </div>
      </div>
      
      <div class="btn-group dropup">
        <button 
          type="button" 
          class="btn btn-dark dropdown-toggle" 
          data-toggle="dropdown" 
          aria-haspopup="true" 
          aria-expanded="false"
        >
          <i class="far fa-plus fa-2x"></i>
          <p>Nuevo</p>
        </button>
        <div class="dropdown-menu" style="background-color: #343a40 !important;  position: absolute;">
          <a href="/ticket-type/1/ticket/crear" class="btn btn-dark text-white dropdown-item">
            <p class="text-decoration-none">Ticket</p>
          </a>
          <a href="/ticket-type/2/ticket/crear" class="btn btn-dark text-white dropdown-item">
            <p class="text-decoration-none">Parte</p>
          </a>
        </div>
      </div>

      <li class="nav-item text-white align-items-center">
        <a :href="`/profile/${user.id}`" class="btn btn-link text-white">
          <i class="far fa-user fa-2x"></i>
          <p class="text-decoration-none">Perfil</p>
        </a>
      </li>

      <li class="nav-item text-white align-items-center">
        <button type="button" class="btn btn-link text-white" 
          onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
        >
          <i class="far fa-sign-out-alt fa-2x"></i>
          <p class="text-decoration-none">SALIR</p>
        </button>
        <form id="logout-form-mobile" action="/logout" method="POST" class="d-none">
          <input type="hidden" name="_token" :value="csrf" />
        </form>
      </li>

      <!-- <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Features</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Pricing</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown link
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li> -->
    </ul>
  </nav>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
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