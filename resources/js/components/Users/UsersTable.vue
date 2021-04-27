<template>
  <div class="card mx-3 shadow mt-3">
    <div class="card-body">
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
      <div
        class="alert alert-danger alert-dismissible fade show"
        role="alert"
        v-if="error.status"
      >
        <span>{{ error.msg }}</span>
        <button
          type="button"
          class="close"
          data-dismiss="alert"
          aria-label="Close"
          @click="error.status = false"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="table-responsive">
        <table class="table table-hover table-striped shadow">
          <thead class="thead-dark">
            <tr>
              <th scope="col" class="text-center"># ID</th>
              <th scope="col">Nombre</th>
              <th scope="col">Usuario</th>
              <th scope="col">Email</th>
              <th scope="col" class="text-center">Estado</th>
              <th scope="col" class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users.data" :key="user.id">
              <th scope="row" class="text-center">{{ user.id }}</th>
              <td>{{ user.name }}</td>
              <td>{{ user.username }}</td>
              <td>{{ user.email }}</td>
              <td>
                <div
                  class="d-flex flex-wrap justify-content-around align-items-center"
                >
                  <button
                    type="button"
                    class="btn btn-sm btn-success"
                    v-if="user.is_active"
                    disabled
                  >
                    <i class="fa fa-check-circle"></i>
                  </button>
                  <button
                    type="button"
                    class="btn btn-sm btn-danger"
                    v-else
                    disabled
                  >
                    <i class="fa fa-times-circle"></i>
                  </button>
                </div>
              </td>
              <td>
                <div
                  class="d-flex flex-wrap justify-content-around align-items-center"
                >
                  <a
                    v-if="permissions.find((permission) => permission.name == 'user.update')"
                    :href="`/user/${user.id}/editar`"
                    class="btn btn-sm btn-info text-white"
                    ><i class="fa fa-edit"></i>
                  </a>
                  <button
                    v-if="permissions.find((permission) => permission.name == 'user.destroy')"
                    type="button"
                    class="btn btn-sm btn-danger"
                    title="Borrar Usuario"
                    data-toggle="modal"
                    data-target="#deleteModal"
                    @click="deleteModal(user)"
                  >
                    <i class="fa fa-trash-alt"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <delete-modal
          v-if="showDelete"
          :data="user.id"
          title="usuario"
          toDelete="user"
          @success="deleted"
          @error="notDeleted"
        />
      </div>
    </div>
    <pagination
      size="small"
      align="center"
      :data="users"
      :limit="3"
      @pagination-change-page="emitPagination"
    >
      <span slot="prev-nav">&lt; Anterior</span>
      <span slot="next-nav">Siguiente &gt;</span>
    </pagination>
  </div>
</template>

<script>
export default {
  props: ["users", "permissions"],
  data() {
    return {
      showDelete: false,
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
    emitPagination(page) {
      this.$emit("page", page);
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
      this.showDelete = false;
    },
    deleted(data) {
      this.closeAll();
      $("html, body").animate({ scrollTop: 0 }, "slow");
      this.success = {
        status: true,
        msg: data,
      };
      setTimeout(() => {
        this.success = {
          status: false,
          msg: "",
        };
        this.$emit("deleted");
      }, 2000);
    },
    notDeleted(data) {
      this.closeAll();
      $("html, body").animate({ scrollTop: 0 }, "slow");
      this.error = {
        status: true,
        msg: data,
      };
    },
    deleteModal(user) {
      this.showDelete = true;
      this.user = user;
    },
  },
};
</script>

<style>
</style>