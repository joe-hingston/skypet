<template>

    <div>
        <v-app id="inspire">
            <v-toolbar color="white" flat>
                <v-toolbar-title>Journals</v-toolbar-title>

                <v-spacer></v-spacer>

                <v-dialog max-width="500px" v-model="dialog2">
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

                <v-dialog max-width="1000px" v-model="dialog">
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
                                    <v-flex md4>
                                        <v-text-field label="Name" v-model="editedItem.title"></v-text-field>
                                    </v-flex>
                                    <v-flex md4 sm6 xs12>
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
                    <td class="text-xs-right">{{ props.item.updated_at}}</td>
                    <td class="justify-center layout px-0">
                        <v-icon
                            @click="editItem(props.item)"
                            class="mr-2"
                            small
                        >
                            edit
                        </v-icon>
                        <v-icon
                            @click="deleteItem(props.item)"
                            small
                        >
                            delete
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
    import axios from 'axios';

    const API_URL = 'http://localhost:8000';
    export default {


        data: () => ({
            alert: true,
            rowsPerPageItems: [25, 50, 100],
            loading: true,
            totalJournals: 0,
            dialog: false,
            dialog2: false,
            headers: [
                {text: 'Name', value: 'name', sortable: true},
                {text: 'ISSN', value: 'issn', sortable: true},
                {text: 'Last Updated', value: 'lastupdated', sortable: true},
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
            status: 0
        }),


        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
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
            this.checkItem();
        },
        created() {
            this.initialize()
        },

        methods: {

            fetchData() {
                this.loading = true
                return new Promise((resolve, reject) => {

                    this.$http
                        .get(
                            "http://skypet.lar/api/journals"
                        )
                        .then(response => {
                            let journals = response.data;
                            const totalJournals = response.data.total;
                            this.journals = journals;
                            this.totalJournals = totalJournals;
                            this.loading = false;
                            resolve();

                        });
                });

            },
            initialize() {
            },

            editItem(item) {
                this.editedIndex = this.journals.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem(item) {
                const index = this.journals.indexOf(item)
                confirm('Are you sure you want to delete this item?') && this.journals.splice(index, 1)
            },

            close() {
                this.dialog = false
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                }, 300)
            },

             checkItem() {

                const  url = `http://api.crossref.org/journals/` + this.editedItem.issn;
                return  axios.get(url);
            },

            save() {
                let self = this

                if (this.editedIndex > -1) {
                    Object.assign(this.journals[this.editedIndex], this.editedItem)

                    //  checkItem(this.editedItem)

                    //
                    //     if (this.checkItem(this.editedItem)) {
                    //         console.log('should be okay to save')
                    //         this.journals.push(this.editedItem)
                    //         //api function to add
                    //     }
                    //
                    // } else if (!this.checkItem(this.editedItem)) {
                    //     //
                    //     console.log('is not okay to save')
                    //     this.dialog2 = true
                    // }
                }
                this.close()

                if (this.editedIndex = -1) {

                   this.checkItem().then(function(response){
                      if(response.data.status = 'ok'){
                          //add via API call
                      }

                   })
                       .catch(function (error) {
                           if (error.response) {
                               if(error.response.status===404){
                                   self.dialog2 = true
                               };

                           }
                       });;

                }
                this.close()
            },


        },
    }

</script>
//TODO need to add in Vdialog for error on API check, and add journal through API
