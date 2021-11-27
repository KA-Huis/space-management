<template>
    <h1>Create Space</h1>

    <form @submit.prevent="submitCreateSpaceForm">
        <label for="name">Naam</label><br>
        <input type="text" id="name" v-model="createSpaceForm.name">
        <InputError :errors="createSpaceForm.errors.name" :onlyFirst="false"/>
        <br>
        <br>
        <label for="description">Omschrijving</label><br>
        <textarea rows="6" id="description" v-model="createSpaceForm.description"></textarea>
        <InputError :errors="createSpaceForm.errors.description"/>
        <br>
        <br>
        <input type="checkbox" name="is_open_for_reservations" id="is_open_for_reservations"
               v-model="createSpaceForm.is_open_for_reservations">
        <label for="is_open_for_reservations">Open voor reserveringen</label>
        <InputError :errors="createSpaceForm.errors.is_open_for_reservations"/>
        <br>
        <br>
        <button type="submit">Ruimte aanmaken</button>
    </form>
</template>

<script>
import {useForm} from '@inertiajs/inertia-vue3';
import InputError from '@/Shared/InputError';

export default {
    components: {
        InputError,
    },

    setup(props) {
        const createSpaceForm = useForm({
            name: null,
            description: null,
            is_open_for_reservations: null,
        });

        const submitCreateSpaceForm = (s) => {
            console.log(s)
            createSpaceForm.post(route('admin.space.store'));
        };

        return {
            createSpaceForm,
            submitCreateSpaceForm,
        };
    },
};
</script>
