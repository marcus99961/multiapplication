import axios from 'axios';
import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useItemStore = defineStore('ItemStore', () => {
    const item = ref({
        name: '',
        id: '',
      
    });

    const getItem = async () => {
        await axios.get('/api/getItem')
            .then((response) => {
                item.value = response.data;
            });
    };

    return { item, getItem };
});
