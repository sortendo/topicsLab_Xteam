<template>
  <div>
    <Skeleton v-if="loading"></Skeleton>
    <Card v-else>
      <template #title>
        {{topic.title}}
      </template>
      <template #content>
        <div class="body-text">
          {{topic.body}}
        </div>
      </template>
      <template #footer>
        <span>
          <router-link :to="`/user/${user.id}`">{{user.name}}</router-link>
        </span>
          <!-- いいねボタン追加 -->
          <span class="like">
           <Button label="♡" class="p-button-help p-button-sm"/>
          </span>
      </template>
    </Card>
    <Comments :comments="this.comments" />
    <CommentForm class="comment-form" :topicId="this.topic.id" @sentComment="receiveComment" />
  </div>
</template>

<script>
import axios from '@/supports/axios'
import Comments from '@/components/Comments'
import CommentForm from '@/components/CommentForm'
import Button from 'primevue/button'
import Skeleton from 'primevue/skeleton'

export default {
  name: 'Topic',
  components: {
    Comments,
    CommentForm,
    Button,
    Skeleton
  },
  data () {
    return {
      topic: {},
      user: {},
      comments: [],
      id: null,
      loading: true
    }
  },
  mounted () {
    this.id = this.$route.params.id
    if (!this.id) {
      alert('不正なIDです。')
    }
    if (localStorage.getItem('authenticated') !== 'true') {
      this.$router.push('/login')
      return
    }
    this.getTopic()
  },
  methods: {
    getTopic () {
      this.loading = true
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get(`/api/topic/${this.id}`)
            .then((res) => {
              if (res.status === 200 && res.data.length === 1) {
                this.topic = res.data[0]
                this.user = this.topic.user
                this.comments.splice(0)
                this.comments.push(...this.topic.comments)
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
    },
    receiveComment (comment) {
      this.comments.push(comment)
    }
  }
}
</script>

<style scoped>
.body-text {
  white-space:pre-wrap;
}
.p-card-footer span {
  text-align: right;
  display: block;
}
.comment-form{
  margin-top: 50px;
}
</style>
