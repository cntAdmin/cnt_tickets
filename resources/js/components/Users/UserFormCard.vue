<template>
  <div class="col-12 col-md-6 col-lg-4 mt-2">
    <div class="card shadow">
      <div class="card-header">
        <span class="font-weight-bold text-uppercase"># {{ user.id }}</span>
      </div>
      <div class="card-body">
        <user-form :user="user" :editable="true" :cardTemplate="true"/>
      </div>
    </div>
  </div>
</template>

<script>
import FormErrors from "../FormErrors.vue";
import UserForm from './UserForm.vue';
export default {
  components: { FormErrors, UserForm },
  props: ["user", "editable"],
  data() {
    return {
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
  methods: {
    handleSubmit() {
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
          console.log(err.response.data);
          this.error = {
            status: true,
            errors: err.response.data.errors,
          };
        });
    },
  },
};
</script>

<style>
</style>