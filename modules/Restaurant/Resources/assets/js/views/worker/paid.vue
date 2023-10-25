<template>
<el-dialog title="Ingrese Pin de Usuario" :visible.sync="showDialogPing" @close="close">
     <div class="row d-flex justify-content-center mb-3">
      <div class="col-md-12">
        <input
          v-model="maskPin"
          class="form-control"
          type="text"
          size="2"
          name="listeanzahl"
          value="10"
          style="text-align: center; letter-spacing: 1rem"
        />
      </div>
    </div>
      <div class="container d-flex justify-content-center" v-loading="loading">
      <div class="row justify-content-center">
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary w-100" @click="getOption(1)">
              1
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary w-100" @click="getOption(2)">
            2
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary w-100" @click="getOption(3)">
            3
          </button>
        </div>

        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary w-100" @click="getOption(4)">
            4
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary w-100" @click="getOption(5)">
            5
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary w-100" @click="getOption(6)">
            6
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary w-100" @click="getOption(7)">
            7
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary w-100" @click="getOption(8)">
            8
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary w-100" @click="getOption(9)">
            9
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary w-100" @click="getOption(0)">
            0
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-danger w-100" @click="getOption('delete')">
            Borrar
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-success w-100" @click="enter">
            Ingresar
          </button>
        </div>
      </div>
    </div>

</el-dialog>
</template>
<script>
import printjs from "print-js";
export default {
    props:['showDialogPing','ordenSelectedId','tableId','localOrden','configuration','to_carry'],
       data() {
        return {
            resource: 'restaurant',
            loading:false,
            maskPin: null,
            pinHide:null,
            form_submit:{},
            printerDefault:null
         }
    },
    created() {
         this.getOption('delete');
    },
    methods: {
      getOption(option) {
        if (option == "delete") {
          this.pinHide = "";
          this.maskPin = null;
          return;
        }
        if (this.pinHide && this.pinHide.length == 4) {
          return;
        }
        this.pinHide += option.toString();
         this.maskPin = "*".repeat(this.pinHide.length);
      },
      close(){
            this.$emit("update:showDialogPing", false);
      },

      clearPin() {
      if (this.maskPin.length > 0) {
         this.pinHide = "";
          this.maskPin = null;
      }
    },
    // async printer(FileLink,ordenId){
    //      try {
    //        //  if(this.configuration.print_direct==true){
    //             if(localStorage.getItem("printerName") != null || localStorage.getItem("printerName") != "" ||localStorage.getItem("printerName") != false  || localStorage.getItem("printerName") != undefined){
    //                 qz.websocket.connect()
    //                     .then(qz.printers.getDefault)
    //                     .then(function(printer) {
    //                     let  printerName = { 'printers': printer};
    //                     localStorage.setItem('printerName', JSON.stringify(printerName));
    //                  });

    //              }
    //               let printerName=JSON.parse(localStorage.getItem('printerName'))
    //                 let config = qz.configs.create(printerName.printers, {scaleContent : false});
    //                 if (!qz.websocket.isActive()) {
    //                     await qz.websocket.connect(config);
    //                 }
    //                 let data = [
    //                     {
    //                         type: 'pdf',
    //                         format: 'file',
    //                         data: FileLink
    //                     }
    //                 ];
    //                 qz.print(config, data).catch((e) => {
    //                     this.$message.error(e.message);
    //                 });
    //                  this.$message.success("se esta imprimiendo el comprobante");

    //     //  }else{
    //         //      printjs({
    //         //         printable: `restaurant/worker/print-ticket/${ordenId}`,
    //         //         type: "pdf",
    //         //         showModal: true,
    //         //         modalMessage: "Imprimiendo el pedido ...",
    //         //     });
    //         //  }

    //         } catch (e) {
    //             this.$message.error(e.message);
    //         }

    // },
      async enter() {
      //this.form_submit=this.form_pe
      /*
        id:null,
            caja:true,
            printing:this.configuration.print_commands,
            commands_fisico:this.commands_fisico,
            orden:{
                table_id:1,
                status_orden_id: 1,
            },
            items:this.localOrden,
            pin:null
      */
      let form_submit={
          id:this.ordenSelectedId,
          caja:false,
          to_carry:this.to_carry,
          printing:this.configuration.print_commands,
          commands_fisico:null,
          orden:{
              table_id: this.tableId,
              status_orden_id: 1,
          },
          items:this.localOrden,
          pin:this.pinHide
      }
     this.loading = true;
      const response = await this.$http.post("send-orden", form_submit);
      if (response.status == 200) {
        const { success, message } = response.data;
        if (success) {
          const { ordenId } = response.data;


          this.$message.success(message);
          this.$emit('add',ordenId);
          this.close()
           this.loading = false;
        } else {
          this.$message.error(message);
           this.loading = false;
        }
      }
      this.clearPin();
      this.loading = false;

      },

    }
}
</script>
