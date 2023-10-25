<template>
    <el-dialog
    @close="close"
    :visible="showDialog"
    append-to-body
    @open="open"
    :title="title"
    v-loading="loading"
    >
    <div class="d-flex flex-column align-items-center m-2">
        <table v-if="form.files.length != 0" class="table w-100 ">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(file,idx) in form.files" :key="idx">
                    <td>{{file.original_name}}</td>
                    <td>
                        <a :download="file.original_name" :href="file.url" class="btn btn-success btn-sm">Descargar</a>
                        <button
                        @click="deleteFile(file.id)"
                        class="btn btn-danger btn-sm"
                        >
                        Eliminar
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
       <el-upload
       v-if="form.files.length < 10"
            class="self-align-center"
            :action="`/suscription/client/files/${form.id}`"
            multiple
            :beforeUpload="beforeUpload"
            :limit="10"
            :headers="headers"
            name="file"
              :multipart="true"
            :show-file-list="false"
              :on-success="onSuccess"
              :on-error="handleError"
            :on-exceed="handleExceed"
            :file-list="form.files">
  <el-button size="small" type="primary" >Clic para subir archivo</el-button>
</el-upload>
    </div>
    </el-dialog>
</template>


<script>
export default {
    props: {
        showDialog: {
            type: Boolean,
            default: false,
        },
        children: {
            type: Object,
            required: true,
        }
    },
    data() {
        return {
            headers: headers_token,
            title: "",
            loading: false,
            form: {
                files:[],
                id: null,
            },
        };
    },
    methods:{
        deleteFile(id){
            this.$confirm('¿Está seguro de eliminar el archivo?', 'Advertencia', {
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                type: 'warning'
            }).then(async () => {
                try{
                    this.loading = true;
                    const response = await this.$http.delete(`/suscription/client/files/${id}`);
                    if(response.status == 200){
                        this.$message.success(response.data.message);
                        this.getFiles();
                    }
                }catch(e){
                    console.log(e);
                }finally{
                    this.loading = false;
                }
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: 'Eliminación cancelada'
                });          
            });
        },
        handleError(){
            this.loading = false;
            this.$message.error('Error al subir el archivo');
        },
        handleExceed(){
            this.$message.error('Solo se pueden subir 10 archivos');
        },
        onSuccess(response) {
            this.loading = false;
            this.getFiles();
  },
         beforeUpload(file) {
            this.loading = true;
            const isJPGorPNG = file.type === 'image/jpeg' || file.type === 'image/png' || file.type === 'application/pdf' ||
            file.type === 'image/jpg' || file.type === 'application/vnd.android.package-archive';
            const isLt10M = file.size / 1024 / 1024 < 15;

            if (!isJPGorPNG) {
            this.$message.error('El archivo debe ser de tipo JPG | PNG | PDF | APK');
            }
            if (!isLt10M) {
            this.$message.error('El archivo debe ser menor de 15MB');
            }

            let pass = isJPGorPNG && isLt10M;
            if(!pass){
                this.loading = false;
            }
            return pass
  },
       async getFiles(){
            try{
                this.loading = true;
                const response = await this.$http.get(`/suscription/client/files/${this.form.id}`);
                const{data} = response.data;
                this.form.files = data;
            }catch(e){
                console.log(e);
            }finally{
                this.loading = false;   
            }
        },
        close() {
            this.$emit("update:showDialog", false);
        },
        open() {
            let {name,id} = this.children;
            this.form.id = id;
            this.form.files = [];
            this.title =`Archivos de ${name}`;
            this.getFiles();
        },
    }
}
</script>