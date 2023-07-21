<template>
    <div>
        <h2 class="text-2xl font-bold">Users calendar</h2>
        <button class=" add-button p-2 font-semibold border-2 rounded-md " @click="showSaveForm">Add new user</button>

        <!-- Modal -->
        <Modal :id="'formModal'" v-if="showModal">
            <!-- Modal Title -->
            <template #title>
                <h2 class="text-center text-xl">User Form</h2>
            </template>

            <!-- Form User -->
            <ModalForm :user="user" ref="userForm" @submitUser="getSubmittedUser">
            </ModalForm>

            <!-- Actions -->
            <div class="flex flex-row flex-wrap gap-3 mt-3">
                <button class="p-2 font-semibold border-2 mr-2 add-button" @click="submitForm">Save</button>
                <button @click="showModal = false" class="p-2 font-semibold border-2 rounded-md error ">Close</button>
            </div>
        </Modal>


        <FullCalendar :options="calendarOptions">
            <template #eventContent='arg'>
                <div class="flex flex-row flex-wrap items-center ">
                    <span class="text-lg cursor-pointer warn" @click="showUpdateForm(arg.event.id)">{{ arg.event.title }}</span>
                    <svg @click="() => deleteUser(arg.event.id)" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ml-3 cursor-pointer error">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </template>
        </FullCalendar>
    </div>
</template>

<script >
import Swal from 'sweetalert2';
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
                weekends: true,
                events: [],
            },
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
        showUpdateForm(id) {
            const user = this.users.find(user => user.id == id);
            if (!user) return;
            this.user = user;
            this.showModal = true;
        },
        async deleteUser(id) {
            try {
                const user = this.users.find(user => user.id == id);
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
                        const response = await fetch(`/api/calendar/users/${id}`, {
                            method: 'DELETE',
                            headers: {
                                "Content-Type": "application/json",
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                        });

                        await response.json();

                        await this.getUsers();

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


        },

        async getSubmittedUser(user) {
            if (user.id === 0) {
                try {
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
.error{
    color: #ff0000;
}
.warn {
    color: #f59133;
}
</style>
