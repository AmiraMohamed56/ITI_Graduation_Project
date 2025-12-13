@extends('layouts.admin')
@section('title', 'create')
@section('breadcrumb')
    <a href="{{ route('admin.payments.index') }}" class="hover:underline">Payments</a> / <span>Create Payment</span>
@endsection

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <x-admin.alert type="error" :message="$error" />
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <x-admin.alert type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-admin.alert type="error" :message="session('error')" />
@endif

<x-admin.card>
    <h2 class="text-lg font-semibold mb-6">Create New Payment</h2>

    <form id="paymentForm" action="{{ route('admin.payments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <x-admin.input type="number" label="Appointment_id" name="appointment_id" value="{{ $appointment->id }}" readonly />
        <x-admin.input type="text" label="" name="patient_id" value="{{ $appointment->patient_id }}" hidden />
        <x-admin.input type="text" label="Patient" name="fake" value="{{ $appointment->patient->user->name }}" readonly />
        <x-admin.input type="number" label="Price" name="amount" id="amount" required />

        <div class="flex items-center justify-start gap-10">
            <div>
                <label for="status" class="block text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" id="status" class="w-80 px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                    <option value="">select status</option>
                    <option value="paid">Paid</option>
                    <option value="refunded">Refunded</option>
                    <option value="failed">Failed</option>
                </select>
            </div>

            <div>
                <label for="method" class="block text-gray-700 dark:text-gray-300 mb-1">Method</label>
                <select name="method" id="method" class="w-80 px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400" required>
                    <option value="">select method</option>
                    <option value="cash">Cash</option>
                    <option value="wallet">Wallet</option>
                    <option value="card">Card</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>
        </div>

        <!-- Payment Proof Upload (Initially Hidden) -->
        <div id="paymentProofSection" class="hidden">
            <label for="payment_proof" class="block text-gray-700 dark:text-gray-300 mb-1">
                Payment Proof <span class="text-red-500">*</span>
            </label>
            <div class="flex items-center gap-4">
                <input
                    type="file"
                    name="payment_proof"
                    id="payment_proof"
                    accept="image/jpeg,image/png,image/jpg,application/pdf"
                    class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                >
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Upload payment screenshot or receipt (JPEG, PNG, JPG, PDF - Max 5MB)
            </p>
            <!-- Preview -->
            <div id="imagePreview" class="mt-4 hidden">
                <img id="previewImg" src="" alt="Payment Proof Preview" class="max-w-md rounded border border-gray-300 dark:border-gray-600">
            </div>
        </div>

        <div class="flex items-center justify-end gap-6">
            <button type="button" onclick="window.history.back()" class="px-4 py-2 text-sm inline-flex items-center justify-center font-medium rounded-xl transition-all duration-200 select-none focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed bg-gray-200 text-gray-800 hover:bg-gray-300 active:bg-gray-400 focus:ring-gray-400 shadow-sm hover:shadow drop-shadow">
                Cancel
            </button>
            <x-admin.button type="primary" id="submitBtn">Create Payment</x-admin.button>
        </div>
    </form>
</x-admin.card>

