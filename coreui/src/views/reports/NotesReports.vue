<template>
  <CRow>
<!--------------------------------------------------------------SHOW MODAL------------------------------------------------------------->
     <b-modal
      id="modal-prevent-closing-report-show"
      ref="modal"
      title="Report Info"
      scrollable 
      hide-footer
    >
    <!--
      header-bg-variant="primary"
      header-text-variant="light"
    -->
        <CRow>
          <CCol col="12" xl="12">
            <CCard no-header>
              <CCardBody>
                <h4>#ID:  {{ formReport.id }}</h4>
                <h4>#Name Product:</h4>
                <p>{{formProduct.name_product}}</p>
                <h4>#Quantity:</h4>
                <p>{{formReport.quantity}}
                </p>
                <h4>#Name Buyer:</h4> 
                <p>{{formReport.name_buyer}}</p>
                <h4>#Price Unit Sold:</h4> 
                <p>R$ {{formatPrice(formProduct.price_unit_sold)}}</p>
                <h4>#Price Unit Purchased:</h4> 
                <p>R$ {{formatPrice(formProduct.price_unit_purchased)}}</p>
                <h4>#CPF:</h4> 
                <p>{{formatCpf(formReport.cpf)}}</p>
                <h4>#CNPJ:</h4> 
                <p>{{formatCnpj(formReport.cnpj)}}</p>
                <h4>#Discount:</h4> 
                <p>R$ {{formatPrice(formReport.discount)}}</p>
                <h4>#Profit or Expense:</h4> 
                <p>R$ {{formatPrice(formReport.profit_or_expense)}}</p>            
                <h4>#Type: </h4>
                <p>
                  <template v-if="formReport.type == 'Compra'">
                    <CBadge color="primary">Compra</CBadge>
                  </template>
                  <template v-else>
                    <CBadge color="success">Venda</CBadge>
                  </template>
                </p>
                <h4>#Created at:</h4>
                <p>{{ formatDate(formReport.created_at) }}</p>
                <h4>#Updated at:</h4>
                <p>{{ formatDate(formReport.updated_at) }}</p>
              </CCardBody>
            </CCard>
          </CCol>
        </CRow>
    </b-modal>
<!--------------------------------------------------------------SHOW MODAL------------------------------------------------------------->

    <CCol col="12" xl="12">
      <transition name="slide">
      <CCard>
        <CCardBody>
            <CAlert
              :show.sync="dismissCountDown"
              color="primary"
              fade
            >
              ({{dismissCountDown}}) {{ message }}
            </CAlert>
            <CDataTable
              hover
              :items="items"
              :fields="fields"
              :items-per-page="10"
              pagination
              table-filter
              items-per-page-select
              sorter
            >
              <template #id="{item}">
                <td>
                  <strong>{{item.id}}</strong>
                </td>
              </template>
              <template #name_product="{item}">
                <td>
                  <strong>{{item.products.name_product}}</strong>
                </td>
              </template>
              <template #product_id="{item}">
                <td>
                  <strong>{{item.product_id}}</strong>
                </td>
              </template>
              <template #quantity="{item}">
                <td>
                  {{item.quantity}}
                </td>  
              </template>
              <template #name_buyer="{item}">
                <td>
                   {{item.name_buyer}}
                </td>
              </template>
              <template #cpf="{item}">
                <td>
                   {{formatCpf(item.cpf)}}
                </td>
              </template>
              <template #cnpj="{item}">
                <td>
                   {{formatCnpj(item.cnpj)}}
                </td>
              </template>
              <template #discount="{item}">
                <td>
                   {{formatPrice(item.discount)}}
                </td>
              </template>
              <template #profit_or_expense="{item}">
                <td>
                   {{formatPrice(item.profit_or_expense)}}
                </td>
              </template>              
              <template #type="{item}">
                <td>
                  <template v-if="item.type == 'Compra'">
                    <CBadge color="primary">Compra</CBadge>
                  </template>
                  <template v-else>
                    <CBadge color="success">Venda</CBadge>
                  </template>
                </td>
              </template>              
              <template #created_at="{item}">
                <td>
                   {{formatDate(item.created_at)}}
                </td>
              </template>
              <template #show="{item}">
                <td>
                  <CButton color="primary" @click="showReport( item.id )">Show</CButton>
                </td>
              </template>
<!--
              <template #delete="{item}">
                <td>
                  <CButton v-if="you!=item.id" color="danger" @click="deleteProduct( item.id )">Delete</CButton>
                </td>
              </template>
-->
            </CDataTable>
        </CCardBody>  
      </CCard>
      </transition>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'

export default {
  name: 'NotesReports',
  data: () => {
    return {
      items: [],
      /*
      fields: [
        {key: 'author'},
        {key: 'title'},
        {key: 'content'},
        {key: 'applies_to_date'},
        {key: 'status'},
        {key: 'note_type'},
        {key: 'show'},
        {key: 'edit'},
        {key: 'delete'}
      ],
      */
      fields: ['id', 'product_id', 'name_product', 'quantity', 'name_buyer', 'cpf', 'cnpj', 'discount','profit_or_expense', 'type', 'created_at', 'show'],
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      you: null,
      message: '',
      showMessage: false,
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false,
      id: null,
      money: {
            decimal: ',',
            thousands: '.',
            prefix: 'R$ ',
            suffix: '',
            precision: 2,
            masked: false
        },
      formReport: {},
      formProduct: {}
    }
  },
  computed: {
  },
  methods: {
    	onSubmit(evt) {
			evt.preventDefault()
      var metodo = this.id ? 'patch' : 'post'
			var finalUrl = this.id ? `/${this.id}` : ''

			axios[metodo](`/api/products${finalUrl}?token=` + localStorage.getItem("api_token"), this.form)
			.then(resp => {
          this.getNotesReports();
          this.$bvModal.hide('modal-prevent-closing-modify')
          this.$swal(
						'Sucesso',
						'Cadastrado com sucesso!',
						'success'
						)
        })
			.catch(err => {
        		this.$swal({
							icon: 'error',
							title: 'Oops...',
							text: 'Ocorreu um erro!'
						})
        })
			},
		onReset() {
        this.id = null
        this.form = {}
      },
    getRowCount (items) {
      return items.length
    },
    showReport ( id ) {
      this.formReport = {...this.items.find(product => product.id === id)}
      this.formProduct = {...this.formReport.products}
      console.log(this.form)
      this.$bvModal.show('modal-prevent-closing-report-show')
    },
    formatPrice(value) {
        let val = (value/1).toFixed(2).replace('.', ',')
        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    },
    formatCpf(value) {
      if(value != null){
        return value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g,"\$1.\$2.\$3\-\$4");
      }
    },
    formatCnpj(value){
      if(value != null){
        return value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "\$1.\$2.\$3/\$4-\$5");
      }
    },
    formatDate(value) {
      var ts = new Date(value);
        return ts.toLocaleString()
    },
    getNotesReports (){
      let self = this;
      axios.get(  '/api/reportProducts?token=' + localStorage.getItem("api_token") )
      .then(function (response) {
        self.items = response.data;
      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    },
    newOperation(id){
        console.log(id)
    },
  },
  mounted: function(){
    this.getNotesReports();
  }
}
</script>

<style scoped>
.card-body >>> table > tbody > tr > td {
  cursor: pointer;
}
</style>
