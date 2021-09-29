<template>
  <TabView>
    <TabPanel header="プロフィール">
      プロフィール
    </TabPanel>
    <TabPanel header="トピック">

      <Card v-for="topic in topics" :key="topic.id">
        <template #content>
          <span class="topic-date">投稿日：{{moment(topic.created_at)}}</span>
          <h2>
            <router-link :to="`/topic/${topic.id}`">
              {{topic.title}}
            </router-link>
          </h2>
        </template>
    </Card>
    </TabPanel>
    <TabPanel header="コメント">
      コメント
    </TabPanel>
  </TabView>
</template>

<script>
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import moment from 'moment'
export default {
  components: {
    TabView,
    TabPanel
  },
  props: {
    topics: Array,
    id: Number
  },
  methods: {
    moment: function (date) {
      return moment(date).format('YYYY/MM/DD HH:mm')
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
