<template>
    <AppLayout :title="post.title">
        <Container>
            Show Page
        </Container>
    </AppLayout>
</template>
<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
const props = defineProps(['post']);
</script>
