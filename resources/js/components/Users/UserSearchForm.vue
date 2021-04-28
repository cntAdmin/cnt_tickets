<template>
  <div class="d-flex flex-row justify-content-center container-fluid mt-3">
    <div class="card w-100 shadow border-dark">
      <div class="card-body">
        <form @submit.prevent="handleSubmit" class="form-inline">
          <customers-dropdown-select :customer="null" :editable="true" />

          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <label class="sr-only" for="ticket_id">Roles</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text d-none d-lg-block py-1">Roles</div>
                <div class="input-group-text d-block d-lg-none py-1">
                  <i class="fa fa-hashtag"></i>
                </div>
              </div>
              <vue-select
                class="col-10 col-lg-8 col-xl-9 px-0"
                transition="vs__fade"
                label="name"
                itemid="id"
                :options="roles"
                @input="setRole"
              >
                <div slot="no-options">No hay opciones con esta búsqueda</div>
                <template slot="option" slot-scope="option">
                  {{ option.name }}
                </template>
              </vue-select>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <label class="sr-only" for="name">Nombre</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text d-none d-lg-block">Nombre</div>
                <div class="input-group-text d-block d-lg-none">
                  <i class="fa fa-hashtag"></i>
                </div>
              </div>
              <input
                type="text"
                class="form-control"
                id="name"
                placeholder="Nombre a buscar"
                v-model="search.name"
              />
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <label class="sr-only" for="email">Email</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text d-none d-lg-block">Email</div>
                <div class="input-group-text d-block d-lg-none">
                  <i class="fa fa-hashtag"></i>
                </div>
              </div>
              <input
                type="text"
                class="form-control"
                id="email"
                placeholder="Dirección de correo electrónico a buscar"
                v-model="search.email"
              />
            </div>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-sm btn-success btn-block mt-3">
              <i class="fa fa-search"></i><span class="ml-2">Buscar</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import CustomersDropdownSelect from "../CustomersDropdownSelect.vue";
export default {
  components: { CustomersDropdownSelect },
  props: ["page", "deleted"],
  data() {
    return {
      customers: [],
      roles: [],
      search: {
        page: 1,
        customer_id: null,
        role_id: null,
        name: null,
        email: null,
      },
    };
  },
  mounted() {
    this.get_all_customers();
    this.get_all_roles();
    this.handleSubmit();
  },
  methods: {
    get_all_roles() {
      axios.get("/api/get_all_roles").then((res) => {
        this.roles = res.data.roles;
      });
    },
    setRole(role) {
      this.search.role_id = role ? role.id : null;
    },
    get_all_customers() {
      axios.get("/api/get_all_customers").then((res) => {
        this.customers = res.data.customers;
      });
    },
    setCustomer(value) {
      this.search.customer_id = value ? value.id : null;
    },
    handleSubmit(e) {
      this.$emit("searching", true);
      if (e == undefined) {
        this.search.page = this.page;
      } else {
        this.search.page = 1;
      }

      axios
        .get("/api/user", {
          params: this.search,
        })
        .then((res) => {
          this.$emit("searching", false);
          this.$emit("searched", res.data.users);
        })
        .catch((err) => console.log(err.response.data));
    },
  },
  watch: {
    page(val) {
      this.handleSubmit();
    },
    deleted() {
      this.handleSubmit();
    },
  },
};
</script>

<style>
</style>