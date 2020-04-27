<template>
  <CChartBar
    :datasets="defaultDatasets"
    :options="defaultOptions"
    :labels="['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro']"
  />
</template>

<script>
import { CChartBar } from '@coreui/vue-chartjs'
import axios from 'axios'

export default {
  name: 'CChartBarExample',
  components: { CChartBar },
  data(){
    return {
    defaultDatasets : [
        {
          label: 'Lucro',
          backgroundColor: '#f87979',
          data: []
        }
      ],
    defaultOptions :{

        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true,
              maxTicksLimit: 15,
              stepSize: Math.ceil(100000 / 5),
              max: 100000
            }
          }]
        }
      },
      dataChart: [],
      max: null
    }
  },
  methods: {
    formatPrice(value) {
        let val = (value/1).toFixed(2).replace('.', '.')
        return val.replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    },
     getData (){
      let self = this
      
      axios.get(  '/api/getDataChartLucroVendas?token=' + localStorage.getItem("api_token") )
      .then(function (response) {     

       // self.dataChart = response.data
        let max = Math.max(...self.dataChart)

        response.data.forEach(myFunction);

        function myFunction(item, index) {
            self.dataChart.push(item)
        }

        //console.log(self.dataChart)

      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    }
  },
  mounted: function(){
          this.getData()
  },
  watch: {
    dataChart (newData) {
      const opera = this.defaultDatasets
      opera[0].data = newData
      this.defaultDatasets = [...opera]
    }
  }
}
</script>
