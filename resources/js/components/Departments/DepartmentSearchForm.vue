<template>
  <div class="d-flex flex-row justify-content-center container-fluid mt-3">
    <div class="card w-100 shadow border-dark">
      <div class="card-body">
        <form @submit.prevent="handleSubmit" class="form-inline">
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
            <label class="sr-only" for="name">Usuario</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text d-none d-lg-block py-1">
                  Usuario
                </div>
                <div class="input-group-text d-block d-lg-none">
                  <i class="fa fa-hashtag"></i>
                </div>
              </div>
              <vue-select
                class="col-10 col-lg-8 col-xl-9 px-0"
                transition="vs__fade"
                label="name"
                itemid="id"
                :options="agents"
                @input="setAgent"
              >
                <div slot="no-options">No hay opciones con esta búsqueda</div>
                <template slot="option" slot-scope="option">
                  {{ option.id }} -
                  {{ option.name }}
                </template>
              </vue-select>
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
  props: ["page", "deleted"],
  data() {
    return {
      agents: [],
      search: {
        page: 1,
        name: "",
        agent_id: null,
      },
    };
  },
  mounted() {
    this.handleSubmit();
    this.get_all_agents();
  },
  methods: {
    setAgent(value) {
      this.search.agent_id = value ? value.id : null;
    },
    get_all_agents() {
      axios
        .get("/api/get_all_agents")
        .then((res) => {
          this.agents = res.data.agents;
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
        .get("/api/department", {
          params: this.search,
        })
        .then((res) => {
          this.$emit("searching", false);
          this.$emit("searched", res.data.departments);
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