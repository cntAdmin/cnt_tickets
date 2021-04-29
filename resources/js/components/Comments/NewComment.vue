<template>
  <div class="d-flex justify-content-center flex-wrap align-items-center my-4">
    <transition name="fade" mode="out-in" v-if="success.status">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
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
    </transition>
    <transition name="fade" mode="in-out" v-else>
      <form @submit.prevent="handleSubmit" class="form-inline col-12 col-md-10">
        <div class="col-12">
          <ejs-richtexteditor
            class="shadow"
            v-model="newComment"
            :height="400"
            :quickToolbarSettings="quickToolbarSettings"
            :toolbarSettings="toolbarSettings"
          ></ejs-richtexteditor>
        </div>
        <div class="col-12 mt-2 align-self">
          <input
            class="form-control w-100"
            type="file"
            @change="uploadFile"
            multiple
          />
        </div>
        <div class="col-12 mt-2">
          <button class="btn btn-sm btn-info text-light btn-block">
            Enviar comentario
          </button>
        </div>
      </form>
    </transition>
  </div>
</template>

<script>
import {
  Toolbar,
  Image,
  Link,
  HtmlEditor,
  QuickToolbar,
} from "@syncfusion/ej2-vue-richtexteditor";

export default {
  provide: {
    richtexteditor: [Toolbar, Image, Link, HtmlEditor, QuickToolbar],
  },
  props: ["ticket", "userRole"],
  data() {
    return {
      newComment: "",
      success: {
        status: false,
        msg: "",
      },
      files: null,
      quickToolbarSettings: {
        image: [
          "Replace",
          "Align",
          "Caption",
          "Remove",
          "InsertLink",
          "OpenImageLink",
          "-",
          "EditImageLink",
          "RemoveImageLink",
          "Display",
          "AltText",
          "Dimension",
        ],
      },
      toolbarSettings: {
        items: [
          "Bold",
          "Italic",
          "Underline",
          "StrikeThrough",
          "FontName",
          "FontSize",
          "FontColor",
          "BackgroundColor",
          "LowerCase",
          "UpperCase",
          "|",
          "Formats",
          "Alignments",
          "OrderedList",
          "UnorderedList",
          "Outdent",
          "Indent",
          "|",
          "CreateLink",
          "Image",
          "|",
          "ClearFormat",
          "Print",
          "SourceCode",
          "FullScreen",
          "|",
          "Undo",
          "Redo",
        ],
      },
    };
  },
  methods: {
    uploadFile(e) {
      this.files = e.target.files;
    },
    handleSubmit() {
      if (this.newComment.trim() == "") return;

      const formData = new FormData();
      if (this.files) {
        for (const i of Object.keys(this.files)) {
          formData.append(`files[${i}]`, this.files[i]);
        }
      }
      formData.append("comment", this.newComment);

      axios
        .post(`/api/ticket/${this.ticket.id}/comment`, formData, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
        })
        .then((res) => {
          this.success = {
            status: true,
            msg: res.data.msg,
          };
          setTimeout(() => {
            this.success = {
              status: false,
              msg: "",
            };
            this.newComment = "";
            console.log(this.ticket.comments)
            if(this.userRole) {
              this.$emit("submitted");
            } else {
              window.location = `/ticket/comment/${this.ticket.comments[this.ticket.comments.length - 1]._token}`;
            }
          }, 2000);
        })
        .catch((err) => console.log(err.response.data));
    },
  },
};
</script>