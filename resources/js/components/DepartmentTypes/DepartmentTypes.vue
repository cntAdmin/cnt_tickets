<template>
  <div class="d-flex flex-column justify-content-center w-100">
    <div class="d-flex flex-wrap justify-content-around">
      <counter
        color="primary"
        :count="departmentTypesCount"
        title="Sub Dep"
        icon="plus"
      />
    </div>
    <a
      href="/department-type/crear"
      class="btn btn-sm btn-block btn-secondary font-weight-bold mt-3"
    >
      <i class="fa fa-plus"></i><span class="ml-2">Crear SubDepartamento</span>
    </a>
    <department-type-search-form
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
      v-else-if="departmentTypes.data && Object.keys(departmentTypes.data).length > 0"
    >
      <department-type-table
        class="d-none d-lg-block"
        :department-types="departmentTypes"
        :permissions="permissions"
        :paginated="true"
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
import Spinner from '../Spinner.vue';
import DepartmentTypeSearchForm from './DepartmentTypeSearchForm.vue';
import DepartmentTypeTable from './DepartmentTypeTable.vue';
export default {
  components: { DepartmentTypeSearchForm, DepartmentTypeTable, Spinner },
  props: ["departmentTypesCount", "permissions"],
  data() {
    return {
      departmentTypes: [],
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
      this.departmentTypes = data;
    },
  },
};
</script>

<style>
</style>