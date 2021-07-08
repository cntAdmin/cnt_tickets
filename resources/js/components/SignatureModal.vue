<template>
  <div
    class="modal fade"
    id="signatureModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="signatureModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <!-- MODAL HEADER -->
        <header class="modal-header">
          <slot name="header">
            <h5 class="modal-title" id="signatureModalLabel">
              Introduce tu firma
            </h5>
          </slot>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
            @click="close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </header>

        <!-- MODAL BODY -->
        <section class="modal-body">
          <slot name="body">
            <form
              @submit.prevent="addSignature"
              id="signpicker"
              class="form-inline w-100"
            >
              <div class="col-12 mt-2">
                  <div class="input-group">
                      <div class="input-group-text d-none d-lg-block py-1">Firma Digital</div>
                  </div>
                  <div class="w-100">
                    <VueSignaturePad
                      id="signature"
                      ref="signaturePad"
                      width="250px"
                      height="250px"
                      class="form-control p-0"
                    />
                  </div>
                  <div>
                      <button 
                          class="btn btn-sm btn-primary"
                          @click.prevent="cleanSign">Limpiar firma
                      </button>
                  </div>
                  
              </div>
            </form>
          </slot>
        </section>

        <!-- MODAL FOOTER -->
        <footer class="modal-footer">
          <slot name="footer">
            <button
              type="button"
              class="btn btn-secondary text-uppercase"
              data-dismiss="modal"
            >
              Cerrar
            </button>
            <button
              type="submit"
              form="signpicker"
              class="btn btn-success text-uppercase"
            >
              Añadir firma
            </button>
          </slot>
        </footer>
      </div>
    </div>
  </div>
</template>

<script>

export default{
  components: {},
  props: ["ticket_id", "type"],
  name: "SignatureModal",
  data() {
    return {
      dates: {
        ticket_id: this.ticket_id,
        signature: '',
      },
    };
  },
  mounted() {
    $('#signature')[0].firstChild.setAttribute('width', '250')
    $('#signature')[0].firstChild.setAttribute('height', '250')
  },
  methods: {
    close(){
      this.$emit('close');
    },
    addSignature() {
      $("#signatureModal").modal("hide");
      this.signature = this.$refs.signaturePad.saveSignature().data;
      this.$emit("firmaEnviadaPorModal", this.signature);
    },
    cleanSign() {
      this.$refs.signaturePad.clearSignature();
    },
  }
};

</script>

<style>
</style>