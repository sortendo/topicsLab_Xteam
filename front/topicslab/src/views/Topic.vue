<template>
  <div>
    <Card>
      <template #title>
        {{topic.title}}
      </template>
      <template #content>
        <!-- 指示書21 ダイアログボックス -->
        <Dialog header="ERROR" v-model:visible="display" >
          <span>{{message}}</span>
        </Dialog>
        <Skeleton v-if="loading" height="50px"></Skeleton>
        <div class="body-text" v-else>
          {{topic.body}}
        </div>
      </template>
      <template #footer>
        <Skeleton class="skeleton-user" v-if="loading" height="20px" width="50px"></Skeleton>
        <span v-else>
          <router-link v-if="this.topic.user" :to="`/user/${user.id}`">{{user.name}}</router-link>
          <span v-else>退会したユーザー</span>
        </span>
          <!-- いいねボタン追加 -->
          <Skeleton class="skeleton-user" v-if="loading" height="40px" width="50px"></Skeleton>
          <span class="like" v-else>
           <Button label="いいね♡" class="p-button-help p-button-sm"/>
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
import Dialog from 'primevue/dialog'

export default {
  name: 'Topic',
  components: {
    Comments,
    CommentForm,
    Button,
    Skeleton,
    Dialog
  },
  data () {
    return {
      topic: {},
      user: {},
      comments: [],
      id: null,
      loading: true,
      // 指示書21 ダイアログを基本は非表示にする
      message: '',
      display: false
    }
  },
  mounted () {
    this.id = this.$route.params.id
    if (!this.id) {
      this.message = '不正なIDです。'
      this.display = true
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
                this.loading = false
                console.log('取得失敗しました')
                // 指示書21 ダイアログを表示
                this.message = '取得失敗しました'
                this.display = true
              }
            })
            .catch((err) => {
              console.log(err)
              this.loading = false
              // 指示書21 ダイアログを表示
              this.message = '取得失敗しました'
              this.display = true
            })
        })
        .catch((err) => {
          console.log(err)
          this.loading = false
          this.message = '取得失敗'
          this.display = true
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
.skeleton-user{
  margin-left: auto;
  margin-top: 10px;
}
</style>
