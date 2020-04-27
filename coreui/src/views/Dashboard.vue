<template>
  <div>
    <CCard>
      <CCardBody>
        <CRow>
          <CCol sm="5">
            <h4 id="traffic" class="card-title mb-0">Lucro e Gasto</h4>
            <div class="small text-muted">Comparativo Mensal</div>
          </CCol>
          <CCol sm="7" class="d-none d-md-block">
            <CButton color="primary" class="float-right">
              <CIcon name="cil-cloud-download"/>
            </CButton>
            <CButtonGroup class="float-right mr-3">
              <CButton
                color="outline-secondary"
                v-for="(value, key) in ['Month']"
                :key="key"
                class="mx-0"
                :pressed="value === selected ? true : false"
                @click="selected = value"
              >
                {{value}}
              </CButton>
            </CButtonGroup>
          </CCol>
        </CRow>
        <MainChartExample style="height:300px;margin-top:40px;"/>
      </CCardBody>
    </CCard>

    <CCardGroup columns class="card-columns cols-2">
      <CCard>
        <CCardHeader>Lucro por Mês (Ano Atual)</CCardHeader>
          <CCardBody>
            <BarChartExample/>
          </CCardBody>
      </CCard>
      <CCard>
        <CCardHeader>Vendas e Compras (Ano Atual) </CCardHeader>
          <CCardBody>
          <CChartDoughnutExample/>
          </CCardBody>
      </CCard>
    </CCardGroup>
  </div>
</template>

<script>
import MainChartExample from './charts/MainChartExample'
import BarChartExample from './charts/CChartBarExample'
import CChartDoughnutExample from './charts/CChartDoughnutExample'
import axios from 'axios'

export default {
  name: 'Dashboard',
  components: {
    MainChartExample,
    BarChartExample,
    CChartDoughnutExample
  },
  data () {
    return {
      selected: 'Month',
    }
  }
}
</script>
