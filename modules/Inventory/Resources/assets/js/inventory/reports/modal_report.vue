<template>
    <el-dialog
        title="Descargas"
        append-to-body
        :visible="showDialog"
        @close="close"
        width="1500px"
    >
        <data-report></data-report>
        <span slot="footer" class="dialog-footer">
            <el-button @click="close">Cerrar</el-button>
        </span>
    </el-dialog>
</template>

<script>
import DataReport from "../../../../../../Report/Resources/assets/js/views/download_tray/index.vue";
export default {
    components: { DataReport },
    props: {
        showDialog: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {};
    },
    methods: {
        close() {
            this.$emit("update:showDialog", false);
        }
    }
};
</script>
