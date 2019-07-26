<template>
    <v-app id="inspire">
        <v-card>
            <v-card-title>
                <v-spacer></v-spacer>
                <v-text-field
                    append-icon="search"
                    @click:append="getsearch"
                    hide-details
                    label="Search"
                    single-line
                    v-model="search"
                ></v-text-field>
            </v-card-title>
            <v-data-table
                :headers="headers"
                :items="outputs"
                :pagination.sync="pagination"
                :rows-per-page-items="rowsPerPageItems"
                :loading="loading"
                :total-items="totalOutputs"
            >
                <template v-slot:items="props">
                    <td class="text-xs-left">{{ props.item.title }}</td>
                    <td class="text-xs-right">{{ props.item.journal.title}}</td>
                    <td class="text-xs-right">{{ props.item.created }}</td>
                </template>
                <template v-slot:no-results>
                    <v-alert :value="true" color="error" icon="warning">
                        Your search for "{{ search }}" found no results.
                    </v-alert>
                </template>
            </v-data-table>
        </v-card>
    </v-app>
</template>

<script>
    export default {

        //TODO add in url sorting parameters for the VUE = https://codepen.io/retrograde/pen/pmzQNP?editors=1010
        data() {
            return {
                rowsPerPageItems: [25, 50, 100],
                loading: true,
                totalOutputs: 0,
                pagination: {},

                search: '',
                headers: [
                    {text: 'Title', align: 'left', value: 'title',sortable: true},
                    {text: 'Journal', value: 'journal', sortable: true},
                    {text: 'Published On', value: 'created', sortable: true},
                ],
                outputs: []
            }
        },

        watch: {
            pagination: {
                handler() {
                    this.fetchData();
                },
                deep: true
            }
        },
        methods: {
            fetchData() {
                this.loading = true
                return new Promise((resolve, reject) => {
                    const {sortBy, descending, page, rowsPerPage} = this.pagination;
                    let search = this.search.trim().toLowerCase();

                    this.$http
                        .get(
                            "http://homestead.test/api/output?page=" +
                            page +
                            "&rowsPerPage=" +
                            rowsPerPage

                        )
                        .then(response => {
                            console.log(response.data);
                            let outputs = response.data.data;
                            const totalOutputs = response.data.total;
                            if (search) {
                                outputs = outputs.filter(item => {
                                    return Object.values(item)
                                        .join(",")
                                        .toLowerCase()
                                        .includes(search);
                                });
                            }

                            this.outputs = outputs;
                            this.totalOutputs = totalOutputs;
                            this.loading = false;
                            resolve();

                        });
                });

            },

            getsearch(){
                return new Promise((resolve, reject) => {
                    const {sortBy, descending, page, rowsPerPage} = this.pagination;
                    let search = this.search.trim().toLowerCase();

                    this.$http
                        .get(
                            "http://horizon.test/api/output?page=" +
                            page +
                            "&rowsPerPage=" +
                            rowsPerPage +
                            "&title=" +
                            search
                        )
                        .then(response => {
                            console.log(response.data);
                            let outputs = response.data.data;
                            const totalOutputs = response.data.total;
                            if (search) {
                                outputs = outputs.filter(item => {
                                    return Object.values(item)
                                        .join(",")
                                        .toLowerCase()
                                        .includes(search);
                                });
                            }

                            this.outputs = outputs;
                            this.totalOutputs = totalOutputs;
                            this.loading = false;
                            resolve();

                        });
                });
            }


        },
        mounted() {
            this.fetchData();
            this.getsearch();
        },
    }

</script>
