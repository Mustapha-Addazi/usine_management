<template>
  <div class="container">
    <div class="row my-5">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header ">
                    <h5 class="text-center mt-2">
                       Update Reception
                    </h5>
                </div>
                <div class="card-body">
                    <form  @submit.prevent="updateReception">
                      <div class="mb-3">
                            <label for="type">
                                Product Name*
                            </label>
                            <select v-model="form.product_id" id="type" class="form-select" @change="updateProductUnit">
                                <option disabled selected value="">Choose Name*</option>
                                <option v-for="product in products" :key="product.id" :value="product.id">{{ product.name }}</option>
                            </select>
                            <div class="bg-danger text-white rounded p-2" v-if="form.errors.product_id">{{ form.errors.product_id[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="recipied">
                                Quantity Recipied* ({{ productUnit }})
                            </label>
                            <input type="number" id="consumed" v-model="form.recipied" placeholder="Enter the Quantity recipied" class="form-control">
                             <div class="bg-danger text-white rounded p-2" v-if="form.errors.recipied">{{ form.errors.recipied[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Update
                            </button>
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
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  products: {
    type: Array,
    required: true
  },
  reception: {
    type: Object,
    required: true
  },
})

const form = useForm({
  product_id: props.reception.product_id,
  recipied: props.reception.recipied
})


const productUnit = ref('')

const updateProductUnit = () => {
  const selectedProduct = props.products.find(product => product.id === form.product_id)
  productUnit.value = selectedProduct ? selectedProduct.unit : ''
}

const updateReception = () => {
  form.put(route('crudreception.update',props.reception.id), {
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
