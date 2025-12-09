<script setup lang="ts">
import {ref} from 'vue'
import AppLayout from '@/layouts/AppLayout.vue';
import { employee } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import AddEmployee from './Employee/AddEmployee.vue';
import EmployeeCard from './Employee/EmployeeCard.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Employees',
        href: employee().url,
    },
];
const props= defineProps({
     employee:Array<any>
}

)
const search=ref('')
const employeearr:any=ref([...props.employee ?? []])

function filterCustomers() {
    if (search.value.trim() === '') {
        employeearr.value = [...props.employee ?? []]
    } else {
        const searchTerm = search.value.toLowerCase()
        employeearr.value = (props.employee ?? []).filter((employee) =>
            employee.fname.toLowerCase().includes(searchTerm) ||
            employee.email.toLowerCase().includes(searchTerm)
        )
    }
}

function addarremployee(employee:any)
{
    employeearr.value.push(employee)
}
</script>

<template>
    <Head title="Employees" />

    <AppLayout :breadcrumbs="breadcrumbs">
  <AddEmployee v-on:employee-added="addarremployee"/>
<div>
    <div v-for="employee in employeearr">
        <EmployeeCard :employee="employee"/>
    </div>
</div>

    </AppLayout>
</template>
