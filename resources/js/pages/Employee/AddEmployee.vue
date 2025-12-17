<template>
    <div class="container">
        <button @click="isModalOpen = true" class="btn-add">
            Add Employee
        </button>

        <div v-if="isModalOpen" class="modal-overlay" @click="closeModal">
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

                    <!-- <div class="form-group">
                        <label for="position">Position *</label>
                        <input
                            id="file"
                            @change="handleFileChange"
                            type="file"
                            required
                            placeholder="Browse files"
                        />
                    </div> -->

                    <div class="form-group">
    <label>Signature *</label>

    <canvas
        ref="signatureCanvas"
        width="400"
        height="150"
        class="signature-canvas"
    ></canvas>

    <button
        type="button"
        class="btn-cancel"
        style="margin-top: 10px"
        @click="clearSignature"
    >
        Clear Signature
    </button>
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
import { ref,onMounted,nextTick,watch} from 'vue'
import { useForm } from '@inertiajs/vue3'
import {store} from '@/actions/App/Http/Controllers/EmployeeController'
import SignaturePad from 'signature_pad'
import jsPDF from 'jspdf'
const isModalOpen = ref(false)
const signatureCanvas = ref<HTMLCanvasElement | null>(null)
let signaturePad: SignaturePad

const emit=defineEmits<{ (e: 'employee-added', employee: any): void }>()
const form = useForm({
    fname: '',
    lname: '',
    dob: '',
    position: '',
    created_at: new Date().toLocaleString('sv-SE').replace(' ', ' '),

     pdf: null as File | null
})

const closeModal = () => {
    isModalOpen.value = false
    form.fname = ''
    form.lname = ''
    form.dob = ''
    form.position = ''

}

// function handleFileChange(event:any)  {
//   // Access the FileList object
//   const files = event.target.files;
//   if (files.length > 0) {
//     // Store the first file object in your data property
//     form.files = files[0];
//   }
// };

// onMounted(() => {
//     if (signatureCanvas.value) {
//         signaturePad = new SignaturePad(signatureCanvas.value,{
//     minWidth: 5,
//     maxWidth: 10,
//     penColor: "rgb(66, 133, 244)"
// })
//     }
// })
watch(isModalOpen, async (open) => {
    if (open) {
        await nextTick() // wait for DOM
        if (!signatureCanvas.value) return

        signaturePad = new SignaturePad(signatureCanvas.value, {
            penColor: 'black',
            backgroundColor: 'white'
        })
    }
})
const clearSignature = () => {
    signaturePad?.clear()
}
function submitForm() {


     if (signaturePad.isEmpty()) {
        alert('Please provide a signature')
        return
    }

    // 1️⃣ Create PDF
    const pdf = new jsPDF()

    pdf.setFontSize(14)
    pdf.text('Employee Information', 20, 20)

    pdf.setFontSize(11)
    pdf.text(`First Name: ${form.fname}`, 20, 40)
    pdf.text(`Last Name: ${form.lname}`, 20, 50)
    pdf.text(`DOB: ${form.dob}`, 20, 60)
    pdf.text(`Position: ${form.position}`, 20, 70)

    // 2️⃣ Add signature image
    const signatureImage = signaturePad.toDataURL('image/png')
    pdf.text('Signature:', 20, 90)
    pdf.addImage(signatureImage, 'PNG', 20, 95, 160, 40)

    // 3️⃣ Convert PDF to File
    const pdfBlob = pdf.output('blob')
    const pdfFile = new File([pdfBlob], 'employee-signature.pdf', {
        type: 'application/pdf'
    })

    form.pdf = pdfFile



   form.post(store().url, {
   onSuccess: ({ }) => {
    console.log('Employee successfully submitted:', { ...form })
  emit('employee-added', form.data())
    closeModal() // ✅ reset only after success
     isModalOpen.value = false

},
    onError: (errors) => {
      console.error('Validation errors:', errors)
    }
  })
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

.signature-canvas {
    border: 2px solid black;
    border-radius: 4px;
    width: 100%;
    touch-action: none;
}
</style>
