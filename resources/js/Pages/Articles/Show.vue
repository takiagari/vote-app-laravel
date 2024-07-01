<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';

const { props } = usePage();
const article = ref(props.article);
const selectedChoices = ref([]);

// 記事削除
const deleteArticle = () => {
  if (confirm('本当にこの記事を削除しますか？')) {
    Inertia.delete(route('articles.destroy', { id: article.value.id }), {
      onSuccess: () => {
        Inertia.visit(route('articles.index'));
      },
    });
  }
};

// 選択肢の選択処理
const toggleChoice = (choiceId) => {
  if (selectedChoices.value.includes(choiceId)) {
    selectedChoices.value = selectedChoices.value.filter(id => id !== choiceId);
  } else if (selectedChoices.value.length < article.value.max_choices) {
    selectedChoices.value.push(choiceId);
  }
};
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <Head :title="`記事: ${article.title}`" />
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">記事詳細</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <h1 class="text-3xl font-bold">{{ article.title }}</h1>
          <div v-if="article.images.length" class="mt-4">
            <div class="grid grid-cols-2 gap-4 mt-2">
              <div v-for="image in article.images" :key="image.id">
                <img :src="`/storage/${image.image_path}`" :alt="image.image_title" class="w-full h-auto object-cover aspect-square">
              </div>
            </div>
          </div>
          <p class="mt-4 text-gray-600">{{ article.body }}</p>
          <div v-if="article.choices.length" class="mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
              <button 
                v-for="choice in article.choices" 
                :key="choice.id" 
                class="w-full p-4 bg-gray-100 rounded border border-gray-300 hover:bg-gray-300 relative overflow-hidden"
                :class="{'bg-green-200': selectedChoices.includes(choice.id)}"
                @click="toggleChoice(choice.id)"
              >
                <div class="absolute inset-0 flex justify-end items-center pr-4">
                  <svg v-if="selectedChoices.includes(choice.id)" class="w-32 h-32 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                </div>
                <div class="flex items-center z-10 relative">
                  <img v-if="choice.image_path" :src="`/storage/${choice.image_path}`" :alt="choice.title" class="w-36 h-36 object-cover mr-4">
                  <span>{{ choice.title }}</span>
                </div>
              </button>
            </div>
          </div>
          <button class="mt-6 w-full bg-blue-500 text-white p-4 rounded hover:bg-blue-600">
            投票する
          </button>
          <p class="mt-4 text-sm text-gray-400">{{ new Date(article.created_at).toLocaleDateString() }}</p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
