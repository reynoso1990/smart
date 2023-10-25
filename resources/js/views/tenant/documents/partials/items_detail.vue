<template>
    <el-dialog
        width="850px"
        :visible="showDialog"
        @close="close"
        @open="open"
        :title="title"
        append-to-body
        top="25vh"
    >
        <table class="table">
            <thead>
                <tr>
                    <th>
                        #
                    </th>
                    <th>Descrip.</th>
                    <th>Precio unitario</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, idx) in items" :key="idx">
                    <td class="text-end">{{ idx + 1 }}</td>
                    <td>{{ item.description }}</td>
                    <td class="text-end">{{ item.unit_price }}</td>
                    <td class="text-end">{{ item.quantity }}</td>
                    <td class="text-end">{{ item.total }}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="text-end">{{ total }}</td>
                </tr>
            </tbody>
        </table>
    </el-dialog>
</template>

<script>
export default {
    props: ["document", "showDialog"],
    data() {
        return {
            title: "Detalle",
            items: [],
            total: 0
        };
    },

    methods: {
        close() {
            this.$emit("update:showDialog", false);
        },
        open() {
            let { items, total } = this.document;
            this.items = items.map(i => ({
                ...i,
                total: Number(i.total).toFixed(2),
                unit_price: Number(i.unit_price).toFixed(2)
            }));
            this.total = this.items
                .reduce((a, b) => a + Number(b.total), 0)
                .toFixed(2);
        }
    }
};
</script>
