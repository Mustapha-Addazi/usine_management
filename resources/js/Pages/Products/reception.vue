<template>
  <div class="container">
    <div class="row my-5">
      <div class="col-md-9">
        <div v-if="$page.props.flash && $page.props.flash.success" class="alert alert-success">
            {{ $page.props.flash.success }}
        </div>
        <div class="card">
          <div class="card-body">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Unit</th>
                  <th>Recipied</th>
                  <th>Created At</th>
                  <th>Updated At</th>
                  <th v-if="user.IsAdmin !=='user'" >Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="reception in receptions.data" :key="reception.id">
                  <td>{{ reception.id }}</td>
                  <td>{{ reception.product.name }}</td>
                  <td>{{ reception.product.unit }}</td>
                  <td>{{ reception.recipied }}</td>
                  <td>{{ reception.created_at }}</td>
                  <td>{{ reception.updated_at }}</td>
                   <td v-if="user.IsAdmin !=='user'"> <Link :href="route('crudreception.edit',reception.id)" class="btn btn-warning btn-sm mx-1">
                  <ion-icon name="create-outline"></ion-icon></Link>
                   </td>
                   <td v-if="user.IsAdmin !=='user'"> <button @click="deletereception(reception)" class="btn btn-danger btn-sm mx-1">
                  <ion-icon name="trash-outline"></ion-icon></button> 
                  </td>
                </tr>
              </tbody>
            </table>
            <ul class="pagination d-flex justify-content-center">
              <li class="page-item" :class="{ 'active': link.active }" v-for="(link, index) in receptions.links" :key="index">
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
      <div class="col-md-3">
        <div class="card">
          <div class="card-body d-flex justify-content-center">
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                Filter by Product name
              </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li>
                  <Link class="dropdown-item" :href="route('reception.product',{ product : 0 })">All</Link>
                </li>
                <li v-for="product in products" :key="product.id">
                  <Link class="dropdown-item" :href="route('reception.product',{ product:product.id })">
                    {{ product.name }}
                  </Link>
                </li>
              </ul>
            </div>
          </div>
        </div>
      <div class="card my-3">
          <div class="card-body d-flex justify-content-center">
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                Order by
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li >
                  <Link class="dropdown-item" :href="route('order.reception', { column: 'id', direction: 'asc' })">
                    ID <ion-icon name="arrow-up-outline"></ion-icon>
                  </Link>
                </li>
                 <li >
                  <Link class="dropdown-item" :href="route('order.reception', { column: 'id', direction: 'desc' })">
                    ID <ion-icon name="arrow-down-outline"></ion-icon>
                  </Link>
                </li>
                 <li >
                  <Link class="dropdown-item" :href="route('order.reception', { column: 'recipied', direction: 'asc' })">
                    Reception <ion-icon name="arrow-up-outline"></ion-icon>
                  </Link>
                </li>
                <li >
                  <Link class="dropdown-item" :href="route('order.reception', { column: 'recipied', direction: 'desc' })">
                    Reception <ion-icon name="arrow-down-outline"></ion-icon>
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
import { defineProps,computed } from 'vue'
import { Link,router,usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
const props = defineProps({
  receptions: {
    type: Object,
    required: true
  },
   products: {
    type: Object,
    required: true
  }
})
const user=computed(()=>usePage().props.user)
const deletereception=(reception)=>{
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
    router.delete(route('crudreception.destroy',reception.id))
    Swal.fire({
      title: "Deleted!",
      text: "Your Consumption has been deleted.",
      icon: "success"
    });
  }
});
  
}
</script>
