<template>
    <div class="employee-card">
        <!-- Header -->
        <div class="card-header">
            <div class="header-left">
                <button class="btn btn-primary" @click="updateemp(employee)">Update</button>
                <button class="btn btn-danger" @click="deleteemp(employee.id)">Delete</button>
            </div>
            <div class="header-right">
                <span class="timestamp">{{ datetime.toLocaleDateString()  }} {{ datetime.toLocaleTimeString() }}</span>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card-main">
            <div class="employee-info">
                <div class="info-row">
                    <span class="label">Name:</span>
                    <span class="value">{{ employee.fname }} {{ employee.lname }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Position:</span>
                    <span class="value">{{ employee.position }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Date of Birth:</span>
                    <span class="value">{{ employee.dob }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {ref} from 'vue'

const emit= defineEmits<{ (e: 'employee-update', employee: any): void,(e: 'delete-emp', id: number): void }>()
const props=defineProps({
    employee:{
        type: Object,
        default: () => ({
            fname: '',
            lname: '',
            dob: '',
            position: '',
            created_at:''
        })
    }
})
const datetime= ref( new Date(props.employee.created_at))

function updateemp(employee:any)
{
    emit('employee-update',employee)
}
function deleteemp(id:number)
{
    if (confirm('Are you sure you want to delete this employee?')) {
    emit('delete-emp', id)
  }

}

</script>

<style scoped>
.employee-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(#f5f5f5);
    background-color: #f5f5f5;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;

}

.header-left {
    display: flex;
    gap: 8px;

}



.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.timestamp {
    font-size: 12px;
    color: #333;
}

.card-main {
    padding: 24px;
    text-align: center;
}

.employee-info {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.info-row {
    display: flex;
    justify-content: center;
    gap: 12px;
}

.label {
    font-weight: bold;
    color: #333;
}

.value {
    color: #666;
}
</style>
