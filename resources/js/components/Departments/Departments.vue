<template>
  <div class="d-flex flex-column justify-content-center w-100">
    <div class="d-flex flex-wrap justify-content-around">
      <counter
        color="primary"
        :count="departmentsCount"
        title="Depart."
        icon="plus-circle"
      />
    </div>
    <a
      href="/department/crear"
      class="btn btn-sm btn-block btn-secondary font-weight-bold mt-3"
    >
      <i class="fa fa-plus"></i><span class="ml-2">Crear Departamento</span>
    </a>
    <department-search-form
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
      v-else-if="departments.data && Object.keys(departments.data).length > 0"
    >
      <departments-table
        class="d-none d-lg-block"
        :departments="departments"
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
import Spinner from '../Spinner.vue';
import DepartmentSearchForm from "./DepartmentSearchForm.vue";
import DepartmentsTable from './DepartmentsTable.vue';
export default {
  components: { DepartmentSearchForm, Spinner, DepartmentsTable },
  props: ["departmentsCount", "permissions"],
  data() {
    return {
      departments: [],
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
      this.departments = data;
    },
  },
};
</script>

<style>
</style>