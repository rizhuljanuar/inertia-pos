<template>
  <Head>
    <title>Products - Aplikasi Kasir</title>
  </Head>

  <main class="c-main">
    <div class="containe-fluid">
      <div class="fade-in">
        <div class="row">
          <div
            class="col-md-12 card border-0 rounded-3 shadow border-top-purple"
          >
            <div class="card-header">
              <span class="font-weight-bold"
                ><i class="fa fa-shoping-bag"></i> PRODUCTS</span
              >
            </div>
            <div class="card-body">
              <form @submit.prevent="handleSearch">
                <div class="input-group mb-3">
                  <Link
                    href="/apps/products/create"
                    v-if="hasAnyPermission(['products.create'])"
                    class="btn btn-primary input-group-text"
                    ><i class="fa fa-plus-circle me-2"></i> NEW</Link
                  >
                  <input
                    type="text"
                    class="form-control"
                    v-model="search"
                    placeholder="search by product title..."
                  />
                  <button
                    type="submit"
                    class="btn btn-primary input-group-text"
                  >
                    <i class="fa fa-search me-2"></i> SEARCH
                  </button>
                </div>
              </form>
              <table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th scope="col">Barcode</th>
                    <th scope="col">Title</th>
                    <th scope="col">Buy Price</th>
                    <th scope="col">Sell Price</th>
                    <th scope="col">Stock</th>
                    <th scope="col" style="width: 20%">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(product, index) in products.data" :key="index">
                    <td class="text-center">
                      <Barcode
                        :value="product.barcode"
                        :format="`CODE39`"
                        :lineColor="`#000`"
                        :width="1"
                        :height="20"
                      />
                    </td>
                    <td>{{ product.title }}</td>
                    <td>Rp. {{ formatPrice(product.buy_price) }}</td>
                    <td>Rp. {{ formatPrice(product.sell_price) }}</td>
                    <td>{{ product.stock }}</td>
                    <td class="text-center">
                      <Link
                        :href="`/apps/products/${product.id}/edit`"
                        v-if="hasAnyPermission(['products.edit'])"
                        class="btn btn-success btn-sm"
                        ><i class="fa fa-pencil-alt me-1"></i> EDIT</Link
                      >
                      <button
                        @click.prevent="destroy(product.id)"
                        v-if="hasAnyPermission(['products.delete'])"
                        class="btn btn-danger btn-sm ms-3"
                      >
                        <i class="fa fa-trash"></i> DELETE
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <Pagination :links="products.links" align="end" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import LayoutApp from "../../../Layouts/App.vue";
import { Link, Head } from "@inertiajs/inertia-vue3";
import Pagination from "../../../Components/Pagination.vue";
import { ref } from "@vue/reactivity";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";
import Barcode from "../../../Components/Barcode.vue";
export default {
  layout: LayoutApp,

  components: {
    Link,
    Head,
    Pagination,
    Barcode,
  },

  props: {
    products: Object,
  },

  setup() {
    const search = ref("" || new URL(document.location).searchParams.get("q"));

    const handleSearch = () => {
      Inertia.get("/apps/products", {
        q: search.value,
      });
    };

    const destroy = (id) => {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          Inertia.delete(`/apps/products/${id}`);

          Swal.fire({
            title: "Success!",
            text: "Product deleted successfully.",
            icon: "success",
            showConfirmButton: false,
            timer: 2000,
          });
        }
      });
    };

    return {
      search,
      handleSearch,
      destroy,
    };
  },
};
</script>