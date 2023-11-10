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
            <div class="input-group-text py-1">Departamento</div>
          </div>
          <select v-model="departmentType.department_id" class="form-control">
            <option :value="department.id" v-for="department in departments" :key="department.id">
              {{ department.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Nombre</div>
          </div>
          <input type="text" v-model="departmentType.name" class="form-control" maxlength="100"/>
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
  props: ["departmentType", "buttonText", "type"],
  data() {
    return {
      departments: [],
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
  mounted() {
    this.get_all_departments();
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
          .post("/api/department-type", this.departmentType)
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
          .put(`/api/department-type/${this.departmentType.id}`, this.departmentType)
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
    get_all_departments() {
      axios
        .get("/api/get_all_departments")
        .then((res) => {
          this.departments = res.data.departments;
        })
        .catch((err) => console.log(err.response.data));
    },
  },
};
</script>

<style>
</style>