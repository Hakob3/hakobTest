import {computed, ref} from "vue";

export default function useSortedProducts(products) {
    const selectedSort = ref('');
    const sortedProducts = computed(() => {
        return [...products.value].sort((product1, product2) => {
            return isNaN(product1[selectedSort.value]) ?
                product1[selectedSort.value]?.localeCompare(product2[selectedSort.value]) :
                product1[selectedSort.value] - product2[selectedSort.value];
        })
    });

    return {
        selectedSort, sortedProducts
    };
}