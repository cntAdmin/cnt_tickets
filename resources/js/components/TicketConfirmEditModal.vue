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
            
            <a
                class="btn btn-danger"
                :href="`/ticket/${ticket_id}/editar`"
                title="Editar"
            >
              Continuar
            </a>
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
  mounted() {},
  methods: {}
};

</script>

<style scoped>

.bd-orange-500 {
    color: #000;
    background-color: #fd7e14;
    border-color: #fd7e14;
}

</style>