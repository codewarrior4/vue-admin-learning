<template>
  <Bar :data="chartData" :options="chartOptions" />
</template>

<script setup>
import { ref, onMounted,computed } from 'vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);


const chartLabels = ref([]); // Define chartLabels as a reactive ref
const chartcounts = ref([])
const chartData = computed(() => {
  return {
    labels: chartLabels.value, // Use chartLabels.value as the labels array using computed property
    datasets: [
      {
        label: 'Appointment Data',
        backgroundColor: [ 
            '#3498db',
            '#00ff00',
            '#ff0000',
          ],
        data: chartcounts.value, 
      },
    ],
  };
});

const chartOptions = computed(() => {
  return {
    responsive: true,
    indexAxis: 'y', // Set the index axis to 'y' for horizontal bars
    scales: {
      x: {
        stacked: false,
        beginAtZero: true,
        ticks: {
          stepSize: 1, // Adjust the step size as needed
        },
      },
      y: {
        stacked: true,
        grid: {
          display: true,
        },
      },
    },
    plugins: {
      legend: {
        display: true,
        position: 'bottom', // You can display the legend if needed
      },
    },
    barPercentage: 0.8, // Adjust the bar percentage to control the width
    categoryPercentage: 0.8, // Adjust the category percentage to control the width
  };
});


const renderChart = () => {
  const chartCanvas = ref('myChart');
  new Bar({
    el: chartCanvas.value,
    data: chartData.value,
    
    options: {
      responsive: true,
    },
  });
};

const appointmentStatus = ref([]);

const getAppointmentStatus = async () => {
  try {
    const response = await axios.get('/api/appointments-status');
    appointmentStatus.value = response.data;
    const statusNames = appointmentStatus.value.map((status) => status.name);
    const count = appointmentStatus.value.map((status) => status.count);
    chartLabels.value = statusNames;
    chartcounts.value = count;
  } catch (error) {
    console.error(error);
  }
};


onMounted(async() => {
  // renderChart();
  console.log(1)
  await getAppointmentStatus()
});
</script>
