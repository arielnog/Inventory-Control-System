<template>
  <CRow>
     <b-modal
      id="modal-prevent-closing-modify"
      ref="modal"
      title="Preencha os campos"
      hide-footer
    >
      <b-form @submit="onSubmit" @reset="onReset">
          <b-form-group
            id="input-group-1"
            label="Nome:"
            label-for="input-1"
          >
          <input class="form-control"
              id="input-1"
              v-model="form.name_product"
              required
              placeholder="Digite o nome do produto"
            >
          </b-form-group>
          
          <b-form-group id="input-group-2" label="Quantidade em Estoque:" label-for="input-2" v-if="id === null">
            <input class="form-control"
              id="input-2"
              type="number"
              v-model="form.quantity_in_stock"
              required
              placeholder="Digite a quantidade"
            >
          </b-form-group>

          <b-form-group id="input-group-3" label="Preço Unitário (Comprado):" label-for="input-3">
            <money 
            class="form-control"
            id="input-3"
            v-model="form.price_unit_purchased" 
            v-bind="money"
            required
            placeholder="Digite o preço unitário"></money>
          </b-form-group>

          <b-form-group id="input-group-4" label="Preço Unitário (Vendido):" label-for="input-4">
            <money 
            class="form-control"
            id="input-4"
            v-model="form.price_unit_sold" 
            v-bind="money"
            required
            placeholder="Digite o preço unitário"></money>
          </b-form-group>

          <b-button type="submit" :disabled="submitting" variant="primary" size="lg" style="float: right; margin-top: 30px">{{value}}</b-button>
        </b-form>
    </b-modal>

<!--------------------------------------------------------------SHOW MODAL------------------------------------------------------------->
     
     <b-modal
      id="modal-prevent-closing-show"
      ref="modal"
      title="Product Info"
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
                <h4>#ID:  {{ form.id }}</h4>
                <h4>#Name Product:</h4>
                <p>{{ form.name_product }}</p>
                <h4>#Quantity in Stock:</h4>
                <p>{{ form.quantity_in_stock }}</p>
                <h4>#Price Unit Purchased:</h4> 
                <p>R$ {{formatPrice(form.price_unit_purchased)}}</p>
                <h4>#Price Unit Sold:</h4> 
                <p>R$ {{formatPrice(form.price_unit_sold)}}</p>
                <h4>#Status: </h4>
                <p>
                    <template v-if="form.quantity_in_stock > 0">
                      <CBadge color="success">#Em Estoque</CBadge>
                    </template>
                    <template v-else>
                      <CBadge color="danger">#Fora de Estoque</CBadge>
                    </template>
                </p>
                <h4>#Created at:</h4>
                <p>{{ formatDate(form.created_at) }}</p>
                <h4>#Updated at:</h4>
                <p>{{ formatDate(form.updated_at) }}</p>
              </CCardBody>
            </CCard>
          </CCol>
        </CRow>
    </b-modal>

