<template>
    <AppLayout title="Create a Post">
        <Container>
            <h1 class="text-2xl font-bold">Create a Post</h1>

            <form @submit.prevent="createPost" class="mt-6">
                <div class="mt-3">
                    <InputLabel for="title" class="sr-only">Title</InputLabel>
                    <TextInput id="title" class="w-full" v-model="form.title" placeholder="Give a great title" />
                    <InputError :message="form.errors.title" class="mt-1"></InputError>
                </div>
                <div class="mt-3">
                    <InputLabel for="body" class="sr-only">Body</InputLabel>
                    <MarkdownEditor v-model="form.body"></MarkdownEditor>
                    <InputError :message="form.errors.body" class="mt-1"></InputError>

                </div>
                <div>
                    <PrimaryButton type="submit" size="lg">Create Post</PrimaryButton>
                </div>
            </form>

        </Container>
    </AppLayout>
</template>
<script setup>
    import {useForm} from "@inertiajs/vue3";
    import AppLayout from "@/Layouts/AppLayout.vue";
    import Container from "@/Components/Container.vue";
    import PrimaryButton from "@/Components/PrimaryButton.vue";
    import InputLabel from "@/Components/InputLabel.vue";
    import TextInput from "@/Components/TextInput.vue";
    import InputError from "@/Components/InputError.vue";
    import TextArea from "@/Components/TextArea.vue";
    import MarkdownEditor from "@/Components/MarkdownEditor.vue";

    const form = useForm({
        'title' : '',
        'body' : '',
    });

    const createPost =  () => form.post(route('posts.store'));
</script>
