<template>
  <div>
    <Skeleton v-if="loading"></Skeleton>
    <Card v-else>
      <template #title>
        マイページ
      </template>
      <template #content>
        <!-- ダイアログボックス -->
        <Dialog header="ERROR" v-model:visible="display" >
          <span>{{message}}</span>
        </Dialog>
        {{user.name}}
        <UserContents />
      </template>
      <template #footer>
        <Button label="Topicを投稿する" v-on:click="toNewTopic" />
        <Button label="ログアウト" class="p-button-warning" v-on:click="logout" />
        <Button label="アカウント削除" class="p-button-danger" v-on:click="withdraw" />
      </template>
    </Card>
  </div>
</template>

<script>
import UserContents from '@/components/UserContents'
import axios from '@/supports/axios'
import Skeleton from 'primevue/skeleton'
import Dialog from 'primevue/dialog'

export default {
  name: 'Userself',
  components: {
    UserContents,
    Skeleton,
    Dialog
  },
  data () {
    return {
      user: {},
      loading: false,
      message: '',
      display: false
    }
  },
  mounted () {
    if (localStorage.getItem('authenticated') !== 'true') {
      this.$router.push('login')
      return
    }

    this.getUser()
  },
  methods: {
    toNewTopic () {
      this.$router.push('topic')
    },
    logout () {
      this.loading = true
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/logout')
            .then(res => {
              console.log(res)
              localStorage.setItem('authenticated', 'false')
              this.$router.push('/login')
              this.loading = false
            })
            .catch(err => {
              console.log(err)
              this.loading = false
              this.message = 'ログアウトに失敗しました'
              this.display = true
            })
        })
        .catch((err) => {
          this.loading = false
          console.log(err)
          this.message = 'ログアウトに失敗しました'
          this.display = true
        })
    },
    withdraw () {
      this.loading = true
      //  項目番号19
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.delete('/api/withdraw') // logout -> withdoraw
            .then(res => {
              console.log(res)
              localStorage.setItem('authenticated', 'false')
              this.$router.push('/') // login -> ''
              this.loading = false
            })
            .catch(err => {
              console.log(err)
              this.loading = false
              this.message = 'アカウント削除に失敗しました'
              this.display = true
            })
        })
        .catch((err) => {
          this.loading = false
          console.log(err)
          this.message = 'アカウント削除に失敗しました'
          this.display = true
        })
    },
    getUser () {
      this.loading = true
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get('/api/user')
            .then((res) => {
              if (res.status === 200) {
                this.user = res.data
                this.loading = false
              } else {
                console.log('取得失敗')
                this.message = '取得失敗'
                this.display = true

                this.loading = false
              }
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
.p-card-footer {
  .p-button {
    margin-right: 10px;
  }
}
</style>
