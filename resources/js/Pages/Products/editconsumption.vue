<template>
  <div class="container">
    <div class="row my-5">
      <div class="col-md-6 mx-auto">
        <div class="card">
          <div class="card-header">
            <h5 class="text-center mt-2">Update Consumption</h5>
          </div>
          <div class="card-body">
            <form @submit.prevent="updateConsumption">
              <div class="mb-3">
                <label for="type">Product Name*</label>
                <select v-model="form.product_id" id="type" class="form-select" @change="updateProductUnit">
                  <option disabled selected value="">Choose Name*</option>
                  <option v-for="product in products" :key="product.id" :value="product.id">{{ product.name }}</option>
                </select>
                <div class="bg-danger text-white rounded p-2" v-if="form.errors.product_id">{{ form.errors.product_id[0] }}</div>
              </div>
              <div class="mb-3">
              <label for="consumed">
                  Day of consumption*
              </label>
              <input type="date" id="consumed" v-model="form.date" class="form-control">
                <div class="bg-danger text-white rounded p-2" v-if="form.errors.date">{{ form.errors.date[0] }}</div>
              </div>
              <div class="mb-3">
                <label for="consumed">Quantity Consumed* ({{ productUnit }})</label>
                <input type="number" id="consumed" v-model="form.consumed" placeholder="Enter the Quantity consumed" class="form-control">
                <div class="bg-danger text-white rounded p-2" v-if="form.errors.consumed">{{ form.errors.consumed[0] }}</div>
              </div>
              <div class="mb-3">
                <label for="consumed">
                  Quantity produced in Tone*
                </label>
                <input type="number" id="consumed" step="0.01"  v-model="form.pf" placeholder="Enter the quantity produced" class="form-control">
                  <div class="bg-danger text-white rounded p-2" v-if="form.errors.pf">{{ form.errors.pf[0] }}</div>
              </div>
              <div class="mb-3">
                <button type="submit" class="btn btn-primary btn-sm">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

const props = defineProps({
  products: {
    type: Array,
    required: true
  },
  consumption: {
    type: Object,
    required: true
  }
})

const form = useForm({
  product_id: props.consumption.product_id,
  consumed: props.consumption.consumed,
  date:props.consumption.date,
  pf:props.consumption.pf
})

const productUnit = ref('')

const updateProductUnit = () => {
  const selectedProduct = props.products.find(product => product.id === form.product_id)
  productUnit.value = selectedProduct ? selectedProduct.unit : ''
}

const updateConsumption = () => {
  form.put(route('crudconsumption.update',props.consumption.id), {
    onSuccess: () => {
      form.reset()
      productUnit.value = ''
    },
    onError: () => {
      // Handle error messages if any
    }
  })
}
</script>
