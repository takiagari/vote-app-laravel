
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();
const articles = ref(props.articles);
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <Head title="記事一覧" />
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">記事一覧</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div v-if="articles.length === 0" class="p-6 bg-white shadow sm:rounded-lg">
          <p>記事がありません</p>
        </div>
        <div v-else v-for="article in articles" :key="article.id" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <div class="space-y-4">
            <Link :href="route('articles.show', { id: article.id })" class="text-lg font-semibold text-blue-600 hover:underline">{{ article.title }}</Link>
            <p class="text-gray-600 mt-2">{{ article.body.substring(0, 100) }}...</p>
            <p class="text-sm text-gray-400 mt-1">{{ new Date(article.created_at).toLocaleDateString() }}</p>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>