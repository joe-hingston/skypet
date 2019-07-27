<template>
    <div>

        <v-app id="inspire">
            <div class="text-grey-darkest font-normal uppercase text-3xl font-bold leading-none mb-3">Journals</div>
            <v-container fluid grid-list-xl>
                <v-layout wrap column align-center>
                    <v-flex :key="i"
                            v-for="(journal, i) in journals">
                        <v-card color="red darken-1" dark width="300px">
                            <v-card-text>{{ journal.title}}</v-card-text>
                            <v-img src="https://picsum.photos/id/1074/5472/3648" height="100px"></v-img>
                            <v-card-actions>
                                <v-card-text>{{ journal.total_articles}} Articles </v-card-text>
                                <v-spacer></v-spacer>
                                <v-tooltip bottom>
                                    <v-btn icon flat color="red"><v-icon>favorite</v-icon></v-btn>
                                    <span>お気に入り</span>
                                </v-tooltip>
                                <v-spacer></v-spacer>
                                <v-btn icon><v-icon>arrow_right</v-icon></v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>

        </v-app>
    </div>
</template>

<script>
    import {APIService} from './scripts/APIService';

    const apiService = new APIService();
    export default {

        data: () => ({
            journals: [],
        }),
        methods: {
            fetchData() {
                return new apiService.getJournals().then((data) => {
                    let journals = data;
                    this.journals = journals;
                                 });

            },
        },
        mounted() {
            this.fetchData();
        },
    };


</script>
