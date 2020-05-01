<template>
  <CChartLine
    :datasets="defaultDatasets"
    :options="defaultOptions"
    :labels="['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro']"
  />
</template>

<script>
import { CChartLine } from '@coreui/vue-chartjs'
import { getStyle, hexToRgba } from '@coreui/utils/src'
import axios from 'axios'

function random (min, max) {
  return Math.floor(Math.random() * (max - min + 1) + min)
}

export default {
  name: 'MainChartExample',
  components: {
    CChartLine
  },
  data(){
    return {
        defaultDatasets: [
        { 
          label: 'Gasto',
          backgroundColor: hexToRgba(getStyle('info') || '#20a8d8', 10),
          borderColor: getStyle('info') || '#20a8d8',
          pointHoverBackgroundColor: getStyle('info') || '#20a8d8',
          borderWidth: 2,
          data: []
        },
        {
          label: 'Lucro',
          backgroundColor: 'transparent',
          borderColor: getStyle('success2') || '#4dbd74',
          pointHoverBackgroundColor: getStyle('success2') || '#4dbd74',
          borderWidth: 2,
          data: []
        }
      ],
      defaultOptions: {

        maintainAspectRatio: false,
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            gridLines: {
              drawOnChartArea: false
            }
          }],
          yAxes: [{
            ticks: {
              beginAtZero: true,
              maxTicksLimit: 15,
              stepSize: null,
              max: null
            },
            gridLines: {
              display: true
            }
          }]
        },
        elements: {
          point: {
            radius: 0,
            hitRadius: 10,
            hoverRadius: 4,
            hoverBorderWidth: 3
          }
        }
      },
      data1: [],
      data2: [],
      dataMax:[],
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
      
      axios.get(  '/api/getDataChartLineGastosVendas?token=' + localStorage.getItem("api_token") )
      .then(function (response) {     

        response.data[0].Compra.forEach(myFunction);

        function myFunction(item, index) {
            self.data1.push(item)
            self.dataMax.push(item)
        }

        response.data[0].Vendas.forEach(myFunctionTwo);

        function myFunctionTwo(item, index) {
            self.data2.push(item)
            self.dataMax.push(item)
        }

        self.max = parseInt(Math.max(...self.dataMax)) + 1000
        //console.log(response.data)

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
    data2 (newData) {
      this.defaultOptions.scales.yAxes[0].ticks.max = this.max
      this.defaultOptions.scales.yAxes[0].ticks.stepSize = Math.ceil(this.max / 5)

      this.defaultDatasets = [
        { 
          label: 'Gastos',
          backgroundColor: hexToRgba(getStyle('info') || '#20a8d8', 10),
          borderColor: getStyle('info') || '#20a8d8',
          pointHoverBackgroundColor: getStyle('info') || '#20a8d8',
          borderWidth: 2,
          data: this.data1
        },
        {
          label: 'Lucros',
          backgroundColor: 'transparent',
          borderColor: getStyle('success2') || '#4dbd74',
          pointHoverBackgroundColor: getStyle('success2') || '#4dbd74',
          borderWidth: 2,
          data: newData
        }
      ]

    }

  }
}
</script>
