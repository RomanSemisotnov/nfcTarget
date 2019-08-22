<template>
    <div>
        <client-nav-bar></client-nav-bar>

        <el-row>
            <el-col :offset="3" :span="21">
                <h2 style="display: inline-block;">{{client.name}}</h2>
            </el-col>
        </el-row>

        <el-row>
            <el-col :offset="3" :span="8">
                <div class="block">
                    <el-date-picker
                            @change="changeDate"
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

            <el-col :offset="1" :span="6">
                <el-button disabled @click="unloading">Выгрузить статистику</el-button>
            </el-col>
        </el-row>

        <el-row>
            <el-col :offset="1" :span="6" v-for="(param,ind) in client.params" :key="ind">
                <el-card class="box-card">

                    <div slot="header" class="clearfix">
                        <span>{{param.index_number}}{{') '+param.name}}</span>
                    </div>

                    <el-row>
                        <el-col :offset="2" :span="20" :key="index" v-for="(varialbe, index) in param.variables">
                            <el-button type="primary" plain>{{varialbe.name}}=> {{varialbe.requests_count}}</el-button>
                        </el-col>
                    </el-row>

                </el-card>
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
                dateRang: null
            }
        },
        created() {
            this.getClient();
        },
        methods: {
            changeDate() {
                this.getClient();
            },
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
                let start = '';
                let end = '';
                if (this.dateRang !== null) {
                    start = this.dateRang[0];
                    end = this.dateRang[1];
                }
                axios.get('/api/client/' + this.$route.params.name + '?start=' + start + '&end=' + end).then(response => {
                    this.client = response.data;
                }).catch(reason => {
                    this.$message.error('Не удалось получить клиента ' + this.$route.params.name);
                })
            },
        },
        watch: {
            '$route'(to, from) {
                this.getClient();
            },
        }
    }
</script>

<style scoped>

</style>