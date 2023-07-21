<template>
    <div>
        <form class="flex flex-col flex-wrap" autocomplete="off" @submit.prevent="submit">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="grid grid-cols-1 text-left mt-2">
                <label class="font-semibold" for="name">Name:</label>
                <input id="name" v-model="state.name" class="border-2 rounded-md px-2 py-1" type="text">
                <ul v-if="v$.name.$error">
                    <li class="error-text" v-for="error in v$.name.$errors" :key="error">{{ error.$message }}</li>
                </ul>
            </div>
            <div class="grid grid-cols-1 text-left mt-2">
                <label class="font-semibold" for="email">Email:</label>
                <input v-model="state.email" class="border-2 rounded-md px-2 py-1" id="email" type="email">
                <ul v-if="v$.email.$error">
                    <li class="error-text" v-for="error in v$.email.$errors" :key="error">{{ error.$message }}</li>
                </ul>
            </div>
            <div class="grid grid-cols-1 text-left mt-2">
                <label class="font-semibold" for="password">Password:</label>
                <input v-model="state.password" class="border-2 rounded-md px-2 py-1" id="password" type="password">
                <ul v-if="v$.password.$error">
                    <li class="error-text" v-for="error in v$.password.$errors" :key="error">{{ error.$message }}</li>
                </ul>
            </div>

            <div class="flex flex-row flex-wrap gap-3 mt-3">
                <slot></slot>
            </div>
        </form>
    </div>
</template>

<script setup>
import { computed, onMounted, defineEmits,defineExpose, ref } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { required, minLength, email } from '@vuelidate/validators';

const state = ref({
    name: '',
    email: '',
    password: ''
});

const rules = computed(() => ({
    name: { required, minLength: minLength(5) },
    email: { required, minLength: minLength(5), email },
    password: { required, minLength: minLength(8) },
}))

const v$ = useVuelidate(rules, state.value);

const submit = async () => {
    await v$.value.$validate();
    if (v$.value.$invalid) return;

    emits('submitUser', state.value);
}

const emits = defineEmits(['submitUser'])

defineExpose({
    submit,
})

onMounted(async () => {
    await v$.value.$validate()
});

</script>
<style scoped>
.error-text {
    color: red;

}
</style>
