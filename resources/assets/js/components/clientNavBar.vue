<template>
    <el-menu :default-active="$route.path" class="el-menu-demo" mode="horizontal" @select="handleSelect">
        <el-menu-item :index="getRoute('setting')">Настройки</el-menu-item>
        <el-menu-item :index="getRoute('params')">Параметры и подпараметры</el-menu-item>
        <el-menu-item :index="getRoute('patterns')">Управление</el-menu-item>
        <el-menu-item :index="getRoute('record')">Запись</el-menu-item>
        <el-menu-item :index="getRoute('analytics')">Аналитика</el-menu-item>
    </el-menu>
</template>

<script>
    export default {
        data() {
            return {
                activeIndex: "1"
            }
        },
        created() {

        },
        methods: {
            getRoute(razdel) {
                let array = this.$route.path.split('/');
                array[array.length - 1] = razdel;
                let path = array.join('/');
                return path;
            },
            handleSelect(key, keyPath) {
                this.$router.push({path: key});
            }
        }
    }
</script>

<style scoped>

</style>