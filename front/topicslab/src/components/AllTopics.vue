<template>
  <div>
    <Skeleton v-if="loading"></Skeleton>
    <Card v-for="topic in topics" :key="topic.id" v-else>
        <template #content>
          <span class="topic-date">投稿日：{{moment(topic.created_at)}}</span>
          <h2>
            <router-link :to="`/topic/${topic.id}`">
              {{topic.title}}
            </router-link>
          </h2>
        </template>
    </Card>
  </div>
</template>

<script>
import axios from '@/supports/axios'
import moment from 'moment'
import Skeleton from 'primevue/skeleton'

export default {
  name: 'AllTopics',
  components: {
    Skeleton
  },
  data () {
    return {
      topics: [],
      loading: false
    }
  },
  mounted () {
    this.getAllTopics()
  },
  methods: {
    moment: function (date) {
      return moment(date).format('YYYY/MM/DD HH:mm:SS')
    },
    getAllTopics () {
      this.loading = true
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get('/api/topics')
            .then((res) => {
              if (res.status === 200) {
                this.topics.splice(0)
                this.topics.push(...res.data)
                this.loading = false
              } else {
                console.log('取得失敗')
                this.loading = false
              }
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

<style lang="scss" scoped>
.p-card.p-component {
  margin-bottom: 20px;
}
.p-card-content {
  .topic-date {
    font-size: 80%;
  }
}
</style>
