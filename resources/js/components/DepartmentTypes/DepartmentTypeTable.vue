<template>
  <div class="card mx-3 shadow mt-3 border-dark">
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
      <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="error.status"      >
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
        <table class="table table-striped text-center">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Departamento</th>
              <th scope="col">Servicio</th>
              <th scope="col">Tickets</th>
              <th scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="departmentType in paginated ? departmentTypes.data : departmentTypes" :key="departmentType.id">
              <th>{{ departmentType.department.name }}</th>
              <td>{{ departmentType.name }}</td>
              <td>{{ departmentType.tickets_count }}</td>
              <td>
                <div class="d-flex flex-wrap justify-content-center" style="gap: 0.5rem">
                  <a v-if="permissions.find((permission) => permission.name == 'department_type.update')"
                    :href="`/department-type/${departmentType.id}/editar`"
                    class="btn btn-sm btn-info text-white"
                  >
                    <i class="fa fa-edit"></i>
                  </a>
                  <button v-if="permissions.find((permission) => permission.name == 'department_type.destroy')"
                    type="button"
                    class="btn btn-sm btn-danger"
                    title="Borrar Usuario"
                    data-toggle="modal"
                    data-target="#deleteModal"
                    @click="deleteModal(departmentType)"
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
          :data="departmentType.id"
          title="Servicio"
          toDelete="department-type"
          @success="deleted"
          @error="notDeleted"
        />
      </div>
    </div>
    <pagination
      v-if="paginated"
      size="small"
      align="center"
      :data="departmentTypes"
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
  props: ["departmentTypes", "permissions", "paginated"],
  data() {
    return {
      departmentType: {},
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
    deleteModal(departmentType) {
      this.showDelete = true;
      this.departmentType = departmentType;
    },
  },
};
</script>

<style>
</style>