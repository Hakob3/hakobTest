<template>
  <tr>
    <td class="product-col">
      <div class="tex-center">
        <figure>
          <a
              :href="urlShowProduct"
              target="_blank"
          >
            <img
                :src="getUrlProductImage(productImage)"
                :alt="cartProduct.product.title">
          </a>
        </figure>
        <div class="product-title">
          <a
              :href="urlShowProduct"
              target="_blank"
          >
            {{ cartProduct.product.title }}
          </a>
        </div>
      </div>
    </td>
    <td class="price-col">
      ${{ cartProduct.product.price }}
    </td>
    <td class="quantity-col">
      <input
          v-model="quantity"
          type="number"
          class="form-control"
          min="1"
          step="1"
          data-decimal="0"
          @focusout="updateQuantity(cartProduct.id, quantity)"
      >
    </td>
    <td class="total-col">
      ${{ productPrice }}
    </td>
    <td class="remove-col">
      <button
          class="btn btn-remove btn-outline-danger"
          title="Remove product"
          @click="removeCartProduct(cartProduct.id)"
      >
        X
      </button>
    </td>
  </tr>
</template>

<script>
import {mapActions, mapState} from "vuex";

export default {
  name: "CartProductItem",
  data() {
    return {
      quantity: 1
    }
  },
  props: {
    cartProduct: {
      type: Object,
      default: () => {
      }
    },
  },
  created() {
    this.quantity = this.cartProduct.quantity;
  },
  computed: {
    ...mapState({
      staticStore: state => state.cart.staticStore,
    }),
    productImage() {
      const productImages = this.cartProduct.product.productImages;

      return productImages.length ? productImages[0] : null
    },
    productPrice() {
      return this.quantity * this.cartProduct.product.price;
    },
    urlShowProduct() {
      return '/product/' + this.cartProduct.product.uuid;
    }
  },
  methods: {
    ...mapActions({
      removeCartProduct: "cart/removeCartProduct",
      updateCartProductQuantity: "cart/updateCartProductQuantity",
    }),
    getUrlProductImage(productImage) {

      return (this.staticStore.url.urlAssetImageProducts +
          "/" +
          this.cartProduct.product.id +
          "/" +
          productImage.filename);
    },
    updateQuantity() {
      const payload = {
        cartProductId: this.cartProduct.id,
        quantity: this.quantity
      }

      this.updateCartProductQuantity(payload)
    }
  }
}
</script>

<style scoped>

</style>