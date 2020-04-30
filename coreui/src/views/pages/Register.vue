<template>
  <div class="d-flex align-items-center min-vh-100">
    <CContainer fluid>
      <CRow class="justify-content-center">
        <CCol md="6">
          <CCard class="mx-4 mb-0">
            <CCardBody class="p-4">
              <CForm @submit.prevent="register" method="POST">
                <h1>Register</h1>
                <p class="text-muted">Create your account</p>
                <CInput
                  placeholder="Username"
                  prependHtml="<i class='cui-user'></i>"
                  autocomplete="username"
                  v-model="name"
                >
                  <template #prepend-content><CIcon name="cil-user"/></template>
                </CInput>
                <CInput
                  placeholder="Email"
                  prepend="@"
                  autocomplete="email"
                  v-model="email"
                />
                <CInput
                  placeholder="Password"
                  type="password"
                  prependHtml="<i class='cui-lock-locked'></i>"
                  autocomplete="new-password"
                  v-model="password"
                >
                  <template #prepend-content><CIcon name="cil-lock-locked"/></template>
                </CInput>
                <CInput
                  placeholder="Repeat password"
                  type="password"
                  prependHtml="<i class='cui-lock-locked'></i>"
                  autocomplete="new-password"
                  class="mb-4"
                  v-model="password_confirmation"
                >
                  <template #prepend-content><CIcon name="cil-lock-locked"/></template>
                </CInput>
              <CCardFooter class="p-4">
                <CRow>
                  <CCol col="6">
                    <CButton type="submit" color="success" block>{{value}}</CButton>
                  </CCol>
                  <CCol col="6">
                    <CButton @click="$router.push({ path: 'login' });" block color="facebook">
                      Login
                    </CButton>
                  </CCol>
                </CRow>
            </CCardFooter>

              </CForm>
            </CCardBody>
          </CCard>
        </CCol>
      </CRow>
    </CContainer>
  </div>
</template>

  <script>
    import axios from 'axios'
    export default {
      data() {
        return {
          name: '',
          email: '',
          password: '',
          password_confirmation: '',
          submitting: false,
          value: 'Create Account' 
        }
      },    
      methods: {
        async register() {
          var self = this;
          this.submitting = true;
          this.value = 'Registrando...';
          await axios.post(  '/api/register', {
            name: self.name,
            email: self.email,
            password: self.password,
            password_confirmation: self.password_confirmation
          }).then(function (response) {
            self.name = '';
            self.email = '';
            self.password = '';
            self.password_confirmation = '';
            console.log(response);
            self.$router.push({ path: '/login' });
          })
          .catch(function (error) {
            console.log(error);
              self.$swal({
							icon: 'error',
							title: 'Oops...',
							text: 'Ocorreu um erro!'
						})
          });
          this.value = 'Create Account';
          this.submitting = false;
        }
      }
    }
  
  </script>
