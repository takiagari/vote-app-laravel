<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <Head title="Dashboard" />
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ t('Dashboard') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow sm:rounded-lg">
                    <div class="p-6 bg-itemBackgroundColor text-gray-900">{{ t("You're logged in!") }}</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
