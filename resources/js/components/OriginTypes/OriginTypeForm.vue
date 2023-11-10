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
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Nombre</div>
          </div>
          <input type="text" v-model="originType.name" class="form-control" />
        </div>
      </div>
      <div class="col-12 mt-3">
        <button class="btn btn-sm btn-primary btn-block">
          {{ buttonText }}
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import FormErrors from "../FormErrors.vue";
export default {
  components: { FormErrors },
  props: ["originType", "type", "buttonText"],
  data() {
    return {
      success: {
        status: false,
        msg: "",
      },
      error: {
        status: false,
        errors: [],
      },
    };
  },
  methods: {
    closeAll() {
      this.success = {
        status: false,
        msg: "",
      };
      this.error = {
        status: false,
        msg: "",
      };
    },
    handleSubmit() {
      this.closeAll();
      if (this.type === "new") {
        axios
          .post("/api/origin-type", this.originType)
          .then((res) => {
            this.success = {
              status: true,
              msg: res.data.msg,
            };
            setTimeout(() => {
              this.closeAll();
              this.$emit("success");
            }, 2000);
          })
          .catch((err) => {
            console.log(err.response.data.message);
            if (err.response.status == 500) {
              this.error = {
                status: true,
                errors: err.response.data.message,
              };
            }
            this.error = {
              status: true,
              errors: err.response.data.errors,
            };
          });
      } else {
        axios
          .put(
            `/api/origin-type/${this.originType.id}`,
            this.originType
          )
          .then((res) => {
            //   console.log(res.data);
            this.success = {
              status: true,
              msg: res.data.msg,
            };
            setTimeout(() => {
              this.success = {
                status: false,
                msg: "",
              };
              this.$emit("success");
            }, 2000);
          })
          .catch((err) => {
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