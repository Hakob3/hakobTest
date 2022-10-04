<template>
  <div class="m-4">
    <h1>Страница продуктов</h1>
    <div class="row justify-content-between align-items-center">
      <div class="col-5">
      </div>
      <div class="col-3">
        <my-input
            v-focus
            v-model="searchQuery"
            placeholder="search"
        />
      </div>
      <div class="col-3">
        <my-select
            v-model="selectedSort"
            :options="sortOptions"
        />
      </div>
    </div>
    <product-list
        :products="sortAndSearch"
        v-if="!isLoading"
    />
    <div v-else>loading...</div>
    <my-dialog v-model:show="dialogVisible">
      <product-form
          @create="add"
      />
    </my-dialog>
  </div>
</template>

<script>
import ProductForm from "../components/ProductForm";
import ProductList from "../components/ProductList";
import MySelect from "../components/UI/MySelect";
import MyButton from "../components/UI/MyButton";
import {useProducts} from "../hooks/useProducts";
import useSortedProducts from "../hooks/useSortedProducts";
import useSortAndSearch from "../hooks/useSortAndSearch";

export default {
  components: {
    MyButton,
    MySelect,
    ProductList,
    ProductForm
  },
  data() {
    return {
      dialogVisible: false,
      sortOptions: [
        {value: 'title', name: 'По названию'},
        {value: 'price', name: 'По цене'}
      ]
    }
  },
  setup(props) {
    const {isLoading, products, totalPages} = useProducts();
    const {selectedSort, sortedProducts} = useSortedProducts(products);
    const {sortAndSearch, searchQuery} = useSortAndSearch(sortedProducts);

    return {
      isLoading,
      products,
      totalPages,
      selectedSort,
      sortedProducts,
      sortAndSearch,
      searchQuery
    };
  }
}
</script>

<style>

</style>