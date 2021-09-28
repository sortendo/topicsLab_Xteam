<template>
  <div>
    <Card>
      <template #title>
        ログイン
      </template>
      <template #content>
         <!-- 指示書21 ダイアログボックス -->
        <Dialog header="ERROR" v-model:visible="display" >
          <span id="alart">{{message}}</span>
        </Dialog>
        <div class="fields">
          <Skeleton class="skeleton" v-if="loading" height
          ="30px" width="320px"></Skeleton>
          <div class="p-field" v-else>
            <label for="email">メールアドレス</label>
            <InputText id="email" type="email" v-model="email"/>
          </div>
          <Skeleton class="skeleton" v-if="loading" height
          ="30px" width="320px"></Skeleton>
          <div class="p-field" v-else>
            <label for="password">パスワード</label>
            <InputText id="password" type="password" v-model="password" />
          </div>
        </div>
        <Skeleton class="skeleton" v-if="loading" height
          ="30px"></Skeleton>
        <div class="p-field" v-else>
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
import Dialog from 'primevue/dialog'

export default {
  name: 'Login',
  components: {
    Dialog,
    Skeleton
  },
  data () {
    return {
      email: '',
      password: '',
      error: false,
      message: '',
      loading: false,
      // 指示書21 ダイアログを基本は非表示にする
      display: false
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
                this.loading = false
                this.message = 'ログインに失敗しました。'
                // 指示書21 ダイアログを表示
                this.display = true
              }
            })
            .catch((err) => {
              if (err.response.status === 403) {
                this.message = '退会済みのユーザーです。'
              } else {
                console.log(err)
                this.message = 'ログインに失敗しました。'
              }
              this.loading = false
              this.display = true
            })
        })
        .catch((err) => {
          this.loading = false
          console.log(err)
          this.message = '取得失敗'
          this.display = true
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
.skeleton{
  margin: 10px auto 0px;
}
</style>
