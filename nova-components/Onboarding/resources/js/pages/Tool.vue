<template>
    <div>
        <h2 class="text-2xl font-bold mb-2">Users</h2>

        <!-- VOnboarding -->

        <VOnboardingWrapper ref="wrapper" :steps="steps">
            <template #default="{ previous, next, step, exit, isFirst, isLast, index }">
                <VOnboardingStep>
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="sm:flex sm:items-center sm:justify-between">
                                <div v-if="step.content">
                                    <h3 v-if="step.content.title" class="text-lg font-medium leading-6 text-gray-900">{{
                                        step.content.title }}</h3>
                                    <div v-if="step.content.description" class="mt-2 max-w-xl text-sm text-gray-500">
                                        <p>{{ step.content.description }}</p>
                                    </div>
                                </div>
                                <div
                                    class="mt-5 space-x-4 sm:mt-0 sm:ml-6 sm:flex sm:flex-shrink-0 sm:items-center relative">
                                    <span
                                        class="absolute right-0 bottom-full mb-2 mr-2 text-gray-600 font-medium text-xs">{{
                                            `${index + 1}/${steps.length}` }}</span>
                                    <template v-if="!isFirst">
                                        <button @click="previous" type="button"
                                            class="border-2 rounded-md font-bold p-2 mb-2 warn-button">Previous</button>
                                    </template>
                                    <button @click="next" type="button"
                                        class="border-2 rounded-md font-bold p-2 mb-2 add-button">{{
                                            isLast ? 'Finish' : 'Next' }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </VOnboardingStep>
            </template>
        </VOnboardingWrapper>

        <!-- Add new user -->
        <button id="addUser" @click="showSaveForm" class="border-2 rounded-md font-bold p-2 mb-2 add-button">Add
            User</button>


        <!-- Modal -->
        <Modal :id="'formModal'" v-if="showModal">
            <!-- Modal Title -->
            <template #title>
                <h2 class="text-center text-xl">User Form</h2>
            </template>

            <!-- Form User -->
            <ModalForm ref="formRef" :user="user" @submitUser="getSubmittedUser">
            </ModalForm>

            <!-- Actions -->
            <div class="flex flex-row flex-wrap gap-3 mt-3">
                <button class="p-2 font-semibold border-2 mr- add-button" @click="submitForm">Save</button>
                <button @click="showModal = false" class="p-2 font-semibold border-2 rounded-md error">Close</button>
            </div>
        </Modal>

        <!-- Table component -->
        <Table :idRow="'idRow'" :prevId="'prevId'" :nextId="'nextId'" :data="users" :fields="fields"
            v-if="users.data.length" :headers="headers" @select="getNewList">
            <template #actions="{ row }">
                <div class="flex flex-row flex-wrap items-center justify-center gap-2">
                    <svg id="update" @click="() => update(row)" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 cursor-pointer warn">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>

                    <svg id="delete" @click="() => deleteUser(row)" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 cursor-pointer error">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>

                </div>
            </template>
        </Table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';
import { useVOnboarding, VOnboardingWrapper, VOnboardingStep } from 'v-onboarding';
import Table from '../components/general/Table.vue';
import Modal from '../components/general/Modal.vue';
import ModalForm from '../components/forms/ModalForm.vue';



const users = ref({
    current_page: 1,
    data: [],
    first_page_url: "",
    from: 0,
    last_page: 0,
    last_page_url: "",
    links: [],
    next_page_url: "",
    path: "",
    per_page: 0,
    prev_page_url: null,
    to: 0,
    total: 0
});

const randomText = ref('null');

const user = ref({
    id: 0,
    name: '',
    email: '',
    password: '',
})


const formRef = ref();
const showModal = ref(false);
const headers = ref(['Name', 'Email', 'Created At']);
const fields = ref(['name', 'email', 'created_at']);

// for v-onboarding
const wrapper = ref();
const { start, goToStep, finish } = useVOnboarding(wrapper)

const steps = ref([]);

const getRandomText = async () => {
    const response = await fetch('https://baconipsum.com/api/?type=all-meat&words=3&start-with-lorem=1');
    const result = await response.json();
    randomText.value = result[0] || 'lorem ipsum';
    steps.value = [
        { attachTo: { element: '#addUser' }, content: { title: `you can add a new user by clicking here and also ${randomText.value}` } },
        { attachTo: { element: `#idRow` }, content: { title: `and after to save a user you will see the new user  and also ${randomText.value}` } },
        { attachTo: { element: `#update` }, content: { title: `you can edit a user by clicking here and also ${randomText.value}` } },
        { attachTo: { element: `#delete` }, content: { title: `you can delete a  user by clicking here and also ${randomText.value}` } },
        { attachTo: { element: `#prevId` }, content: { title: `you see the previous users by clicking here and also ${randomText.value}` } },
        { attachTo: { element: `#nextId` }, content: { title: `you see the next users by clicking here and also ${randomText.value}` } },
    ];
}

// Get All users
const getUsers = async () => {
    const response = await fetch('/api/onboarding/users');
    const result = await response.json();
    users.value = result;
}

// delete user
const deleteUser = async (user) => {
    try {
        Swal.fire({
            title: `user ${user.name} will be deleted?`,
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await fetch(`/api/onboarding/users/${user.id}`, {
                    method: 'DELETE',
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });

                await response.json();

                await getUsers();

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: `user ${user.name} was deleted`,
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        })

    } catch (error) {
        Swal.fire({
            position: 'center',
            icon: 'warning',
            title: `something went wrong`,
            showConfirmButton: false,
            timer: 2000
        });
    }

}

// add and update user
const getSubmittedUser = async (user) => {
    if (user.id === 0) {
        try {
            const response = await fetch('/api/onboarding/users', {
                method: 'POST',
                body: JSON.stringify(user),
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            });
            await response.json();
            showModal.value = false;
            await getUsers();

            Swal.fire({
                position: 'center',
                icon: 'success',
                title: `user ${user.name} was created`,
                showConfirmButton: false,
                timer: 2000
            });
        } catch (error) {
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: `something went wrong`,
                showConfirmButton: false,
                timer: 2000
            });
        }

    } else {
        try {
            const { id, ...rest } = user;
            const response = await fetch(`/api/onboarding/users/${id}`, {
                method: 'PUT',
                body: JSON.stringify(rest),
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            });
            await response.json();
            showModal.value = false;
            await getUsers();

            Swal.fire({
                position: 'center',
                icon: 'success',
                title: `user ${user.name} was updated`,
                showConfirmButton: false,
                timer: 2000
            });
        } catch (error) {
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: `something went wrong`,
                showConfirmButton: false,
                timer: 2000
            });
        }

    }
}

const submitForm = () => formRef.value.submit();

const update = (row) => {
    user.value = row;
    showModal.value = true;
    console.log(user.value);
}


const showSaveForm = () => {
    user.value = {
        id: 0,
        name: '',
        email: '',
        password: '',
    }
    showModal.value = true;
}

// get user by paginate
const getNewList = async (url) => {
    const response = await fetch(url);
    const result = await response.json();
    users.value = result;
}

// getUser at start
(async () => {
    await getUsers();
    await getRandomText();
})();

onMounted(async () => {
    start();
});

</script>

<style  scoped>
.warn {
    color: #f59133;
}

.warn-button {
    background-color: #f59133;
    color: #fff;
}

.add-button {
    background-color: #706edf;
    color: #fff;
}
</style>
