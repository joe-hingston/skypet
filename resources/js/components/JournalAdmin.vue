<template>

    <div>
        <v-app id="inspire">
            <v-toolbar color="white" flat>
                <v-toolbar-title>Journals</v-toolbar-title>

                <v-spacer></v-spacer>

                <v-dialog max-width="400px" v-model="dialog2">
                    <v-card>
                        <v-card-title>
                            Error
                        </v-card-title>
                        <v-card-text>
                            {{errorText}}
                        </v-card-text>
                        <v-card-actions>
                            <v-btn @click="dialog2=false" color="primary" flat>Close</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-dialog max-width="400px" v-model="dialog">
                    <template v-slot:activator="{ on }">
                        <v-btn class="mb-2" color="primary" dark v-on="on">Add Issn</v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex md12 sm24 xs48>
                                        <v-text-field label="ISSN" v-model="editedItem.issn"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn @click="close" color="blue darken-1" flat>Cancel</v-btn>
                            <v-btn @click="save" color="blue darken-1" flat>Save</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>

            <v-data-table
                :headers="headers"
                :items="journals"
                class="elevation-1"
            >

                <template v-slot:items="props">
                    <td>{{ props.item.title }}</td>
                    <td class="text-xs-right">{{ props.item.issn }}</td>
                    <td class="text-xs-right">{{ props.item.total_articles}}</td>
                    <td class="text-xs-right">{{ props.item.updated_at}}</td>
                    <td class="justify-center layout px-3">

                        <v-icon
                            @click="deleteItem(props.item)"
                            small
                        >
                            delete
                        </v-icon>

                        <v-icon
                            @click="deleteItem(props.item)"
                            small
                        >
                            refresh
                        </v-icon>

                        <v-icon
                            @click="deleteItem(props.item)"
                            small
                        >
                            offline_pin
                        </v-icon>

                        <v-icon
                            @click="deleteItem(props.item)"
                            small
                        >
                            send
                        </v-icon>

                    </td>
                </template>
                <template v-slot:no-data>
                    <v-btn @click="initialize" color="primary">Reset</v-btn>
                </template>
            </v-data-table>
        </v-app>
    </div>
</template>
<script>

    import {APIService} from './scripts/APIService';

    const apiService = new APIService();


    export default {


        data: () => ({
            alert: true,
            rowsPerPageItems: [25, 50, 100],
            loading: true,
            totalJournals: 0,
            dialog: false,
            dialog2: false,
            headers: [
                {text: 'Name', value: 'name', sortable: false},
                {text: 'ISSN', value: 'issn', sortable: true},
                {text: 'Total Articles', value: 'total_articles', sortable: true},
                {text: 'Last Updated', value: 'lastupdated', sortable: true},
                {text: 'Actions', value: 'actions', sortable: false},
            ],
            journals: [],
            editedIndex: -1,
            editedItem: {
                name: '',
                issn: 0,
                lastedited: 0,
                lastupdated: 0,
            },
            defaultItem: {
                name: '',
                issn: 0,
                lastedited: 0,
                lastupdated: 0,
            },
            saveitem: false,
        }),


        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'Add Journal' : 'Edit Item'
            },
            errorText() {
                return 'The ISSN you provided is incorrect'
            }
        },

        watch: {
            dialog(val) {
                val || this.close()
            }
        },

        mounted() {
            this.fetchData();
        },
        created() {
            this.initialize()
        },

        methods: {

            fetchData() {
                this.loading = true;
                return new apiService.getJournals().then((data) => {
                    let journals = data;
                    const totalJournals = data.total;
                    this.journals = journals;
                    this.totalJournals = totalJournals;
                    this.loading = false;

                });

            },
            initialize() {
            },

            editItem(item) {
                this.editedIndex = this.journals.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.dialog = true
            },

            deleteItem(item) {
                this.editedIndex = this.journals.indexOf(item);
                this.editedItem = Object.assign({}, item);

                if(confirm('Are you sure you want to delete this item?')){
                    this.journals.splice(this.editedIndex, 1);
                    apiService.deleteJournal(this.editedItem.issn).then((data) => {
                    });
                }

            },

            close() {
                this.dialog = false;
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1
                }, 300)
            },


            save() {

                let self = this;
                if (this.editedIndex === -1) {
                    //add item
                    apiService.checkCrossRefJournal(this.editedItem.issn).then((data) => {
                        if (data.status === 200) {
                            apiService.createJournal(this.editedItem.issn).then((data) => {
                            })

                        }
                    }).catch(function (error) {
                        if (error.response) {
                            if (error.response.status === 404) {
                                console.log('hello');
                                self.dialog2 = true
                            }

                        }
                    });
                }
                this.close()
            },


        }
        ,
    }

</script>
