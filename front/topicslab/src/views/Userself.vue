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
        <Skeleton v-if="loading" height="100px"></Skeleton>
        <UserContents v-else/>
      </template>
      <template #footer>
        <Skeleton class="skeleton-btn" v-if="loading" width="100px" height="40px"></Skeleton>
        <Button label="Create Topic" v-on:click="toNewTopic" v-else/>
        <Skeleton class="skeleton-btn" v-if="loading" width="100px" height="40px"></Skeleton>
        <Button label="Logout" class="p-button-warning" v-on:click="logout" v-else/>
        <Skeleton class="skeleton-btn" v-if="loading" width="100px" height="40px"></Skeleton>
        <Button label="Withdraw" class="p-button-danger" v-on:click="withdraw" v-else/>
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
.skeleton-btn{
  display: inline-block;
  margin-right: 15px;
}
</style>
