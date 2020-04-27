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
      data1: [],
      data2: [],
    }
  },
  computed: {
    defaultOptions () {
      return {

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
              maxTicksLimit: 5,
              stepSize: Math.ceil(100000 / 5),
              max: 100000
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
      }
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
        }

        response.data[0].Vendas.forEach(myFunctionTwo);

        function myFunctionTwo(item, index) {
            self.data2.push(item)
        }


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
          label: 'Vendas',
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
