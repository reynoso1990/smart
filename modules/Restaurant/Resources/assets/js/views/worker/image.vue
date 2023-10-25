<template>
  <el-dialog @close="close" :visible="showDialog" append-to-body>
    <div class="col container">
      <img :src="image" alt="photo" class="img-responsive" />
    </div>
  </el-dialog>
</template>
<script>
export default {
  props: ["showDialog", "image"],
  methods: {
    close() {
      this.$emit("update:showDialog", false);
    },
  },
};
</script>