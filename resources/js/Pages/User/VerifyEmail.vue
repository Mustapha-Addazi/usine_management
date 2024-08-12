<template>
  <div class="container">
    <div class="row my-5">
      <div class="col-md-6 mx-auto">
        <div v-if="$page.props.flash && $page.props.flash.failed" class="alert alert-danger">
          {{ $page.props.flash.failed }}
        </div>
        <div class="card">
          <div class="card-header">
            <h5 class="text-center mt-2">Forgot Password</h5>
          </div>
          <div class="card-body">
            <form @submit.prevent="sendResetLink">
              <div class="mb-3">
                <label for="email">
                  Enter your email address, and we will send a link to reset your password*
                </label>
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
                <button type="submit" class="btn btn-primary btn-sm">Reset Password</button>
              </div>
              <p class="text-center text-muted">
                Back to <Link :href="route('login')">Login</Link>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
  email: ''
})

const sendResetLink = () => {
  form.post(route('verify_email'))
}
</script>
