<template>
  <div>
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
    <div v-if="error.status">
      <form-errors
        :errors="error.errors"
        @close="error.status = false"
      ></form-errors>
    </div>

    <form @submit.prevent="handleSubmit" class="form-inline">
      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="name">Nombre</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Nombre</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-hashtag"></i>
            </div>
          </div>
          <input
            class="form-control"
            type="text"
            v-model="ticketStatus.name"
            :disabled="!editable ? true : false"
          />
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="color">Color</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Color</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-hashtag"></i>
            </div>
          </div>
          <input
            class="form-control"
            type="text"
            v-model="ticketStatus.color"
            :disabled="!editable ? true : false"
            placeholder="Elegir un tipo de color de Bootstrap (primary, secondary, success, etc...)"
          />
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="icon">Icono</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Icono</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-hashtag"></i>
            </div>
          </div>
          <input
            class="form-control"
            type="text"
            v-model="ticketStatus.icon"
            :disabled="!editable ? true : false"
            placeholder="Elegir un tipo de icono de fa-icons"
          />
          <a
            href="https://fontawesome.com/icons?d=gallery&p=2&m=free"
            class="btn btn-sm btn-link align-self-center"
            target="_blank"
          >
            <i class="fa fa-link"></i>
          </a>
        </div>
      </div>
      <div class="col-12 mt-3" v-if="editable">
        <button class="btn btn-sm btn-primary btn-block">
          {{ buttonText }}
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import FormErrors from '../FormErrors.vue';
export default {
  components: { FormErrors },
  props: ["ticketStatus", "editable", "type", "buttonText"],
  data() {
    return {
      success: {
        status: false,
        msg: "",
      },
      error: {
        status: false,
        msg: "",
      },
    };
  },
  methods: {
    closeAll() {
      this.success = {
        status: false,
        msg: ""
      };
      this.error = {
        status: false,
        msg: ""
      }
    },
    handleSubmit() {
      if (!this.editable) return;
      this.closeAll();
      if (this.type == "new") {
        axios
          .post(`/api/ticket-status`, this.ticketStatus)
          .then((res) => {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            this.success = {
              status: true,
              msg: res.data.msg,
            };
            setTimeout(() => {
              this.success = {
                status: false,
                msg: "",
              };
              this.$emit("created");
            }, 2000);
          })
          .catch((err) => {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            console.log(err.response.data);
            this.error = {
              status: true,
              errors: err.response.data.errors,
            };
          });
      } else {
        axios
          .put(`/api/ticket-status/${this.ticketStatus.id}`, this.ticketStatus)
          .then((res) => {
            // console.log(res.data)
            $("html, body").animate({ scrollTop: 0 }, "slow");
            this.success = {
              status: true,
              msg: res.data.msg,
            };
            setTimeout(() => {
              this.success = {
                status: false,
                msg: "",
              };
              this.$emit("updated");
            }, 2000);
          })
          .catch((err) => {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            // console.log(err.response.data);
            this.error = {
              status: true,
              errors: err.response.data.errors,
            };
          });
      }
    },
  },
};
</script>

<style>
</style>