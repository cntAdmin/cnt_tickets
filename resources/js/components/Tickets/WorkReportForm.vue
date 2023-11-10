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

    <form @submit.prevent="handleSubmit" class="form-inline" enctype="multipart/form-data">
      <input type="hidden" name="_token" :value="csrf">

      <customers-dropdown-select
        :customer="ticket.customer"
        :editable="editable ? true : false"
        @setCustomer="setCustomer"
      />

      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Usuarios</div>
          </div>
          <select v-model="ticket.user_id" class="form-control" :disabled="!editable ? true : false">
            <option :value="ticket.user_id" v-if="Object.keys(users).length === 0">
              {{ ticket.user ? ticket.user.name : "" }}
            </option>
            <option :value="user.id" v-for="user in users" :key="user.id">
              {{ user.name }}
            </option>
          </select>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">
              Departamento
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
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Servicio</div>
          </div>
          <select v-model="ticket.department_type_id" class="form-control" :disabled="!editable ? true : false">
            <option :value="department_type.id" v-for="department_type in department_types" :key="department_type.id">
              {{ department_type.name }}
            </option>
          </select>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Garantia</div>
          </div>
          <select v-model="ticket.warranty_id" class="form-control" :disabled="!editable ? true : false">
            <option :value="warranty.id" v-for="warranty in warranties" :key="warranty.id">
              {{ warranty.name }}
            </option>
          </select>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-4 mt-2">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Facturable</div>
          </div>
          <select v-model="ticket.invoiceable_type_id" class="form-control" :disabled="!editable ? true : false">
            <option :value="invoiceable_type.id" v-for="invoiceable_type in invoiceable_types" :key="invoiceable_type.id">
              {{ invoiceable_type.name }}
            </option>
          </select>
        </div>
      </div>

      <div class="col-12 mt-2">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Título</div>
          </div>
          <input class="form-control" type="text" v-model="ticket.title" :disabled="!editable ? true : false"/>
        </div>
      </div>  

      <!-- HORAS REGISTRADAS, SI EXISTEN -->
      <div class="col-12 mt-3" v-if="timeslots.length > 0">
        <hr>
        <h5>Horas de trabajo registradas</h5>
      </div>
      <div class="d-flex flex-row justify-content-start mb-3 w-100" v-if="timeslots.length > 0">
        <div class="col-12 col-md-6 col-lg-4 mt-2" v-for="(date, idx) in timeslots" :key="idx">
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text py-1">Hora Inicio</div>
            </div>
            <input class="form-control" type="datetime-local" v-model="date.start_date_time_picker" disabled="disabled"/>
          </div>
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text py-1">Tiempo Trabajado</div>
            </div>
            <input class="form-control" v-model="date.work_time" disabled="disabled"/>
          </div> 
          <button v-if="editable" type="button" class="btn btn-sm btn-danger btn-block" @click="deleteDate(date)">
            <i class="fa fa-trash-alt"></i>
          </button>
        </div>
      </div>
      <div class="col-12 mb-1" v-if="timeslots.length > 0">
        <hr>
      </div>

      <div class="col-12 mt-3" v-if="ticket.is_signed && !editable">
        <h5>Firma</h5>
        <img :src="`../../storage/${ticket.signature}`" alt="firma">
        <hr>
      </div>

      <div class="col-12 mt-2">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text py-1">Descripción</div>
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

      <div class="col-12 mt-3" v-if="editable">
        <input class="form-control w-100" type="file" @change="uploadFile" multiple/>
        <sub>(max. 25MB)</sub>
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
    "customerSign",
  ],
  data() {
    return {
      ticketEdit:{},  // fix csrf token missmatch...
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
  computed: {
    csrf() {
      return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    },
  },
  beforeMount() {
    if (!this.ticket.user_id) this.ticket.user_id = "";
  },
  mounted() {
    this.get_all_department_types();
    this.get_all_departments();
    this.get_all_warranties();
    this.get_all_invoiceable_types();
    if(this.editable){
      this.ticket.signature = null;
      this.ticket.is_signed = 0;
    }
  },
  methods: {
    get_all_invoiceable_types() {
      axios.get("/api/get_all_invoiceable_types").then((res) => {
        this.invoiceable_types = res.data.invoiceable_types;
      }).catch((error) => 
        console.log(error.response)
      );
    },
    deleteDate(date) {
      if (date.start_date_time) {
        axios.delete(`/api/ticket-timeslot/${date.id}`).then((res) => {
          this.$emit("deleted", date);
        }).catch((error) => 
          console.log(error.response.data)
        );
      } else {
        this.$emit("deleted", date);
      }
    },
    get_all_warranties() {
      axios.get("/api/get_all_warranties").then((res) => {
        this.warranties = res.data.warranties;
      }).catch((error) => 
        console.log(error.response.data)
      );
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
      axios.post("/api/attachment", formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      }).then((res) => {
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

      // A cada timeslots le asigna valores.
      this.timeslots.forEach((timeslot, idx) => {
        formData.append(
          `timeslots[${idx}][start_date_time]`,
          this.timeslots[idx].start_date_time_picker
        );
        formData.append(
          `timeslots[${idx}][end_date_time]`,
          this.timeslots[idx].end_date_time_picker
        );
        formData.append(
         `timeslots[${idx}][work_time]`,
          this.timeslots[idx].work_time
        );
      });

      this.ticket.ticket_status_id = 1;
      
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
        if(this.ticket.invoiceable_type_id)
          formData.append("invoiceable_type_id", this.ticket.invoiceable_type_id);
        if (this.ticket.title) 
          formData.append("title", this.ticket.title);
        if (this.ticket.description)
          formData.append("description", this.ticket.description);
        if(this.customerSign)
          formData.append("signature", this.customerSign);

        axios.post(`/api/ticket`, formData).then((res) => {
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
        }).catch((err) => {
          $("html, body").animate({ scrollTop: 0 }, "slow");
          this.error = {
            status: true,
            errors: err.response.data.errors,
          };
        });
      } 
      else {
        this.ticket.ticketType_id = this.ticketType.id;

        // En EDIT, si hay timeslots añadidos previamente, debemos validar primero si están insertados y si están quitarlos ya que sino los duplica en BD.
        if(this.timeslots.length > 0){
          for(var element=0; element<this.timeslots.length; element++){ 
            // Si ya está insertado, lo eliminamos del array de fechas auxiliar.
            if(this.timeslots[element].inserted === 1){ 
              this.timeslots.splice(element, 1);
              element--;
            }
          }
          // Finalizada la comprobación, metemos el array de fechas auxiliar en this.ticket.timeslots para cargarlos en BD sólo los nuevos
          this.ticket.timeslots = this.timeslots;
        }
        this.ticket.signature = this.customerSign;


        // Por alguna razón, si enviamos this.ticket por POST tira un error de csrf token missmatch
        // Por tanto asignamos cada campo de forma manual a this.ticketEdit y enviamos this.ticketEdit.
        this.ticketEdit.agent = this.ticket.agent;
        this.ticketEdit.agent_id = this.ticket.agent_id;
        this.ticketEdit.attachments = this.ticket.attachments;
        this.ticketEdit.created_at = this.ticket.created_at;
        this.ticketEdit.created_by = this.ticket.created_by;
        this.ticketEdit.created_by_user = this.ticket.created_by_user;
        this.ticketEdit.custom_id = this.ticket.custom_id;
        this.ticketEdit.customer = this.ticket.customer;
        this.ticketEdit.customer_id = this.ticket.customer_id;
        this.ticketEdit.deleted_at = this.ticket.deleted_at;
        this.ticketEdit.deleted_by = this.ticket.deleted_by;
        this.ticketEdit.department_type = this.ticket.department_type;
        this.ticketEdit.department_type_id = this.ticket.department_type_id;
        this.ticketEdit.description = this.ticket.description;
        this.ticketEdit.description_short = this.ticket.description_short;
        this.ticketEdit.expires_in = this.ticket.expires_in;
        this.ticketEdit.id = this.ticket.id;
        this.ticketEdit.invoiceable_type = this.ticket.invoiceable_type;
        this.ticketEdit.invoiceable_type_id = this.ticket.invoiceable_type_id;

        this.ticketEdit.is_signed = this.ticket.is_signed;
        this.ticketEdit.origin_type = this.ticket.origin_type;
        this.ticketEdit.origin_type_id = this.ticket.origin_type_id;
        this.ticketEdit.priority = this.ticket.priority;
        this.ticketEdit.priority_id = this.ticket.priority_id;
        this.ticketEdit.read_by_admin = this.ticket.read_by_admin;
        this.ticketEdit.signature = this.ticket.signature;
        this.ticketEdit.ticketType_id = this.ticket.ticketType_id;
        this.ticketEdit.ticket_status = this.ticket.ticket_status;
        this.ticketEdit.ticket_status_id = this.ticket.ticket_status_id;
        this.ticketEdit.ticket_timeslots = this.ticket.ticket_timeslots;
        this.ticketEdit.ticket_type = this.ticket.ticket_type;
        this.ticketEdit.ticket_type_id = this.ticket.ticket_type_id;
        this.ticketEdit.timeslots = this.ticket.timeslots;
        // this.ticketEdit.ticketType_id = this.ticket.ticketType_id;
        this.ticketEdit.title = this.ticket.title;
        this.ticketEdit.updated_at = this.ticket.updated_at;
        this.ticketEdit.user = this.ticket.user;
        this.ticketEdit.user_id = this.ticket.user_id;
        this.ticketEdit.warranty_id = this.ticket.warranty_id;

        console.log(this.ticket);
        console.log(this.ticketEdit);

        // return;

        // axios.patch(`/api/ticket/${this.ticket.id}`, this.ticket)
        axios.patch(`/api/ticket/${this.ticketEdit.id}`, this.ticketEdit).then((res) => {
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
          console.log(err.response);
          $("html, body").animate({ scrollTop: 0 }, "slow");
          this.error = {
            status: true,
            errors: err.response.data.errors,
          };
        });
      }
    },
    get_all_department_types() {
      if (!this.ticket.department_type.department_id) return;

      axios.get(`/api/department/${this.ticket.department_type.department_id}/department_types`)
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