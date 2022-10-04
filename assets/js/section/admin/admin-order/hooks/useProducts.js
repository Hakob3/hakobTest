import axios from "axios";
import {ref, onMounted} from "vue";

export function useProducts() {
    const products = ref([]);
    const totalPages = ref(10);
    const isLoading = ref(false);
    const fetching = async () => {
        try {
            const response = await axios.get('/api/products',
                {
                    params: {
                        itemsPerPage: 10,
                        page: 1
                    }
                });
            products.value = response.data;
        } catch (e) {
            alert('Ошибка');
        } finally {
            isLoading.value = false;
        }
    };

    onMounted(fetching)
    return {
        products, isLoading, totalPages
    }
}