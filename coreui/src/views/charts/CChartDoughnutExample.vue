<template>
  <CChartDoughnut
    ref="chart"
    :datasets="operations"
    :labels="['Compras', 'Vendas']"
  />
</template>

<script>
import { CChartDoughnut } from '@coreui/vue-chartjs'
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
      dataChart: []
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
