<template>
  <div>
    <Card>
      <template #content>
        <Skeleton class="skeleton" v-if="loading" height="100px"></Skeleton>
        <div class="p-field" v-else>
          <Textarea v-model="comment" :autoResize="true" rows="5" />
          <p>{{message}}</p>
        </div>
        <Skeleton class="skeleton" v-if="loading" height="35px"></Skeleton>
        <div class="p-field" v-else>
          <Button icon="pi pi-check" label="コメントする" v-on:click="submit" />
        </div>
      </template>
    </Card>
  </div>
</template>

<script>
import axios from '@/supports/axios'
import Skeleton from 'primevue/skeleton'

export default {
  name: 'CommentForm',
  components: {
    Skeleton
  },
  props: {
    topicId: Number
  },
  data () {
    return {
      comment: '',
      message: '',
      loading: false
    }
  },
  methods: {
    submit () {
      const comment = this.comment.trim()
      if (!comment) {
        this.message = '未記入(空白のみ)は送信できません。'
        return
      }

      this.loading = true
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/comment', {
            topicId: this.topicId,
            body: comment
          })
            .then((res) => {
              if (res.status === 201) {
                this.comment = ''
                this.$emit('sentComment', res.data)
                this.loading = false
              } else {
                this.message = '送信に失敗しました。'
                this.loading = false
              }
            })
            .catch((err) => {
              console.log(err)
              this.message = '送信に失敗しました。'
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

<style scoped>
.p-card.p-component {
  margin-top:  20px;
}
.p-field * {
  display: block;
  width: 100%;
}
.skeleton{
  margin-top: 10px;
}
</style>
