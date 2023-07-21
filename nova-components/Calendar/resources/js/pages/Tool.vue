<template>
    <div>
        <h2 class="text-2xl font-bold">Users calendar</h2>
        <button class=" add-button p-2 font-semibold border-2 rounded-md " @click="showSaveForm">Add new user</button>


        <Modal :id="'formModal'" v-if="showModal">
            <template #title>
                <h2 class="text-center text-xl">User Form</h2>
            </template>
            <ModalForm :user="user" ref="userForm" @submitUser="getSubmittedUser">
            </ModalForm>

            <div class="flex flex-row flex-wrap gap-3 mt-3">
                <button class="p-2 font-semibold border-2 mr-2" @click="submitForm">Guardar</button>
                <button @click="showModal = false" class="p-2 font-semibold border-2 rounded-md ">Close</button>
            </div>
        </Modal>


        <FullCalendar :options="calendarOptions">
            <template #eventContent='arg'>
                <div class="flex flex-row flex-wrap items-center ">
                    <span class="text-lg cursor-pointer" @click="updateUser(arg.event.id)">{{ arg.event.title }}</span>
                    <svg @click="() => deleteUser(arg.event.id)" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ml-3 cursor-pointer ">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </template>
        </FullCalendar>
    </div>
</template>

<script >
import FullCalendar from '@fullcalendar/vue3';
import DayGridPlugin from '@fullcalendar/daygrid';
import Modal from '../components/general/Modal.vue';
import ModalForm from '../components/forms/ModalForm.vue';

export default {
    components: {
        FullCalendar,
        Modal,
        ModalForm,
    },
    data() {
        return {
            user: {
                id: 0,
                name: '',
                email: '',
                password: '',
            },
            showModal: false,
            users: [],
            events: [],
            calendarOptions: {
                plugins: [DayGridPlugin],
                initialView: 'dayGridMonth',
                weekends: false,
                events: [],
            }
        }
    },
    methods: {
        async getUsers() {
            const response = await fetch('/api/calendar/users');
            const data = await response.json();
            this.users = data;
            this.calendarOptions.events = data.map(user => ({
                title: user.name,
                id: user.id,
                start: new Date(user.created_at)
            }));
        },
        updateUser(id) {
            const user = this.users.find(user => user.id == id);
            if (!user) return;
            this.user = user;
            this.showModal = true;
        },
        async deleteUser(id) {
            const response = await fetch(`/api/calendar/users/${id}`, {
                method: 'DELETE',
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            });

            await response.json();

            await this.getUsers();
        },

        async getSubmittedUser(user) {
            if (user.id === 0) {
                const response = await fetch('/api/calendar/users', {
                    method: 'POST',
                    body: JSON.stringify(user),
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });
                await response.json();
                this.showModal = false;
                await this.getUsers();
            } else {
                const { id, ...rest } = user;
                const response = await fetch(`/api/calendar/users/${id}`, {
                    method: 'PUT',
                    body: JSON.stringify(rest),
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });
                await response.json();
                this.showModal = false;
                await this.getUsers();
            }

        },
        submitForm() {
            this.$refs.userForm.submit();
        },
        showSaveForm() {
            this.user = {
                id: 0,
                name: '',
                email: '',
                password: '',
            }
            this.showModal = true;
        }
    },
    async created() {
        await this.getUsers();
    }
}
</script>

<style  scoped>
.add-button {
    background-color: #706edf;
    color: #fff;
}
</style>