<!--------------------------------------------------------------SHOW MODAL------------------------------------------------------------->

  <b-modal id="modal-multi-3" size="sm" title="Operation" ok-only>
    <div style="display: flex; justify-content: center;">
    <b-button @click="openModalSell" variant="success">Sell</b-button>
    <b-button v-b-modal.modal-prevent-closing-add class="ml-2">Add</b-button>
    </div>
  </b-modal>

       <b-modal
      id="modal-prevent-closing-sell"
      ref="modal"
      title="Preencha os campos"
      hide-footer
    >
      <b-form @submit="sellProduct" @reset="onReset">
          <b-form-group id="input-group-1" label="Quantidade a ser vendida:" label-for="input-1">
            <input class="form-control"
              id="input-1"
              type="number"
              v-model="formSell.quantity"
              @input="updateValueLimit"
              required
              placeholder="Digite a quantidade"
            >
          </b-form-group>

          <p v-if="errors.length">
            <ul>
              <li v-for="error in errors" :key="error">{{ error }}</li>
            </ul>
          </p>

      <b-form-group
            id="input-group-2"
            label="Nome:"
            label-for="input-2"
          >
          <input class="form-control"
              id="input-2"
              v-model="formSell.name_buyer"
              required
              placeholder="Digite o nome do comprador"
            >
          </b-form-group>

      <b-form-group id="input-group-3" label="CPF:" label-for="input-3">

          <the-mask 
          :mask="['###.###.###-##']" 
          class="form-control"
          id="input-3"
          v-model="formSell.cpf" 
          placeholder="Digite o CPF"
          />
          </b-form-group>



      <b-form-group id="input-group-4" label="CNPJ:" label-for="input-4">
          <the-mask 
          :mask="['##.###.###/####-##']" 
          class="form-control"
          id="input-4"
          v-model="formSell.cnpj" 
          placeholder="Digite o CNPJ"
          />          
          </b-form-group>

      <b-form-group id="input-group-5" label="Desconto:" label-for="input-5">
          <money 
            class="form-control"
            id="input-5"
            v-model="formSell.discount" 
            v-bind="money"
            placeholder="Digite o valor do desconto"></money>
          </b-form-group>

          <b-button type="submit" :disabled="submitting" variant="primary" size="lg" style="float: right; margin-top: 30px">{{value}}</b-button>
        </b-form>
    </b-modal>


       <b-modal
      id="modal-prevent-closing-add"
      ref="modal"
      title="Preencha os campos"
      hide-footer
    >
      <b-form @submit="addQuantityProduct" @reset="onReset">

          <b-form-group id="input-group-2" label="Quantidade a ser adicionada em Estoque:" label-for="input-2">
            <input class="form-control"
              id="input-2"
              type="number"
              v-model="quantity_add"
              required
              placeholder="Digite a quantidade"
            >
          </b-form-group>

          <b-button type="submit" :disabled="submitting" variant="primary" size="lg" style="float: right; margin-top: 30px">{{value}}</b-button>
        </b-form>
    </b-modal>


    <CCol col="12" xl="12">
      <transition name="slide">
      <CCard>
        <CCardBody>
            <CButton color="primary" @click="newProduct">Create Product</CButton>
            <hr>
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
                  <strong>{{item.name_product}}</strong>
                </td>
              </template>
              <template #quantity_in_stock="{item}">
                <td>
                  {{item.quantity_in_stock}}
                </td>  
              </template>
              <template #price_unit_purchased="{item}">
                <td>
                  R$ {{formatPrice(item.price_unit_purchased)}}
                </td>
              </template>
              <template #price_unit_sold="{item}">
                <td>
                  R$ {{formatPrice(item.price_unit_sold)}}
                </td>
              </template>

              <template #status="{item}">
                <td>
                  <template v-if="item.quantity_in_stock > 0">
                    <CBadge color="success">Em Estoque</CBadge>
                  </template>
                  <template v-else>
                    <CBadge color="danger">Fora de Estoque</CBadge>
                  </template>
                </td>
              </template>
              <template #operation="{item}">
                <td>
                  <CButton color="primary" @click="newOperation( item.id )">Operation</CButton>
                </td>
              </template>
              <template #show="{item}">
                <td>
                  <CButton color="primary" @click="showProduct( item.id )">Show</CButton>
                </td>
              </template>
              <template #edit="{item}">
                <td>
                  <CButton color="primary" @click="editProduct( item.id )">Edit</CButton>
                </td>
              </template>
              <template #delete="{item}">
                <td>
                  <CButton v-if="you!=item.id" color="danger" @click="deleteProduct( item.id )">Delete</CButton>
                </td>
              </template>
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
  name: 'Notes',
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
      fields: ['id', 'name_product', 'quantity_in_stock', 'price_unit_purchased', 'price_unit_sold', 'status', 'operation', 'show', 'edit', 'delete'],
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
      quantity_add: '', 
      errors: [],
      money: {
            decimal: ',',
            thousands: '.',
            prefix: 'R$ ',
            suffix: '',
            precision: 2,
            masked: false
        },
      form: {
          	name_product: '',
          	quantity_in_stock: '',
            price_unit_purchased	: '',
            price_unit_sold	: '',
            operation: ''
        },
      formSell: {
          	quantity: '',
          	name_buyer: '',
            cpf	: '',
            cnpj	: '',
            discount: ''
        },
      submitting: false,
      value: 'Confirmar' 
    }
  },
  computed: {
  },
  methods: {
      updateValueLimit(evt){
        this.errors = []
        const value = event.target.value
        if(this.form.quantity_in_stock < this.formSell.quantity){
          this.formSell.quantity = this.form.quantity_in_stock
          this.errors.push('Você não possui está quantidade em estoque.');
        }

      },
    	async onSubmit(evt) {
      evt.preventDefault()
      this.submitting = true;
      this.value = 'Enviando...';
      var metodo = this.id ? 'patch' : 'post'
      var finalUrl = this.id ? `/${this.id}` : ''
      
      
			await axios[metodo](`/api/products${finalUrl}?token=` + localStorage.getItem("api_token"), this.form)
			.then(resp => {
          this.getProducts();
          this.$bvModal.hide('modal-prevent-closing-modify')
          this.$swal(
						'Sucesso',
						'Cadastrado com sucesso!',
						'success'
						)
        })
			.catch(err => {
        console.log(err)
        		this.$swal({
							icon: 'error',
							title: 'Oops...',
							text: 'Ocorreu um erro!'
						})
        })
      this.value = 'Confirmar';
      this.submitting = false;
			},
		onReset() {
        this.id = null
        this.quantity_add = ''
        this.form.name_product = ''
        this.form.quantity_in_stock = ''
        this.form.price_unit_purchased =  ''
        this.form.price_unit_sold =  ''
        this.formSell.quantity = ''
        this.formSell.name_buyer = ''
        this.formSell.cpf = ''
        this.formSell.cnpj = ''
        this.formSell.discount = ''
        this.errors = []
      },
    newProduct(){
      this.onReset()
      this.$bvModal.show('modal-prevent-closing-modify')
      },
    getRowCount (items) {
      return items.length
    },
    /*noteLink (id) {
      return `notes/${id.toString()}`
    },
    editLink (id) {
      return `notes/${id.toString()}/edit`
    },*/
    showProduct ( id ) {
      this.form = {...this.items.find(product => product.id === id)}
      this.$bvModal.show('modal-prevent-closing-show')
    },
    editProduct ( id ) {
          this.id = id
          this.form = {...this.items.find(product => product.id === id)}
          this.$bvModal.show('modal-prevent-closing-modify')
    },
    deleteProduct ( id ) {
      let self = this;
      let noteId = id;
      axios.post(  '/api/products/' + id + '?token=' + localStorage.getItem("api_token"), {
        _method: 'DELETE'
      })
      .then(function (response) {
          self.$swal(
						'Sucesso',
						'Deletado com sucesso!',
						'success'
						)
          self.getProducts();
      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    },
    createProduct () {
      this.$router.push({path: 'notes/create'});
    },
    countDownChanged (dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
    formatPrice(value) {
        let val = (value/1).toFixed(2).replace('.', ',')
        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    },
    formatDate(value) {
      var ts = new Date(value);
        return ts.toLocaleString()
    },
    getProducts (){
      let self = this;
      axios.get(  '/api/products?token=' + localStorage.getItem("api_token") )
      .then(function (response) {
        console.log(response.data)
        self.items = response.data;
      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    },
    newOperation(id){
        this.onResetFormSell()
        this.id = id
        this.form = {...this.items.find(product => product.id === id)}
        this.$bvModal.show('modal-multi-3')
    },
    async addQuantityProduct(evt) {
      evt.preventDefault()
      this.submitting = true;
      this.value = 'Enviando...';
      var metodo = 'patch' 
      var finalUrl = `/${this.id}`
      this.form.quantity_in_stock = this.form.quantity_in_stock + parseInt(this.quantity_add)
      this.form.quantity_add_report = parseInt(this.quantity_add)

			await axios[metodo](`/api/productsAddQuantity${finalUrl}?token=` + localStorage.getItem("api_token"), this.form)
			.then(resp => {
          this.getProducts();
          this.$bvModal.hide('modal-prevent-closing-add')
          this.$bvModal.hide('modal-multi-3')
          this.onReset();
          this.$swal(
						'Sucesso',
						'Adicionado com sucesso!',
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
      this.value = 'Confirmar';
      this.submitting = false;
      },
      onResetFormSell(){
        this.formSell.quantity = ''
        this.formSell.name_buyer = ''
        this.formSell.cpf = ''
        this.formSell.cnpj = ''
        this.formSell.discount = ''
        this.errors = []
      },
      openModalSell(){
        this.onResetFormSell()
        this.$bvModal.show('modal-prevent-closing-sell')
      },
      async sellProduct(evt) {
      evt.preventDefault()
      this.submitting = true;
      this.value = 'Enviando...';
      var metodo = 'patch' 
      var finalUrl = `/${this.id}`
      this.form.quantity_in_stock = this.form.quantity_in_stock + parseInt(this.quantity_add)

			await axios[metodo](`/api/productsSell${finalUrl}?token=` + localStorage.getItem("api_token"), this.formSell)
			.then(resp => {
          this.getProducts();
          this.$bvModal.hide('modal-prevent-closing-sell')
          this.$bvModal.hide('modal-multi-3')
          this.onReset();
          this.$swal(
						'Sucesso',
						'Vendido com sucesso!',
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
      this.value = 'Confirmar';
      this.submitting = false;
			},
  },
  mounted: function(){
    this.getProducts();
  }
}
</script>

<style scoped>
.card-body >>> table > tbody > tr > td {
  cursor: pointer;
}
</style>



