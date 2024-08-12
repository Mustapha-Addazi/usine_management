<template>
  <div class="container">
    <div class="row my-5">

      <div v-if="$page.props.flash && $page.props.flash.success" class="alert alert-success">
        {{ $page.props.flash.success }}
      </div>
      <div class="card">
        <div class="card-body">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Profile</th>
                <th>Name</th>
                <th>Email</th>
                <th>Type</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in filteredUsers" :key="user.id">
                <td>{{ user.id }}</td>
                <td><img :src="user.url" alt="profile" width="70px" height="60px" class="img-fluide rounded"></td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.IsAdmin }}</td>
                <td>{{ user.created_at }}</td>
                <td>{{ user.updated_at }}</td>
                <td>
                  <button @click="deleteuser(user)" class="btn btn-danger btn-sm">
                    <ion-icon name="trash-outline"></ion-icon>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <ul class="pagination d-flex justify-content-center">
            <li class="page-item" :class="{ 'active': link.active }" v-for="(link, index) in users.links" :key="index">
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
  </div>
</template>
<script setup>
import { computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
  users: {
    type: Object,
    required: true
  }
})

const user1 = computed(() => usePage().props.user)

const filteredUsers = computed(() => {
  return props.users.data.filter(user => user1.value.name !== user.name)
})

const deleteuser = (user) => {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(route('user.destroy', user.id))
      Swal.fire({
        title: "Deleted!",
        text: "Your user has been deleted.",
        icon: "success"
      });
    }
  });
}
</script>
