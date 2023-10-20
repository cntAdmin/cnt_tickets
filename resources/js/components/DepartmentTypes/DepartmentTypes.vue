<template>
  <div class="d-flex flex-column justify-content-center w-100">
    <!-- <div class="d-flex flex-wrap justify-content-around"> -->
    <div class="row">
      <counter
        color="primary"
        :count="departmentTypesCount"
        title="Sub Dep"
        icon="plus"
      />
    </div>
    
    <!-- PARTE MOBILE -->
    <div class="d-flex d-lg-none flex-wrap mt-3" v-if="this.$screen.breakpoint == 'xs' || this.$screen.breakpoint == 'sm' || this.$screen.breakpoint == 'md'">
      <department-type-search-form
        :esmovil=true
        :page="page"
        :deleted="deleted"
        @searched="searched"
        @searching="searching"
        @mobileSearch="mobileSearch"
      />
      <transition name="fade" mode="out-in" v-if="is_searching">
        <spinner />
      </transition>
      <transition name="fade" mode="out-in" v-else-if="departmentTypes.total > 0">
        <department-type-cards
          class="w-100"
          :department-types="departmentTypes"
          :permissions="permissions"
          :formsearch="formsearch"
          @page="setPage"
          @ticketDeleted="deleted = true"
        ></department-type-cards>
      </transition>
      <transition name="fade" mode="out-in" v-else-if="departmentTypes.total == 0">
        <div class="w-100">
          <div class="alert alert-info fade show mt-3 text-center" role="alert">
            No se han encontrado resultados, por favor, haga una nueva búsqueda
            <i class="fa fa-thumbs-up"></i>
          </div>
        </div>
      </transition>
    </div>

    <div v-else>
      <div class="row d-flex justify-content-center mt-3">
        <a class="btn btn-secondary text-white shadow-lg mt-1" href="/department-type/crear">
          <i class="fa fa-plus"></i><span class="ml-2">Nuevo servicio</span>
        </a>
      </div>
      <department-type-search-form
        :esmovil=false
        :page="page"
        :deleted="deleted"
        @searched="searched"
        @searching="searching"
      />
      <transition name="fade" mode="out-in" v-if="is_searching">
        <spinner />
      </transition>
      <transition name="fade" mode="out-in" v-else-if="departmentTypes.total > 0">
        <department-type-table
          class="d-none d-lg-block"
          :department-types="departmentTypes"
          :permissions="permissions"
          :paginated="true"
          @page="setPage"
          @deleted="deleted = true"
        />
      </transition>
      <transition name="fade" mode="out-in" v-else-if="departmentTypes.total == 0">
        <div class="alert alert-info fade show mt-3 mx-3 text-center" role="alert">
            No se han encontrado resultados, por favor, haga una nueva búsqueda
            <i class="fa fa-thumbs-up"></i>
        </div>
      </transition>
    </div>

  </div>
</template>

<script>
import Spinner from '../Spinner.vue';
import DepartmentTypeSearchForm from './DepartmentTypeSearchForm.vue';
import DepartmentTypeTable from './DepartmentTypeTable.vue';
export default {
  components: { DepartmentTypeSearchForm, DepartmentTypeTable, Spinner },
  props: ["departmentTypesCount", "permissions"],
  data() {
    return {
      departmentTypes: [],
      formsearch: [],
      page: 1,
      is_searching: false,
      deleted: false,
    };
  },
  methods: {
    mobileSearch(data) {
      console.log("mobileSearch");
      console.log(data);
      this.formsearch = data;
    },
    searching(data) {
      this.is_searching = data;
    },
    setPage(data) {
      this.page = data;
    },
    searched(data) {
      this.deleted = false;
      this.departmentTypes = data;
    },
  },
};
</script>

<style>
</style>