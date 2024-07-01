<script setup>
import { defineProps } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  articleId: {
    type: Number,
    required: true,
  },
  isOwner: {
    type: Boolean,
    required: true,
  },
});

const deleteArticle = () => {
  if (confirm('本当にこの記事を削除しますか？')) {
    router.delete(route('articles.destroy', { id: props.articleId }), {
      onSuccess: () => {
        router.visit(route('articles.index'));
      },
    });
  }
};
</script>

<template>
  <div v-if="isOwner">
    <button @click="deleteArticle" class="text-red-600 hover:text-red-800">
      削除
    </button>
  </div>
</template>
