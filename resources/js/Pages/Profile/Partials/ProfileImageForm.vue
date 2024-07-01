<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, computed } from 'vue';
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useI18n } from "vue-i18n";
import { usePage } from "@inertiajs/vue3";

const { t } = useI18n();
const page = usePage();
const form = useForm({
  profile_image: null,
});

const fileInput = ref(null);
const preview = ref(null);

const updateProfileImage = () => {
  form.post(route("profile.updateImage"), {
    forceFormData: true,
    onSuccess: () => {
      form.reset("profile_image");
      preview.value = null;  // プレビューをリセット
    },
    onError: () => {
      console.error("Error updating profile image.");
    },
  });
};

// 画像読み込み
const profileImageUrl = computed(() => {
  return page.props.profileImage ? `/storage/${page.props.profileImage}` : null;
});

// 画像プレビュー
const handleFileChange = (event) => {
  const file = event.target.files[0];
  form.profile_image = file;
  
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      preview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">
        {{ t("Update Profile Image") }}
      </h2>
      <p class="mt-1 text-sm text-gray-600">
        {{ t("Upload a new profile image.") }}
      </p>
    </header>

    <div v-if="profileImageUrl" class="mt-6">
      <img
        :src="profileImageUrl"
        alt="Profile Image"
        class="rounded-full w-32 h-32 object-cover"
      />
    </div>
    <form @submit.prevent="updateProfileImage" class="mt-6 space-y-6">
      
      <div v-if="preview" class="mt-6">
        <h3 class="text-sm font-medium text-gray-700">プレビュー</h3>
        <img :src="preview" alt="New Profile Image Preview" class="rounded-full w-32 h-32 object-cover mt-2" />
      </div>
      
      <div>
        <input
          ref="fileInput"
          type="file"
          class="block w-full text-sm text-gray-900"
          @change="handleFileChange"
        />
        <InputError :message="form.errors.profile_image" class="mt-2" />
      </div>

      <div class="flex items-center gap-4">
        <PrimaryButton :disabled="form.processing">{{
          t("Save")
        }}</PrimaryButton>
      </div>
    </form>
  </section>
</template>
