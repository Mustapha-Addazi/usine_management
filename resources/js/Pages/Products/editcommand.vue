<template>
  <div class="container">
    <div class="row my-5">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header ">
                    <h5 class="text-center mt-2">
                       Update Order
                    </h5>
                </div>
                <div class="card-body">
                    <form  @submit.prevent="updatecommand">
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
                            <label for="stage">
                                Select stage*
                            </label>
                            <select v-model="form.status" id="stage" class="form-select">
                                <option disabled selected value="">Choose Stage*</option>
                                <option  :value="'expression of needs'">expression of needs</option>
                                <option :value="'Request of purchase'">Request of purchase</option>
                                <option :value="'expression of interest'">expression of interest</option>
                                <option :value="'call for tender'">call for tender</option>
                                <option :value="'technical'">technical</option>
                                <option :value="'commercial opening'">commercial opening</option>
                                <option :value="'confirmed'">confirmed</option>
                            </select>
                            <div class="bg-danger text-white rounded p-2" v-if="form.errors.status">{{ form.errors.status[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="commanded">
                                Quantity commanded* ({{ productUnit }})
                            </label>
                            <input type="number" id="consumed" v-model="form.commanded" placeholder="Enter the Quantity commanded" class="form-control">
                             <div class="bg-danger text-white rounded p-2" v-if="form.errors.commanded">{{ form.errors.commanded[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="consumed">
                               Alert before days
                            </label>
                            <input type="number" id="commandd" v-model="form.notif" placeholder="Enter the days before alert" class="form-control">
                             <div class="bg-danger text-white rounded p-2" v-if="form.errors.notif">{{ form.errors.notif[0] }}</div>
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
  command: {
    type: Object,
    required: true
  },
})

const form = useForm({
  product_id: props.command.product_id,
  commanded: props.command.on_hold,
  notif:props.command.notif,
  status:props.command.status
})


const productUnit = ref('')

const updateProductUnit = () => {
  const selectedProduct = props.products.find(product => product.id === form.product_id)
  productUnit.value = selectedProduct ? selectedProduct.unit : ''
}

const updatecommand = () => {
  form.put(route('command.update',props.command), {
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
