<template>
  <div class="d-flex flex-column justify-content-center pb-5">
    <div class="card shadow">
      <div class="card-header">
        <div class="d-flex justify-content-between flex-row align-items-center">
          <div class="mr-auto">
            <span class="font-weight-bold text-uppercase"
              ># {{ originType.id }}</span
            >
          </div>
          <div class="ml-auto">
            <a href="/origin-type" class="btn btn-sm btn-info text-white"
              >Volver al listado</a
            >
          </div>
        </div>
      </div>
      <div class="card-body">
        <origin-type-form
          buttonText="Actualizar Departamento"
          :origin-type="originType"
          :editable="true"
          @success="redirectTo"
        />
      </div>
    </div>
  </div>
</template>

<script>
import OriginTypeForm from './OriginTypeForm.vue'
export default {
  components: { OriginTypeForm },
  props:["originType"],
  methods: {
    redirectTo() {
      window.location = '/origin-type';
    }
  }
}
</script>

<style>

</style>