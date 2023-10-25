<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info p-b-30">
            <h3 class="my-0 text-white">Consulta de ventas resumidas</h3>
        </div>

        <div class="card-body">
            <data-table :resource="resource" :applyCustomer="true">
            </data-table>
        </div>
    </div>
</template>

<script>
import DataTable from "../../components/DataTableReportsSummary.vue";

export default {
    props: ["configuration"],
    components: { DataTable },
    data() {
        return {
            resource: "reports/summary-sales",
            form: {},
            columns: {
                seller: {
                    title: "Vendedor",
                    visible: true,
                },
                web_platforms: {
                    title: "Plataformas web",
                    visible: false,
                },
                region: {
                    title: "Region",
                    visible: false,
                },
            },
        };
    },
    async created() {},
    methods: {},
};
</script>
