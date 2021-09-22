import Button from "primevue/button"

<template>
  <div>
    <Fieldset v-for="comment in comments" :key="comment.id">
      <template #legend>
        <span>
          <router-link :to="`/user/${comment.user.id}`">{{comment.user.name}}</router-link>
        </span>
      </template>
      <div class="comment-text">
        {{comment.body}}
      </div>
      <!-- いいねボタン -->
      <Button label="♡" class="p-button-help p-button-sm"/>
      <!-- end -->
    </Fieldset>
  </div>
</template>

<script>
export default {
  name: 'Comments',
  props: {
    comments: Array
  }
}
</script>

<style lang="scss" scoped>
.p-fieldset {
  margin-top: 20px;
}

.comment-text {
  white-space:pre-wrap;
}
</style>
