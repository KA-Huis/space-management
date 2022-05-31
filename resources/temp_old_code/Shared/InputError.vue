<template>
    <div style="color: red;" v-if="onlyFirst && hasErrors">{{ firstError}}</div>

    <ul v-if="!onlyFirst && hasErrors">
        <li v-for="error in errors" style="color: red;">{{ error }}</li>
    </ul>
</template>

<script>
import { computed } from 'vue';

export default {
    props: {
        errors: Array,
        onlyFirst: {
            type: Boolean,
            default: true,
        },
    },

    setup(props) {
        const firstError = computed(() => {
            return props.errors.find(Boolean);
        });

        const hasErrors = computed(() => {
            return Array.isArray(props.errors) && props.errors.length;
        });

        return {
            hasErrors,
            firstError,
        };
    },
};
</script>
