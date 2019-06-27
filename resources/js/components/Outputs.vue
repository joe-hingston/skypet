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
        <pagination
            :meta_data="meta_data"
            v-on:next="fetchOutputs()">
        </pagination>
    </div>

</template>
<script>
    import axios from 'axios';
    import {loadProgressBar} from 'axios-progress-bar';
    import Pagination from './outputs/Paigination';


    loadProgressBar();


    axios.interceptors.request.use(request => {
        console.log('Starting Request', request);
        return request
    });

    axios.interceptors.response.use(response => {
        console.log('Response:', response);
        return response
    });


    export default {

        components: {
            Pagination
        },
//TODO add in paignation to the results
        data: function () {
            return {
                outputs: [],
                start: 0,
                amount: 10,
                meta_data: {
                    last_page_url: null,
                    current_page_url: null,
                    prev_page_url: null
                },
                baseurl: new URL('/api/test')
            }

        },


        methods: {
            nextPage() {
                this.pageNumber++;
            },
            prevPage() {
                this.pageNumber--;
            },
            fetchOutputs() {

                const params = {
                    size: this.amount,
                    start: this.start,
                };

                axios.get('/api/test', {params})
                    .then((response) => {
                        this.outputs = response.data;
                        console.log('Outputs loaded')
                    })
                    .catch((err) => {
                        console.log(err);
                    });
              this.meta_data.current_page_url = this.baseurl.searchParams.append(params);
              console.log(meta_data);
              // this.meta_data.last_page_url = lastPage();
            },

        },

        computed: {

            pageCount() {
                let l = this.outputs.length,
                    s = this.amount;
                return Math.ceil(l / s);
            },
            paginatedData() {
                const start = this.start * this.amount,
                    end = start + this.size;
                return this.outputs.slice(start, end);
            },
            // lastPage(){


        },


        mounted() {

            this.fetchOutputs();


        },

    }


</script>
