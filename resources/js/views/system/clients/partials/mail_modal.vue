<template>
    <el-dialog
        :visible="showDialog"
        title="Nuevo correo"
        @open="open"
        @close="close"
        append-to-body
        v-loading="loading"
        element-loading-text="Enviando correo.."
    >
        <div class="row">
            <div class="col-md-6">
                <label for="to">Destinatario</label>
                <el-input
                    v-model="form.to"
                    id="to"
                    placeholder="Destinatario"
                ></el-input>
            </div>
            <div class="col-md-6">
                <label for="subject">Asunto</label>
                <el-input
                    v-model="form.subject"
                    id="subject"
                    placeholder="Asunto"
                ></el-input>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <label for="body">Mensaje</label>
                <textarea
                    class="form-control"
                    v-model="form.body"
                    id="exampleFormControlTextarea1"
                    rows="3"
                ></textarea>
            </div>
        </div>
        <div>
            <label
                for="file"
                class="btn btn-primary btn-sm mt-2"
                @click="$refs.fileInput.click()"
                >Adjuntar archivos </label
            ><br />
            <small>
                <i class="fas fa-info-circle"></i>
                <span>
                    Puede adjuntar archivos con extensión .pdf, .jpg, .jpeg,
                    .png, .doc, .docx, .xls, .xlsx, .ppt, .pptx
                </span>
            </small>
            <input
                accept=".pdf,.jpg,.jpeg,.png,application/msword,application/vnd.ms-excel,application/vnd.ms-powerpoint"
                hidden
                type="file"
                ref="fileInput"
                multiple
                @change="handleFileInput"
            />
            <div v-for="(file, index) in files" :key="index">
                <el-tag class="mt-2" closable @close="deleteFile(index)">
                    {{ getFileName(file) }}
                </el-tag>
            </div>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button @click.prevent="close">Cerrar</el-button>
            <el-button type="primary" @click="sendMail">Enviar</el-button>
        </span>
    </el-dialog>
</template>

<script>
export default {
    props: {
        showDialog: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            loading: false,
            form: {},
            files: []
        };
    },
    methods: {
        getFileName(file) {
            let name = file.name;
            let extension = name.split(".").pop();
            let nameWithoutExtension = name.substring(0, name.lastIndexOf("."));
            let nameLength = nameWithoutExtension.length;

            if (nameLength > 15) {
                name =
                    nameWithoutExtension.substring(0, 8) +
                    "..." +
                    nameWithoutExtension.substring(nameLength - 3) +
                    "." +
                    extension;
            } else {
                name = nameWithoutExtension + "." + extension;
            }
            return name;
        },
        deleteFile(index) {
            this.files.splice(index, 1);
        },
        validateExtensionFile(file) {
            let extension = file.name.split(".").pop();
            if (
                extension == "pdf" ||
                extension == "jpg" ||
                extension == "jpeg" ||
                extension == "png" ||
                extension == "doc" ||
                extension == "docx" ||
                extension == "xls" ||
                extension == "xlsx" ||
                extension == "ppt" ||
                extension == "pptx"
            ) {
                return true;
            }
            return false;
        },

        handleFileInput() {
            const files = this.$refs.fileInput.files;

            for (let i = 0; i < files.length; i++) {
                this.files.push(files[i]);
            }
            let totalSize = 0;
            for (let i = 0; i < this.files.length; i++) {
                totalSize += this.files[i].size;
            }
            if (totalSize > 10000000) {
                this.$message({
                    message: "El tamaño máximo de los archivos es 10 mb",
                    type: "error"
                });
                this.files.pop();
            }
            if (!this.validateExtensionFile(files[files.length - 1])) {
                this.$message({
                    message: "El archivo no tiene una extensión válida",
                    type: "error"
                });
                this.files.pop();
            }
        },

        open() {
            this.form = {};
        },
        close() {
            this.$emit("update:showDialog", false);
        },
        validate() {
            if (this.form.to && this.form.subject && this.form.body) {
                return true;
            }
            this.$message({
                message: "Complete todos los campos",
                type: "error"
            });
            return false;
        },
        async sendMail() {
            const formData = new FormData();
            for (let i = 0; i < this.files.length; i++) {
                formData.append("files[]", this.files[i]);
            }
            formData.append("form", JSON.stringify(this.form));
            try {
                this.loading = true;
                const response = await this.$http.post(
                    "/clients/mail",
                    formData
                );
                if (response.status == 200) {
                    this.$message({
                        message: "Correo enviado",
                        type: "success"
                    });
                }
            } catch (e) {
                console.log(e);
                this.$message({
                    message: "Error al enviar el correo",
                    type: "error"
                });
            } finally {
                this.loading = false;
                this.close();
            }
        }
    }
};
</script>
