<template>
  <div>
    <Skeleton v-if="loading"></Skeleton>
    <Card v-else>
      <template #content>
        {{user.name}}
        <UserContents />
      </template>
    </Card>
  </div>
</template>

<script>
import UserContents from '@/components/UserContents'
import axios from '@/supports/axios'
import Skeleton from 'primevue/skeleton'

export default {
  name: 'user',
  components: {
    UserContents,
    Skeleton
  },
  data () {
    return {
      id: null,
      user: {},
      loading: false
    }
  },
  mounted () {
    if (localStorage.getItem('authenticated') !== 'true') {
      this.$router.push('/login')
      return
    }

    this.id = this.$route.params.id
    if (!this.id) {
      alert('不正なIDです。')
    }
    this.getUser()
  },
  methods: {
    getUser () {
      this.loading = true
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get(`/api/user/${this.id}`)
            .then((res) => {
              console.log(res)
              if (res.status === 200) {
                this.user = res.data
                this.loading = false
              } else {
                console.log('取得失敗')
                this.loading = false
              }
            })
            .catch((err) => {
              console.log(err)
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
