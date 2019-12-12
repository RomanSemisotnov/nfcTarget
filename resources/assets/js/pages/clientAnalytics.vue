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
                <el-button @click="unloading">Выгрузить статистику</el-button>
            </el-col>
        </el-row>

        <el-row style="padding-top:13px;">
            <el-col :offset="3" :span="19">
                <el-table
                        :loading="records.isLoading"
                        :data="records.data"
                        style="width: 100%">

                    <el-table-column
                            prop="id"
                            label="#"
                            width="60">
                    </el-table-column>

                    <el-table-column
                            prop="patternlink.value"
                            label="URL"
                            width="330">
                    </el-table-column>

                    <el-table-column
                            prop="priceOneTag"
                            label="Цена"
                            width="100">
                    </el-table-column>

                    <el-table-column
                            width="150">
                        <template slot-scope="scope">
                            <el-button @click="getAnalytics(scope.row.id)"
                                       type="success"
                                       :loading="analyticDialog.isLoading"
                                       size="small"
                                       plain round>Аналитика
                            </el-button>
                        </template>
                    </el-table-column>

                </el-table>
            </el-col>
        </el-row>

        <el-row style="padding-top:13px;">
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


        <el-dialog
                title="Список тегов"
                :visible.sync="analyticDialog.visible"
                @closed="closeAnalyticDialog"
                width="90%">

            <el-row>
                <el-col span="20" offset="2">
                    <el-button-group>
                        <el-button plain round>{{'Common: '+analyticDialog.commonData.commonCount}}</el-button>
                        <el-button type="success" plain round>{{'Andr: '+analyticDialog.commonData.androidCount}}
                        </el-button>
                        <el-button type="primary" plain round>{{'Ios: '+analyticDialog.commonData.iosCount}}</el-button>
                        <el-button plain round>{{'unkn: '+analyticDialog.commonData.unknownCount}}</el-button>
                    </el-button-group>
                </el-col>
            </el-row>


            <el-row>
                <el-col span="20" offset="2">
                    <el-table
                            :data="analyticDialog.data"
                            style="width: 90%">
                        <el-table-column
                                prop="uid_value"
                                label="Значение тега"
                                width="200">
                        </el-table-column>
                        <el-table-column
                                prop="request_count"
                                label="Всего"
                                width="120">
                        </el-table-column>
                        <el-table-column
                                prop="android_count"
                                label="Android"
                                width="120">
                        </el-table-column>
                        <el-table-column
                                prop="ios_count"
                                label="Ios"
                                width="120">
                        </el-table-column>
                        <el-table-column
                                prop="unknown_count"
                                label="Неизвестно"
                                width="150">
                        </el-table-column>
                    </el-table>
                </el-col>
            </el-row>


            <span slot="footer" class="dialog-footer">
            <el-button @click="analyticDialog.visible = false">Назад</el-button>
        </span>
        </el-dialog>

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
                dateRang: null,
                records: {
                    isLoading: true,
                    data: []
                },
                analyticDialog: {
                    visible: false,
                    data: [],
                    commonData: {},
                    isLoading : false
                }
            }
        },
        async created() {
            await this.getClient();
            this.getRecords();
        },
        methods: {
            closeAnalyticDialog(){
                this.analyticDialog.data=[];
                this.analyticDialog.commonData={};
            },
            getAllAnalytics(record_id) {
                axios.get('/api/recordAnalytics/' + record_id + this.getFromTo(this.dateRang)).then(response => {
                    this.analyticDialog.commonData = response.data[0];
                    this.analyticDialog.visible = true;
                }).catch(reason => {
                    this.$message.error("Ошибка");
                });
            },
            getAnalytics(record_id) {
                this.analyticDialog.isLoading = true;
                this.getAllAnalytics(record_id);
                axios.get('/api/recordAnalytics/' + record_id + '/withUid' + this.getFromTo(this.dateRang)).then(response => {
                    this.analyticDialog.isLoading = false;
                    this.analyticDialog.data = response.data;
                    this.analyticDialog.visible = true;
                }).catch(reason => {
                    this.$message.error("Ошибка");
                    this.analyticDialog.isLoading = false;
                });
            },
            getRecords() {
                this.records.isLoading = true;
                axios.get('/api/record/' + this.client.id).then(response => {
                    this.records.data = response.data;
                    this.records.isLoading = false;
                }).catch(reason => {
                    this.$message.error('Не удалось получить партии ссылок');
                    this.records.isLoading = false;
                });
            },
            changeDate() {
                this.getClient();
            },
            getFromTo(dateRang) {
                if (dateRang === null)
                    return "";
                return '?from=' + dateRang[0] + '&to=' + dateRang[1];
            },
            unloading() {
                axios.get('/api/excelAnalytics?client_id=' + this.client.id +
                    this.getFromTo(this.dateRang)
                        .then(response => {

                        }).catch(reason => {

                    }));
            },
            async getClient() {
                await axios.get('/api/client/' + this.$route.params.name).then(response => {
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