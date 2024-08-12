<template>
  <div class="container">
    <div class="row my-5">
      <div class="col-md-6 mx-auto">
        <div v-if="$page.props.flash && $page.props.flash.failed" class="alert alert-danger text-center">
          {{ $page.props.flash.failed }}
        </div>
        <div v-if="$page.props.flash && $page.props.flash.success" class="alert alert-success text-center">
          {{ $page.props.flash.success }}
        </div>
        <div class="card">
          <div class="card-header">
            <h5 class="text-center mt-2">Login</h5>
          </div>
          <div class="card-body">
            <form @submit.prevent="login">
              <div class="mb-3">
                <label for="email">Email*</label>
                <input
                  type="email"
                  id="email"
                  v-model="form.email"
                  placeholder="Email"
                  class="form-control"
                />
                <div class="bg-danger text-white rounded p-2" v-if="form.errors.email">
                  {{ form.errors.email[0] }}
                </div>
              </div>
              <div class="mb-3">
                <label for="password">Password*</label>
                <input
                  type="password"
                  id="password"
                  v-model="form.password"
                  placeholder="Password"
                  class="form-control"
                />
                <div class="bg-danger text-white rounded p-2" v-if="form.errors.password">
                  {{ form.errors.password[0] }}
                </div>
              </div>
              <div class="mb-3 d-flex justify-content-between">
                <button type="submit" class="btn btn-primary btn-sm">Login</button>
                 <Link as="button" class="nav-link active border-0" :href="route('forgot')">Forgot Password</Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm,Link } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: ''
})

const login = () => {
  form.post(route('login'))
}
</script>
