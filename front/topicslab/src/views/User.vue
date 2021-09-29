<template>
  <div>
    <Card>
      <template #content>
        <!-- ダイアログボックス -->
        <Dialog header="ERROR" v-model:visible="display" >
          <span>{{message}}</span>
        </Dialog>
        {{user.name}}
        <Skeleton v-if="loading" height="80px"></Skeleton>
        <UserContents v-else :topics="this.topics" :id="this.id" />
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
  name: 'user',
  components: {
    UserContents,
    Skeleton,
    Dialog
  },
  data () {
    return {
      id: null,
      user: {},
      loading: false,
      message: '',
      display: false,
      topics: []
    }
  },
  mounted () {
    if (localStorage.getItem('authenticated') !== 'true') {
      this.$router.push('/login')
      return
    }

    this.id = this.$route.params.id
    if (!this.id) {
      this.message = '不正なIDです。'
      this.display = true
    }

    this.getUser()

    axios.get('/sanctum/csrf-cookie')
      .then(() => {
        axios.get('/api/topics')
          .then((res) => {
            if (res.status === 200) {
              res.data.forEach(responce => {
                if (responce.user_id === Number(this.id)) {
                  this.topics.push(responce)
                }
              })
            } else {
              console.log('err')
            }
          })
      })
  },
  methods: {
    getUser () {
      this.loading = true

      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get(`/api/user/${this.id}`)
            .then((res) => {
              if (res.status === 200) {
                this.user = res.data[0]
                this.loading = false
              } else {
                console.log('取得失敗')
                this.loading = false
                this.message = '取得失敗'
                this.display = true
              }
            })
            .catch((err) => {
              console.log(err)
              this.loading = false
              this.message = '取得失敗'
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
