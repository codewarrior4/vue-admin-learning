<template>
<Pie :data="chartData" :options="chartOptions" :width="chartWidth" :height="chartHeight" />
</template>

<script setup>
import { ref, onMounted,computed } from 'vue';
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale,ArcElement  } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale,ArcElement );


const chartLabels = ref([]); // Define chartLabels as a reactive ref
const chartcounts = ref([])
const chartWidth = ref(20); // Set the default width of the chart
const chartHeight = ref(20);
const chartData = computed(() => {
  return {
    labels: chartLabels.value, // Use chartLabels.value as the labels array using computed property
    datasets: [
      {
        label: 'Appointment Data',
        borderColor: [
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)'
          ],
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
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
        stacked: true,
        beginAtZero: true,
        ticks: {
          stepSize: 1, // Adjust the step size as needed
        },
      },
      y: {
        stacked: true,
        grid: {
          display: false,
        },
      },
    },
    plugins: {
      legend: {
        display: true, // You can display the legend if needed
      },
    },
    maintainAspectRatio: true,
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
