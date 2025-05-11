
// File: public/js/sweetalert-handler.js

// Show SweetAlert2 notifications for session messages
function showSessionMessages() {
    const success = document.querySelector('meta[name="success-message"]');
    const error = document.querySelector('meta[name="error-message"]');

    if (success) {
        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: success.content,
            timer: 3000,
            showConfirmButton: false,
        });
    }

    if (error) {
        Swal.fire({
            icon: "error",
            title: "Gagal!",
            text: error.content,
            timer: 3000,
            showConfirmButton: false,
        });
    }
}

// Add Data Form Submission Handler
function initAddFormHandler() {
    const addForm = document.getElementById("addDataForm");
    if (addForm) {
        addForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: "Simpan Data?",
                text: "Pastikan data yang dimasukkan sudah benar",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Simpan!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }
}

// Edit Forms Submission Handler
function initEditFormsHandler() {
    document.querySelectorAll(".edit-form").forEach((form) => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: "Update Data?",
                text: "Perubahan akan disimpan ke database",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Update!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
}

// Delete Forms Submission Handler
function initDeleteFormsHandler() {
    document.querySelectorAll(".delete-form").forEach((form) => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: "Hapus Data?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
}

// Initialize all handlers
document.addEventListener("DOMContentLoaded", function () {
    showSessionMessages();
    initAddFormHandler();
    initEditFormsHandler();
    initDeleteFormsHandler();
});
