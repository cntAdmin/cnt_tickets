<template>
  <div class="d-flex flex-column justify-content-center w-100">
    <counter
      color="primary"
      icon="building"
      title="Clientes"
      search="customers"
      :count="customer_count"
    />
    
    <div
      v-show="success.status"
      class="alert alert-success alert-dismissible fade show text-center my-3 py-4"
      role="alert"
    >
      {{ success.msg }}

      <button
        type="button"
        class="close"
        aria-label="Close"
        @click="success.status = false"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="row d-flex justify-content-center mt-3">
      <a class="btn btn-secondary text-white shadow-lg mt-1"
        href="/customer/crear">
        <i class="fa fa-plus"></i><span class="ml-2">Nuevo cliente</span>
      </a>
      <button v-if="spinButton"
        class="btn btn-primary text-white shadow-lg mt-1 ml-2" disabled
      >
          <spinner class="mx-auto my-0 p-0 text-white"></spinner>
      </button>
      <button v-else class="btn btn-primary text-white shadow-lg mt-1 ml-2"
        @click="import_siptize_customer">
        <i class="fa fa-tools"></i><span class="ml-2">Importar clientes Dolibarr</span>
      </button>
    </div>

    <customers-search-form
      :page="page"
      :customerDeleted="customerDeleted"
      @searched="searched"
      @searching="searching"
    />
    <transition name="fade" mode="out-in" v-if="is_searching">
      <spinner />
    </transition>
    <transition name="fade" mode="out-in" v-else-if="customers.data && Object.keys(customers.data).length > 0">
    <customers-table 
        class="d-none d-lg-block"
        :customers="customers"
        :permissions="permissions"
        @page="setPage"
        @customerDelete="customerDelete = true"
      />
    </transition>
    <transition name="fade" mode="out-in" v-else-if="customers.data && Object.keys(customers.data).length == 0">
      <div class="alert alert-warning fade show mt-3 mx-3 text-center" role="alert">
        <span class="font-weight-bold">
          No se han encontrado resultados, por favor, haga una nueva búsqueda
          <i class="fa fa-thumbs-up"></i>
        </span>
      </div>
    </transition>
  </div>
</template>

<script>
import Counter from "../Counter.vue";
import Spinner from '../Spinner.vue';
import CustomersSearchForm from "./CustomersSearchForm.vue";
import CustomersTable from './CustomersTable.vue';
export default {
  components: { Counter, CustomersSearchForm, CustomersTable, Spinner },
  props: ["customer_count", "permissions"],
  data() {
    return {
      customers: [],
      page: 1,
      is_searching: false,
      customerDeleted: false,
      spinButton: false,
      success: {
          status: false,
          waiting: false,
          msg: "",
      },
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
      this.customerDeleted = false;
      this.customers = data;
    },
    import_siptize_customer() {
      this.spinButton = true;

      axios.get("/import_siptize_customer").then((res) => {
        setTimeout(() => {
          this.spinButton = false;
          this.success = {
            status: true,
            msg: 'Importación realizada correctamente.',
          };
        }, 3000);
      });
    },
  },
};
</script>
<style>
.fade-enter-active,
.fade-leave-active {
  transition-duration: 0.3s;
  transition-property: opacity;
  transition-timing-function: ease;
}

.fade-enter,
.fade-leave-active {
  opacity: 0;
}
</style>