<template>
  <div class="chart-container">
    <div class="filter-options my-3">
      <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
      <button class="btn btn-primary" @click="setTimeUnit('day')">Day</button>
      <button class="btn btn-primary" @click="setTimeUnit('month')">Month</button>
      <button class="btn btn-primary" @click="setTimeUnit('year')">Year</button>
      </div>
    <div v-if="timeUnit === 'day'" class="date-picker">
      <label for="start-date">Start date :</label>
      <input type="date" id="start-date" v-model="startDate">
      <label for="end-date">End date :</label>
      <input type="date" id="end-date" v-model="endDate">
    </div>
    <div v-if="timeUnit === 'month'" class="date-picker">
      <label for="start-month">Start month :</label>
      <input type="month" id="start-month" v-model="startDate">
      <label for="end-month">End month:</label>
      <input type="month" id="end-month" v-model="endDate">
    </div>
    <div v-if="timeUnit === 'year'" class="date-picker">
      <label for="start-year">Start Year :</label>
      <input type="number" id="start-year" v-model="startDate" min="2000" max="2100">
      <label for="end-year">End Year :</label>
      <input type="number" id="end-year" v-model="endDate" min="2000" max="2100">
    </div>

    <button class="btn btn-secondary" @click="fetchData">Applicate Filter</button>
    </div>
    <div class="product-chart">
    <div v-for="(product, index) in localConsumptionsByProduct" :key="index" class="chart" >
      <h3>{{ product.name }}</h3>
      <Bar :data="getChartData(product)" :options="chartOptions" />
    </div>
  </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

// Props
const props = defineProps({
  consumptionsByProduct: {
    type: Array,
    required: true,
    default: () => []
  },
  initialStartDate: {
    type: String,
    required: false
  },
  initialEndDate: {
    type: String,
    required: false
  },
  initialUnit: {
    type: String,
    default: 'day'
  }
});

// Reactive data and form initialization
const timeUnit = ref(props.initialUnit);
const startDate = ref(props.initialStartDate || '');
const endDate = ref(props.initialEndDate || '');
const localConsumptionsByProduct = ref([...props.consumptionsByProduct]);

// Use Inertia's useForm to manage form data and submissions
const form = useForm({
  startDate: startDate.value,
  endDate: endDate.value,
  unit: timeUnit.value
});

// Methods
const setTimeUnit = (unit) => {
  timeUnit.value = unit;
  startDate.value = '';
  endDate.value = '';
};

const fetchData = () => {
  if (!startDate.value || !endDate.value) {
    alert('Veuillez sélectionner les dates de début et de fin.');
    return;
  }

  form.startDate = startDate.value;
  form.endDate = endDate.value;
  form.unit = timeUnit.value;

  form.get(route('statistic'), {
    onSuccess: (page) => {
      localConsumptionsByProduct.value = page.props.consumptionsByProduct;
    },
    onError: (errors) => {
      console.error(errors);
    }
  });
};

// Computed properties for chart options
const chartOptions = computed(() => ({
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    x: {
      title: {
        display: true,
        text: 'Date'
      }
    },
    y: {
      beginAtZero: true,
      title: {
        display: true,
        text: 'Consommation'
      }
    }
  }
}));

const getChartData = (product) => {
  const labels = Object.keys(product.consumptions).sort();
  const data = labels.map(label => product.consumptions[label]);

  return {
    labels: labels,
    datasets: [
      {
        label: 'Consommation',
        backgroundColor: '#42A5F5',
        data: data
      }
    ]
  };
};
</script>

<style scoped>
.filter-options {
  width: 80%;
  display: flex;
  justify-content: space-around;
}
.product-chart {
  display: grid;
  grid-template-columns: repeat(2, 1fr); 
  gap: 20px; 
  width: 100%; 
  margin: 20px auto; 
  box-sizing: border-box; 
  
}
.chart{
  height: 500px;
  padding: 3%;
}
</style>
