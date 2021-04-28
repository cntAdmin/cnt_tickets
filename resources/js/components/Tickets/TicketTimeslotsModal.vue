<template>
  <div
    class="modal fade"
    id="ticketTimeslotsModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="ticketTimeslotsModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ticketTimeslotsModalLabel">
            Seleccione hora de inicio y hora de fin
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
            v-if="success.status"
          >
            <span>{{ success.msg }}</span>
            <button
              type="button"
              class="close"
              data-dismiss="alert"
              aria-label="Close"
              @click="success.status = false"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <form-errors v-if="error.status == true" :errors="error.errors" />
          <form
            @submit.prevent="addDates"
            id="datepicker"
            class="form-inline w-100"
          >
            <div class="col-12">
              <label class="sr-only" for="title">Hora Inicio</label>
              <div class="input-group mt-2">
                <div class="input-group-prepend">
                  <div class="input-group-text d-none d-lg-block py-1">
                    Hora Inicio
                  </div>
                  <div class="input-group-text d-block d-lg-none py-1">
                    <i class="fa fa-heading"></i
                    ><span class="ml-2">Hora Inicio</span>
                  </div>
                </div>
                <input
                  class="form-control"
                  type="datetime-local"
                  v-model="dates.start_date_time"
                />
              </div>
            </div>
            <div class="col-12">
              <label class="sr-only" for="title">Hora Fin</label>
              <div class="input-group mt-2">
                <div class="input-group-prepend">
                  <div class="input-group-text d-none d-lg-block py-1">
                    Hora Fin
                  </div>
                  <div class="input-group-text d-block d-lg-none py-1">
                    <i class="fa fa-heading"></i
                    ><span class="ml-2">Hora Fin</span>
                  </div>
                </div>
                <input
                  class="form-control"
                  type="datetime-local"
                  v-model="dates.end_date_time"
                />
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary text-uppercase"
            data-dismiss="modal"
          >
            Close
          </button>
          <button
            type="submit"
            form="datepicker"
            class="btn btn-success text-uppercase"
          >
            Añadir Fechas
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
  components: { FormErrors },

<script>
import FormErrors from "../FormErrors.vue";

export default {
  components: { FormErrors },
  props: ["ticket_id", "type"],
  data() {
    return {
      error: {
        status: false,
        errors: null,
      },
      success: {
        status: false,
        msg: null,
      },
      dates: {
        ticket_id: this.ticket_id,
        start_date_time: null,
        end_date_time: null,
      },
    };
  },
  methods: {
    closeAll() {
      this.success = {
        status: false,
        msg: null,
      };
      this.error = {
        status: false,
        errors: null,
      };
    },
    addDates() {
      if (!this.dates.start_date_time || !this.dates.end_date_time) return;
      this.closeAll();
      if (this.type !== "new") {
        axios
          .post("/api/ticket-timeslot", this.dates)
          .then((res) => {
            this.success = {
              status: true,
              msg: res.data.msg,
            };
            setTimeout(() => {
              $("#ticketTimeslotsModal").modal("hide");
              this.success = {
                status: false,
                msg: null,
              };
              this.$emit("close", this.dates);
            }, 2000);
          })
          .catch((err) => {
            if (err.response.status !== 500) {
              this.error = {
                status: true,
                errors: err.response.data.errors,
              };
            } else {
              console.log(err.response.data);
            }
          });
      } else {
        $("#ticketTimeslotsModal").modal("hide");
        this.$emit("close", this.dates);
      }
    },
  },
};
</script>

<style>
</style>