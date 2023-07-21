<template>
    <div>
        <h2 class="text-2xl font-bold">Users calendar</h2>
        <button class=" add-button p-2 font-semibold border-2 rounded-md " @click="showModal = true">Add new user</button>


        <Modal v-if="showModal">
            <template #title>
                <h2 class="text-center text-xl">User Form</h2>
            </template>
            <ModalForm ref="userForm" @submitUser="getSubmittedUser">
                <button class="p-2 font-semibold border-2 mr-2" @click="submitForm">Guardar</button>
                <button @click="showModal = false" class="p-2 font-semibold border-2 rounded-md ">Close</button>
            </ModalForm>
        </Modal>


        <FullCalendar :options="calendarOptions">
            <template #eventContent='arg'>
                <span class="text-lg cursor-pointer" @click="selectUser(arg.event.id)">{{ arg.event.title }}</span>
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
            users: [],
            user: {
                id: 0,
                name: '',
                email: '',
                password: '',
            },
            showModal: false,
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
            this.events = data.map(user => ({ title: user.name, id: user.id, start: new Date(user.created_at) }));
        },
        selectUser(id) {
            const user = this.users.find(user => user.id == id);
            console.log(user);
        },
        submitForm() {
            this.$refs.userForm.submit();
        },
        async getSubmittedUser(user) {
            this.showModal = false;
            const response = await fetch('/api/calendar/users', {
                method: 'POST',
                body: JSON.stringify(user),
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            });

            const result = await response.json();
            console.log(result);

        }
    },
    computed: {
        events() {
            const events = this.users.map(user => ({ title: user.name, start: new Date(user.created_at) }));
            return events;
        },
    },
    async created() {
        await this.getUsers();
        this.calendarOptions.events = this.events;
    }
}
</script>

<style  scoped>
.add-button {
    background-color: #706edf;
    color: #fff;
}
</style>
