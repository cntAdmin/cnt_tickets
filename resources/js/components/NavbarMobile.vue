<template>
  <nav
    class="navbar navbar-dark bg-dark justify-content-center shadow-lg fixed-bottom"
  >
    <ul class="navbar-nav d-flex flex-row justify-content-around w-100 pb-2">
      <li class="nav-item text-white align-items-center">
        <a href="/ticket" class="btn btn-link text-white">
          <i class="far fa-ticket-alt fa-2x"></i>
          <p class="text-decoration-none">Tickets</p>
        </a>
      </li>
      <li class="nav-item text-white align-items-center">
        <a href="/ticket?ticket-status=1" class="btn btn-link text-white">
          <i class="far fa-envelope-open fa-2x"></i>
          <p class="text-decoration-none">No Leídos</p>
        </a>
      </li>
      <li class="nav-item text-white align-items-center">
        <a href="/ticket/crear" class="btn btn-link text-white">
          <i class="far fa-plus fa-2x"></i>
          <p class="text-decoration-none">Nuevo</p>
        </a>
      </li>
      <li class="nav-item text-white align-items-center">
        <button
          type="button"
          class="btn btn-link text-white"
          onclick="event.preventDefault();  
                                document.getElementById('logout-form-mobile').submit();"
        >
          <i class="far fa-sign-out-alt fa-2x"></i>
          <p class="text-decoration-none">SALIR</p>
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