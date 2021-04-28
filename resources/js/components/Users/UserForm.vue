<template>
  <form @submit.prevent="handleSubmit" class="d-flex flex-wrap">
    <div
      class="col-12 alert alert-success alert-dismissible fade show"
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

    <div class="col-12" v-if="error.status">
      <form-errors
        :errors="error.errors"
        @close="error.status = false"
      ></form-errors>
    </div>
    <div
      :class="cardTemplate ? 'col-12 mt-2' : 'col-12 col-md-6 col-lg-3 mt-2'"
      v-if="userRole === 1"
    >
      <label class="sr-only" for="customer">Cliente</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text d-none d-lg-block py-1">Cliente</div>
          <div class="input-group-text d-block d-lg-none py-1">
            <i class="fa fa-text"></i>
          </div>
        </div>
        <vue-select
          class="col-10 col-lg-8 col-xl-9 px-0"
          transition="vs__fade"
          label="name"
          itemid="id"
          :options="customers"
          @input="setCustomer"
          v-model="user.customer.alias"
          :disabled="!editable ? true : false"
        >
          <div slot="no-options">No hay opciones con esta búsqueda</div>
          <template slot="option" slot-scope="option">
            {{ option.id }} - {{ option.name }}
          </template>
        </vue-select>
      </div>
    </div>
    <div
      :class="cardTemplate ? 'col-12 mt-2' : 'col-12 col-md-6 col-lg-3 mt-2'"
      v-if="userRole === 1"
    >
      <label class="sr-only" for="name">Rol</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text d-none d-lg-block py-1">Rol</div>
          <div class="input-group-text d-block d-lg-none py-1">
            <i class="fa fa-user"></i>
          </div>
        </div>
        <select
          v-model="user.role_id"
          class="form-control"
          @change="type === 'new' ? user_role() : null"
        >
          <option :value="role.id" v-for="role in roles" :key="role.id">
            {{ role.name }}
          </option>
        </select>
      </div>
    </div>
    <div
      :class="cardTemplate ? 'col-12 mt-2' : 'col-12 col-md-6 col-lg-3 mt-2'"
      v-if="Object.keys(user.roles).length > 0 && user.roles[0].id == 2"
    >
      <label class="sr-only" for="name">Departamento</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text d-none d-lg-block py-1">
            Departamento
          </div>
          <div class="input-group-text d-block d-lg-none py-1">
            <i class="fa fa-door-open"></i><span class="ml-2">Dep</span>
          </div>
        </div>
        <select v-model="user.department_id" class="form-control">
          <option
            :value="department.id"
            v-for="department in departments"
            :key="department.id"
          >
            {{ department.name }} - {{ department.code }}
          </option>
        </select>
      </div>
    </div>
    <div
      :class="cardTemplate ? 'col-12 mt-2' : 'col-12 col-md-6 col-lg-3 mt-2'"
    >
      <label class="sr-only" for="name">Nombre</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text d-none d-lg-block py-1">Nombre</div>
          <div class="input-group-text d-block d-lg-none py-1">
            <i class="fa fa-text"></i>
          </div>
        </div>
        <input
          type="text"
          v-model="user.name"
          class="form-control"
          autocomplete="name"
        />
      </div>
    </div>
    <div
      :class="cardTemplate ? 'col-12 mt-2' : 'col-12 col-md-6 col-lg-3 mt-2'"
    >
      <label class="sr-only" for="name">Usuario</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text d-none d-lg-block py-1">Usuario</div>
          <div class="input-group-text d-block d-lg-none py-1">
            <i class="fa fa-user"></i>
          </div>
        </div>
        <input
          type="text"
          v-model="user.username"
          class="form-control"
          autocomplete="username"
        />
      </div>
    </div>
    <div
      :class="cardTemplate ? 'col-12 mt-2' : 'col-12 col-md-6 col-lg-3 mt-2'"
    >
      <label class="sr-only" for="name">Email</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text d-none d-lg-block py-1">Email</div>
          <div class="input-group-text d-block d-lg-none py-1">
            <i class="fa fa-at"></i>
          </div>
        </div>
        <input
          type="email"
          v-model="user.email"
          class="form-control"
          autocomplete="email"
        />
      </div>
    </div>
    <div
      :class="cardTemplate ? 'col-12 mt-2' : 'col-12 col-md-6 col-lg-3 mt-2'"
    >
      <label class="sr-only" for="name">Contraseña</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text d-none d-lg-block py-1">Contraseña</div>
          <div class="input-group-text d-block d-lg-none py-1">
            <i class="fa fa-lock"></i>
          </div>
        </div>
        <input
          :type="passwordType ? 'password' : 'text'"
          v-model="user.password"
          class="form-control"
          autocomplete="new-password"
        />
        <button
          type="button"
          class="btn btn-sm btn-link text-dark"
          @click="passwordType = !passwordType"
        >
          <i class="fa fa-eye"></i>
        </button>
      </div>
    </div>
    <div
      :class="cardTemplate ? 'col-12 mt-2' : 'col-12 col-md-6 col-lg-3 mt-2'"
    >
      <label class="sr-only" for="name">Confirmar</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text d-none d-lg-block py-1">Confirmar</div>
          <div class="input-group-text d-block d-lg-none py-1">
            <i class="fa fa-lock"></i>
            <i class="fa fa-redo-alt"></i>
          </div>
        </div>
        <input
          :type="passwordConfirmationType ? 'password' : 'text'"
          v-model="user.password_confirmation"
          class="form-control"
          autocomplete="new-password"
        />
        <button
          type="button"
          class="btn btn-sm btn-link text-dark"
          @click="passwordConfirmationType = !passwordConfirmationType"
        >
          <i class="fa fa-eye"></i>
        </button>
      </div>
    </div>
    <div
      :class="cardTemplate ? 'col-12 mt-2' : 'col-12 col-md-6 col-lg-3 mt-2'"
    >
      <label class="sr-only" for="name">Activo</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text d-none d-lg-block py-1">Activo</div>
          <div class="input-group-text d-block d-lg-none py-1">
            <i class="fa fa-at"></i>
          </div>
        </div>
        <select v-model="user.is_active" class="form-control">
          <option value="1">Activo</option>
          <option value="0">Desactivado</option>
        </select>
      </div>
    </div>

    <div class="col-12 mt-3" v-if="editable">
      <button class="btn btn-sm btn-primary btn-block">
        Actualizar Usuario
      </button>
    </div>
  </form>
