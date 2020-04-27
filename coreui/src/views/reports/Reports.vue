<template>
  <div>
    <CRow>
      <CCol md="12">
        <CCard>
          <CCardHeader>
            Reports <!--&amp; Sales-->
          </CCardHeader>
          <CCardBody>
              <NotesReports />
            <br/>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  </div>
</template>

<script>
import NotesReports from '@/views/reports/NotesReports'

export default {
  name: 'Reports',
  components: {
      NotesReports
  }
}
</script>
