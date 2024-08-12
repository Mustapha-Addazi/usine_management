<template>
  <div class="container">
    <div class="row my-5 d-flex">
      <div v-if="$page.props.flash && $page.props.flash.success" class="alert alert-success">
              {{ $page.props.flash.success }}
      </div>
      <div class="col-md-10">
        <div class="card">
          <div class="card-body">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Type</th>
                  <th>Unit</th>
                  <th>Covering in day</th>
                  <th> unit weight </th>
                  <th>Created At</th>
                  <th v-if="user.IsAdmin !=='user'">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="product in products.data" :key="product.id">
                  <td>{{ product.id }}</td>
                  <td>{{ product.name }}</td>
                  <td>{{ product.type }}</td>
                  <td>{{ product.unit }}</td>
                  <td>{{ product.covering }}</td>
                  <td v-if="product.type==='Solid' || product.type==='Fluid'">{{ product.weightunit }}</td>
                  <td v-else>-</td>
                  <td>{{ product.created_at }}</td>
                  <td v-if="user.IsAdmin !=='user'"> <Link :href="route('crudproduct.edit',product.id)" class="btn btn-warning mx-1 btn-sm">
                    <ion-icon name="create-outline"></ion-icon></Link>
                    <button @click="deleteproduct(product)" class="btn btn-danger btn-sm">
                    <ion-icon name="trash-outline"></ion-icon></button> 
                    </td>
                </tr>
              </tbody>
            </table>
            <ul class="pagination d-flex justify-content-center">
                <li class="page-item" :class="{ 'active': link.active }" v-for="(link, index) in products.links" :key="index">
                    <Link 
                    v-if="link.url"
                    class="page-link" 
                    :href="link.url"
                  v-dompurify-html="link.label">
                    </Link>
                  <div v-else class="page-link" v-dompurify-html="link.label">
                    </div>
                </li>
             </ul>
           </div>
        </div>
      </div>
      <div class="col-md-2">
          <div class="card">
            <div class="card-body d-flex justify-content-center">
              <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                  Filter by Type
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li>
                      <Link class="dropdown-item" :href="route('product.type',{ type : 0 })">All</Link>
                    </li>
                    <li v-for="type in productTypes" :key="type">
                      <Link class="dropdown-item" :href="route('product.type',{ type:type })">
                        {{ type }}
                      </Link>
                    </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</template>

<script setup>
import { computed, defineProps} from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
const props = defineProps({
  products: {
    type: Object,
    required: true
  }
})
const productTypes = ['Solid', 'Fluid', 'Packaging', 'Consumable']
const user=computed(()=>usePage().props.user)
const deleteproduct=(product)=>{
  Swal.fire({
  title: "Are you sure?",
  text: "You won't be able to revert this!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Yes, delete it!"
}).then((result) => {
  if (result.isConfirmed) {
    router.delete(route('crudproduct.destroy',product.id))
    Swal.fire({
      title: "Deleted!",
      text: "Your Product has been deleted.",
      icon: "success"
    });
  }
});
  
}
</script>
