<template>
  <div class="m-4">
    <h1>Страница продуктов</h1>
    <div class="row justify-content-between align-items-center">
      <div class="col-5">
        <my-button @click="showDialog" class="btn-primary">
          Создать продукт
        </my-button>
      </div>
                <div class="col-3">
                  <my-input
                      v-focus
                      :model-value="searchQuery"
                      @update:model-value="setSearchQuery"
                      placeholder="search"
                  />
                </div>
                <div class="col-3">
                  <my-select
                      :model-value="selectedSort"
                      @update:model-value="setSelectedSort"
                      :options="sortOptions"
                  />
                </div>
    </div>
    <!--    <nav aria-label="Page navigation example text-right">-->
    <!--      <ul class="pagination">-->
    <!--        <li class="pag-item  pointer">-->
    <!--          <div class="page-link" aria-label="Previous">-->
    <!--            <span aria-label="true">&laquo;</span>-->
    <!--            <span class="sr-only">Previous</span>-->
    <!--          </div>-->
    <!--        </li>-->
    <!--        <li class="page-item"-->
    <!--            v-for="pageNumber in totalPages"-->
    <!--            :key="pageNumber"-->
    <!--            @click="changePage(pageNumber)"-->
    <!--            :class="{-->
    <!--              'active': page === pageNumber-->
    <!--            }"-->
    <!--        >-->
    <!--          <div class="page-link pointer"-->
    <!--          >-->
    <!--            {{ pageNumber }}-->
    <!--          </div>-->
    <!--        </li>-->
    <!--      </ul>-->
    <!--    </nav>-->
    <product-list
        :products="sortAndSearch"
        @remove="removeProduct"
        v-if="!isLoading"
    />
    <div v-else>loading...</div>
    <my-dialog v-model:show="dialogVisible">
      <product-form
          @create="add"
      />
    </my-dialog>
    <div v-intersection="loadMore"></div>
  </div>
</template>

<script>
import ProductForm from "../components/ProductForm";
import ProductList from "../components/ProductList";
import MySelect from "../components/UI/MySelect";
import {mapState, mapGetters, mapActions, mapMutations} from "vuex";

export default {
  components: {
    MySelect,
    ProductList,
    ProductForm
  },
  data() {
    return {
      dialogVisible: false,
    }
  },
  methods: {
    ...mapMutations({
      setPage: "products/setPage",
      setSearchQuery: "products/setSearchQuery",
      setSelectedSort: "products/setSelectedSort",
    }),
    ...mapActions({
      loadMore: "products/loadMore",
      fetchProducts: "products/fetchProducts",
    }),
    add(product) {
      this.products.push(product);
      this.dialogVisible = false;
    },
    removeProduct(product) {
      this.products = this.products.filter(p => p.uuid !== product.uuid);
    },
    showDialog() {
      this.dialogVisible = true;
    },
    // changePage(pageNumber) {
    //   this.page = pageNumber;
    // },

  },
  mounted() {
    this.fetchProducts();
  },
  computed: {
    ...mapState({
      products: state => state.products.products,
      dialogVisible: state => state.products.dialogVisible,
      isLoading: state => state.products.isLoading,
      selectedSort: state => state.products.selectedSort,
      searchQuery: state => state.products.searchQuery,
      page: state => state.products.page,
      productsPerPage: state => state.products.productsPerPage,
      totalPages: state => state.products.totalPages,
      sortOptions: state => state.products.sortOptions
    }),
    ...mapGetters({
      sortedProducts: "products/sortedProducts",
      sortAndSearch: "products/sortAndSearch"
    })
  },
  watch: {
    // page(){
    //   this.fetchProducts();
    // }
  }
}
</script>

<style>

</style>