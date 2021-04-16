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
            type="text"
            v-model="department.name"
            class="form-control"
            maxlength="10"
          />
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="code">Código</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Código</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-hashtag"></i>
            </div>
          </div>
          <input
            type="text"
            v-model="department.code"
            class="form-control"
            maxlength="10"
          />
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="code">Agentes</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Agentes</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-hashtag"></i>
            </div>
          </div>
          <vue-select
            class="col-10 col-lg-8 col-xl-9 px-0"
            transition="vs__fade"
            label="name"
            itemid="id"
            :options="agents"
            @option:selected="setAgent"
            @option:deselected="unsetAgent"
            v-model="agentsSelect"
            :disabled="!editable ? true : false"
            multiple
          >
            <div slot="no-options">No hay opciones con esta búsqueda</div>
            <template slot="option" slot-scope="option">
              {{ option.id }} -
              {{ option.name }}
            </template>
          </vue-select>
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
  props: ["department", "editable", "type", "buttonText"],
  data() {
    return {
      agents: [],
      agentsSelect:[],
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
    this.get_all_agents();
    this.agentsSelect = this.department.agents;
},
  methods: {
    setAgent(value) {
      this.department.agents.push(value);
    },
    unsetAgent(value) {
        this.department.agents.splice(this.department.agents.indexOf(value.id), 1);
    },
    get_all_agents() {
      axios
        .get("/api/get_all_agents")
        .then((res) => {
          this.agents = res.data.agents;
        })
        .catch((err) => console.log(err.response.data));
    },
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
          .post("/api/department", this.department)
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
            console.log(err.response.data.message)
            if(err.response.status == 500) {
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
          .put(`/api/department/${this.department.id}`, this.department)
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
              this.$emit('success')
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