<!-- Payment Proof Modal -->
<div id="paymentProofModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Upload Payment Proof</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            Please upload a screenshot or receipt of the payment transaction.
        </p>

        <div class="mb-6">
            <label for="modalPaymentProof" class="block text-gray-700 dark:text-gray-300 mb-2">
                Payment Proof <span class="text-red-500">*</span>
            </label>
            <input
                type="file"
                id="modalPaymentProof"
                accept="image/jpeg,image/png,image/jpg,application/pdf"
                class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
            >
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                JPEG, PNG, JPG, or PDF (Max 5MB)
            </p>
        </div>

        <!-- Modal Preview -->
        <div id="modalImagePreview" class="mb-6 hidden">
            <img id="modalPreviewImg" src="" alt="Payment Proof Preview" class="w-full rounded border border-gray-300 dark:border-gray-600">
        </div>

        <div class="flex justify-end gap-4">
            <button
                type="button"
                id="cancelModal"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                Cancel
            </button>
            <button
                type="button"
                id="confirmModal"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                Confirm & Submit
            </button>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentForm');
    const methodSelect = document.getElementById('method');
    const statusSelect = document.getElementById('status');
    const paymentProofSection = document.getElementById('paymentProofSection');
    const paymentProofInput = document.getElementById('payment_proof');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    // Modal elements
    const modal = document.getElementById('paymentProofModal');
    const modalPaymentProof = document.getElementById('modalPaymentProof');
    const modalImagePreview = document.getElementById('modalImagePreview');
    const modalPreviewImg = document.getElementById('modalPreviewImg');
    const cancelModal = document.getElementById('cancelModal');
    const confirmModal = document.getElementById('confirmModal');

    // Show/hide payment proof section based on method
    methodSelect.addEventListener('change', function() {
        const method = this.value;

        if (method === 'wallet' || method === 'card') {
            paymentProofSection.classList.remove('hidden');
            paymentProofInput.setAttribute('required', 'required');
        } else {
            paymentProofSection.classList.add('hidden');
            paymentProofInput.removeAttribute('required');
            paymentProofInput.value = '';
            imagePreview.classList.add('hidden');
        }
    });

    // Image preview for main form
    paymentProofInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('hidden');
        }
    });

    // Image preview for modal
    modalPaymentProof.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                modalPreviewImg.src = e.target.result;
                modalImagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
            confirmModal.disabled = false;
        } else if (file) {
            modalImagePreview.classList.add('hidden');
            confirmModal.disabled = false;
        } else {
            modalImagePreview.classList.add('hidden');
            confirmModal.disabled = true;
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const method = methodSelect.value;
        const status = statusSelect.value;
        const amount = document.getElementById('amount').value;

        // Validation
        if (!method || !status || !amount) {
            alert('Please fill in all required fields');
            return;
        }

        // For cash payments, submit directly
        if (method === 'cash') {
            form.submit();
            return;
        }

        // For wallet/card, show modal if no file selected
        if ((method === 'wallet' || method === 'card') && !paymentProofInput.files[0]) {
            modal.classList.remove('hidden');
            return;
        }

        // For PayPal, redirect to PayPal
        if (method === 'paypal') {
            const paypalForm = document.createElement('form');
            paypalForm.method = 'POST';
            paypalForm.action = '{{ route("paypal.payment") }}';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            paypalForm.appendChild(csrfInput);

            const appointmentInput = document.createElement('input');
            appointmentInput.type = 'hidden';
            appointmentInput.name = 'appointment_id';
            appointmentInput.value = document.querySelector('input[name="appointment_id"]').value;
            paypalForm.appendChild(appointmentInput);

            const amountInput = document.createElement('input');
            amountInput.type = 'hidden';
            amountInput.name = 'amount';
            amountInput.value = amount;
            paypalForm.appendChild(amountInput);

            document.body.appendChild(paypalForm);
            paypalForm.submit();
            return;
        }

        // Submit form for other cases
        form.submit();
    });

    // Modal cancel
    cancelModal.addEventListener('click', function() {
        modal.classList.add('hidden');
        modalPaymentProof.value = '';
        modalImagePreview.classList.add('hidden');
        confirmModal.disabled = true;
    });

    // Modal confirm
    confirmModal.addEventListener('click', function() {
        // Transfer file from modal to main form
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(modalPaymentProof.files[0]);
        paymentProofInput.files = dataTransfer.files;

        // Show preview in main form
        if (modalPaymentProof.files[0].type.startsWith('image/')) {
            previewImg.src = modalPreviewImg.src;
            imagePreview.classList.remove('hidden');
        }

        modal.classList.add('hidden');
        form.submit();
    });
});
</script>
@endsection
@endsection
