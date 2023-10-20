<template>
  <div class="d-flex flex-row justify-content-center container-fluid mt-3">
    <div class="card w-100 shadow border-dark">
      <div class="card-body">
        <form @submit.prevent="handleSubmit" class="form-inline">

          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <label class="sr-only" for="name">Nombre</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">Nombre</div>
                <!-- <div class="input-group-text d-block d-lg-none">
                  <i class="fa fa-hashtag"></i>
                </div> -->
              </div>
              <input
                type="text"
                class="form-control"
                id="name"
                v-model="search.name"
              />
            </div>
          </div>

          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <label class="sr-only" for="email">Email</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">Email</div>
                <!-- <div class="input-group-text d-block d-lg-none">
                  <i class="fa fa-hashtag"></i>
                </div> -->
              </div>
              <input
                type="text"
                class="form-control"
                id="email"
                v-model="search.email"
              />
            </div>
          </div>

          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <label class="sr-only" for="is_active">Estado</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">Estado</div>
                <!-- <div class="input-group-text d-block d-lg-none">
                  <i class="fa fa-hashtag"></i>
                </div> -->
              </div>
              <select v-model="search.is_active" class="form-control">
                <option value="" selected>Todos</option>
                <option value="1">Activos</option>
                <option value="2">Inactivos</option>
              </select>
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
  props: ["page", "customerDeleted"],
  data() {
    return {
      search: {
        name: null,
        email: null,
        is_active: "",
      },
    };
  },
  mounted() {
    this.handleSubmit();
  },
  methods: {
    handleSubmit(e) {
      this.$emit("searching", true);
      if (e == undefined) {
        this.search.page = this.page;
      } else {
        this.search.page = 1;
      }

      axios.get("/api/customer", {
        params: {
          page: this.search.page,
          name: this.search.name,
          email: this.search.email,
          is_active: this.search.is_active,
        },
      })
      .then((res) => {
          this.$emit("searching", false);
          this.$emit("searched", res.data.customers);
      })
      .catch((err) => err.response.data);
    },
  },
  watch: {
    page(val) {
      this.handleSubmit();
    },

    customerDeleted() {
      this.handleSubmit();
    },
  },
};
</script>

<style>
</style>