<template>
  <div class="container">
    <div class="row my-5">
       <div class="col-md-10">
      <div class="card">
        <div class="card-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Unit</th>
                <th>Cover_day</th>
                <th>stock</th>
                <th>Commanded</th>
                <th>Cover order</th>
                <th> SC </th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in products.data" :key="product.id">
                <td>{{ product.id }}</td>
                <td>{{ product.name }}</td>
                <td>{{ product.unit }}</td>
                <td>{{ format(product.covering_day) }}</td>
                <td>{{ product.stock }}</td>
                <td v-if="findcommand(product.id)">{{findcommand(product.id).commanded}}</td>
                <td v-else>-</td>
                <td v-if="findcommand(product.id)">{{format(findcommand(product.id).commanded * product.weightunit/product.covering)}}</td>
                <td v-else>-</td>
                <td>{{ format(product.cs) }}</td>
                <td>  
                   <div v-if="isAboveAlert(product)" class="btn btn-danger ">
                      <ion-icon name="notifications-circle-outline"></ion-icon>
                    </div>
                    <div v-else class="btn btn-success ">
                      <ion-icon name="notifications-off-outline"></ion-icon>
                    </div>
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
import { defineProps } from 'vue'
import {Link} from '@inertiajs/vue3'
const props = defineProps({
  products: {
    type: Object,
    required: true
  },
  commands:{
    type:Array,
    required:true
  }
})
console.log(props.products)
function findcommand(productId){
  return props.commands.find(command => command.product_id===productId)
}
const productTypes=['Solid', 'Fluid', 'Packaging', 'Consumable']
const format = (value) => {
  return parseFloat(value).toFixed(3)
}
const isAboveAlert=(product)=>{
  return (product.covering_day<14);
}
</script>
