<template>
  <div class="mb-5">
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

    <form @submit.prevent="handleSubmit" class="form-inline">
      <customers-dropdown-select v-if="admins.includes(userRole)" :customer="ticket.customer" :editable="editable" @setCustomer="setCustomer" />
      
      <div class="col-12 col-md-6 col-lg-4 mt-2" v-if="admins.includes(userRole)">
        <label class="sr-only" for="users">Usuarios</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Usuarios</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-user"></i>
            </div>
          </div>
          <select
            v-model="ticket.user_id"
            class="form-control"
            :disabled="!editable ? true : false"
          >
            <option
              :value="ticket.user_id"
              v-if="Object.keys(users).length === 0"
            >
              {{ ticket.user ? ticket.user.name : "" }}
            </option>
            <option :value="user.id" v-for="user in users" :key="user.id">
              {{ user.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="agent_id">Asignar a</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Asignar a</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-user-tie"></i>
            </div>
          </div>
          <vue-select
            class="col-10 col-lg-8 col-xl-9 px-0"
            transition="vs__fade"
            label="name"
            itemid="id"
            :options="agents"
            @input="setAgent"
            :value="agentValue"
            :disabled="!editable ? true : false"
          >
            <div slot="no-options">No hay opciones con esta búsqueda</div>
            <template slot="option" slot-scope="option">
              {{ option.name }}
            </template>
          </vue-select>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-4 mt-2" v-if="type !== 'new'">
        <label class="sr-only" for="ticket_id">ID</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">ID</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-hashtag"></i>
            </div>
          </div>
          <input
            type="text"
            class="form-control"
            id="ticket_id"
            placeholder="ID Ticket"
            v-model="ticket.id"
            :disabled="!editable ? true : false"
          />
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-4 mt-2">
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
            v-model="ticket.department_type.department_id"
            class="form-control"
            @change="get_all_department_types"
            :disabled="!editable ? true : false"
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
      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="ticket_id">Subdepartamento</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">
              Subdepartamento
            </div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-couch"></i><span class="ml-2">Sub. Dep.</span>
            </div>
          </div>
          <select
            v-model="ticket.department_type_id"
            class="form-control"
            :disabled="!editable ? true : false"
          >
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
      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="ticket_id">Prioridad</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Prioridad</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-exclamation-circle"></i>
            </div>
          </div>
          <select
            v-model="ticket.priority_id"
            class="form-control"
            :disabled="!editable ? true : false"
          >
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
      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="ticket_id">Originado De</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">
              Originado De
            </div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-ticket-alt"></i>
            </div>
          </div>
          <select
            v-model="ticket.origin_type_id"
            class="form-control"
            :disabled="!editable ? true : false"
          >
            <option
              :value="origin.id"
              v-for="origin in origins"
              :key="origin.id"
            >
              {{ origin.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="ticket_id">Garantia</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Garantia</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-hashtag"></i>
            </div>
          </div>
          <select
            v-model="ticket.warranty_id"
            class="form-control"
            :disabled="!editable ? true : false"
          >
            <option
              :value="warranty.id"
              v-for="warranty in warranties"
              :key="warranty.id"
            >
              {{ warranty.name }}
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
          <input
            class="form-control"
            type="text"
            v-model="ticket.title"
            :disabled="!editable ? true : false"
          />
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
            v-if="editable"
            ref="test"
            :height="400"
            :quickToolbarSettings="quickToolbarSettings"
            :toolbarSettings="toolbarSettings"
            v-model="ticket.description"
          ></ejs-richtexteditor>
          <div
            v-else
            class="border w-100 p-3"
            v-html="ticket.description"
          ></div>
        </div>
      </div>
      <div class="col-12 mt-3" v-if="editable">
        <input
          class="form-control w-100"
          type="file"
          @change="uploadFile"
          multiple
        />
      </div>
      <div class="col-12 mt-3" v-if="editable">
        <button class="btn btn-sm btn-primary btn-block">
          {{ buttonText }}
        </button>
      </div>
    </form>
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
import FormErrors from "../FormErrors.vue";
import CustomersDropdownSelect from '../CustomersDropdownSelect.vue';

export default {
  components: { FormErrors, CustomersDropdownSelect },
  provide: {
    richtexteditor: [Toolbar, Image, Link, HtmlEditor, QuickToolbar],
  },
  props: ["ticket", "editable", "buttonText", "type", "customer", "ticketType", "userRole"],
  data() {
    return {
      warranties: [],
      files: [],
      dates: [],
      customers: [],
      users: [],
      agents: [],
      admins: [1, 2],
      agentValue: this.ticket.agent ? this.ticket.agent : "",
      departments: [],
      department_types: [],
      priorities: [],
      origins: [],
      success: {
        status: false,
        msg: "",
      },
      error: {
        status: false,
        errors: [],
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
    // SI ES EDITABLE ASIGNAR VARIABLES A LOS VUE-SELECT
    this.get_all_customers();
    this.get_all_agents();
    this.get_all_departments();
    this.get_all_department_types();
    this.get_all_priorities();
    this.get_all_origins();
    this.get_all_waranties();
    if (this.customer) {
      this.ticket.customer.alias = this.customer.alias;
      this.ticket.customer_id = this.customer.id;
      this.get_customer_users();
    }
  },
  methods: {
    get_all_waranties() {
      axios
        .get("/api/get_all_warranties")
        .then((res) => {
          this.warranties = res.data.warranties;
        })
        .catch((err) => console.log(err.response.data));
    },
    closeAll() {
      this.success.status = false;
      this.error.status = false;
    },
    uploadFile(e) {
      this.files = e.target.files;
    },
    uploadFiles(formData) {
      formData.append("ticket_id", this.ticket.id);

      axios
        .post("/api/attachment", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          // console.log(res.data);
        });
    },
    handleSubmit() {
      if (!this.editable) return;
      this.closeAll();
      const formData = new FormData();
      if (Object.keys(this.files).length > 0) {
        for (const i of Object.keys(this.files)) {
          formData.append(`files[${i}]`, this.files[i]);
        }
        if (this.type !== "new") {
          this.uploadFiles(formData);
        }
      }
      if (Object.keys(this.dates).length > 0) {
        for (const i of Object.keys(this.dates)) {
          if (this.type == "new") {
            formData.append(`dates[${i}]`, this.dates[i]);
          } else {
            this.ticket.dates = this.dates;
          }
        }
      }
      console.log(this.ticketType)
      if (this.type == "new") {
        formData.append("ticket_type_id", this.ticketType.id);
        formData.append("department_type_id", this.ticket.department_type_id);
        formData.append("customer_id", this.ticket.customer_id);
        formData.append("agent_id", this.ticket.agent_id);
        formData.append("user_id", this.ticket.user_id);
        formData.append("priority_id", this.ticket.priority_id);
        formData.append("origin_type_id", this.ticket.origin_type_id);
        formData.append("ticket_status_id", this.ticket.ticket_status_id);
        formData.append("warranty_id", this.ticket.warranty_id);
        formData.append("title", this.ticket.title);
        formData.append("description", this.ticket.description);

        axios
          .post(`/api/ticket`, formData)
          .then((res) => {
            // console.log(res.data);
            $("html, body").animate({ scrollTop: 0 }, "slow");
            this.success = {
              status: true,
              msg: res.data.msg,
            };
            setTimeout(() => {
              this.success = {
                status: false,
                msg: "",
              };
              this.$emit("created");
            }, 2000);
          })
          .catch((err) => {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            console.log(err.response.data);
            this.error = {
              status: true,
              errors: err.response.data.errors,
            };
          });
      } else {
        this.ticket.ticketType_id = this.ticketType.id;
        axios
          .put(`/api/ticket/${this.ticket.id}`, this.ticket)
          .then((res) => {
            // console.log(res.data)
            $("html, body").animate({ scrollTop: 0 }, "slow");
            this.success = {
              status: true,
              msg: res.data.msg,
            };
            setTimeout(() => {
              this.success = {
                status: false,
                msg: "",
              };
              this.$emit("updated");
            }, 2000);
          })
          .catch((err) => {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            // console.log(err.response.data);
            this.error = {
              status: true,
              errors: err.response.data.errors,
            };
          });
      }
    },
    setAgent(value) {
      this.ticket.agent_id = value ? value.id : null;
      this.agentValue = value ? value.name : null;
    },
    setCustomer(value) {
      this.ticket.customer.id = value ? value.id : null;
      this.ticket.customer_id = value ? value.id : null;

      if (value) {
        this.get_customer_users();
      }
    },
    get_all_origins() {
      axios.get("/api/get_all_origins").then((res) => {
        this.origins = res.data.origins;
      });
    },
    get_all_priorities() {
      axios.get("/api/get_all_priorities").then((res) => {
        this.priorities = res.data.priorities;
      });
    },
    get_all_department_types() {
      if (!this.ticket.department_type.department_id) return;

      axios
        .get(
          `/api/department/${this.ticket.department_type.department_id}/department_types`
        )
        .then((res) => {
          this.department_types = res.data.department_types;
        });
    },
    get_all_departments() {
      axios.get("/api/get_all_departments").then((res) => {
        this.departments = res.data.departments;
      });
    },
    get_all_customers() {
      axios.get("/api/get_all_customers").then((res) => {
        this.customers = res.data.customers;
      });
    },
    get_customer_users() {
      axios.get(`/api/customer/${this.ticket.customer_id}/user`).then((res) => {
        this.users = res.data.users;
      });
    },
    get_all_agents() {
      axios.get(`/api/get_all_agents`).then((res) => {
        this.agents = res.data.agents;
      });
    },
  },
};
</script>

<style>
</style>