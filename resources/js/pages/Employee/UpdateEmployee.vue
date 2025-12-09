<template>
    <div class="container">

        <div v-if="props.openmodal" class="modal-overlay" @click="closeModal">
            <div class="modal-content" @click.stop>
                <h2>Add New Employee</h2>
                <form @submit.prevent="submitForm">
                    <div class="form-group">
                        <label for="firstName">First Name *</label>
                        <input
                            id="firstName"
                            v-model="form.fname"
                            type="text"
                            required
                            placeholder="Enter first name"
                        />
                    </div>

                    <div class="form-group">
                        <label for="lastName">Last Name *</label>
                        <input
                            id="lastName"
                            v-model="form.lname"
                            type="text"
                            required
                            placeholder="Enter last name"
                        />
                    </div>

                    <div class="form-group">
                        <label for="dob">Date of Birth *</label>
                        <input
                            id="dob"
                            v-model="form.dob"
                            type="date"
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label for="position">Position *</label>
                        <input
                            id="position"
                            v-model="form.position"
                            type="text"
                            required
                            placeholder="Enter position"
                        />
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Submit</button>
                        <button type="button" class="btn-cancel" @click="closeModal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { update } from "@/actions/App/Http/Controllers/EmployeeController";

const props = defineProps({
  employee: Object,
  openmodal: Boolean,
});

const emit = defineEmits<{ (e: "empupdate", emp: any): void, (e:"closeupmodal"):void }>();

// Create the form (empty at first)
const form = useForm({
  fname: "",
  lname: "",
  dob: "",
  position: "",
  id:''
});

// 🔥 Watch for new employee passed from parent
watch(
  () => props.employee,
  (newEmp) => {
    if (!newEmp) return;
    form.fname = newEmp.fname;
    form.lname = newEmp.lname;
    form.dob = newEmp.dob;
    form.position = newEmp.position;
    form.id= newEmp.id
  },
  { immediate: true }
);

const closeModal = () => {
  emit("closeupmodal"); // optional
};

function submitForm() {
  form.put(update(form.id).url, {
    onSuccess: () => {
      emit("empupdate", form.data());
    },
  });
}
</script>

<style scoped>
.container {
    padding: 20px;
}

.btn-add {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.btn-add:hover {
    background-color: #0056b3;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 90%;
    max-width: 500px;
}

.modal-content h2 {
    color: black;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    color: black;
    margin-bottom: 5px;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 2px solid black;
    border-radius: 4px;
    font-size: 14px;
    color: black;
    box-sizing: border-box;
}

.form-group input:focus {
    outline: none;
    border-color: #0056b3;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 25px;
}

.btn-submit {
    flex: 1;
    padding: 10px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.btn-submit:hover {
    background-color: #218838;
}

.btn-cancel {
    flex: 1;
    padding: 10px;
    background-color: #6c757d;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.btn-cancel:hover {
    background-color: #5a6268;
}
</style>
