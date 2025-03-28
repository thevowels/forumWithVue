<template>
    <AppLayout :title="post.title">
        <Container>
            <h1 class="text-2xl font-bold">{{post.title}}</h1>
            <span class="block mt-1 text-small text-gray-600">{{formattedDate}} by {{post.user.name}}</span>
            <article class="mt-6 prose prose-sm max-w-none" v-html="post.html">
            </article>

            <div class="mt-12">
                <h2 class="text-xl font-semibold">Comments</h2>
                <form v-if="$page.props.auth?.user" @submit.prevent="() => commentIdBeingEdited ? updateComment() : addComment() ">
                    <div>
                        <InputLabel for="body" class="sr-only">Comment</InputLabel>
                        <MarkdownEditor ref="commentTextAreaRef" id="body" v-model="commentForm.body"  placeholder="Speak your mind Spock…" editorClass="min-h-[160px]" />
                        <InputError :message="commentForm.errors.body" class="mt-1"/>
                    </div>

                    <PrimaryButton type="submit" class="mt-3" :disabled="commentForm.processing" v-text="commentIdBeingEdited ?'Update Comment': 'Add Comment' "></PrimaryButton>
                    <SecondaryButton v-if="commentIdBeingEdited" @click="cancelEditComment" class="ml-2">Cancel</SecondaryButton>
                </form>
                <ul class="divide-y mt-4">
                    <li v-for="comment in comments.data" :key="comment.id" class=" px-2 py-4">
                        <Comment :comment="comment" @delete="deleteComment" @update="editComment" />
                    </li>
                </ul>

                <Pagination :meta="comments.meta" :only="['comments']"/>
            </div>
        </Container>
    </AppLayout>
</template>
<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import {computed, ref} from "vue";
import {Link, router, useForm, usePage} from "@inertiajs/vue3";
import {formatDistance, parseISO } from "date-fns";
import {relativeDate } from "@/Utilities/date";
import Pagination from "@/Components/Pagination.vue";
import Comment from "@/Components/Comment.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {useConfirm} from "@/Utilities/useConfirm.js";
import MarkdownEditor from "@/Components/MarkdownEditor.vue";


const props = defineProps(['post', "comments"]);

const formattedDate = computed(() => relativeDate(props.post.created_at));

const commentForm = useForm({
    'body': ''
})


const commentTextAreaRef = ref(null);
const commentIdBeingEdited = ref(null);
const commentBeingEdited = computed(() => props.comments.data.find(comment => comment.id === commentIdBeingEdited.value) );
const editComment = (commentId) => {
    commentIdBeingEdited.value = commentId;
    commentForm.body = commentBeingEdited.value?.body;
    commentTextAreaRef.value?.focus();
};
const cancelEditComment = () => {
    commentIdBeingEdited.value = null;
    commentForm.reset();
}


const addComment = () => commentForm.post(route('posts.comments.store', props.post.id), {preserveScroll: true, onSuccess: () => commentForm.reset()});

const updateComment = async () => {
    if (! await confirmation('Are you sure you want to update this comment?')) {
        commentTextAreaRef.value?.focus();
        return;
    }

    commentForm.put(route('comments.update', {
        comment: commentIdBeingEdited.value,
        page: props.comments.meta.current_page,
    }), {
        preserveScroll: true,
        onSuccess: cancelEditComment,
    });
};


const deleteComment =  async (commentId) => {
    if(! await confirmation('Are you sure you want to delete this comment?')) {
        return;
    }

    router.delete(route('comments.destroy', {comment: commentId, page:props.comments.meta.current_page}),{
    preserveScroll: true,

    })
};

const { confirmation } = useConfirm();



</script>
