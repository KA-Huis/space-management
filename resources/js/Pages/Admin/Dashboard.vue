<template>
    <h1>Hi, {{ user.full_name }}</h1>
    <p>This is your dashboard.</p>
</template>

<script>
export default {
    props: {
        user: {
            type: Object,
            required: true,
        },
    },
}
</script>
