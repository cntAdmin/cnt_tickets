<template>
  <div
    class="modal fade"
    id="ticketConfirmEditModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="ticketConfirmEditModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <!-- MODAL HEADER -->
        <header class="modal-header">
          <slot name="header">
            <h5 class="modal-title" id="ticketConfirmEditModalLabel">
              Confirmar Edición
            </h5>
          </slot>
        </header>

        <!-- MODAL BODY -->
        <section class="modal-body">
          <slot name="body">

              <p>Estás a punto de editar un Parte de Trabajo que está firmado y cerrado.</p>
              <p>Si continúas con la edición, deberás firmarlo nuevamente.</p>

          </slot>
        </section>

        <!-- MODAL FOOTER -->
        <footer class="modal-footer">
          <slot name="footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cancelar
            </button>
            <form @submit.prevent="handleSubmit" class="form-inline" enctype="multipart/form-data">
              <input type="hidden" name="_token" :value="csrf">
              <!-- <a
                class="btn btn-danger"
                :href="`/ticket/${ticket_id}/editar`"
                title="Editar"
              >
                Continuar
              </a> -->
              <button class="btn btn-danger">
                Editar
              </button>
            </form>
          </slot>
        </footer>
      </div>
    </div>
  </div>
</template>

<script>

export default{
  components: {},
  props: ["ticket_id"],
  name: "TicketConfirmEditModal",
  data() {
    return {
      dates: {
        ticket_id: this.ticket_id
      },
    };
  },
  computed: {
    csrf() {
      return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    },
  },
  mounted() {},
  methods: {
    handleSubmit() {
        axios.post('/api/ticket/editar_parte/' + this.ticket_id)
        .then((res) => {
          setTimeout(() => {
            window.location = "/ticket/" + this.ticket_id + '/editar';
          }, 1000);
        })
        .catch((err) => console.log(err.response.data));
    }
  }
};

</script>

<style scoped>

.bd-orange-500 {
    color: #000;
    background-color: #fd7e14;
    border-color: #fd7e14;
}

</style>