<template>
    <div>
        <h2 class="text-2xl font-bold">Users calendar</h2>
        <FullCalendar :options="calendarOptions" />
    </div>
</template>

<script >
import FullCalendar from '@fullcalendar/vue3';
import DayGridPlugin from '@fullcalendar/daygrid';


export default {
    components: {
        FullCalendar,
    },
    data() {
        return {
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
            this.events = data.map(user => ({ title: user.name, start: new Date(user.created_at) }));
        }
    },
    computed: {
        events() {
            const events = this.users.map(user => ({ title: user.name, start: new Date(user.created_at) }));
            console.log(events);
            return events;
        }
    },
    async created() {
        await this.getUsers();
        this.calendarOptions.events = this.events;
    }
}
</script>

<style lang="scss" scoped></style>
