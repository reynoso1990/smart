<template>
    <el-dialog
        :title="titleDialog"
        :visible="showDialog"
        @close="close"
        @open="open"
        v-loading="loading"
    >
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Facturas</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Numero</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="invoice in invoices"
                                    :key="invoice.id"
                                >
                                    <td>{{ invoice.date_of_issue }}</td>
                                    <td>{{ invoice.number }}</td>
                                    <td>{{ invoice.total.toFixed(2) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th>{{ total_invoices.toFixed(2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4>Notas de venta</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Numero</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="sale_note in saleNotes"
                                    :key="sale_note.id"
                                >
                                    <td>{{ sale_note.date_of_issue }}</td>
                                    <td>{{ sale_note.number }}</td>
                                    <td>{{ sale_note.total.toFixed(2) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th>{{ total_sale_notes.toFixed(2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-end">
                        <h4>Total: {{ total.toFixed(2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </el-dialog>
</template>

<script>
import moment from "moment";
export default {
    props: {
        showDialog: {
            type: Boolean,
            default: false
        },
        record: {
            type: Object,
            default: () => {}
        }
    },
    data() {
        return {
            titleDialog: "Documentos",
            loading: false,
            documents: [],
            invoices: [],
            saleNotes: [],
            total_invoices: 0,
            total_sale_notes: 0,
            total: 0,
            months: [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ]
        };
    },
    methods: {
        separateDocuments() {
            this.invoices = this.documents
                .filter(document => document.document_id)
                .map(document => ({
                    ...document.document,
                    number: `${document.document.series}-${
                        document.document.number
                    }`,
                    total: document.document.total  / (document.document.periods ? document.document.periods.length : 1),
                    date_of_issue: moment(
                        document.document.date_of_issue
                    ).format("DD-MM-YYYY")
                }));
            this.saleNotes = this.documents
                .filter(document => document.sale_note_id)
                .map(saleNote => ({
                    ...saleNote.sale_note,
                    number: `${saleNote.sale_note.series}-${
                        saleNote.sale_note.number
                    }`,
                        total: saleNote.sale_note.total  / (saleNote.sale_note.periods ? saleNote.sale_note.periods.length : 1),
                    date_of_issue: moment(
                        saleNote.sale_note.date_of_issue
                    ).format("DD-MM-YYYY")
                }));

            this.total_invoices = this.invoices.reduce((total, invoice) => {
                return total + invoice.total;
            }, 0);
            this.total_sale_notes = this.saleNotes.reduce(
                (total, sale_note) => {
                    return total + sale_note.total;
                },
                0
            );

            this.total = this.total_invoices + this.total_sale_notes;
        },
        async getRecords() {
            let { month, year, student } = this.record;

            try {
                this.loading = true;
                const response = await this.$http.get(
                    `/suscription/college/documents/${month}/${year}/${
                        student.id
                    }/${student.parent_id}`
                );
                if (response.status == 200) {
                    this.documents = response.data.documents;
                    this.separateDocuments();
                }
            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false;
            }
        },
        close() {
            this.$emit("update:showDialog", false);
        },
        open() {
            let month = this.months[this.record.month - 1];

            let studenName = this.record.student.name;
            this.titleDialog = `Documentos de ${studenName} - ${month} ${
                this.record.year
            }`;
            this.getRecords();
        }
    }
};
</script>
