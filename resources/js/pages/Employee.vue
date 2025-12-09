<script setup lang="ts">
import {ref,} from 'vue'
import AppLayout from '@/layouts/AppLayout.vue';
import { employee } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router} from '@inertiajs/vue3';
import AddEmployee from './Employee/AddEmployee.vue';
import EmployeeCard from './Employee/EmployeeCard.vue';
import UpdateEmployee from './Employee/UpdateEmployee.vue';

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
const upemployee:any=ref()
const openmodal=ref(false)
const employeearr:any=ref([...props.employee ?? []])

function deleteEmp(id:number)
{
   router.delete(`/DeleteEmployee/${id}`, {
    onSuccess: () => {
      // 2️⃣ Remove the employee from the local array
      employeearr.value = employeearr.value.filter((emp:any) => emp.id !== id)
    },
    onError: (errors) => {
      console.error('Error deleting employee:', errors)
    }
  })
}
function filterEmployees() {
    if (search.value.trim() === '') {
        employeearr.value = [...props.employee ?? []]
    } else {
        const searchTerm = search.value.toLowerCase()
        employeearr.value = (props.employee ?? []).filter((employee) =>
            employee.fname.toLowerCase().includes(searchTerm) ||
            employee.lname.toLowerCase().includes(searchTerm)
        )
    }
}
function UpdatingEmployee(employee:any)
{

    upemployee.value=employee

    openmodal.value=true

}
function closeupmodal()
{
    openmodal.value=false
}

function updateemp(emp: any) {

  employeearr.value = employeearr.value.map((it:any) =>
    it.id === emp.id ? { ...emp } : it
  )

  // Force Vue to see the change
  employeearr.value = [...employeearr.value]
openmodal.value=false
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
  <div style="margin:10px;">
    <label>SEARCH</label>
    <input style="margin-left: 1ch;" type="text" v-model="search" @input="filterEmployees" placeholder="SEARCH EMPLOYEE"/>
  </div>

  <UpdateEmployee :employee="upemployee" :openmodal="openmodal" v-on:empupdate="updateemp" v-on:closeupmodal="closeupmodal"/>
<div>
    <div v-for="employee in employeearr" :key="employee.id">
        <EmployeeCard :employee="employee" :key="employee.id" v-on:employee-update="UpdatingEmployee" v-on:delete-emp="deleteEmp"/>
    </div>
</div>

    </AppLayout>
</template>