</template>

<script>
import FormErrors from "../FormErrors.vue";
export default {
  components: { FormErrors },
  props: ["user", "editable", "cardTemplate", "type", "userRole"],
  data() {
    return {
      customers: [],
      roles: [],
      departments: [],
      passwordType: true,
      passwordConfirmationType: true,
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
  mounted() {
    this.get_all_customers();
    this.get_all_roles();
    this.get_all_departments();
    if (!this.type || this.type !== "new") {
      this.user.role_id = this.user.roles[0].id;
      this.user.department_id = this.user.roles[0].id;
    }
  },
  methods: {
    get_all_departments() {
      axios
        .get("/api/get_all_departments")
        .then((res) => {
          this.departments = res.data.departments;
        })
        .catch((err) => console.log(err.response.data.message));
    },
    user_role() {
      this.user.roles[0].id = this.user.role_id;
    },
    get_all_roles() {
      axios
        .get("/api/get_all_roles")
        .then((res) => {
          this.roles = res.data.roles;
        })
        .catch((err) => console.log(err.response.data.message));
    },
    closeAll() {
      (this.success = {
        status: false,
        msg: "",
      }),
        (this.error = {
          status: false,
          msg: "",
        });
    },
    setCustomer(value) {
      this.user.customer_id = value ? value.id : null;
    },
    get_all_customers() {
      axios
        .get("/api/get_all_customers")
        .then((res) => {
          this.customers = res.data.customers;
        })
        .catch((err) => console.log(err.response.data));
    },
    handleSubmit() {
      this.closeAll();
      if (this.type === "new") {
        this.user.type = "new";
        axios
          .post("/api/user", this.user)
          .then((res) => {
            this.success = {
              status: true,
              msg: res.data.msg,
            };
            setTimeout(() => {
              this.closeAll();
              this.$emit("created");
            }, 2000);
          })
          .catch((err) => {
            // console.log(err.response.data)
            this.error = {
              status: true,
              errors: err.response.data.errors,
            };
          });
      } else {
        axios
          .put(`/api/user/${this.user.id}`, this.user)
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