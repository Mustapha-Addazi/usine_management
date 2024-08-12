<template>
  <div class="container">
    <div class="row my-5">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-center mt-2">
                        Create new product
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <form @submit.prevent="addproduct">
                    <div class="mb-3">
                        <label for="name">Name*</label>
                        <input type="text" placeholder="Name" class="form-control" v-model="form.name">
                        <div class="bg-danger text-white rounded p-2" v-if="form.errors.name">{{ form.errors.name[0] }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="type">Type*</label>
                        <select v-model="form.type" id="type" class="form-control" @change="updateproductunit">
                            <option selected disabled value="">Select Type</option>
                            <option value="Solid">Solid</option>
                            <option value="Fluid">Fluid</option>
                            <option value="Packaging">Packaging</option>
                            <option value="Consumable">Consumable</option>
                        </select>
                        <div class="bg-danger text-white rounded p-2" v-if="form.errors.type">{{ form.errors.type[0] }}</div>
                    </div>
                     <div class="mb-3" v-if="form.type==='Packaging' || form.type==='Consumable'">
                        <label for="type">Unit*</label>
                        <select v-model="form.unit" id="type" class="form-control" @change="updateproductunit">
                            <option selected disabled value="">Select Unit</option>
                             <option value="Kg">Kg</option>
                            <option value="Piece">Piece</option>
                        </select>
                        <div class="bg-danger text-white rounded p-2" v-if="form.errors.unit">{{ form.errors.unit[0] }}</div>
                    </div>
                    <div class="mb-3" v-if="form.type==='Solid'">
                        <label for="unit">Unit*</label>
                        <input v-model="form.unit" type="text" placeholder="Unit" class="form-control">
                        <div class="bg-danger text-white rounded p-2" v-if="form.errors.unit">{{ form.errors.unit[0] }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="coveringUnity"> {{unit}} consumed per day*</label>
                        <input v-model="form.cover" step="0.01" type="number" id="coveringUnity" placeholder="Enter quantity consumed in day" class="form-control">
                        <div class="bg-danger text-white rounded p-2" v-if="form.errors.cover">{{ form.errors.cover[0] }}</div>
                    </div>
                    <div class="mb-3" v-if="form.type === 'Solid' || form.type==='Fluid'">
                        <label for="unityweight"> {{productunit}}</label>
                        <input v-model="form.unityweight" step="0.01" type="text" placeholder="" class="form-control">
                        <div class="bg-danger text-white rounded p-2" v-if="form.errors.unityweight">{{ form.errors.unityweight[0] }}</div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary btn-sm">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  type: '',
  unit: '',
  cover: '',
  unityweight: ''
})
const unit =ref('')
const productunit=ref('')
const updateproductunit=()=>{
  if(form.type==="Solid"){
    productunit.value="Weight unity in kg*"
    unit.value='kg'
  }else if(form.type==="Fluid"){
    productunit.value="Container area in m² *"
    unit.value='litres'
    form.unit="m"
  }else {
    unit.value=form.unit
    form.unityweight=1
  }
  }
const addproduct = () => {
  form.post('/crudproduct', {
    onSuccess: () => {
      form.reset(),
      productunit.value=''
    },
    onError: () => {
      // Handle error messages if any
    }
  })
}
</script>
