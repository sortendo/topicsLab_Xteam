<template>
  <div>
    <Card>
      <template #title>
        Register
      </template>
      <template #content>
        <Skeleton v-if="loading"></Skeleton>
        <div class="fields" v-else>
          <div class="p-field">
            <label for="name">ユーザー名</label>
            <InputText id="name" type="text" v-model="name" />
          </div>
          <div class="p-field">
            <label for="email">メールアドレス</label>
            <InputText id="email" type="email" v-model="email" />
          </div>
          <div class="p-field">
            <label for="password">パスワード</label>
            <InputText id="password" type="password" v-model="password" />
          </div>
        </div>
        <span>{{message}}</span>
        <div class="p-field">
          <Button icon="pi pi-check" label="Register" v-on:click="register" />
        </div>
      </template>
    </Card>
  </div>
</template>

<script>
import axios from '@/supports/axios'
import Skeleton from 'primevue/skeleton'

export default {
  name: 'Register',
  components: {
    Skeleton
  },
  data () {
    return {
      name: '',
      email: '',
      password: '',
      message: '',
      loading: false
    }
  },
  methods: {
    register () {
      const name = this.name.trim()
      const email = this.email.trim()
      const password = this.password.trim()
      if (!name || !email || !password) {
        this.message = '全て必須項目です。'
        return
      }

      this.loading = true
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/register', {
            name: this.name,
            email: this.email,
            password: this.password
          })
            .then((res) => {
              if (res.status === 201) {
                alert('ユーザー登録成功')
                this.$router.push('/login')
                this.loading = false
              } else {
                this.message = 'ユーザー登録に失敗しました。'
                this.loading = false
              }
            })
            .catch((err) => {
              console.log(err)
              this.message = 'ユーザー登録に失敗しました。'
              this.loading = false
            })
        })
        .catch((err) => {
          alert(err)
          this.loading = false
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.p-card-content {
  .fields {
    text-align: center;
  }

  .p-field {
    display: block;

    label {
      display: inline-block;
      width: 10em;
      margin-bottom: 10px;
    }

    .p-button {
      margin-top: 20px;
      display: block;
      width: 100%;
    }
  }
}
</style>
