<template>
    <div>
        <ul>
            <li v-for="output in outputs">
                {{ output.title }}
            </li>
        </ul>
        <v-paginator :resource_url="resource_url" @update="updateResource"></v-paginator>
    </div>
</template>
<script>

    import VuePaginator from 'vuejs-paginator'

    export default {
        data () {
            return {
                outputs: [],
                resource_url: '/api/output',
            }
        },
        components: {
            VPaginator: VuePaginator
        },
        methods: {
            updateResource(data){
                this.outputs = data
            }
        }
    };
</script>
