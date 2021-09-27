<template>
  <div>
    <Skeleton v-if="loading"></Skeleton>
     <Card v-else>
     <template #title>
      新しいTopicを投稿しよう
     </template>
     <template #content>
        <div class="p-field">
          <label for="title">Topicタイトル</label>
          <InputText v-model="title" id="title" type="text" aria-describedby="title-help" />
          <small id="title-help">タイトルを入力してください。</small>
          <span class="error">{{messages.title}}</span>
        </div>
        <div class="p-field">
          <label for="title">Topic内容</label>
          <Textarea v-model="body" :autoResize="true" rows="10" />
          <span class="error">{{messages.body}}</span>
        </div>
      <div class="p-field">
        <Button icon="pi pi-check" label="Save" v-on:click="submit" />
        <span class="error">{{messages.submit}}</span>
      </div>
    </template>
  </Card>
  </div>
</template>

<script>
import axios from '@/supports/axios'
import Skeleton from 'primevue/skeleton'

export default {
  name: 'NewTopic',
  components: {
    Skeleton
  },
  data () {
    return {
      title: '',
      body: '',
      messages: {
        submit: '',
        title: '',
        body: ''
      },
      loading: false
    }
  },
  mounted () {
    if (localStorage.getItem('authenticated') !== 'true') {
      this.$router.push('login')
    }
  },
  methods: {
    submit () {
      const title = this.title.trim()
      if (!title) {
        this.messages.title = '未記入(空白のみ)は送信できません。'
      }
      const body = this.body.trim()
      if (!body) {
        this.messages.body = '未記入(空白のみ)は送信できません。'
      }

      if (!title || !body) return

      this.loading = true
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/topic', {
            title: title,
            body: body
          })
            .then((res) => {
              if (res.status === 201) {
              //
                this.loadng = false
              } else {
                this.messages.submit = '送信に失敗しました。'
                this.loadng = false
              }
            })
            .catch((err) => {
              console.log(err)
              this.messages.submit = '送信に失敗しました。'
              this.loadng = false
            })
        })
        .catch((err) => {
          alert(err)
          this.loadng = false
        })
    }
  }
}
</script>

<style scoped>
.p-field * {
  display: block;
  width: 100%;
}
.error{
  color: red;
}
</style>
