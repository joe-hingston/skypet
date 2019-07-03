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
            <div :key="output.id" class="table-row hover:bg-grey-light" v-for="output in laravelData.data">
                <div class="table-cell bg-gray-400 text-gray-700 px-4 py-2 text-sm">{{ output.id}}</div>
                <div class="table-cell bg-gray-400 text-gray-700 px-4 py-2 text-sm">{{ output.title }}</div>
                <div class="table-cell bg-gray-400 text-gray-700 px-4 py-2 text-sm">{{ output.journal_id }}</div>
            </div>
        </div>


        <pagination :data="laravelData" @pagination-change-page="getResults">
            <button class="btn btn-blue" slot="prev-nav">&lt; Previous</button>
            <button class="btn btn-blue" slot="next-nav">Next &gt;</button>
        </pagination>

    </div>

</template>
<script>
    import axios from 'axios';
    import {loadProgressBar} from 'axios-progress-bar';


    loadProgressBar();


    export default {


//TODO add in paignation to the results
        data: function () {
            return {
                laravelData: {},
            }

        },

        props:{
          limit :5,
        },


        mounted() {

            this.getResults();


        },


        methods: {
            // Our method to GET results from a Laravel endpoint
            getResults(page = 1) {
                axios.get('api/outputs?page=' + page)
                    .then(response => {
                        this.laravelData = response.data;
                    });
            }
        },


    }


</script>
