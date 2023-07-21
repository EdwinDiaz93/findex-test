<template>
    <div>
        <table class="main">
            <tr>
                <th v-for="header in headers" :key="header">{{ header }}</th>
                <th>Actions</th>
            </tr>
            <tr v-for="(row, i) in data.data" :key="row.id" :id="i === 0 && idRow">
                <td>{{ row[fields[0]] }}</td>
                <td>{{ row[fields[1]] }}</td>
                <td>{{ row[fields[2]] }}</td>
                <td>
                    <slot name="actions" :row="row"></slot>
                </td>
            </tr>
        </table>
        <div class="flex flex-row flex-wrap items-center justify-right w-full mt-2">

            <button :id="prevId" :disabled="!data.prev_page_url" class="border-2 rounded-md font-bold p-2"
                @click="select(data.prev_page_url)">
                Previous Page
            </button>
            <button :id="nextId" :disabled="!data.next_page_url" class="border-2 rounded-md font-bold p-2 ml-3"
                @click="select(data.next_page_url)">
                Next Page
            </button>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const emitter = defineEmits(['select']);

const select = (url) => {
    emitter('select', url);
}

const { data, headers, fields, idRow, prevId, nextId } = defineProps(['data', 'headers', 'fields', 'idRow', 'prevId', 'nextId']);



</script>

<style  scoped>
.main {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

.main td,
.main th {
    border: 1px solid #ddd;
    padding: 8px;
}

.main tr:nth-child(even) {
    background-color: #f2f2f2;
}

.main tr:hover {
    background-color: #ddd;
}

.main th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #000;
    color: white;
}

.pagination {
    display: flex;
    flex-flow: row wrap;
}

.pagination a {
    color: black;
    float: left;
    padding: 8px 16px;
    text-decoration: none;
    margin-top: 1rem;
    justify-content: right;
    align-items: center;
}

.pagination a.active {
    background-color: #4CAF50;
    color: white;
    border-radius: 5px;
}

.pagination a:hover:not(.active) {
    background-color: #ddd;
    border-radius: 5px;
}
</style>
