<template>
  <div class="mt-3">
    <div
      class="alert alert-success alert-dismissible fade show"
      role="alert"
      v-if="success.status"
    >
      <span>{{ success.msg }}</span>
      <button
        type="button"
        class="close"
        data-dismiss="alert"
        aria-label="Close"
        @click="success.status = false"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div
      class="alert alert-danger alert-dismissible fade show"
      role="alert"
      v-if="error.status"
    >
      <span>{{ error.msg }}</span>
      <button
        type="button"
        class="close"
        data-dismiss="alert"
        aria-label="Close"
        @click="error.status = false"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div
      v-for="comment in comments"
      :key="comment.id"
      :class="'col-12 col-md-11 col-lg-10 mt-3 ' + alignDiv(comment)"
    >
      <div class="card shadow">
        <div :class="'card-header text-' + alignText(comment)">
          <h5 class="text-uppercase font-weight-bold">
            {{ comment.user.name }}
          </h5>
        </div>
        <div class="card-body">
          <p v-html="comment.description"></p>
        </div>
        <div class="card-footer">
          <div :class="'row mx-1 align-items-center justify-content-' + alignFooter(comment) ">
            <span :class="'font-weight-bold order-' + dateOrder(comment)">
              {{ comment.created_at | moment("DD-MM-YYYY HH:mm:ss") }}
            </span>
            <button
              v-if="userRole && userRole < 3"
              data-toggle="modal"
              data-target="#deleteModal"
              :class="
                'btn btn-sm btn-danger mx-3 order-' + buttonsOrder(comment)
              "
              @click="deleteModal(comment.id)"
            >
              <i class="fa fa-trash-alt"></i>
            </button>
          </div>
        </div>
      </div>
      <attachments
        class="col-12 col-md-11 col-lg-10"
        :attachments="comment.attachments"
        :align="alignAttachment(comment)"
        @attachmentDeleted="commentDeleted"
      ></attachments>
    </div>

    <delete-modal
      v-if="showDelete"
      title="Comentario"
      toDelete="comment"
      :data="comment_id"
      @success="commentDeleted"
      @error="commentNotDeleted"
    />
  </div>
</template>

<script>
import Attachments from "../Attachments/Attachments.vue";
import DeleteModal from "../DeleteModal.vue";
export default {
  components: { Attachments, DeleteModal },
  props: ["comments", "userRole"],
  data() {
    return {
      success: {
        status: false,
        msg: "",
      },
      error: {
        status: false,
        msg: "",
      },
      showDelete: false,
      comment_id: null,
      admin_users: [1, 2],
    };
  },
  mounted() {
    // console.log(this.userRole)
  },
  methods: {
    commentDeleted(data) {
      $("html, body").animate({ scrollTop: 0 }, "slow");
      this.success = {
        status: true,
        msg: data,
      };
      this.$emit("commentDeleted");
      setTimeout(() => {
        this.success = {
          status: false,
          msg: "",
        };
      }, 1500);
    },
    commentNotDeleted(data) {
      $("html, body").animate({ scrollTop: 0 }, "slow");
      this.error = {
        status: true,
        msg: data,
      };
    },
    deleteModal(id) {
      this.showDelete = true;
      this.comment_id = id;
    },
    alignText(comment) {
      if (this.admin_users.includes(comment.user.roles[0].id)) {
        return "left";
      }
      return "right";
    },
    alignDiv(comment) {
      if (this.admin_users.includes(comment.user.roles[0].id)) {
        return "mr-auto";
      }
      return "ml-auto";
    },
    alignAttachment(comment) {
      if (this.admin_users.includes(comment.user.roles[0].id)) {
        return "start";
      }
      return "end";
    },
    alignFooter(comment){
      if (this.admin_users.includes(comment.user.roles[0].id)) {
        return "start";
      }
      return "end";
    },
    dateOrder(comment) {
      if (this.admin_users.includes(comment.user.roles[0].id)) {
        return "first";
      }
      return "last";
    },
    buttonsOrder(comment) {
      if (this.admin_users.includes(comment.user.roles[0].id)) {
        return "last";
      }
      return "first";
    },
  },
};
</script>