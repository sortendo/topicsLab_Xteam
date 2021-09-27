<template>
  <Card>
    <template #title>
      新しいTopicを投稿しよう
    </template>
    <template #content>
      <!-- 指示書21 ダイアログボックス -->
      <Dialog header="ERROR" v-model:visible="display" >
        <span class="error">{{messages.title}}</span>
        <span class="error">{{messages.body}}</span>
        <span class="error">{{messages.submit}}</span>
      </Dialog>
      <div class="p-field">
        <label for="title">Topicタイトル</label>
        <InputText v-model="title" id="title" type="text" aria-describedby="title-help" />
        <small id="title-help">タイトルを入力してください。</small>
      </div>
      <div class="p-field">
        <label for="title">Topic内容</label>
        <Textarea v-model="body" :autoResize="true" rows="10" />
      </div>
      <div class="p-field">
        <Button icon="pi pi-check" label="Save" v-on:click="submit" />
      </div>
    </template>
  </Card>
</template>

<script>
import axios from '@/supports/axios'
import Dialog from 'primevue/dialog'

export default {
  name: 'NewTopic',
  components: {
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

      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/topic', {
            title: title,
            body: body
          })
            .then((res) => {
              if (res.status === 201) {
              //
              } else {
                this.messages.submit = '送信に失敗しました。'
                // 指示書21 ダイアログを表示
                this.display = true
              }
            })
            .catch((err) => {
              console.log(err)
              this.messages.submit = '送信に失敗しました。'
              // 指示書21 ダイアログを表示
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

<style scoped>
.p-field * {
  display: block;
  width: 100%;
}
.error{
  color: red;
}
</style>
