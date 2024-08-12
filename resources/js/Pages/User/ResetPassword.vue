<template>
  <div class="container">
    <div class="row my-5">
      <div class="col-md-6 mx-auto">
        <div v-if="form.errors" class="alert alert-danger">
          <div v-for="(error, index) in form.errors" :key="index">
            {{ error[0] }}
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h5 class="text-center mt-2">Reset Password</h5>
          </div>
          <div class="card-body">
            <form @submit.prevent="resetPassword">
              <div class="mb-3">
                <label for="email">Email Address</label>
                <input
                  type="email"
                  id="email"
                  v-model="form.email"
                  placeholder="Email"
                  class="form-control"
                />
                <div
                  v-if="form.errors.email"
                  class="bg-danger text-white rounded p-2"
                >
                  {{ form.errors.email[0] }}
                </div>
              </div>
              <div class="mb-3">
                <label for="password">New Password</label>
                <input
                  type="password"
                  id="password"
                  v-model="form.password"
                  placeholder="Password"
                  class="form-control"
                />
                <div
                  v-if="form.errors.password"
                  class="bg-danger text-white rounded p-2"
                >
                  {{ form.errors.password[0] }}
                </div>
              </div>
              <div class="mb-3">
                <label for="password_confirmation">Confirm Password</label>
                <input
                  type="password"
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  placeholder="Confirm Password"
                  class="form-control"
                />
                <div
                  v-if="form.errors.password_confirmation"
                  class="bg-danger text-white rounded p-2"
                >
                  {{ form.errors.password_confirmation[0] }}
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Reset Password</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();
const token = props.token || '';
const email = props.email || '';

const form = useForm({
  email: email,
  password: '',
  password_confirmation: '',
  token: token
});

const resetPassword = () => {
  form.post('/password/reset', {
    onSuccess: () => {
      // Redirection ou autre traitement après le succès
    },
    onError: (errors) => {
      console.error(errors);
    }
  });
};
</script>
