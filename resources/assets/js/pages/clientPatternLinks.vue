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
                        <el-col :offset="1" :span="8">
                            <el-input placeholder="Нужный подпараметр" @change="paramInput"
                                      v-model="needParam"></el-input>
                        </el-col>
                    </el-row>

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
                                        prop="summ"
                                        label="Переходов"
                                        width="100">
                                </el-table-column>

                                <el-table-column
                                        prop="created_at"
                                        label="Дата"
                                        width="160">
                                </el-table-column>

                                <el-table-column
                                        width="100">
                                    <template slot-scope="scope">
                                        <el-button @click="openEditDialog(scope.row.id, scope.$index)" size="small"
                                                   type="warning"
                                                   plain round>Изменить
                                        </el-button>
                                    </template>
                                </el-table-column>

                                <el-table-column
                                        width="100">
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

        <el-dialog title="Изменить редирект" :visible.sync="editDialog.visible">
            <el-input v-model="editDialog.redirectTo" autocomplete="off"></el-input>
            <span slot="footer" class="dialog-footer">
                <el-button @click="closeEditDialog()">Отмена</el-button>
                <el-button type="primary" :loading="editDialog.loading" @click="updateRedirect()">Обновить</el-button>
            </span>
        </el-dialog>

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
                currentPage: 1,
                needParam: '',
                editDialog: {
                    redirectTo: '',
                    visible: false,
                    link_id: null,
                    rowIndex: null,
                    loading: false,
                }
            }
        },
        created() {
            this.getClient();
        },
        methods: {
            updateRedirect() {
                this.editDialog.loading = true;
                axios.post('/api/patterns/update/' + this.editDialog.link_id, {redirectTo: this.editDialog.redirectTo.trim()}).then(response => {
                    this.editDialog.loading = false;
                    this.data[this.editDialog.rowIndex].redirectTo = response.data.redirectTo;
                    this.data[this.editDialog.rowIndex].summ = 0;
                    this.closeEditDialog();
                }).catch(reason => {
                    this.editDialog.loading = false;
                    this.$message.error('Не удалось обновить редирект');
                });
            },
            openEditDialog(link_id, index) {
                this.editDialog.redirectTo = this.data[index].redirectTo;
                this.editDialog.link_id = link_id;
                this.editDialog.rowIndex = index;
                this.editDialog.visible = true;
            },
            closeEditDialog() {
                this.editDialog.visible = false;
                this.editDialog.link_id = null;
                this.editDialog.rowIndex = null;
                this.editDialog.redirectTo = '';
            },
            paramInput() {
                this.getLinks();
            },
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
                    response.data.summ=0;
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
                axios.get('/api/patterns/' + this.client.id + '?page=' + this.currentPage + '&needParam=' + this.needParam.trim()).then(response => {
                    this.data = response.data.data;
                    this.totalLinks = response.data.total;

                    for (let index in this.data) {
                        let summ = 0;
                        for (let recordIndex in this.data[index].records) {
                            console.log(this.data[index].records);
                            for (let uidIndex in this.data[index].records[recordIndex].uids) {
                                console.log(this.data[index].records[recordIndex].uids[uidIndex].correctrequests_count);
                                summ += this.data[index].records[recordIndex].uids[uidIndex].correctrequests_count;
                            }
                        }
                        this.data[index].summ = summ;
                    }

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