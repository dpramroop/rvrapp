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

//   const normalizedEmp = {
//     ...emp,
//     dob: typeof emp.dob === 'string' ? emp.dob : emp.dob.toISOString().split('T')[0],
//     created_at: typeof emp.created_at === 'string' ? emp.created_at : emp.created_at.toISOString()
//   };

//     const index = employeearr.value.findIndex((e: any) => e.id === normalizedEmp.id);

//   if (index !== -1) {
//     // Replace the object at the found index
//     employeearr.value[index] = normalizedEmp;

//     // Force Vue to detect the update
//     employeearr.value = [...employeearr.value];
//   }
//   openmodal.value = false;



  employeearr.value = employeearr.value.map((it:any) =>
    it.id === emp.id ? { ...emp } : it
  )

  // Force Vue to see the change
  employeearr.value = [...employeearr.value]
openmodal.value=false
}

function addarremployee(employee:any)
{
    window.location.reload()
    // employeearr.value.push(employee)
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
    <table>
        <thead>
        <tr>
        <th>Name</th>
        <th>Date of Birth</th>
        <th>Position</th>
        <th>Created</th>
        <th></th>
        <th></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="employee in employeearr" :key="employee.id">
             <td>{{ employee.fname }} {{ employee.lname }}</td>
             <td>{{ employee.dob }}</td>
             <td>{{ employee.position }}</td>
             <td>{{ new Date(employee.created_at).toLocaleDateString() }} {{ new Date(employee.created_at).toLocaleTimeString() }} </td>
             <td><button type="button" @click="UpdatingEmployee(employee)">Update</button></td>
             <td><button class="danger-delete" type="button" @click="deleteEmp(employee.id)">Delete</button></td>
        </tr>
        </tbody>
    </table>
    <!-- <div v-for="employee in employeearr" :key="employee.id">
        <EmployeeCard :employee="employee" :key="employee.id" v-on:employee-update="UpdatingEmployee" v-on:delete-emp="deleteEmp"/>
    </div> -->
</div>

    </AppLayout>
</template>
<style scoped>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th {
        /* background-color: #f5f5f5; */
        padding: 12px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid #ddd;
    }

    td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }

    tr:hover {
        background-color: #0862d8;
    }

    button {
        padding: 6px 12px;
        margin: 0 4px;
        border: none;
        border-radius: 4px;
        background-color: #007bff;
        color: white;
        cursor: pointer;
        font-size: 14px;
    }
    .danger-delete{
         padding: 6px 12px;
        margin: 0 4px;
        border: none;
        border-radius: 4px;
        background-color: #e84611;
        color: white;
        cursor: pointer;
        font-size: 14px;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>
