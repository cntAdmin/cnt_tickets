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

    <div :class="!ticket.read_by_admin? 'card mt-3 shadow border-left border-danger font-weight-bold' : 'card mt-3 shadow'"
      v-for="ticket in tickets"
      :key="ticket.id"
    >
      <div class="card-header">
        <h4 class="text-uppercase text-left font-weight-bold">
          <a :href="`/ticket/${ticket.id}`">{{ ticket.id }}</a>
        </h4>
        <span>{{ ticket.created_at | moment("DD-MM-YYYY HH:mm:ss") }}</span>
      </div>
      <div class="card-body">
        <p class="text-truncate">{{ ticket.title }}</p>
      </div>
      <div class="card-footer">
        <div class="d-flex flex-row">
          <div class="col-8">
            <div class="row justify-content-center">
              <span :class="`disabled col-4 btn btn-sm btn-block btn-${ticket.ticket_status.color} ${ticket.ticket_status.color == 'info' ? 'text-white' : ''}`"
                type="button" :title="ticket.ticket_status.name"
              >
                <i :class="`fas fa-${ticket.ticket_status.icon}`"></i>
              </span>
              <span class="col-4 btn btn-sm btn-link">
                <i class="text-secondary fas fa-paperclip"></i
                ><span class="badge badge-dark ml-2">
                  {{ Object.keys(ticket.attachments).length }}
                </span>
              </span>
            </div>
          </div>
          <div class="col-4 px-2 py-0 m-0">
            <div class="row justify-content-between">
              <div class="col-6 px-2 py-0">
                <a :href="`/ticket/${ticket.id}`" class="btn btn-sm btn-success btn-block">
                  <i class="fa fa-eye"></i>
                </a>
              </div>
              <!-- SI ESTADO ES ABIERTO -->
              <div class="col-6 px-2 py-0">
                <div class="dropdown">
                  <button
                    class="btn btn-sm btn-primary dropdown-toggle btn-block"
                    type="button"
                    id="statuses"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="fa fa-exchange-alt"></i>
                  </button>
                  <div
                    class="dropdown-menu dropdown-menu-right"
                    aria-labelledby="statuses"
                  >
                    <div v-for="status in ticketStatuses" :key="status.id">
                      <button
                        type="button"
                        class="dropdown-item"
                        @click.prevent="setStatus(ticket, status.id)"
                      >
                        {{ status.name }}
                      </button>
                    </div>
                    <button
                      v-if="ticket.ticket_status.id == 1"
                      type="button"
                      title="Cambiar estado"
                      class="dropdown-item"
                      @click="openDeleteModal(ticket)"
                    >
                      Borrar Ticket
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END OF CARD FOOTER -->
    </div>
  </div>
</template>

<script>
export default {
  props: ["tickets", "formsearch"],
  data() {
    return {
      ticketStatuses: [],
      stopLoading: false,
      page: 0,
      success: {
        status: false,
        msg: "",
      },
    };
  },
  mounted() {
    this.get_ticket_statuses();
    window.onscroll = () => {
      this.scroll();
    };
      this.formsearch.offset = 10;
  },
  methods: {
    get_mobile_tickets() {
      axios
        .get("/api/ticket", {
          params: {
            type: "infinite",
            customer_id: this.formsearch.customer_id,
            ticket_status_id: this.formsearch.ticket_status_id,
            priority_id: this.formsearch.priority_id,
            agent_id: this.formsearch.agent_id,
            ticket_id: this.formsearch.ticket_id,
            text: this.formsearch.text,
            dateFrom: this.formsearch.dateFrom,
            dateTo: this.formsearch.dateTo,
            offset: this.formsearch.offset,
          },
        })
        .then((res) => {
          if (res.data.tickets.length <= 10) {
            return (this.stopLoading = true);
          }

          this.tickets.push(...res.data.tickets);
          this.formsearch.offset += 10;
        });
    },
    setStatus(ticket, status_id) {
      axios
        .put(`/api/ticket/${ticket.id}/ticket-status/${status_id}`)
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
            window.location.reload();
          }, 2000);
        })
        .catch((err) => console.log(err.response.data));
    },
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
        this.get_mobile_tickets(); // replace it with your code
      }
    },
    get_ticket_statuses() {
      axios.get("/api/get_all_ticket_statuses").then((res) => {
        this.ticketStatuses = res.data.ticket_statuses;
      });
    },
  },
};
</script>