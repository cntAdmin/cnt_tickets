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
        :customer="customer"
        :editable="editable ? true : false"
        @setCustomer="setCustomer"
      />
      <div class="col-12 col-md-6 col-lg-4 mt-2">
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
      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <label class="sr-only" for="ticket_id">Facturable</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text d-none d-lg-block py-1">Facturable</div>
            <div class="input-group-text d-block d-lg-none py-1">
              <i class="fa fa-hashtag"></i>
            </div>
          </div>
          <select
            v-model="ticket.invoiceable_type_id"
            class="form-control"
            :disabled="!editable ? true : false"
          >
            <option
              :value="invoiceable_type.id"
              v-for="invoiceable_type in invoiceable_types"
              :key="invoiceable_type.id"
            >
              {{ invoiceable_type.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="d-flex flex-row justify-content-start mt-2 w-100" v-if="timeslots.length > 0">
        <div
          class="col-12 col-md-6 col-lg-3"
          v-for="(date, idx) in timeslots"
          :key="idx"
        >
          <label class="sr-only" for="title">Hora Inicio</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text d-none d-lg-block py-1">
                Hora Inicio
              </div>
              <div class="input-group-text d-block d-lg-none py-1">
                <i class="fa fa-heading"></i
                ><span class="ml-2">Hora Inicio</span>
              </div>
            </div>
            <input
              class="form-control"
              type="datetime-local"
              v-model="date.start_date_time_picker"
              disabled="disabled"
            />
          </div>
          <label class="sr-only" for="title">Hora Fin</label>
          <div class="input-group mt-2">
            <div class="input-group-prepend">
              <div class="input-group-text d-none d-lg-block py-1">
                Hora Fin
              </div>
              <div class="input-group-text d-block d-lg-none py-1">
                <i class="fa fa-heading"></i><span class="ml-2">Hora Fin</span>
              </div>
            </div>
            <input
              class="form-control"
              type="datetime-local"
              v-model="date.end_date_time_picker"
              disabled="disabled"
            />
          </div>
          <button
            type="button"
            class="btn btn-sm btn-danger btn-block"
            @click="deleteDate(date)"
          >
            <i class="fa fa-trash-alt"></i>
          </button>
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
import CustomersDropdownSelect from "../CustomersDropdownSelect.vue";
import FormErrors from "../FormErrors.vue";
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
  components: { CustomersDropdownSelect, FormErrors },
  props: [
    "customer",
    "ticket",
    "editable",
    "buttonText",
    "type",
    "ticketType",
    "timeslots",
  ],
  data() {
    return {
      users: [],
      departments: [],
      department_types: [],
      warranties: [],
      invoiceable_types: [],
      files: [],
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
  beforeMount() {
    console.log(this.ticket)
    if(!this.ticket.user_id) this.ticket.user_id = "";
  },
  mounted() {
    this.get_all_department_types();
    this.get_all_departments();
    this.get_all_warranties();
    this.get_all_invoiceable_types();
  },
  methods: {
    get_all_invoiceable_types(){
      axios.get('/api/get_all_invoiceable_types')
        .then( res => {
          this.invoiceable_types = res.data.invoiceable_types;
        }).catch(error => console.log(error.response))
    },
    deleteDate(date) {
      if (date.start_date_time) {
        axios
          .delete(`/api/ticket-timeslot/${date.id}`)
          .then((res) => {
            this.$emit("deleted", date);
          })
          .catch((error) => console.log(error.response.data));
      } else {
        this.$emit("deleted", date);
      }
    },
    get_all_warranties() {
      axios
        .get("/api/get_all_warranties")
        .then((res) => {
          this.warranties = res.data.warranties;
        })
        .catch((error) => console.log(error.response.data));
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

      this.timeslots.forEach((timeslot, idx) => {
        formData.append(
          `timeslots[${idx}][start_date_time]`,
          this.timeslots[idx].start_date_time_picker
        );
        formData.append(
          `timeslots[${idx}][end_date_time]`,
          this.timeslots[idx].end_date_time_picker
        );
      });

      if (this.type == "new") {
        if (this.ticketType.id)
          formData.append("ticket_type_id", this.ticketType.id);
        if (this.ticket.department_type_id)
          formData.append("department_type_id", this.ticket.department_type_id);
        if (this.ticket.customer_id)
          formData.append("customer_id", this.ticket.customer_id);
        if (this.ticket.user_id)
          formData.append("user_id", this.ticket.user_id);
        if (this.ticket.ticket_status_id)
          formData.append("ticket_status_id", this.ticket.ticket_status_id);
        if (this.ticket.warranty_id)
          formData.append("warranty_id", this.ticket.warranty_id);
        if (this.ticket.title) formData.append("title", this.ticket.title);
        if (this.ticket.description)
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
            // console.log(err.response.data);
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

    setCustomer(customer) {
      this.ticket.customer.id = customer ? customer.id : null;
      this.ticket.customer_id = customer ? customer.id : null;
      if (customer) {
        this.get_customer_users();
      }
    },
    get_customer_users() {
      axios.get(`/api/customer/${this.ticket.customer_id}/user`).then((res) => {
        this.users = res.data.users;
      });
    },
  },
  watch: {
    timeslots() {
      this.timeslots = this.timeslots;
    },
  },
};
</script>

<style>
</style>