<template>
    <div>
        <div class="text-grey-darkest font-normal uppercase text-3xl font-bold leading-none mb-3">
            Outputs
        </div>
            <div class="table striped table-auto text-left text-grey-darkest text-lg bg-gray-400 px-4 py-2">
                <!--Border collapse doesn't work on this site yet but it's available in newer tailwind versions -->
                <div class="table-row">
                    <div class="table-cell bg-gray-400 font-bold text-gray-700 px-4 py-2 text-lg">Number</div>
                    <div class="table-cell bg-gray-400 font-bold text-gray-700 px-4 py-2 text-lg">Title</div>
                    <div class="table-cell bg-gray-400 font-bold text-gray-700 px-4 py-2 text-lg">Title</div>

                </div>
                <div class="table-row hover:bg-grey-light" v-for="output in this.outputs">
                    <div class="table-cell bg-gray-400 text-gray-700 px-4 py-2 text-sm">{{ output.id}}</div>
                    <div class="table-cell bg-gray-400 text-gray-700 px-4 py-2 text-sm">{{ output.title }}</div>
                    <div class="table-cell bg-gray-400 text-gray-700 px-4 py-2 text-sm">{{ output.journal_id }}</div>
                </div>
            </div>
        </div>

</template>
<script>
    import axios from 'axios';
    import { loadProgressBar } from 'axios-progress-bar'
    import 'axios-progress-bar/dist/nprogress.css'

    loadProgressBar();
    var client = new elasticsearch.Client({
        hosts: ['http://127.0.0.1:9200']
    });

    export default {


        data: function() {
            return {
                outputs: []
            }
        },


        mounted() {
            axios.get('/api/output')
                .then((response) => {
                    this.outputs = response.data;
                    console.log('Outputs loaded')
                })
                .catch((err) => {
                    console.log(err);
                });



        },

    }


</script>
