<template>
  <div class="card shadow">
    <div class="card-header">
      <div class="d-flex flex-wrap justify-content-between">
        <div class="mr-auto"></div>
        <div class="ml-auto">
          <a href="/department-type" class="btn btn-sm btn-info text-white"
            >Volver al listado</a
          >
        </div>
      </div>
    </div>

    <div class="card-body">
      <department-types-form
        :department-type="departmentType"
        type="new"
        buttonText="Crear Servicio"
        @success="redirectTo"
      />
    </div>
  </div>
</template>

<script>
import DepartmentTypesForm from "./DepartmentTypesForm.vue";
export default {
  components: { DepartmentTypesForm },
  data() {
    return {
      departmentType: {},
    };
  },
};
</script>