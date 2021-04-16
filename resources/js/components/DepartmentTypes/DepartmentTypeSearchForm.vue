<template>
  <div class="d-flex flex-row justify-content-center container-fluid mt-3">
    <div class="card w-100 shadow border-dark">
      <div class="card-body">
        <form @submit.prevent="handleSubmit" class="form-inline">
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <label class="sr-only" for="department">Departamento</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text d-none d-lg-block">
                  Departamento
                </div>
                <div class="input-group-text d-block d-lg-none">
                  <i class="fa fa-hashtag"></i>
                </div>
              </div>
              <select v-model="search.department_id" class="form-control">
                <option value="">-- TODOS --</option>
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
  props:["page", "deleted"],
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
    this.handleSubmit();
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

      axios
        .get("/api/department-type", {
          params: this.search,
        })
        .then((res) => {
          setTimeout(() => {
            this.$emit("searching", false);
            this.$emit("searched", res.data.department_types);
          }, 1000);
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