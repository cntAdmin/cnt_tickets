<template>
    <div class="mb-5"> 
        <transition name="fade" mode="out-in" v-if="error.status">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
        </transition>
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <header class="modal-header">
                    <slot name="header">
                        <h5 class="modal-title" id="signatureModalLabel">
                        Firmar parte de trabajo
                        </h5>
                    </slot>
                </header>

                <!-- MODAL BODY -->
                <section class="modal-body">
                    <slot name="body">
                        <form @submit.prevent="handleSubmit" id="signpicker" class="form-inline w-100">
                            <div class="col-12 mt-2">
                                <div class="w-100">
                                    <VueSignaturePad
                                        id="signature"
                                        ref="signaturePad"
                                        width="250px"
                                        height="250px"
                                        class="form-control p-0"
                                    />
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-primary mt-1" @click.prevent="cleanSign">Limpiar firma</button>
                                </div>
                            </div>
                        </form>
                    </slot>
                </section>

                <!-- MODAL FOOTER -->
                <footer class="modal-footer">
                    <slot name="footer">
                        <button
                            type="submit"
                            form="signpicker"
                            class="btn btn-block btn-success text-uppercase"
                        >
                            Enviar firma
                        </button>
                    </slot>
                </footer>
            </div>
        </div>
    </div>
  </template>
  
  <script>  
  export default {
    components: {},
    props: ["ticket"],
    name: "SignatureModal",
    data() {
        return {
            dates: {
                signature: '',
            },
            success: {
                status: false,
                msg: '',
            },
            error: {
                status: false,
                msg: '',
            },
        };
    },
    mounted() {
        $('#signature')[0].firstChild.setAttribute('width', '250')
        $('#signature')[0].firstChild.setAttribute('height', '250')
    },
    methods: {
        handleSubmit() {
            this.signature = this.$refs.signaturePad.saveSignature().data;

            if(!this.signature){
                this.error = {
                    status: true,
                    msg: 'No se ha registrado firma.',
                };
                setTimeout(() => {
                    this.error = {
                        status: false,
                        msg: '',
                    };
                }, 2500);

                return;
            }
            
            const formData = new FormData();
            
            formData.append("signature", this.signature);
            formData.append("ticket_id", this.ticket.id);

            axios.post('/api/ticket/firmar_parte', formData)
                .then((res) => {
                    this.success = {
                        status: true,
                        msg: res.data,
                    };
                    setTimeout(() => {
                        this.success = {
                            status: false,
                            msg: "",
                        };
                        this.signature = '';
                        window.location = '/ticket';
                    }, 2500);
                })
                .catch((err) => console.log(err.response.data));
        },
        cleanSign() {
            this.$refs.signaturePad.clearSignature();
        },
    },
  };
  </script>
  
  <style>
  </style>