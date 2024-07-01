<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const form = useForm({
  title: '',
  body: '',
  max_choices: 1,
  comments_enabled: true,
  start_time: new Date().toISOString().slice(0, 16),
  end_time: new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString().slice(0, 16),
  choices: [{ text: '', image: null, preview: null }],
  images: [],
  previews: [],
});

const addChoice = () => {
  form.choices.push({ text: '', image: null, preview: null });
  updateMaxChoices();
};

const removeChoice = (index) => {
  form.choices.splice(index, 1);
  updateMaxChoices();
};

const handleFileUpload = (event, index) => {
  const file = event.target.files[0];
  form.choices[index].image = file || null;
  form.choices[index].preview = null;

  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      form.choices[index].preview = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const handleArticleImageUpload = (event) => {
  const files = Array.from(event.target.files);
  form.images = files;

  form.previews = [];
  files.forEach(file => {
    const reader = new FileReader();
    reader.onload = (e) => {
      form.previews.push(e.target.result);
    };
    reader.readAsDataURL(file);
  });
};

const updateMaxChoices = () => {
  if (form.max_choices > form.choices.length) {
    form.max_choices = form.choices.length;
  }
};

watch(() => form.choices.length, (newLength) => {
  if (form.max_choices > newLength) {
    form.max_choices = newLength;
  }
});

const submitForm = () => {
  form.post(route('articles.store'), {
    onError: () => {},
  });
};

const getErrorMessage = (field, index) => {
  const key = `choices.${index}.${field}`;
  return form.errors[key] ? form.errors[key] : '';
};
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <Head title="記事作成" />
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">記事作成</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <form @submit.prevent="submitForm" class="space-y-6" enctype="multipart/form-data">
            <div>
              <label for="title" class="block text-sm font-medium text-gray-700">記事タイトル</label>
              <input type="text" v-model="form.title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
              <p v-if="form.errors.title" class="mt-2 text-sm text-red-600">{{ form.errors.title }}</p>
            </div>
            <div>
              <label for="body" class="block text-sm font-medium text-gray-700">記事本文</label>
              <textarea v-model="form.body" id="body" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
              <p v-if="form.errors.body" class="mt-2 text-sm text-red-600">{{ form.errors.body }}</p>
            </div>
            <div>
              <div v-if="form.previews.length" class="mt-2">
                <h3 class="text-sm font-medium text-gray-700">プレビュー</h3>
                <div class="mt-2 grid grid-cols-2 gap-4">
                  <div v-for="(preview, index) in form.previews" :key="index" class="flex items-center">
                    <img :src="preview" alt="Image preview" class="h-20 w-20 object-cover">
                    <p class="ml-2 text-sm">{{ form.images[index]?.name }}</p>
                  </div>
                </div>
              </div>
              <label for="images" class="block text-sm font-medium text-gray-700">記事画像</label>
              <input type="file" id="images" multiple @change="handleArticleImageUpload" class="mt-1 block w-full border-gray-300 shadow-sm">
              <p v-if="form.errors.images" class="mt-2 text-sm text-red-600">{{ form.errors.images }}</p>
            </div>
            <div v-for="(choice, index) in form.choices" :key="index">
              <label :for="'choice_' + index" class="block text-sm font-medium text-gray-700">選択肢 {{ index + 1 }}</label>
              <input type="text" v-model="choice.text" :id="'choice_' + index" :placeholder="`選択肢${index + 1}の名前`" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
              <p v-if="getErrorMessage('text', index)" class="mt-2 text-sm text-red-600">{{ getErrorMessage('text', index) }}</p>
              <div v-if="choice.preview" class="mt-2">
                <img :src="choice.preview" alt="Choice image preview" class="h-20 w-20 object-cover">
              </div>
              <input type="file" @change="handleFileUpload($event, index)" class="mt-1 block w-full border-gray-300 shadow-sm">
              <p v-if="getErrorMessage('image', index)" class="mt-2 text-sm text-red-600">{{ getErrorMessage('image', index) }}</p>
              <button type="button" @click="removeChoice(index)" class="mt-1 text-red-600">削除</button>
            </div>
            <button type="button" @click="addChoice" class="text-white bg-blue-600 hover:bg-blue-700 rounded-md px-4 py-1">選択肢を追加</button>
            <div>
              <label for="max_choices" class="block text-sm font-medium text-gray-700">投票時の最大選択数</label>
              <p class="mt-1 text-sm text-gray-500">ユーザーが投票時に選択できる数</p>
              <input type="number" v-model="form.max_choices" :max="form.choices.length" min="1" id="max_choices" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
              <p v-if="form.errors.max_choices" class="mt-2 text-sm text-red-600">{{ form.errors.max_choices }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">コメント</label>
              <div class="mt-1">
                <label class="inline-flex items-center">
                  <input type="radio" v-model="form.comments_enabled" :value="true" class="form-radio">
                  <span class="ml-2">受け付ける</span>
                </label>
                <label class="inline-flex items-center ml-6">
                  <input type="radio" v-model="form.comments_enabled" :value="false" class="form-radio">
                  <span class="ml-2">受け付けない</span>
                </label>
              </div>
              <p v-if="form.errors.comments_enabled" class="mt-2 text-sm text-red-600">{{ form.errors.comments_enabled }}</p>
            </div>
            <div>
              <label for="start_time" class="block text-sm font-medium text-gray-700">開始日時</label>
              <input type="datetime-local" v-model="form.start_time" id="start_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
              <p v-if="form.errors.start_time" class="mt-2 text-sm text-red-600">{{ form.errors.start_time }}</p>
            </div>
            <div>
              <label for="end_time" class="block text-sm font-medium text-gray-700">終了日時</label>
              <input type="datetime-local" v-model="form.end_time" id="end_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
              <p v-if="form.errors.end_time" class="mt-2 text-sm text-red-600">{{ form.errors.end_time }}</p>
            </div>
            <div class="flex justify-center">
              <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 rounded-md px-4 py-2">記事を作成する</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
