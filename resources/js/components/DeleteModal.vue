<template>
  <div
    class="modal fade"
    id="deleteModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="deleteModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">¿Estás seguro?</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Si realmente lo deseas borrar, haz click en borrar 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary text-uppercase" data-dismiss="modal">
            Close
          </button>
          <button type="button" class="btn btn-danger text-uppercase" @click="getDeleted" data-dismiss="modal">borrar {{ title }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data", "title", "toDelete"],
  methods: {
      getDeleted() {
          axios.delete(`/api/${this.toDelete}/${this.data}`)
            .then( res => {
                this.$emit('success', res.data.msg)
            }).catch( err => {
              if([404, 500].includes(err.response.status))
                console.log(err.response.data.message);
              this.$emit('error', err.response.data.msg)
            });
      }
  }
};
</script>

<style>
</style>