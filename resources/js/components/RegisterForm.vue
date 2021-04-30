<template>
  <form @submit.prevent="handleSubmit">
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
    <div v-if="error.status">
      <form-errors
        :errors="error.errors"
        @close="error.status = false"
      ></form-errors>
    </div>

    <div class="d-flex flex-wrap justify-content-center">
      <div class="col-12">
        <h3 class="text-uppercase font-weight-bold">Datos de usuario</h3>
      </div>
      <div class="col-12 col-md-6 mt-2">
        <label class="sr-only" for="name">Nombre</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Nombre</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-text"></i>
            </div>
          </div>
          <input
            type="text"
            v-model="ticket.userName"
            class="form-control"
            autocomplete="name"
          />
        </div>
      </div>
      <div class="col-12 col-md-6 mt-2">
        <label class="sr-only" for="name">email</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Email</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-text"></i>
            </div>
          </div>
          <input
            type="email"
            v-model="ticket.userEmail"
            class="form-control"
            autocomplete="email"
          />
        </div>
      </div>
      <div class="col-12 col-md-6 mt-2">
        <label class="sr-only" for="name">Contraseña</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">
              Contraseña
            </div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-lock"></i>
            </div>
          </div>
          <input
            :type="passwordType ? 'password' : 'text'"
            v-model="ticket.userPassword"
            class="form-control"
            autocomplete="new-password"
          />
          <button
            type="button"
            class="btn btn-sm btn-link text-dark"
            @click="passwordType = !passwordType"
          >
            <i class="fa fa-eye"></i>
          </button>
        </div>
      </div>
      <div class="col-12 col-md-6 mt-2">
        <label class="sr-only" for="name">Confirmar</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Confirmar</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-lock"></i>
              <i class="fa fa-redo-alt"></i>
            </div>
          </div>
          <input
            :type="passwordConfirmationType ? 'password' : 'text'"
            v-model="ticket.userPassword_confirmation"
            class="form-control"
            autocomplete="new-password"
          />
          <button
            type="button"
            class="btn btn-sm btn-link text-dark"
            @click="passwordConfirmationType = !passwordConfirmationType"
          >
            <i class="fa fa-eye"></i>
          </button>
        </div>
      </div>
    </div>
    <div class="d-flex flex-wrap justify-content-start">
      <div class="col-12">
        <hr />
        <h3 class="text-uppercase font-weight-bold">Datos del ticket</h3>
      </div>
      <div class="col-12 col-md-6 mt-2">
        <label class="sr-only" for="ticket_id">Departamento</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">
              Departamento
            </div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-door-open"></i><span class="ml-2">Dep</span>
            </div>
          </div>
          <select
            v-model="ticket.department_id"
            class="form-control"
            @change="get_all_department_types"
          >
            <option
              :value="department.id"
              v-for="department in departments"
              :key="department.id"
            >
              {{ department.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="col-12 col-md-6 mt-2">
        <label class="sr-only" for="ticket_id">Servicio</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Servicio</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-couch"></i><span class="ml-2">Sub. Dep.</span>
            </div>
          </div>
          <select v-model="ticket.department_type_id" class="form-control">
            <option
              :value="department_type.id"
              v-for="department_type in department_types"
              :key="department_type.id"
            >
              {{ department_type.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="col-12 col-md-6 mt-2">
        <label class="sr-only" for="ticket_id">Prioridad</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Prioridad</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-exclamation-circle"></i>
            </div>
          </div>
          <select v-model="ticket.priority_id" class="form-control">
            <option
              :value="priority.id"
              v-for="priority in priorities"
              :key="priority.id"
            >
              {{ priority.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="col-12 mt-2">
        <label class="sr-only" for="title">Título</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Título</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-heading"></i><span class="ml-2">Título</span>
            </div>
          </div>
          <input class="form-control" type="text" v-model="ticket.title" />
        </div>
      </div>
      <div class="col-12 mt-2">
        <label class="sr-only" for="ticket_id">Descripción</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">
              Descripción
            </div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-audio-description"></i
              ><span class="ml-2">Desc.</span>
            </div>
          </div>
          <ejs-richtexteditor
            ref="test"
            :height="400"
            :quickToolbarSettings="quickToolbarSettings"
            :toolbarSettings="toolbarSettings"
            v-model="ticket.description"
          ></ejs-richtexteditor>
        </div>
      </div>
      <div class="col-12 mt-3">
        <input
          class="form-control w-100"
          type="file"
          @change="uploadFile"
          multiple
        />
        <sub>(max. 25MB)</sub>
      </div>

      <div class="col-12 mt-3">
        <button
          class="btn btn-sm btn-primary btn-block"
          :disabled="sending ? true : false"
        >
          Crear
        </button>
      </div>
    </div>
  </form>
</template>

<script>
import {
  Toolbar,
  Image,
  Link,
  HtmlEditor,
  QuickToolbar,
} from "@syncfusion/ej2-vue-richtexteditor";

import TicketForm from "./Tickets/TicketForm.vue";
import UserForm from "./Users/UserForm.vue";
import FormErrors from "./FormErrors.vue";

export default {
  components: { UserForm, TicketForm, FormErrors, FormErrors },
  provide: {
    richtexteditor: [Toolbar, Image, Link, HtmlEditor, QuickToolbar],
  },

  data() {
    return {
      departments: [],
      department_types: [],
      priorities: [],
      files: [],
      passwordType: true,
      passwordConfirmationType: true,
      sending: false,
      ticket: {
        userName: null,
        userEmail: null,
        userPassword: null,
        userPassword_confirmation: null,
        department_id: null,
        department_type_id: null,
        priority_id: null,
        title: null,
        description: null,
      },
      success: {
        status: false,
        msg: null,
      },
      error: {
        status: false,
        errors: null,
      },
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
  mounted() {
    this.get_all_departments();
    this.get_all_priorities();
  },
  methods: {
    handleSubmit() {
      this.closeAll();
      this.sending = true;
      const formData = new FormData();
      if (Object.keys(this.files).length > 0) {
        for (const i of Object.keys(this.files)) {
          formData.append(`files[${i}]`, this.files[i]);
        }
      }
    formData.append(`userName`, this.ticket.userName);
    formData.append(`userEmail`, this.ticket.userEmail);
    formData.append(`userPassword`, this.ticket.userPassword);
    formData.append(`userPassword_confirmation`, this.ticket.userPassword_confirmation);
    formData.append(`department_id`, this.ticket.department_id);
    formData.append(`department_type_id`, this.ticket.department_type_id);
    formData.append(`priority_id`, this.ticket.priority_id);
    formData.append(`title`, this.ticket.title);
    formData.append(`description`, this.ticket.description);

    axios.post('/api/annonymous_ticket', formData)
        .then( res => {
            console.log(res.data);
            this.sending = false;
            this.success = {
                status: true,
                msg: res.data.msg
            },
            setTimeout(() => {
                window.location = "/ticket";
            }, 2000);
        }).catch( error => {
            this.sending = false;
            if(error.response.status === 422) {
                this.error = {
                    status: true,
                    errors: error.response.data.errors
                };
            } else {
                console.log(error.response.data);
            }
        })

    },
    closeAll(){
        this.success = { status: false, msg: null };
        this.error = { status: false, errors: null };
    },
    uploadFile(e) {
      this.files = e.target.files;
    },
    get_all_priorities() {
      axios
        .get("/api/get_all_priorities")
        .then((res) => {
          this.priorities = res.data.priorities;
        })
        .catch((error) => console.log(error.response.data));
    },
    get_all_departments() {
      axios
        .get("/api/get_all_departments")
        .then((res) => {
          this.departments = res.data.departments;
        })
        .catch((error) => console.log(error.response.data));
    },
    get_all_department_types() {
      axios
        .get(`/api/department/${this.ticket.department_id}/department_types`)
        .then((res) => {
          this.department_types = res.data.department_types;
        })
        .catch((error) => console.log(error.response.data));
    },
  },
};
</script>

<style>
</style>