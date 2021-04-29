<template>
  <div class="card shadow">
    <div class="card-header">
      <div class="d-flex flex-wrap justify-content-between">
        <div class="mr-auto"></div>
        <div class="ml-auto">
          <a href="/user" class="btn btn-sm btn-info text-white"
            >Volver al listado</a
          >
        </div>
      </div>
    </div>

    <div class="card-body">
      <user-form
        :cardTemplate="false"
        :editable="true"
        :user="user"
        :user-role="userRole"
        type="new"
        @success="userCreated"
      />
    </div>
  </div>
</template>

<script>
import UserForm from "./UserForm.vue";
export default {
  components: { UserForm },
  props: ["userRole"],
  data() {
    return {
      user: {
        roles: { 0: { id: null } },
        customer: {},
      },
    };
  },
  methods: {
    userCreated(){
      window.location = "/user";
    }
  }
};
</script>

<style>
</style>