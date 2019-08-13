<template>
    <el-button @click="del()" :loading="isDeleting" type="danger" size="small" round>Удалить</el-button>
</template>

<script>
    import axios from 'axios';

    export default {
        props: {
            link_id: Number,
            index: Number
        },
        data() {
            return {
                isDeleting: false
            }
        },
        methods: {
            del() {
                this.isDeleting = true;
                axios.post('/api/patterns/delete/' + this.link_id).then(response => {
                    this.isDeleting = false;
                    this.$emit('delete', this.index);
                    this.$message.success("Успешно удаленно");
                }).catch(reason => {
                    this.isDeleting = false;
                    this.$message.error('Не удалось удалить ссылку');
                });
            }
        }
    }
</script>

<style scoped>

</style>