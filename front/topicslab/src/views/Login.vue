<template>
  <div>
    <Card>
      <template #title>
        ログイン
      </template>
      <template #content>
        <Skeleton v-if="loading"></Skeleton>
        <div class="fields" v-else>
          <div class="p-field">
            <label for="email">メールアドレス</label>
            <InputText id="email" type="email" v-model="email" />
          </div>
          <div class="p-field">
            <label for="password">パスワード</label>
            <InputText id="password" type="password" v-model="password" />
          </div>
        </div>
        <span id="alart">{{message}}</span>
        <div class="p-field">
          <Button icon="pi pi-check" label="ログイン" v-on:click="login" />
        </div>
        <!-- 新規登録のリンクを追加 -->
        <div class="p-field">
          <router-link to="/register">新規登録</router-link>
        </div>
        <!-- end -->
      </template>
    </Card>
  </div>
</template>

<script>
import axios from '@/supports/axios'
import Skeleton from 'primevue/skeleton'
export default {
  name: 'Login',
  components: {
    Skeleton
  },
  data () {
    return {
      email: '',
      password: '',
      error: false,
      message: '',
      loading: false
    }
  },
  methods: {
    login () {
      this.loading = true
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/login', {
            email: this.email,
            password: this.password
          })
            .then((res) => {
              if (res.status === 200) {
                console.log('ログイン成功')
                localStorage.setItem('authenticated', 'true')
                this.loading = false
              } else {
                this.message = 'ログインに失敗しました。'
                this.loading = false
              }
            })
            .catch((err) => {
              console.log(err)
              this.message = 'ログインに失敗しました。'
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
#alart{
  color: red;
}
</style>
