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
        <label class="sr-only" for="cif">CIF</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">CIF</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-hashtag"></i>
            </div>
          </div>
          <input
            type="text"
            v-model="customer.cif"
            class="form-control"
            maxlength="10"
          />
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="name">Nombre</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Nombre</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-signature"></i>
            </div>
          </div>
          <input type="text" v-model="customer.name" class="form-control" />
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="alias">Alias</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Alias</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-signature"></i>
              <i class="fa fa-gamepad"></i>
            </div>
          </div>
          <input type="text" v-model="customer.alias" class="form-control" />
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="email">Email</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Email</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-at"></i>
            </div>
          </div>
          <input type="email" v-model="customer.email" class="form-control" />
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="address">Dirección</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Dirección</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-map-marker-alt"></i>
            </div>
          </div>
          <input type="text" v-model="customer.address" class="form-control" />
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="town">Población</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Población</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-city"></i>
            </div>
          </div>
          <input type="text" v-model="customer.town" class="form-control" />
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="postcode">Código Postal</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">
              Código Postal
            </div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-mail-bulk"></i>
            </div>
          </div>
          <input type="text" v-model="customer.postcode" class="form-control" />
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="phone">Teléfono</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Teléfono</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-phone-alt"></i>
            </div>
          </div>
          <input type="text" v-model="customer.phone" class="form-control" />
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="is_active">Estado</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Estado</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-check-circle"></i>
            </div>
          </div>
          <select v-model="customer.is_active" class="form-control">
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
          </select>
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
import FormErrors from "../FormErrors.vue";
export default {
  components: { FormErrors },
  props: ["customer", "editable", "buttonText", "type"],
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
    handleSubmit() {
      if (!this.editable) return;
      if (this.type === "new") {
        axios
          .post("/api/customer", this.customer)
          .then((res) => {
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
            this.error = {
              status: true,
              errors: err.response.data.errors,
            };
          });
      } else {
        axios
          .put(`/api/customer/${this.customer.id}`, this.customer)
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
              this.$emit("success");
            }, 2000);
          })
          .catch((err) => {
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