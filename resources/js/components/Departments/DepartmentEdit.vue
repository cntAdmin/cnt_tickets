<template>
  <div class="d-flex flex-column justify-content-center pb-5">
    <div class="card shadow">
      <div class="card-header">
        <div class="d-flex justify-content-between flex-row align-items-center">
          <div class="mr-auto">
            <span class="font-weight-bold text-uppercase"
              ># {{ department.id }}</span
            >
          </div>
          <div class="ml-auto">
            <a href="/department" class="btn btn-sm btn-info text-white"
              >Volver al listado</a
            >
          </div>
        </div>
      </div>
      <div class="card-body">
        <department-form
          buttonText="Actualizar Departamento"
          :department="department"
          :editable="true"
          @success="redirectTo"
        />
      </div>
    </div>
  </div>
</template>

<script>
import DepartmentForm from './DepartmentForm.vue'
export default {
  components: { DepartmentForm },
    props:["department"],
    methods: {
        redirectTo(){
            window.location = '/department'
        }
    }
}
</script>

<style>

</style>