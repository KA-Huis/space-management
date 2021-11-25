<template>
    <h1>Create Space</h1>
    <br>

    <form @submit.prevent="submitCreateSpaceForm">
        <label for="name">Naam</label>
        <input type="text" id="name" :value="createSpaceForm.name">
        <br>
        <br>
        <label for="description">Omschrijving</label>
        <input type="text" id="description" :value="createSpaceForm.description">
        <br>
        <br>
        <input type="checkbox" name="is_open_for_reservations" id="is_open_for_reservations">
        <label for="is_open_for_reservations">Open voor reserveringen</label>
        <br>
        <br>
        <button type="submit">Ruimte aanmaken</button>
    </form>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';

export default {
    setup(props) {
        const createSpaceForm = useForm({

        });

        return {
            createSpaceForm,
        };
    },


    // data() {
    //     return {
    //         form: this.$inertia.form
    //     };
    // },
    methods: {
        submitCreateSpaceForm() {
            this.createSpaceForm.submit(route('admin.space.store'));
        },
    },
};
</script>
