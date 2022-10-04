import {computed, ref} from "vue";

export default function useSortAndSearch(sortedProducts) {
    const searchQuery = ref('');
    const sortAndSearch = computed(() => {
        return sortedProducts.value.filter(prod => prod.title.toLowerCase().includes(searchQuery.value.toLowerCase()));
    })

    return {
        searchQuery, sortAndSearch
    };
}