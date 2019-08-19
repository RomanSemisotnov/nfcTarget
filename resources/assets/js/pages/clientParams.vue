<template>
    <div>
        <client-nav-bar></client-nav-bar>

        <el-row>
            <el-col :offset="3" :span="21">
                <h2 style="display: inline-block;">{{client.name}}</h2>
            </el-col>
        </el-row>

        <el-row>
            <el-col :offset="1" :span="6" v-for="(param,ind) in client.params" :key="ind">
                <el-card class="box-card">

                    <div slot="header" class="clearfix">
                        <span>{{param.index_number}}{{') '+param.name}}</span>

                        <el-button-group style="float: right; padding: 3px 0">
                            <el-button type="primary" @click="openVariableDialog(param.id)" plain
                                       icon="el-icon-plus"></el-button>
                            <el-button type="primary" @click="openParamDialog(param.id)" plain
                                       icon="el-icon-edit"></el-button>
                        </el-button-group>
                    </div>


                    <el-row>
                        <el-col :offset="2" :span="20" :key="index" v-for="(varialbe, index) in param.variables">
                            <el-button-group>
                                <el-button type="primary" plain>{{varialbe.name}}</el-button>
                                <el-button type="primary" :loading="isDeletingVariable"
                                           @click="deleteVariable(varialbe.id, param.id)"
                                           icon="el-icon-delete"></el-button>
                            </el-button-group>
                        </el-col>
                    </el-row>

                </el-card>
            </el-col>
        </el-row>

        <el-dialog title="Добавить подпараметр" :visible.sync="addVariable.visible">
            <el-input v-model="addVariable.name" autocomplete="off"></el-input>
            <span slot="footer" class="dialog-footer">
                <el-button @click="closeVariableDialog()">Назад</el-button>
                <el-button type="primary" :loading="addVariable.loading" @click="addNewVariable()">Добавить</el-button>
            </span>
        </el-dialog>

        <el-dialog title="Обновить параметр" :visible.sync="updateParam.visible">
            <el-input v-model="updateParam.name" autocomplete="off"></el-input>
            <span slot="footer" class="dialog-footer">
                <el-button @click="closeParamDialog()">Назад</el-button>
                <el-button type="primary" :loading="updateParam.loading" @click="updateParamName()">Обновить</el-button>
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
                isDeletingVariable: false,
                updateParam: {
                    visible: false,
                    name: '',
                    param_id: null,
                    loading: false
                },
                addVariable: {
                    visible: false,
                    name: '',
                    param_id: null,
                    loading: false
                }
            }
        },
        created() {
            this.getClient();
        },
        methods: {
            closeParamDialog() {
                this.updateParam.name = '';
                this.updateParam.visible = false;
            },
            openParamDialog(param_id) {
                this.updateParam.visible = true;
                this.updateParam.param_id = param_id;

                for (let index in this.client.params) {
                    if (this.client.params[index].id === this.updateParam.param_id) {
                        this.updateParam.name = this.client.params[index].name;
                    }
                }
            },
            closeVariableDialog() {
                this.addVariable.name = '';
                this.addVariable.visible = false;
            },
            openVariableDialog(param_id) {
                this.addVariable.name = '';
                this.addVariable.visible = true;
                this.addVariable.param_id = param_id;
            },
            addNewVariable() {
                this.addVariable.loading = true;
                axios.post('/api/variable/create', {
                    param_id: this.addVariable.param_id,
                    name: this.addVariable.name
                }).then(response => {
                    for (let index in this.client.params) {
                        if (this.client.params[index].id === this.addVariable.param_id) {
                            this.client.params[index].variables.push(response.data);
                        }
                    }
                    this.addVariable.name = '';
                    this.addVariable.loading = false;
                    this.addVariable.visible = false;
                }).catch(reason => {
                    this.addVariable.loading = false;
                    this.$message.error('Ошибка добавления');
                });
            },
            deleteVariable(id, param_id) {
                this.isDeletingVariable = true;
                axios.post('/api/variable/delete/' + id).then(response => {
                    for (let index in this.client.params) {
                        if (this.client.params[index].id === param_id) {
                            for (let index1 in this.client.params[index].variables) {
                                if (this.client.params[index].variables[index1].id === id) {
                                    this.client.params[index].variables.splice(index1, 1);
                                }
                            }
                        }
                    }
                    this.isDeletingVariable = false;
                }).catch(reason => {
                    this.$message.error('Не удалось удалить параметр');
                    this.isDeletingVariable = false;
                });
            },
            getClient() {
                axios.get('/api/client/' + this.$route.params.name).then(response => {
                    this.client = response.data;
                }).catch(reason => {
                    this.$message.error('Не удалось получить клиента ' + this.$route.params.name);
                })
            },
            updateParamName() {
                this.updateParam.loading = true;
                axios.post('/api/params/update/' + this.updateParam.param_id, {name: this.updateParam.name}).then(response => {
                    for (let index in this.client.params) {
                        if (this.client.params[index].id === this.updateParam.param_id) {
                            this.client.params[index].name = this.updateParam.name;
                        }
                    }

                    this.$message.success('Успешно обновленно');
                    this.updateParam.loading = false;
                    this.updateParam.visible = false;
                }).catch(reason => {
                    this.$message.error('Не удалось обновить имя параметра');
                    this.updateParam.loading = false;
                })
            }
        },
        watch: {
            '$route'(to, from) {
                this.getClient();
            },
        },
    }
</script>

<style scoped>

</style>