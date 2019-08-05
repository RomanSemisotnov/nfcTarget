<template>
        <div>
            <el-row>
                <el-col :offset="3" :span="8">
                    <el-input placeholder="Please input" v-model="name"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col :offset="3" :span="8">
                    <el-button :loading="isLoading" @click="create()" type="primary" round>Добавить</el-button>
                </el-col>
            </el-row>
        </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                isLoading: false,
                name: ''
            }
        },
        methods: {
            create() {
                this.isLoading=true;
                axios.post('/api/client/create', {name: this.name}).then(response => {
                    this.isLoading=false;
                    this.$router.push({path: '/client/' + this.name + '/params'});
                }).catch(reason => {
                    this.isLoading=false;
                    this.$message.error('Не удалось добавить клиента');
                });
            }
        }
    }
</script>

<style scoped>

</style>