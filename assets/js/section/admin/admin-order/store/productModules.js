import axios from "axios";

export const productModules = {
    state: () => {
        return {
            products: [],
            dialogVisible: false,
            isLoading: false,
            selectedSort: '',
            searchQuery: '',
            page: 1,
            productsPerPage: 10,
            totalPages: 5,
            sortOptions: [
                {value: 'title', name: 'По названию'},
                {value: 'price', name: 'По цене'}
            ]
        }
    },
    getters: {
        sortedProducts(state) {
            return [...state.products].sort((product1, product2) => {
                return isNaN(product1[state.selectedSort]) ?
                    product1[state.selectedSort]?.localeCompare(product2[state.selectedSort]) :
                    product1[state.selectedSort] - product2[state.selectedSort];
            })
        },
        sortAndSearch(state, getters) {
            return getters.sortedProducts.filter(prod => prod.title.toLowerCase().includes(state.searchQuery.toLowerCase()));
        }
    },
    mutations: {
        setProducts(state, products) {
            state.products = products;
        },
        setLoading(state, bool) {
            state.isLoading = bool;
        },
        setPage(state, page) {
            state.page = page;
        },
        setSelectedSort(state, selectedSort) {
            state.selectedSort = selectedSort;
        },
        setSearchQuery(state, searchQuery) {
            state.searchQuery = searchQuery;
        },
        setTotalPages(state, totalPages) {
            state.totalPages = totalPages;
        },
    },
    actions: {
        async fetchProducts({state, commit}) {
            try {
                commit('setLoading', true);
                const response = await axios.get('/api/products',
                    {
                        params: {
                            itemsPerPage: state.productsPerPage,
                            page: state.page
                        }
                    });
                commit('setProducts', response.data);
            } catch (e) {
                alert('Ошибка');
            } finally {
                commit('setLoading', false);
            }
        },
        async loadMore({state, commit}) {
            try {
                commit('setPage', state.page + 1);
                const response = await axios.get('/api/products',
                    {
                        params: {
                            itemsPerPage: state.productsPerPage,
                            page: state.page
                        }
                    });
                commit('setProducts', [...state.products, ...response.data]);
            } catch (e) {
                alert('Ошибка');
                console.log(e);
            }
        },
    },
    namespaced: true
}