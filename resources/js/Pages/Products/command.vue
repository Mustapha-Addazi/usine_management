<template>
  <div class="container">
    <div class="row my-5">
      <div class="col-md-13">
        <div v-if="$page.props.flash && $page.props.flash.success" class="alert alert-success">
            {{ $page.props.flash.success }}
        </div>
        <div class="card">
          <div class="card-body">
            <table class="table table-striped ">
              <thead >
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Unit</th>
                  <th>Commanded</th>
                  <th>On hold</th>
                  <th>Covered</th>
                  <th>Alert</th>
                  <th>stage</th>
                  <th>Status</th>
                  <th>Created At</th>
                  <th>Updated At</th>
                  <th  v-if="user.IsAdmin !=='user'">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="command in commands.data" :key="command.id">
                <td>{{ command.id }}</td>
                <td>{{ command.product.name }}</td>
                <td>{{ command.product.unit }}</td>
                <td>{{ command.commanded }}</td>
                <td>{{command.on_hold}}</td>
                <td>{{ format(convering_day(command)) }}</td>
                <td>{{ command.notif }}d</td>
                <td >{{ command.status }}</td>
                <td>
                   <div v-if="isAboveAlert(command)" class="btn btn-danger ">
                      <ion-icon name="notifications-circle-outline"></ion-icon>
                    </div>
                    <div v-else class="btn btn-success ">
                      <ion-icon name="notifications-off-outline"></ion-icon>
                    </div>
                </td>
                <td>{{ command.created_at }}</td>
                <td>{{ command.updated_at }}</td>
                <td v-if="user.IsAdmin !=='user'">
                  <Link :href="route('command.edit', command)" class="btn btn-warning btn-sm mx-1">
                    <ion-icon name="create-outline"></ion-icon>
                  </Link>
                </td>
                <td v-if="user.IsAdmin !=='user'">
                  <button @click="DeleteCommand(command)" class="btn btn-danger btn-sm mx-1">
                    <ion-icon name="trash-outline"></ion-icon>
                  </button>
                </td>
              </tr>

              </tbody>
            </table>
            <ul class="pagination d-flex justify-content-center">
              <li class="page-item" :class="{ 'active': link.active }" v-for="(link, index) in commands.links" :key="index">
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
  </div>
</template>

<script setup>
import { defineProps,computed } from 'vue'
import { Link,router,usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
const props = defineProps({
  commands: {
    type: Object,
    required: true
  },
   products: {
    type: Object,
    required: true
  }
})
const user = computed ( ()=>usePage().props.user )
const convering_day=(command)=>{
  return command.commanded * command.product.weightunit/command.product.covering}
const isAboveAlert = (command) => {

  return (command.commanded * command.product.weightunit/command.product.covering) < command.notif;
}
const format = (value) => {
  return parseFloat(value).toFixed(3)
}
const DeleteCommand=(command)=>{
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
    router.delete(route('command.destroy',command))
    Swal.fire({
      title: "Deleted!",
      text: "Your Consumption has been deleted.",
      icon: "success"
    });
  }
});
  
}
</script>
