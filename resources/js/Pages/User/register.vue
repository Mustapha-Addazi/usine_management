<template>
  <div class="container">
    <div class="row my-5">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header ">
                    <h5 class="text-center mt-2">
                        Register
                    </h5>
                </div>
                <div class="card-body">
                    <form  @submit.prevent="register">
                      <div class="mb-3">
                            <label for="name">
                                Name*
                            </label>
                            <input type="text" id="name" v-model="form.name" placeholder="Name" class="form-control">
                            <div class="bg-danger text-white rounded p-2" v-if="form.errors.name">{{ form.errors.name[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="email">
                                Email*
                            </label>
                            <input type="email" id="email" v-model="form.email" placeholder="Email" class="form-control">
                             <div class="bg-danger text-white rounded p-2" v-if="form.errors.email">{{ form.errors.email[0] }}</div>
                        </div>
                         <div class="mb-3">
                        <label for="type">Type of user*</label>
                        <select v-model="form.type" id="type" class="form-control">
                            <option selected disabled value="">Select Type</option>
                            <option value="Admin">Admin</option>
                            <option value="user">user</option>
                        </select>
                        <div class="bg-danger text-white rounded p-2" v-if="form.errors.type">{{ form.errors.type[0] }}</div>
                       </div>
                             <div class="mb-3">
                            <label for="password">
                                Password*
                            </label>
                            <input type="password" id="password" v-model="form.password" placeholder="Password" class="form-control">
                             <div class="bg-danger text-white rounded p-2" v-if="form.errors.password">{{ form.errors.password[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Register
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

import { useForm,router } from '@inertiajs/vue3'

const form = useForm({
  name:'',
  email:'',
  type:'',
  password:''
})

const register = () => {
  form.post(route('user.store'))
}
</script>
