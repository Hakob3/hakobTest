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
import axios from "axios";
import MySelect from "../components/UI/MySelect";

export default {
  components: {
    MySelect,
    ProductList,
    ProductForm
  },
  data() {
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
        {
          value: 'title',
          name: 'По названию',
        },
        {
          value: 'price',
          name: 'По цене',
        }
      ]
    }
  },
  methods: {
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
    async fetchProducts() {
      try {
        this.isLoading = true;
        const response = await axios.get('/api/products',
            {
              params: {
                itemsPerPage: this.productsPerPage,
                page: this.page
              }
            });
        this.products = response.data;
      } catch (e) {
        alert('Ошибка');
      } finally {
        this.isLoading = false;
      }
    },
    async loadMore() {
      try {
        this.page += 1;
        const response = await axios.get('/api/products',
            {
              params: {
                itemsPerPage: this.productsPerPage,
                page: this.page
              }
            });
        this.products = [...this.products, ...response.data];
      } catch (e) {
        alert('Ошибка');
      }
    },
  },
  mounted() {
    this.fetchProducts();
  },
  computed: {
    sortedProducts() {
      return [...this.products].sort((product1, product2) => {
        return isNaN(product1[this.selectedSort]) ?
            product1[this.selectedSort]?.localeCompare(product2[this.selectedSort]) :
            product1[this.selectedSort] - product2[this.selectedSort];
      })
    },
    sortAndSearch() {
      return this.sortedProducts.filter(prod => prod.title.toLowerCase().includes(this.searchQuery.toLowerCase()));
    }
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