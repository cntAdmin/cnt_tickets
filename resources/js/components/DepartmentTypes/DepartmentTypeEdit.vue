<template>
  <div class="d-flex flex-column justify-content-center pb-5">
    <div class="card shadow">
      <div class="card-header">
        <div class="d-flex justify-content-between flex-row align-items-center">
          <div class="mr-auto">
            <span class="font-weight-bold text-uppercase"
              ># {{ departmentType.id }}</span
            >
          </div>
          <div class="ml-auto">
            <a href="/department-type" class="btn btn-sm btn-info text-white"
              >Volver al listado</a
            >
          </div>
        </div>
      </div>
      <div class="card-body">
        <department-types-form
          buttonText="Actualizar Departamento"
          :department-type="departmentType"
          :editable="true"
          @success="redirectTo"
        />
      </div>
    </div>
  </div>
</template>

<script>
import DepartmentTypesForm from "./DepartmentTypesForm.vue";
export default {
  components: { DepartmentTypesForm },
  props: ["departmentType"],
  methods: {
    redirectTo() {
      window.location = "/department";
    },
  },
};
</script>

<style>
</style>