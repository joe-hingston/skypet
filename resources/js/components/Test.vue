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
                isLoading: true,
                totalOutputs: 0,
                pagination: {
                    rowsPerPage: 100,
                    descending: false,
                    sortBy: "name",
                    page: 1
                },
                search: '',
                headers: [
                    {
                        text: 'Title',
                        align: 'left',
                        sortable: false,
                        value: 'title'
                    },
                    {text: 'Journal', value: 'journal'},
                    {text: 'Published On', value: 'created'},
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
                return new Promise((resolve, reject) => {
                    const {sortBy, descending, page, rowsPerPage} = this.pagination;
                    let search = this.search.trim().toLowerCase();

                    this.$http
                        .get(
                            "http://skypet.lar/api/output?page=" +
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
                            "http://skypet.lar/api/output?page=" +
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
        },
    }

</script>
