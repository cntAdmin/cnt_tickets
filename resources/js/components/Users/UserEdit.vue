<template>
  <div class="d-flex flex-column justify-content-center pb-5">
    <div class="card shadow">
      <div class="card-header">
        <div class="d-flex justify-content-between flex-row align-items-center">
          <div class="mr-auto">
            <span class="font-weight-bold text-uppercase"># {{ user.id }}</span>
          </div>
          <div class="ml-auto">
            <a :href="`/user`" class="btn btn-sm btn-info text-white"
              >Volver al listado</a
            >
          </div>
        </div>
      </div>
      <div class="card-body">
        <user-form
          :user="user"
          :user-role="userRole"
          :editable="true"
          buttonText="Actualizar usuario"
          @success="redirectUser"
        />
      </div>
    </div>
  </div>
</template>

<script>
import UserForm from "./UserForm.vue";
export default {
  components: { UserForm },
  props: ["user", "userRole"],
  methods: {
    redirectUser() {
      window.location = "/user";
    },
  },
};
</script>

<style>
</style>