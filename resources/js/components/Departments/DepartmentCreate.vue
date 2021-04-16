<template>
  <div class="card shadow">
    <div class="card-header">
      <div class="d-flex flex-wrap justify-content-between">
        <div class="mr-auto"></div>
        <div class="ml-auto">
          <a href="/department" class="btn btn-sm btn-info text-white"
            >Volver al listado</a
          >
        </div>
      </div>
    </div>

    <div class="card-body">
      <department-form
        :cardTemplate="false"
        :editable="true"
        :department="department"
        type="new"
        buttonText="Crear Departamento"
      />
    </div>
  </div>
</template>

<script>
import DepartmentForm from "./DepartmentForm.vue";
export default {
  components: { DepartmentForm },
  data() {
    return {
      department: {
        agents: [],
      },
    };
  },
};
</script>

<style>
</style>