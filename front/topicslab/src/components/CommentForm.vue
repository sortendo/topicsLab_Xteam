<template>
  <div>
    <Skeleton v-if="loading"></Skeleton>
    <Card v-else>
      <template #content>
        <!-- ダイアログボックス -->
        <Dialog header="ERROR" v-model:visible="display" >
          <span>{{message}}</span>
        </Dialog>
        <div class="p-field">
          <Textarea v-model="comment" :autoResize="true" rows="5" />
          <p>{{message}}</p>
        </div>
        <div class="p-field">
          <Button icon="pi pi-check" label="コメントする" v-on:click="submit" />
        </div>
      </template>
    </Card>
  </div>
</template>

<script>
import axios from '@/supports/axios'
import Skeleton from 'primevue/skeleton'
import Dialog from 'primevue/dialog'

export default {
  name: 'CommentForm',
  components: {
    Skeleton,
    Dialog
  },
  props: {
    topicId: Number
  },
  data () {
    return {
      comment: '',
      message: '',
      loading: false,
      display: false
    }
  },
  methods: {
    submit () {
      const comment = this.comment.trim()
      if (!comment) {
        this.message = '未記入(空白のみ)は送信できません。'
        this.display = true
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
                this.display = true
              }
            })
            .catch((err) => {
              console.log(err)
              this.message = '送信に失敗しました。'
              this.loading = false
              this.display = true
            })
        })
        .catch((err) => {
          console.log(err)
          this.message = '通信に失敗しました。'
          this.loading = false
          this.display = true
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
</style>
