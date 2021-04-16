<template>
    <div class="card shadow">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between">
                <div class="mr-auto"></div>
                <div class="ml-auto">
                    <a href="/origin-type" class="btn btn-sm btn-info text-white">Volver al listado</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <origin-type-form :origin-type="originType" type="new" buttonText="Crear Procedencia" @success="redirectTo"/>
        </div>
    </div>
</template>

<script>
import OriginTypeForm from './OriginTypeForm.vue'
export default {
  components: { OriginTypeForm },
    data(){
        return{
            originType: {}
        }
    },
    methods: {
        redirectTo() {
            window.location = '/origin-type';
        }
    }
}
</script>