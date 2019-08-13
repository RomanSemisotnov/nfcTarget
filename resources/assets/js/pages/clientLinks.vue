<template>
    <div>
        <client-nav-bar></client-nav-bar>
        <el-row>
            <el-col :offset="3" :span="21">
                <h2 style="display: inline-block;">{{client.name}}</h2>
            </el-col>
        </el-row>

        <el-row>
            <el-col :offset="2" :span="21">
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
                            label="С токеном"
                            width="90">
                        <template slot-scope="scope">
                            <el-button v-if="scope.row.withToken === 'yes'" type="primary" plain size="mini">Yes
                            </el-button>
                            <el-button v-else-if="scope.row.withToken === 'no'" type="warning" plain size="mini">No
                            </el-button>
                            <span v-else>???(обратитесь к Роману)</span>
                        </template>
                    </el-table-column>
                    <el-table-column
                            prop="created_at"
                            label="Дата"
                            width="190">
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

    </div>
</template>

<script>
    import axios from 'axios';
    import clientNavBar from './../components/clientNavBar';
    import deleteLinkButton from './../components/buttons/deleteClientLink';
    import DeleteClientLink from "../components/buttons/deleteClientLink";

    export default {
        components: {
            DeleteClientLink,
            clientNavBar
        },
        data() {
            return {
                client: {},
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
            deleteRow(index) {
                this.data.splice(index, 1);
            },
            getClient() {
                axios.get('/api/client/' + this.$route.params.name).then(response => {
                    this.client = response.data;
                    this.getLinks();
                }).catch(reason => {
                    this.$message.error('Не удалось получить клиента ' + this.$route.params.name);
                })
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
            }
        }
    }
</script>

<style scoped>

</style>