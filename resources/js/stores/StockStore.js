import axios from 'axios';
import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useStockStore = defineStore('StockStore', () => {
    const stockbalance = ref();

    const getStocks = async () => {
        await axios.get('/api/stockStore')
            .then((response) => {
                stockbalance.value = response.data;
            });
    };

    return { stockbalance, getStocks };
});