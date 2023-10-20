<template>
  <div class="flex-row justify-content-center mb-5 pb-5" id="cards-list">
    <div
      class="alert alert-success alert-dismissible fade show mt-3"
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

    <div class="card mt-3 shadow" v-for="depType in departmentTypes.data" :key="depType.id">
      <div class="card-header">
        <h4 class="text-uppercase text-left font-weight-bold">{{ depType.name }}</h4>
        <span>{{ depType.created_at | moment("DD-MM-YYYY HH:mm:ss") }}</span>
      </div>
      <div class="card-body">
        <p class="text-left"><span class="font-weight-bold">Servicio</span> {{ depType.department.name }}</p>
        <p class="text-left"><span class="font-weight-bold">Nº tickets</span> {{ depType.tickets_count }}</p>
      </div>
      <div class="card-footer">
        <div class="d-flex flex-row">
          <div class="col-12">
            <div class="row justify-content-end">
              <a v-if="permissions.find((permission) => permission.name == 'department_type.update')"
                :href="`/department-type/${depType.id}/editar`"
                class="btn btn-info text-white"
              >
                <i class="fa fa-edit"></i>
              </a>
              <button v-if="permissions.find((permission) => permission.name == 'department_type.destroy')"
                type="button"
                class="btn btn-danger ml-2"
                data-toggle="modal"
                data-target="#deleteModal"
                @click="deleteModal(depType)"
              >
                <i class="fa fa-trash-alt"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <delete-modal
        v-if="showDelete"
        :data="depType.id"
        title="Servicio"
        toDelete="department-type"
        @success="deleted"
        @error="notDeleted"
      />
    </div>
  </div>
</template>

<script>
export default {
  props: ["departmentTypes", "permissions", "formsearch"],
  data() {
    return {
      depType: {},
      stopLoading: false,
      page: 0,
      success: {
        status: false,
        msg: "",
      },
      showDelete: false,
      error: {
        status: false,
        msg: "",
      },
    };
  },
  mounted() {
    // this.get_ticket_statuses();
    window.onscroll = () => {
      this.scroll();
    };
      this.formsearch.offset = 10;
  },
  methods: {
    // get_mobile_tickets() {
    //   axios
    //     .get("/api/ticket", {
    //       params: {
    //         type: "infinite",
    //         customer_id: this.formsearch.customer_id,
    //         ticket_status_id: this.formsearch.ticket_status_id,
    //         priority_id: this.formsearch.priority_id,
    //         agent_id: this.formsearch.agent_id,
    //         ticket_id: this.formsearch.ticket_id,
    //         text: this.formsearch.text,
    //         dateFrom: this.formsearch.dateFrom,
    //         dateTo: this.formsearch.dateTo,
    //         offset: this.formsearch.offset,
    //       },
    //     })
    //     .then((res) => {
    //       if (res.data.tickets.length <= 10) {
    //         return (this.stopLoading = true);
    //       }

    //       this.tickets.push(...res.data.tickets);
    //       this.formsearch.offset += 10;
    //     });
    // },
    // setStatus(ticket, status_id) {
    //   axios
    //     .put(`/api/ticket/${ticket.id}/ticket-status/${status_id}`)
    //     .then((res) => {
    //       $("html, body").animate({ scrollTop: 0 }, "slow");
    //       this.success = {
    //         status: true,
    //         msg: res.data.msg,
    //       };
    //       setTimeout(() => {
    //         this.success = {
    //           status: false,
    //           msg: "",
    //         };
    //         window.location.reload();
    //       }, 2000);
    //     })
    //     .catch((err) => console.log(err.response.data));
    // },
    scroll() {
      let bottomOfWindow =
        Number(
          (
            Math.max(
              window.pageYOffset,
              document.documentElement.scrollTop,
              document.body.scrollTop
            ) + window.innerHeight
          ).toFixed(0)
        ) === document.documentElement.offsetHeight;

      if (bottomOfWindow) {
        // this.get_mobile_tickets(); // replace it with your code
      }
    },
    // get_ticket_statuses() {
    //   axios.get("/api/get_all_ticket_statuses").then((res) => {
    //     this.ticketStatuses = res.data.ticket_statuses;
    //   });
    // },
    deleted(data) {
      this.closeAll();
      $("html, body").animate({ scrollTop: 0 }, "slow");
      this.success = {
        status: true,
        msg: data,
      };
      setTimeout(() => {
        this.success = {
          status: false,
          msg: "",
        };
        this.$emit("deleted");
      }, 2000);
    },
    notDeleted(data) {
      this.closeAll();
      $("html, body").animate({ scrollTop: 0 }, "slow");
      this.error = {
        status: true,
        msg: data,
      };
    },
    deleteModal(depType) {
      this.showDelete = true;
      this.depType = depType;
    },
  },
};
</script>