<template>
  <div :class="`d-flex flex-wrap mt-3 mx-3 justify-content-${align ? align : 'end'}`">
    <div class="col-12 col-md-6 col-lg-3 mt-2 d-inline-flex"
          :key="attachment.id"
      v-for="attachment in attachments"
    >
    <a
      class="btn btn-success btn-sm btn-block"
      :href="`/api/attachment/${attachment.id}/download`"
      :download="attachment.name"
    >
      <span>{{ attachment.name }}</span>
    </a>
      <button class="btn btn-sm btn-link text-dark" @click="deleteAttachment(attachment.id)"><i class="fa fa-times"></i></button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["attachments", "align"],
  methods: {
    deleteAttachment(id) {
      axios.delete(`/api/attachment/${id}`)
        .then( res => {
          this.$emit('attachmentDeleted')
          // console.log(res.data)
        }).catch( err => console.log(err.response.data))
    }
  }
};
</script>

<style>
</style>