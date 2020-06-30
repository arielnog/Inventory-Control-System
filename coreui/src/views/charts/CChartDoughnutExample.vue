<template>
  <CChartDoughnut
    ref="chart"
    :datasets="operations"
    :labels="['Compras', 'Vendas']"
    :options="options"
  />
</template>

<script>
import { CChartDoughnut } from '@coreui/vue-chartjs'
import 'chartjs-plugin-labels'
import axios from 'axios'

export default {
  name: 'CChartDoughnutExample',
  components: { CChartDoughnut },
  data(){
    return {
      operations :  [
        {
          backgroundColor: [
            '#4163b8',
            '#41B883',
          ],
          data: []
        }
      ],
      dataChart: [],
      options: {
        cutoutPercentage: 55,
          plugins: {
            labels: {
              // render 'label', 'value', 'percentage', 'image' or custom function, default is 'percentage'
              render: 'percentage',

              // precision for percentage, default is 0
              precision: 2,

              // font size, default is defaultFontSize
              fontSize: 12,

              // font color, can be color array for each data or function for dynamic color, default is defaultFontColor
              fontColor: '#fff',

              // font style, default is defaultFontStyle
              fontStyle: 'normal',

              // font family, default is defaultFontFamily
              fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

            }
          }
        }
    }
  },
  methods: {
     getData (){
      let self = this
      axios.get(  '/api/getDataChartVendasCompras?token=' + localStorage.getItem("api_token") )
      .then(function (response) {
        response.data.forEach(myFunction);

        function myFunction(item, index) {
            //self.operations[0].data.push(item.total)
            self.dataChart.push(item.total)
        }

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
      const opera = this.operations
      opera[0].data = newData
      this.operations = [...opera]
    }
  }
}

</script>
