<template>
  <div class="container">
    <div class="row my-5">
        <div class="col-md-6 mx-auto">
            <div class="card">
              <div v-if="$page.props.flash && $page.props.flash.failed" class="alert alert-danger">
            {{ $page.props.flash.failed }}
               </div>
                <div class="card-header ">
                    <h5 class="text-center mt-2">
                        Create New Reception
                    </h5>
                </div>
                <div class="card-body">
                    <form  @submit.prevent="addReception">
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
                            <label for="consumed">
                                Quantity Recipied* ({{ productUnit }})
                            </label>
                            <input type="number" id="recipied" v-model="form.recipied" placeholder="Enter the Quantity consumed" class="form-control">
                             <div class="bg-danger text-white rounded p-2" v-if="form.errors.recipied">{{ form.errors.recipied[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Create
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

const form = useForm({
  product_id: '',
  recipied: ''
})

const props = defineProps({
  products: {
    type: Array,
    required: true
  }
})

const productUnit = ref('')

const updateProductUnit = () => {
  const selectedProduct = props.products.find(product => product.id === form.product_id)
  productUnit.value = selectedProduct ? selectedProduct.unit : ''
}

const addReception = () => {
  form.post('/crudreception', {
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
