<template>
  <div>
     <Dialog header="ERROR" v-model:visible="display" >
        <span class="error">{{messages.title}}</span>
        <span class="error">{{messages.body}}</span>
        <span class="error">{{messages.submit}}</span>
      </Dialog>
     <Card>
     <template #title>
      新しいTopicを投稿しよう
     </template>
     <template #content>
        <div class="p-field">
          <label for="title">Topicタイトル</label>
          <Skeleton v-if="loading" height="30px"></Skeleton>
          <InputText  v-model="title" id="title" type="text" aria-describedby="title-help" v-else/>
          <small id="title-help">タイトルを入力してください。</small>
          <span class="error">{{messages.title}}</span>
        </div>
        <div class="p-field">
          <label for="title">Topic内容</label>
          <Skeleton v-if="loading" height="200px"></Skeleton>
          <Textarea v-model="body" :autoResize="true" rows="10" v-else/>
          <span class="error">{{messages.body}}</span>
        </div>
        <div class="p-field">
          <Button icon="pi pi-check" label="Save" v-on:click="submit" />
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
  name: 'NewTopic',
  components: {
    Skeleton,
    Dialog
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
      loading: false,
      // 指示書21 ダイアログを基本は非表示にする
      display: false
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
        this.messages.title = 'タイトル未記入(空白のみ)は送信できません。'
        this.display = true
      }
      const body = this.body.trim()
      if (!body) {
        this.messages.body = '内容が未記入(空白のみ)は送信できません。'
        this.display = true
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
                this.loadng = false
                this.messages.submit = '送信に失敗しました。'
                // 指示書21 ダイアログを表示
                this.display = true
              }
            })
            .catch((err) => {
              console.log(err)

              this.loadng = false
              this.messages.submit = '送信に失敗しました。'
              this.display = true
            })
        })
        .catch((err) => {
          this.loadng = false
          console.log(err)
          this.message = '取得失敗'
          this.display = true
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
