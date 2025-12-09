<script setup lang="ts">
import {ref} from 'vue'
import AppLayout from '@/layouts/AppLayout.vue';
import { employee } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import AddEmployee from './Employee/AddEmployee.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Employees',
        href: employee().url,
    },
];

const employeearr:any=ref([])

function addarremployee(employee:any)
{
    employeearr.value.push(employee)
}
</script>

<template>
    <Head title="Employees" />

    <AppLayout :breadcrumbs="breadcrumbs">
  <h2>Hello</h2>
<div>
    <div v-for="employee in employeearr">
        <h1>{{ employee.fname }} {{ employee.lname }}</h1>
    </div>
</div>
  <AddEmployee v-on:employee-added="addarremployee"/>
    </AppLayout>
</template>
