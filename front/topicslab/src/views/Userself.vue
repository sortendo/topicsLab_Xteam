<template>
  <div>
    <Skeleton v-if="loading"></Skeleton>
    <Card v-else>
      <template #title>
        mypage
      </template>
      <template #content>
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
import Skeleton from 'primevue/skeleton'

export default {
  name: 'Userself',
  components: {
    UserContents,
    Skeleton
  },
  data () {
    return {
      user: {},
      loading: false
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
            })
        })
        .catch((err) => {
          alert(err)
          this.loading = false
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
            })
        })
        .catch((err) => {
          alert(err)
          this.loading = false
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
                this.loading = false
              }
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
.p-card-footer {
  .p-button {
    margin-right: 10px;
  }
}
</style>
