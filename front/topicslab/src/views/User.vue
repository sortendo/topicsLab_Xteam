<template>
  <div>
    <Card>
      <template #content>
        <!-- ダイアログボックス -->
        <Dialog header="ERROR" v-model:visible="display" >
          <span>{{message}}</span>
        </Dialog>
        {{user.name}}
        <UserContents />
      </template>
    </Card>
  </div>
</template>

<script>
import UserContents from '@/components/UserContents'
import axios from '@/supports/axios'
import Dialog from 'primevue/dialog'

export default {
  name: 'user',
  components: {
    UserContents,
    Dialog
  },
  data () {
    return {
      id: null,
      user: {},
      message: '',
      display: false
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
  },
  methods: {
    getUser () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get(`/api/user/${this.id}`)
            .then((res) => {
              console.log(res)
              if (res.status === 200) {
                this.user = res.data
              } else {
                console.log('取得失敗')
                this.message = '取得失敗'
                this.display = true
              }
            })
            .catch((err) => {
              console.log(err)
              this.message = '取得失敗'
              this.display = true
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
