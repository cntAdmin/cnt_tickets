<template>
  <div class="d-flex flex-column justify-content-center w-100">
    <div class="d-flex flex-wrap justify-content-around">
      <counter
        color="primary"
        :count="users_count"
        title="Usuarios"
        icon="users"
      />
    </div>
    <a
      href="/user/crear"
      class="btn btn-sm btn-block btn-secondary font-weight-bold mt-3"
    >
      <i class="fa fa-plus"></i><span class="ml-2">Crear Usuario</span>
    </a>
    <user-search-form
      :page="page"
      :deleted="deleted"
      @searched="searched"
      @searching="searching"
    />
    <transition name="fade" mode="out-in" v-if="is_searching">
      <spinner />
    </transition>
    <transition
      name="fade"
      mode="out-in"
      v-else-if="users.data && Object.keys(users.data).length > 0"
    >
      <users-table
        class="d-none d-lg-block"
        :users="users"
        :permissions="permissions"
        @page="setPage"
        @deleted="deleted = true"
      />
    </transition>
    <transition name="fade" mode="out-in" v-else>
      <div
        class="alert alert-warning fade show mt-3 mx-3 text-center"
        role="alert"
      >
        <span class="font-weight-bold">
          No se han encontrado resultados, por favor, haga una nueva búsqueda
          <i class="fa fa-thumbs-up"></i>
        </span>
      </div>
    </transition>
  </div>
</template>

<script>
import Spinner from "../Spinner.vue";
import UserSearchForm from "./UserSearchForm.vue";
import UsersTable from "./UsersTable.vue";
export default {
  components: { UserSearchForm, Spinner, UsersTable },
  props: ["users_count", "permissions"],
  data() {
    return {
      users: [],
      page: 1,
      is_searching: false,
      deleted: false,
    };
  },
  methods: {
    searching(data) {
      this.is_searching = data;
    },
    setPage(data) {
      this.page = data;
    },
    searched(data) {
      this.deleted = false;
      this.users = data;
    },
  },
};
</script>

<style>
</style>