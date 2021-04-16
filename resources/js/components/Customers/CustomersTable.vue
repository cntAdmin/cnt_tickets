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
              <th scope="col">Alias</th>
              <th scope="col">Email</th>
              <th scope="col" class="text-center">Teléfono</th>
              <th scope="col" class="text-center">Estado</th>
              <th scope="col" class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="customer in customers.data" :key="customer.id">
              <th scope="row" class="text-center">{{ customer.id }}</th>
              <td>{{ customer.name }}</td>
              <td>{{ customer.alias }}</td>
              <td>{{ customer.email }}</td>
              <td class="text-center">{{ customer.phone }}</td>
              <td class="text-center">
                <button
                  class="btn btn-sm btn-success"
                  v-if="customer.is_active"
                  disabled
                >
                  <i class="fa fa-check-circle"></i>
                </button>
                <button class="btn btn-sm btn-danger" v-else disabled>
                  <i class="fa fa-times-circle"></i>
                </button>
              </td>
              <td>
                <div class="d-flex flex-wrap justify-content-around">
                  <a
                    class="btn btn-sm btn-success"
                    :href="`/customer/${customer.id}/ticket/crear`"
                    >
                    <i class="fa fa-ticket"></i>
                    <i class="fa fa-plus"></i>
                  </a>
                  <a
                    class="btn btn-sm btn-info text-white"
                    :href="`/customer/${customer.id}/editar`"
                    ><i class="fa fa-edit"></i
                  ></a>
                </div>
                <!-- BOTONES DE ACCIONES -->
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
  props: ["customers"],
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