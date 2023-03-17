<template>
  <div class="d-flex flex-column justify-content-center w-100">
    <div class="d-flex flex-wrap justify-content-around">
      <counter
        color="primary"
        :count="originTypesCount"
        title="Proced."
        icon="plus"
      />
    </div>
    <div class="row d-flex justify-content-center mt-3">
      <a class="btn btn-secondary text-white shadow-lg mt-1" href="/origin-type/crear">
        <i class="fa fa-plus"></i><span class="ml-2">Nueva procedencia</span>
      </a>
    </div>
    <origin-types-search-form
      :page="page"
      :deleted="deleted"
      @searched="searched"
      @searching="searching"
    />
    <transition name="fade" mode="out-in" v-if="is_searching">
      <spinner />
    </transition>
    <transition name="fade" mode="out-in" v-else-if="originTypes.data && Object.keys(originTypes.data).length > 0">
      <origin-types-table
        class="d-none d-lg-block"
        :origin-types="originTypes"
        :permissions="permissions"
        @page="setPage"
        @deleted="deleted = true"
      />
    </transition>
    <transition name="fade" mode="out-in" v-else>
      <div class="alert alert-warning fade show mt-3 mx-3 text-center" role="alert" >
        <span class="font-weight-bold">
          No se han encontrado resultados, por favor, haga una nueva búsqueda
          <i class="fa fa-thumbs-up"></i>
        </span>
      </div>
    </transition>
  </div>
</template>

<script>
import Spinner from '../Spinner.vue'
import OriginTypesSearchForm from './OriginTypesSearchForm.vue';
import OriginTypesTable from './OriginTypesTable.vue';

export default {
  components: { Spinner, OriginTypesSearchForm, OriginTypesTable },
    props:["originTypesCount", "permissions"],
  data() {
    return {
      originTypes: [],
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
      this.originTypes = data;
    },
  },

}
</script>

<style>
</style>