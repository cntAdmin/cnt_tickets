<template>
  <div :class="`justify-content-center mt-3 ${esmovil == true ? 'w-100 flex-row ' : 'row mx-3'}`">
    <div :class="`card shadow border-dark ${esmovil != true ? 'col-12' : ''}`">
      <div class="card-body">
        <form @submit.prevent="handleSubmit" class="form-inline">
          <div class="col-12 col-md-12 col-lg-6 col-xl-6 mt-2">
            <label class="sr-only" for="department">Departamento</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">Departamento</div>
              </div>
              <select v-model="search.department_id" class="form-control">
                <option value="">Todos</option>
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

          <div class="col-12 col-md-12 col-lg-6 col-xl-6 mt-2">
            <label class="sr-only" for="name">Nombre</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">Nombre</div>
              </div>
              <input
                type="text"
                class="form-control"
                id="name"
                v-model="search.name"
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
export default {
  props: ["page", "deleted", "esmovil"],
  data() {
    return {
      agents: [],
      departments: [],
      search: {
        department_id: "",
        name: "",
        agent_id: null,
      },
    };
  },
  mounted() {
    this.get_all_departments();
    // this.handleSubmit();
  },
  methods: {
    get_all_departments() {
      axios
        .get("/api/get_all_departments")
        .then((res) => {
          this.departments = res.data.departments;
        })
        .catch((err) => console.log(err.response.data));
    },
    handleSubmit(e) {
      this.$emit("searching", true);
      if (e == undefined) {
        this.search.page = this.page;
      } else {
        this.search.page = 1;
      }

      console.log(this.search);
      this.$emit("mobileSearch", this.search);

      axios
        .get("/api/department-type", {
          params: this.search,
        })
        .then((res) => {
          this.$emit("searching", false);
          this.$emit("searched", res.data.department_types);
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