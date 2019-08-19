<template>
    <div>
        <client-nav-bar></client-nav-bar>

        <el-row>
            <el-col :offset="3" :span="21">
                <h2 style="display: inline-block;">{{client.name}}</h2>
            </el-col>
        </el-row>

        <el-row>
            <el-col :offset="3" :span="21">
                <div class="block">
                    <span class="demonstration">Временной интервал</span>
                    <el-date-picker
                            v-model="dateRang"
                            type="daterange"
                            range-separator="--"
                            start-placeholder="Начальная дата"
                            format="yyyy-MM-dd"
                            value-format="yyyy-MM-dd"
                            end-placeholder="Конечная дата">
                    </el-date-picker>
                </div>
            </el-col>
        </el-row>

        <el-row>
            <el-col :offset="3" :span="21">
                <el-button @click="unloading">Выгрузить статистику</el-button>
            </el-col>
        </el-row>

    </div>
</template>

<script>
    import axios from 'axios';
    import clientNavBar from './../components/clientNavBar';

    export default {
        components: {
            clientNavBar
        },
        data() {
            return {
                client: {},
                dateRang: ''
            }
        },
        created() {
            this.getClient();
        },
        methods: {
            unloading() {
                axios.get('/api/analytics/excel?client_id=' + this.client.id +
                    '&start=' + this.dateRang[0] +
                    '&end=' + this.dateRang[1])
                    .then(response => {

                    }).catch(reason => {

                    }
                );
            },
            getClient() {
                axios.get('/api/client/' + this.$route.params.name).then(response => {
                    this.client = response.data;
                }).catch(reason => {
                    this.$message.error('Не удалось получить клиента ' + this.$route.params.name);
                })
            },
        }
    }
</script>

<style scoped>

</style>