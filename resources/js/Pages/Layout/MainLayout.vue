<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <img :src="imageUrl" alt="log" class="img-fluid" width="80px" height="50px" >
        <button class="navbar-toggler" value="" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <Link class="nav-link active" 
                  aria-current="page"
                   :href="route('home')">
                  <ion-icon name="home"></ion-icon> Home
                  </Link>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="true">
                        Status
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <Link class="nav-link dropdown-item"
                    :href="route('consumption')">
                    <ion-icon name="flame"></ion-icon>Consumptions
                    </Link>
                        </li>
                        <li>
                    <Link class="nav-link dropdown-item"
                    :href="route('product')">
                    <ion-icon name="cube"></ion-icon> Products
                    </Link>
                    <Link class="nav-link dropdown-item"
                    :href="route('reception')">
                    <ion-icon name="card"></ion-icon> Receptions
                    </Link>
                    
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><Link class="dropdown-item" :href="route('command.index')">Orders</Link></li>
                    </ul>
                </li>
                <li class="nav-item dropdown" v-if="user && user.IsAdmin !=='user'">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <ion-icon name="create"></ion-icon>Create
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <Link class="nav-link dropdown-item"
                    :href="route('crudconsumption.create')">
                    <ion-icon name="create"></ion-icon>Consumption
                    </Link>
                    </li>
                    <li>
                    <Link class="nav-link dropdown-item"
                    :href="route('crudproduct.create')">
                    <ion-icon name="create"></ion-icon>Product
                    </Link>
                    </li>
                    <li>
                    <Link class="nav-link dropdown-item"
                    :href="route('crudreception.create')">
                    <ion-icon name="create"></ion-icon>Reception
                    </Link>
                    </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><Link class="dropdown-item" :href="route('command.create')">Orders</Link></li>
                    </ul>
                </li>
                <li class="nav-item">
                <Link class="nav-link" :href="route('statistic')" tabindex="-1" aria-disabled="true">Statistic</Link>
                </li>
               
                
            </ul>
            <ul v-if="!user" class="navbar-nav  mb-2 mb-lg-0"> 
                <li class="nav-item">
                  <Link class="nav-link active" 
                  aria-current="page"
                   :href="route('login')">
                  <ion-icon name="log-in-outline"></ion-icon> Login
                  </Link>
                </li>
            </ul>
            <ul v-else class="navbar-nav  mb-2 mb-lg-0">
                <li class="nav-item">
                  <Link class="nav-link active" 
                  aria-current="page"
                   :href="route('profile',{id:user.id})">
                  <ion-icon name="person-outline"></ion-icon> {{user.name}}
                  </Link>
                </li>
               <li class="nav-item"  v-if="user.IsAdmin==='Super Admin'">
                <Link class="nav-link active border-0" as="button" :href="route('users')" >Users</Link>
                </li>
                <li class="nav-item"  v-if="user.IsAdmin==='Super Admin'">
                <Link class="nav-link active border-0" as="button" :href="route('user.create')" >Add user</Link>
                </li>
                
                <li class="nav-item">
                  <Link class="nav-link active border-0" 
                  method="post" as="button"
                  aria-current="page"
                   :href="route('logout')">
                  <ion-icon name="log-out-outline"></ion-icon> Log out
                  </Link>
                </li>
            </ul>
        <form @submit.prevent="search" class="d-flex">
            <input v-model="form.term" class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
        </div>
    </div>
</nav>
<slot></slot>
</template>

<script setup>
import { Link ,useForm, usePage} from '@inertiajs/vue3'
import {computed,ref} from 'vue'
const form=useForm({
    term:''
})

const search=()=>{
 form.get(route('search'))
}

const user = computed ( ()=>usePage().props.user )
const imageUrl = ref('/assets/logo.jpg')
</script>
