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
              <th scope="col">Tickets</th>
              <th scope="col" class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="originType in originTypes.data" :key="originType.id">
              <th scope="row">{{ originType.id }}</th>
              <td>{{ originType.name }}</td>
              <td>{{ originType.tickets_count }}</td>
              <td>
                <div class="d-flex flex-wrap justify-content-around">
                  <a
                    v-if="permissions.find(permission => permission.name =='origin_type.update')"
                    :href="`/origin-type/${originType.id}/editar`"
                    class="btn btn-sm btn-info text-white"
                    ><i class="fa fa-edit"></i>
                  </a>
                  <button
                    v-if="permissions.find(permission => permission.name =='origin_type.destroy')"
                    type="button"
                    class="btn btn-sm btn-danger"
                    title="Borrar Usuario"
                    data-toggle="modal"
                    data-target="#deleteModal"
                    @click="deleteModal(originType)"
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
          :data="originType.id"
          title="Procedencia"
          toDelete="origin-type"
          @success="deleted"
          @error="notDeleted"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["originTypes", "permissions"],
  data() {
    return {
      originType: {},
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
    deleteModal(originType) {
      this.showDelete = true;
      this.originType = originType;
    },
  },
};
</script>

<style>
</style>