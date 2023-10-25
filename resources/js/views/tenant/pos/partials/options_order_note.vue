<template>
    <el-dialog
        :visible="showDialog"
        @open="create"
        @opened="opened"
        width="60%"
        :close-on-click-modal="false"
        :close-on-press-escape="false"
        :show-close="false"
    >
        
        <span slot="title">
            <div class="widget-summary widget-summary-xs pl-3 p-2">
                <div class="widget-summary-col widget-summary-col-icon">
                    <div class="summary-icon bg-success">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
                <div class="widget-summary-col">
                    <div class="summary row">
                        <div class="col-md-6">
                            <h2>
                                Pedido: {{form.number_full}}
                            </h2>
                        </div>
                       
                    </div>
                </div>
            </div>
        </span>
        <div class="form-body el-dialog__body_custom">
            <div class="row">
                <div class="col-md-12 m-bottom">
                    <embed v-if="form.print_ticket"
                                id="nemo"
                                :src="form.print_ticket"
                                type="application/pdf"
                                width="100%"
                                height="450px"
                            />
                </div>
                <div class="col-md-12 d-sm-block d-md-block d-lg-none">
                    <div class="row">
                        <div class="col text-center font-weight-bold mt-3">
                            <button
                                class="btn btn-lg btn-info waves-effect waves-light"
                                type="button"
                                @click="clickPrint(form.print_a4)"
                            >
                                <i class="fa fa-file-alt"></i>
                            </button>
                            <p>A4</p>
                        </div>
                        <div
                            v-if="config !== null && config.show_ticket_80"
                            class="col text-center font-weight-bold mt-3"
                        >
                            <button
                                class="btn btn-lg btn-info waves-effect waves-light"
                                type="button"
                                @click="clickPrint(form.print_ticket)"
                            >
                                <i class="fa fa-receipt"></i>
                            </button>
                            <p>Ticket</p>
                        </div>
                        <div
                            v-if="config.show_ticket_58"
                            class="col text-center font-weight-bold mt-3"
                        >
                            <button
                                class="btn btn-lg btn-info waves-effect waves-light"
                                type="button"
                                @click="clickPrint(form.print_ticket_58)"
                            >
                                <i class="fa fa-receipt"></i>
                            </button>
                            <p>Ticket 58</p>
                        </div>
                        <div
                            v-if="config.show_ticket_50"
                            class="col text-center font-weight-bold mt-3"
                        >
                            <button
                                class="btn btn-lg btn-info waves-effect waves-light"
                                type="button"
                                @click="clickPrint(form.print_ticket_50)"
                            >
                                <i class="fa fa-receipt"></i>
                            </button>
                            <p>Ticket 50</p>
                        </div>
                        <div class="col text-center font-weight-bold mt-3">
                            <button
                                class="btn btn-lg btn-info waves-effect waves-light"
                                type="button"
                                @click="clickPrint(form.print_a5)"
                            >
                                <i class="fa fa-file-alt"></i>
                            </button>
                            <p>A5</p>
                        </div>
                    </div>
                </div>
               
            </div>
            <div class="row">
                <el-button
                @click="clickNewSale"
                type="primary"

                >
                    Cerrar
                </el-button>
            </div>
        </div>

   
    </el-dialog>
</template>

<script>
import { mapState, mapActions } from "vuex/dist/vuex.mjs";

export default {
    props: ["showDialog","data"],
    components: {
  
    },
    data() {
        return {
            loading_Whatsapp: false,
            titleDialog: null,
            loading: false,
            errors: {},
            form: {},
            company: {},
            configuration: {},
            activeName: "first",
            showDialogGenerate: false,
            button_convert_cpe_pos: true
        };
    },
    created() {
        this.initForm();
        this.loadConfiguration();
       // window.addEventListener("keydown", this.clickNewSale);
        
    },
   mounted() {
        document.addEventListener("keydown", this.handleKeydown);
    },
    beforeUnmount() {
        document.removeEventListener("keydown", this.handleKeydown);
    },
    computed: {
        ...mapState(["config"]),
 
    
    },
    methods: {
        handleKeydown(event) {
            if ((event.keyCode === 13 || event.key === "Enter") && this.showDialog) {
                this.clickNewSale();
            }
        },
   

        ...mapActions(["loadConfiguration"]),
   
  
        async clickNewSale() {
            console.log("xd");
            //create the item in localstorage called "option_pos" with value "t1"
            localStorage.setItem("option_pos", "t1");
            await this.initForm();
            await this.$eventHub.$emit("cancelSale");
            await this.$eventHub.$emit("cancelSaleGarage");
            this.$emit("update:showDialog", false);
        },
        initForm() {
            this.errors = {};
            this.configuration = {};
            this.form = {
                customer_email: null,
                download_pdf: null,
                print_a4: null,
                print_a5: null,
                print_ticket: null,
                print_ticket_50: null,
                print_ticket_58: null,
                external_id: null,
                number: null,
                customer_telephone: null,
                message_text: null,
                id: null
            };


            this.button_convert_cpe_pos = true;
        },
        create() {
        },
        opened() {
         console.log(this.data);
         this.form = this.data;
         
        },
      
        clickPrint(url) {
            window.open(`${url}`, "_blank");
        },
      
      
    }
};
</script>
