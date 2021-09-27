<template>
  <div>
    <Card>
      <template #title>
        mypage
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
        <Button label="Create Topic" v-on:click="toNewTopic" />
        <Button label="Logout" class="p-button-warning" v-on:click="logout" />
        <Button label="Withdraw" class="p-button-danger" v-on:click="withdraw" />
      </template>
    </Card>
  </div>
</template>

<script>
import UserContents from '@/components/UserContents'
import axios from '@/supports/axios'
import Dialog from 'primevue/dialog'

export default {
  name: 'Userself',
  components: {
    UserContents,
    Dialog
  },
  data () {
    return {
      user: {},
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
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/logout')
            .then(res => {
              console.log(res)
              localStorage.setItem('authenticated', 'false')
              this.$router.push('/login')
            })
            .catch(err => {
              console.log(err)
              this.message = 'ログアウトに失敗しました'
              this.display = true
            })
        })
        .catch((err) => {
          console.log(err)
          this.message = 'ログアウトに失敗しました'
          this.display = true
        })
    },
    withdraw () {
      //  項目番号19
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.delete('/api/withdraw') // logout -> withdoraw
            .then(res => {
              console.log(res)
              localStorage.setItem('authenticated', 'false')
              this.$router.push('/') // login -> ''
            })
            .catch(err => {
              console.log(err)
              this.message = 'アカウント削除に失敗しました'
              this.display = true
            })
        })
        .catch((err) => {
          console.log(err)
          this.message = 'アカウント削除に失敗しました'
          this.display = true
        })
    },
    getUser () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get('/api/user')
            .then((res) => {
              if (res.status === 200) {
                this.user = res.data
              } else {
                console.log('取得失敗')
                this.message = '取得失敗'
                this.display = true
              }
            })
        })
        .catch((err) => {
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
