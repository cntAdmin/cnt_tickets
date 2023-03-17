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
        <table class="table table-striped text-center">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Nombre</th>
              <th scope="col">Email</th>
              <th scope="col">Teléfono</th>
              <th scope="col">Estado</th>
              <th scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="customer in customers.data" :key="customer.id">
              <th class="text-uppercase">{{ customer.name }}</th>
              <td>{{ customer.email }}</td>
              <td>{{ customer.phone }}</td>
              <td>
                <span class="btn btn-sm disabled" title="Activo" v-if="customer.is_active">
                  <i class="fas fa-check-square text-success"></i>
                </span>
                <span class="btn btn-sm disabled" title="Inactivo" v-else>
                  <i class="fas fa-window-close text-danger"></i>
                </span>
              </td>
              <td>
                <div class="d-flex flex-wrap justify-content-center" style="gap: 0.5rem">
                  <a class="btn btn-sm btn-success" href="/ticket-type/1/ticket/crear" title="Nuevo ticket">
                    <i class="fa fa-ticket"></i>
                    <i class="fa fa-plus"></i>
                  </a>
                  <a v-if="permissions.find((permission) => permission.name == 'customer.update')"
                    class="btn btn-sm btn-info text-white" title="Editar"
                    :href="`/customer/${customer.id}/editar`"
                    ><i class="fa fa-edit"></i
                  ></a>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <pagination
      size="small"
      align="center"
      :data="customers"
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
  props: ["customers", "permissions"],
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
    customerDeleted(data) {
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
        this.$emit("customerDeleted");
      }, 2000);
    },
    customerNotDeleted(data) {
      this.closeAll();
      $("html, body").animate({ scrollTop: 0 }, "slow");
      this.error = {
        status: true,
        msg: data,
      };
    },
    deleteModal(customer) {
      this.showDelete = true;
      this.customer = customer;
    },
  },
};
</script>

<style>
</style>