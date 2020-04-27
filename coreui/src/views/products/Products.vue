<template>
  <div>
    <CRow>
      <CCol md="12">
        <CCard>
          <CCardHeader>
            Products <!--&amp; Sales-->
          </CCardHeader>
          <CCardBody>
              <Notes />
            <br/>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  </div>
</template>

<script>
import Notes from '@/views/notes/Notes'
import { CChartLineSimple, CChartBarSimple } from '../charts/index.js'

export default {
  name: 'Products',
  components: {
      Notes
  }
}
</script>
