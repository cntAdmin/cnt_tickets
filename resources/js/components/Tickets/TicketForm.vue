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
     
      <customers-dropdown-select
        :customer="ticket.customer"
        :editable="editable"
        @setCustomer="setCustomer"
      />

      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="users">Usuarios</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Usuarios</div>
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

      <div class="col-12 col-md-6 col-lg-4 mt-2" v-if="admins.includes(userRole)">
        <label class="sr-only" for="agent_id">Asignar a</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Asignar a</div>
          </div>
          <vue-select
            class="col-8 px-0"
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
            <div class="input-group-text py-1">ID</div>
          </div>
          <input
            type="text"
            class="form-control"
            id="ticket_id"
            placeholder="ID Ticket"
            v-model="ticket.id"
            disabled
          />
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="ticket_id">Departamento</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Departamento</div>
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
        <label class="sr-only" for="ticket_id">Servicio</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Servicio</div>
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
            <div class="input-group-text py-1">Prioridad</div>
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

      <div class="col-12 col-md-6 col-lg-4 mt-2" v-if="admins.includes(userRole)">
        <label class="sr-only" for="ticket_id">Originado De</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Originado De</div>
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

      <div class="col-12 col-md-6 col-lg-4 mt-2" v-if="admins.includes(userRole)">
        <label class="sr-only" for="ticket_id">Garantia</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Garantia</div>
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

      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="ticket_id">Estado</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Estado</div>
          </div>
          <select
            v-model="ticket.ticket_status_id"
            class="form-control"
            :disabled="!editable ? true : false"
          >
            <option
              :value="status.id"
              v-for="status in statuses"
              :key="status.id"
            >
              {{ status.name }}
            </option>
          </select>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-4 mt-2" v-if="admins.includes(userRole)">
        <label class="sr-only" for="title">Email a cliente</label>
        <div class="input-group">
          <div class="input-group-prepend mr-2">
            <div class="input-group-text py-1">Email a cliente</div>
          </div>
          <input
            class="form-check-input"
            type="checkbox"
            v-model="check_send_email"
            :disabled="!editable ? true : false"
          />
        </div>
      </div>
      
      <div class="col-12 mt-2">
        <label class="sr-only" for="title">Título</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Título</div>
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
            <div class="input-group-text py-1">Descripción</div>
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
        <sub>(max. 25MB)</sub>
      </div>

      <div class="col-12 mt-3" v-if="editable">
        <button
          class="btn btn-sm btn-primary btn-block"
          :disabled="sending ? true : false"
        >
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
import CustomersDropdownSelect from "../CustomersDropdownSelect.vue";

export default {
  components: { FormErrors, CustomersDropdownSelect },
  provide: {
    richtexteditor: [Toolbar, Image, Link, HtmlEditor, QuickToolbar],
  },
  props: [
    "ticket",
    "editable",
    "buttonText",
    "type",
    "customer",
    "ticketType",
    "userRole",
  ],
  data() {
    return {
      warranties: [],
      statuses: [],
      files: [],
      customers: [],
      users: [],
      agents: [],
      admins: [1, 2],
      agentValue: this.ticket.agent ? this.ticket.agent : "",
      departments: [],
      department_types: [],
      priorities: [],
      origins: [],
      sending: false,
      check_send_email: false,
      send_email: '',
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
    if (this.type === "annonymous") {
      this.department_types = [this.ticket.department_type];
      this.departments = [this.ticket.department_type.department];
      this.priorities = [this.ticket.priority];
      this.origins = [this.ticket.origin_type];
      this.warranties = [this.ticket.warranty];
      this.statuses = [this.ticket.ticket_status];
    } else {
      this.get_all_customers();
      this.get_all_agents();
      this.get_all_departments();
      this.get_all_department_types();
      this.get_all_priorities();
      this.get_all_origins();
      this.get_all_waranties();
      this.get_all_ticket_statuses();
      if (this.customer) {
        this.ticket.customer.alias = this.customer.alias;
        this.ticket.customer_id = this.customer.id;
        this.get_customer_users();
      }
    }
  },
  methods: {
    // get_all_waranties() {
    //   axios
    //     .get("/api/get_all_warranties")
    //     .then((res) => {
    //       this.warranties = res.data.warranties;
    //     })
    //     .catch((err) => console.log(err.response.data));
    // },
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
      this.sending = true;
      const formData = new FormData();
      if (Object.keys(this.files).length > 0) {
        for (const i of Object.keys(this.files)) {
          formData.append(`files[${i}]`, this.files[i]);
        }
        if (this.type !== "new") {
          this.uploadFiles(formData);
        }
      }

      if (this.type == "new") {

        // Si es customer...
        if(!this.admins.includes(this.userRole)) {
          this.check_send_email = true;         // Enviar email a cliente
          this.ticket.origin_type_id = 3;       // Web
          this.ticket.warranty_id = 3;          // Sin garantía
          this.ticket.agent_id = null;          // No asignamos agente
          this.ticket.ticket_status_id = 1;     // Estado abierto
        }

        if (this.ticket.customer_id)
          formData.append("customer_id", this.ticket.customer_id);
        if (this.ticket.agent_id)
          formData.append("agent_id", this.ticket.agent_id);
        if (this.ticket.user_id)
          formData.append("user_id", this.ticket.user_id);
        if (this.ticketType.id)
          formData.append("ticket_type_id", this.ticketType.id);
        if (this.ticket.department_type_id)
          formData.append("department_type_id", this.ticket.department_type_id);
        if (this.ticket.priority_id)
          formData.append("priority_id", this.ticket.priority_id);
        if (this.ticket.origin_type_id)
          formData.append("origin_type_id", this.ticket.origin_type_id);
        if (this.ticket.ticket_status_id)
          formData.append("ticket_status_id", this.ticket.ticket_status_id);
        if (this.ticket.warranty_id)
          formData.append("warranty_id", this.ticket.warranty_id);
        if (this.ticket.title) 
          formData.append("title", this.ticket.title);
        if (this.ticket.description)
          formData.append("description", this.ticket.description);

        this.check_send_email == true ? 
              this.send_email = 'si' : 
              this.send_email = 'no'

        formData.append("send_email", this.send_email);

        axios
          .post(`/api/ticket`, formData)
          .then((res) => {
            this.sending = false;
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
              this.$emit("success");
            }, 2000);
          })
          .catch((err) => {
            this.sending = false;
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
              this.$emit("success");
            }, 2000);
          })
          .catch((err) => {
            $("html, body").animate({ scrollTop: 0 }, "slow");
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

      axios.get(`/api/department/${this.ticket.department_type.department_id}/department_types`).then((res) => {
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
    get_all_ticket_statuses() {
      axios.get(`/api/get_all_ticket_statuses`).then((res) => {
        this.statuses = res.data.ticket_statuses;
      });
    },
    get_all_waranties() {
      axios.get("/api/get_all_warranties").then((res) => {
        this.warranties = res.data.warranties;
      });
    }
  },
};
</script>

<style>
</style>