<template>
  <div class="col-12 col-md-6 col-lg-4 mt-2">
    <label class="sr-only" for="ticket_id">Cliente</label>
    <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text d-none d-lg-block py-1">Cliente</div>
        <div class="input-group-text d-block d-lg-none py-1">
          <i class="fa fa-building"></i>
        </div>
      </div>
      <vue-select
        class="col-10 col-lg-9 px-0"
        transition="vs__fade"
        label="name"
        itemid="id"
        :options="customers"
        @input="setCustomer"
        v-model="alias"
        :disabled="!editable ? true : false"
      >
        <div slot="no-options">No hay opciones con esta búsqueda</div>
        <template slot="option" slot-scope="option">
          {{ option.id }} - {{ option.name }}
        </template>
      </vue-select>
    </div>
  </div>
</template>

<script>
export default {
    props: ["customer", "editable", "type"],
    data() {
        return {
            customers: [],
            alias: null
        }
    },
    mounted() {
        this.alias = this.customer !== null ? this.customer.name : '';
        this.get_all_customers();
    },
    methods: {
        get_all_customers() {
            axios.get('/api/get_all_customers')
                .then( res => {
                    this.customers = res.data.customers;
                }).catch( error => console.log(error.response.data))
        },
        setCustomer(customer) {
          this.$emit('setCustomer', customer);
          if(this.type == "edit"){
            alert('Cuidado, has modificado el cliente asignado a la incidencia de trabajo.');
          }
        }
    }
};
</script>

<style>
</style>