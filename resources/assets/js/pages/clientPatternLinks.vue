<template>
    <div>
        <client-nav-bar></client-nav-bar>

        <el-row>
            <el-col :offset="3" :span="21">
                <h2 style="display: inline-block;">{{client.name}}</h2>
            </el-col>
        </el-row>


        <el-row>
            <el-col :offset="1" :span="22">

                <el-card class="box-card">
                    <el-row type="flex">
                        <el-col :offset="1" :span="22">
                            <div style="display: inline-block"><h4>https://{{client.subdomain}}.nfc-u.ru/</h4></div>
                            <div :key="index" v-for="(value, index) in client.params" style="display: inline-block">
                                <el-select v-if="value.variables.length && value.type !== 'token'"
                                           v-model="params[index].value"
                                           placeholder="Select">
                                    <el-option
                                            v-for="(varialbe, indexVar) in value.variables"
                                            :key="indexVar"
                                            :label="varialbe.name"
                                            :value="varialbe.name">
                                    </el-option>
                                </el-select>
                                <span v-else-if="value.type === 'token'">ТОКЕН</span>
                                <span v-else>Параметры отсутствуют</span>
                                <span>/</span>
                            </div>
                        </el-col>
                    </el-row>

                    <el-row type="flex">
                        <el-col :offset="1" :span="6">
                            <el-input placeholder="Редирект на" v-model="redirectTo"></el-input>
                        </el-col>
                    </el-row>

                    <el-row type="flex">
                        <el-col :offset="1" :span="9">
                            <el-button :loading="isAdding" type="primary" @click="addLinks()" plain>Добавить</el-button>
                        </el-col>
                    </el-row>

                </el-card>

            </el-col>
        </el-row>


        <el-row>
            <el-col :offset="1" :span="22">
                <el-card class="box-card">

                    <el-row>
                        <el-col :span="24">
                            <el-table
                                    :loading="isTableLoading"
                                    :data="data"
                                    style="width: 100%">
                                <el-table-column
                                        prop="id"
                                        label="#"
                                        width="60">
                                </el-table-column>
                                <el-table-column
                                        prop="value"
                                        label="URL"
                                        width="300">
                                </el-table-column>
                                <el-table-column
                                        prop="redirectTo"
                                        label="Редирект"
                                        width="260">
                                </el-table-column>
                                <el-table-column
                                        prop="created_at"
                                        label="Дата"
                                        width="210">
                                </el-table-column>

                                <el-table-column
                                        width="120">
                                    <template slot-scope="scope">
                                        <delete-client-link v-on:delete="deleteRow" :link_id="scope.row.id"
                                                            :index="scope.$index"></delete-client-link>
                                    </template>
                                </el-table-column>

                            </el-table>
                        </el-col>
                    </el-row>

                    <el-row>
                        <el-col :offset="3" :span="20">
                            <el-pagination
                                    background
                                    layout="prev, pager, next"
                                    :page-size="20"
                                    current-change
                                    @current-change="changePage"
                                    :total="totalLinks">
                            </el-pagination>
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
    import DeleteClientLink from "../components/buttons/deleteClientLink";

    export default {
        components: {
            clientNavBar, DeleteClientLink
        },
        data() {
            return {
                client: {},
                params: [{value: ''}, {value: ''}, {value: ''}],
                redirectTo: '',
                isAdding: false,
                data: [],
                isTableLoading: true,
                totalLinks: 0,
                currentPage: 1
            }
        },
        created() {
            this.getClient();
        },
        methods: {
            addLinks() {
                let myParams = [];
                for (let index = 0; index < this.client.params.length; index++) {
                    if (this.params[index].value.trim()) {
                        myParams.push(this.params[index].value.trim());
                    } else {
                        this.$message.error('Параметр ' + (index + 1) + ' не назначен');
                        return;
                    }
                }

                this.isAdding = true;
                axios.post('/api/patterns', {
                    client_id: this.client.id,
                    redirectTo: this.redirectTo,
                    params: myParams
                }).then(response => {
                    this.data.unshift(response.data);
                    this.isAdding = false;
                    this.$message.success('Успешно добавленно');
                }).catch(reason => {
                    this.isAdding = false;
                    this.$message.error('Не удалось добавить ссылки');
                });
            },
            getClient() {
                axios.get('/api/client/' + this.$route.params.name).then(response => {
                    this.client = response.data;
                    this.getLinks();
                }).catch(reason => {
                    this.$message.error('Не удалось получить клиента ' + this.$route.params.name);
                })
            },
            deleteRow(index) {
                this.data.splice(index, 1);
            },
            getLinks() {
                this.isTableLoading = true;
                axios.get('/api/patterns/' + this.client.id + '?page=' + this.currentPage).then(response => {
                    this.data = response.data.data;
                    this.totalLinks = response.data.total;
                    this.isTableLoading = false;
                }).catch(reason => {
                    this.isTableLoading = false;
                    this.$message.error('Не удалось получить ссылки');
                });
            },
            changePage(newPage) {
                this.currentPage = newPage;
                this.getLinks();
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