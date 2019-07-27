import axios from 'axios';

const API_URL = 'http://skypet.lar';

export class APIService {

    constructor() {
    }

    getJournals() {
        const url = `${API_URL}/api/journals/`;
        return axios.get(url).then(response => response.data);
    }

    getJournal(id) {
        const url = `${API_URL}/api/journals/${id}`;
        return axios.get(url).then(response => response.data);
    }

    createJournal(issn) {
        const url = `${API_URL}/api/journals/`;
        return axios.post(url,{ issn: issn});
    }

    deleteJournal(issn){
        this.issn = issn;
        const url = `${API_URL}/api/journals/`;
        return axios.delete(url,{ data:  {issn: issn}});
    }

    checkCrossRefJournal(issn) {
        const url = `http://api.crossref.org/journals/` + issn;
        return axios.get(url);

    }

}
