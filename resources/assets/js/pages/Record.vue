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

                    <el-row>
                        <el-col :offset="3" :span="21">
                            <h2 style="display: inline-block;">Партии ссылок</h2>
                        </el-col>
                    </el-row>

                    <el-row>
                        <el-col :span="24">
                            <el-table
                                    :loading="records.isLoading"
                                    :data="records.data"
                                    style="width: 100%">

                                <el-table-column type="expand">
                                    <template slot-scope="scope">
                                        <p :key="index" v-for="(uid,index) in scope.row.uids">{{index+1}})
                                            {{uid.value}}</p>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                        prop="id"
                                        label="#"
                                        width="60">
                                </el-table-column>
                                <el-table-column
                                        prop="patternlink.value"
                                        label="URL"
                                        width="270">
                                </el-table-column>

                                <el-table-column
                                        prop="needLinks"
                                        label="Нужно"
                                        width="100">
                                </el-table-column>

                                <el-table-column
                                        prop="uids.length"
                                        label="Сделанно"
                                        width="100">
                                </el-table-column>

                                <el-table-column
                                        width="150"
                                        label="Активность"
                                >
                                    <template slot-scope="scope">
                                        <el-button v-if="scope.row.isActive === 'yes'" size="small" type="success"
                                                   :loading="isNewStatusLoading"
                                                   @click="enableRecord(scope.row.id, 'no')" round>Yes
                                        </el-button>

                                        <el-button v-else-if="scope.row.isActive === 'no'" size="small" type="danger"
                                                   :loading="isNewStatusLoading"
                                                   @click="enableRecord(scope.row.id, 'yes')" round>No
                                        </el-button>
                                    </template>
                                </el-table-column>

                                <el-table-column
                                        prop="created_at"
                                        label="Дата"
                                        width="200">
                                </el-table-column>


                                <el-table-column
                                        width="100"
                                >
                                    <template slot-scope="scope">
                                        <el-button size="mini" type="danger"
                                                   :loading="records.isDeleting"
                                                   @click="deleteRecord(scope.row.id, scope.$index)" round>Удалить
                                        </el-button>
                                    </template>
                                </el-table-column>


                            </el-table>
                        </el-col>
                    </el-row>

                </el-card>
            </el-col>
        </el-row>


        <el-row>
            <el-col :offset="1" :span="22">
                <el-card class="box-card">

                    <el-row>
                        <el-col :offset="3" :span="21">
                            <h2 style="display: inline-block;">Шаблоны ссылок</h2>
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
                                        prop="created_at"
                                        label="Дата"
                                        width="160">
                                </el-table-column>

                                <el-table-column
                                        width="100">
                                    <template slot-scope="scope">
                                        <el-button @click="openCreateRecordDialog(scope.row.id, scope.$index)"
                                                   size="small"
                                                   type="success"
                                                   plain round>Создать партию
                                        </el-button>
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


        <el-dialog
                title="Сколько нужно записать ссылок?"
                :visible.sync="newRecord.isDialogVisible"
                width="30%"
                center>

            <el-input v-model="newRecord.needLinks"></el-input>
            <span slot="footer" class="dialog-footer">
    <el-button @click="newRecord.isDialogVisible = false" :loading="newRecord.isLoading">Отмена</el-button>
    <el-button type="primary" :loading="newRecord.isLoading" @click="createRecord()">Создать</el-button>
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
                newRecord: {
                    isDialogVisible: false,
                    needLinks: 10,
                    pattern_id: null,
                    isLoading: false
                },
                records: {
                    isDeleting: false,
                    data: [],
                    isLoading: true
                },

                isNewStatusLoading: false,
                client: {},
                data: [],
                isTableLoading: true,
                totalLinks: 0,
                currentPage: 1,
            }
        },
        created() {
            this.getClient();
        },
        methods: {
            deleteRecord(record_id, index) {
                this.records.isDeleting = true;
                axios.post('/api/record/delete/' + record_id).then(response => {
                    this.records.data.splice(index, 1);
                    this.$message.success('Успешно удаленно');
                    this.records.isDeleting = false;
                }).catch(reason => {
                    this.$message.error('Ошибка');
                    this.records.isDeleting = false;
                });
            },
            enableRecord(record_id, newStatus) {
                this.isNewStatusLoading = true;
                axios.post('/api/record/enable/' + record_id, {newStatus: newStatus}).then(response => {
                    if (newStatus === 'yes') {
                        for (let index in this.records.data) {
                            if (this.records.data[index].isActive === 'yes') {
                                this.records.data[index].isActive = 'no';
                            }
                            if (this.records.data[index].id === record_id) {
                                this.records.data[index].isActive = 'yes';
                            }
                        }
                    } else if (newStatus === 'no') {
                        for (let index in this.records.data) {
                            if (this.records.data[index].id === record_id) {
                                this.records.data[index].isActive = 'no';
                            }
                        }
                    }
                    this.isNewStatusLoading = false;
                }).catch(reason => {
                    this.isNewStatusLoading = false;
                    this.$message.error('Не удалось обновить');
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
            openCreateRecordDialog(id, index) {
                this.newRecord.pattern_id = id;
                this.newRecord.isDialogVisible = true;
            },
            createRecord() {
                this.newRecord.isLoading = true;
                axios.post('/api/record/create', {
                    pattern_id: this.newRecord.pattern_id,
                    needLinks: this.newRecord.needLinks,
                    client_id: this.client.id
                }).then(response => {
                    this.$message.success('Партия успешно созданна');

                    for (let i = 0; i < this.data.length; i++) {
                        if (this.newRecord.pattern_id === this.data[i].id) {
                            response.data.uids = [];
                            this.records.data.unshift(response.data);
                        }
                    }

                    console.log(this.records.data);
                    this.newRecord.isDialogVisible = false;
                    this.newRecord.isLoading = false;
                }).catch(reason => {
                    this.$message.error('Не удалось создать партию');
                    this.newRecord.isLoading = false;
                });
            },
            getClient() {
                axios.get('/api/client/' + this.$route.params.name).then(response => {
                    this.client = response.data;
                    this.getLinks();
                    this.getRecords();
                }).catch(reason => {
                    this.$message.error('Не удалось получить клиента ' + this.$route.params.name);
                })
            },
            getLinks() {
                this.isTableLoading = true;
                axios.get('/api/patterns/' + this.client.id + '?page=' + this.currentPage).then(response => {
                    this.data = response.data.data;
                    this.totalLinks = response.data.total;

                    for (let index in this.data) {
                        let summ = 0;
                        for (let index1 in this.data[index].uids) {
                            summ += this.data[index].uids[index1].correctrequests_count;
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