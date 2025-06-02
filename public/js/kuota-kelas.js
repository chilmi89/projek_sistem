// public/js/kuota-kelas.js

class KuotaKelasManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupCSRF();
        this.bindEvents();
    }

    setupCSRF() {
        // Setup CSRF token untuk semua AJAX request
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
        }
    }

    bindEvents() {
        // Event listener untuk form tambah kuota
        const addForm = document.getElementById('addKuotaForm');
        if (addForm) {
            addForm.addEventListener('submit', (e) => this.handleAdd(e));
        }

        // Event listener untuk form edit kuota
        const editForm = document.getElementById('editKuotaForm');
        if (editForm) {
            editForm.addEventListener('submit', (e) => this.handleEdit(e));
        }
    }

    async handleAdd(e) {
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);

        try {
            const response = await this.apiCall('/hasil/kuota-kelas', 'POST', data);

            if (response.success) {
                this.showSuccess('Kuota kelas berhasil ditambahkan!');
                this.closeModal('addKuotaModal');
                this.resetForm('addKuotaForm');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                this.showError(response.message || 'Gagal menambah kuota kelas');
            }
        } catch (error) {
            this.handleError(error);
        }
    }

    async handleEdit(e) {
        e.preventDefault();

        const kode = document.getElementById('edit_kode').value;
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);

        try {
            const response = await this.apiCall(`/hasil/kuota-kelas/${kode}`, 'PUT', data);

            if (response.success) {
                this.showSuccess('Kuota kelas berhasil diperbarui!');
                this.closeModal('editKuotaModal');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                this.showError(response.message || 'Gagal memperbarui kuota kelas');
            }
        } catch (error) {
            this.handleError(error);
        }
    }

    async loadEditData(kode) {
        try {
            const response = await this.apiCall(`/hasil/kuota-kelas/${kode}/edit`, 'GET');

            // Populate form fields
            document.getElementById('edit_kode').value = response.kode;
            document.getElementById('edit_kode_display').value = response.kode;
            document.getElementById('edit_nama_kriteria').value = response.nama_kriteria;
            document.getElementById('edit_jumlah_kelas').value = response.jumlah_kelas;
            document.getElementById('edit_kapasitas_per_kelas').value = response.kapasitas_per_kelas;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editKuotaModal'));
            modal.show();

        } catch (error) {
            this.handleError(error);
        }
    }

    async confirmDelete(kode) {
        const result = await Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus kuota kelas ${kode}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        });

        if (result.isConfirmed) {
            await this.deleteKuota(kode);
        }
    }

    async deleteKuota(kode) {
        try {
            const response = await this.apiCall(`/hasil/kuota-kelas/${kode}`, 'DELETE');

            if (response.success) {
                this.showSuccess('Kuota kelas berhasil dihapus!');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                this.showError(response.message || 'Gagal menghapus kuota kelas');
            }
        } catch (error) {
            this.handleError(error);
        }
    }

    async confirmReset() {
        const result = await Swal.fire({
            title: 'Konfirmasi Reset',
            text: 'Apakah Anda yakin ingin mereset semua kuota kelas ke pengaturan default?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f39c12',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        });

        if (result.isConfirmed) {
            await this.resetToDefault();
        }
    }

    async resetToDefault() {
        try {
            const response = await this.apiCall('/hasil/kuota-kelas/reset', 'POST');

            if (response.success) {
                this.showSuccess('Kuota kelas berhasil direset ke pengaturan default!');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                this.showError(response.message || 'Gagal mereset kuota kelas');
            }
        } catch (error) {
            this.handleError(error);
        }
    }

    async apiCall(url, method, data = null) {
        const options = {
            method: method,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        };

        if (data && (method === 'POST' || method === 'PUT')) {
            options.body = JSON.stringify(data);
        }

        const response = await fetch(url, options);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    }

    showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: message,
            timer: 2000,
            showConfirmButton: false,
            position: 'top-end',
            toast: true
        });
    }

    showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: message,
            confirmButtonText: 'OK'
        });
    }

    handleError(error) {
        console.error('Error:', error);
        this.showError('Terjadi kesalahan sistem. Silakan coba lagi.');
    }

    closeModal(modalId) {
        const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
        if (modal) {
            modal.hide();
        }
    }
    
    resetForm(formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.reset();
        }
    }

    // Method untuk validasi form
    validateForm(formData) {
        const errors = [];

        if (!formData.kode || formData.kode.trim().length === 0) {
            errors.push('Kode kriteria harus diisi');
        }

        if (!formData.nama_kriteria || formData.nama_kriteria.trim().length === 0) {
            errors.push('Nama kriteria harus diisi');
        }

        if (!formData.jumlah_kelas || formData.jumlah_kelas < 1 || formData.jumlah_kelas > 10) {
            errors.push('Jumlah kelas harus antara 1-10');
        }

        if (!formData.kapasitas_per_kelas || formData.kapasitas_per_kelas < 1 || formData.kapasitas_per_kelas > 50) {
            errors.push('Kapasitas per kelas harus antara 1-50');
        }

        return errors;
    }

    // Method untuk mengecek duplikasi kode
    async checkDuplicateCode(kode) {
        try {
            const response = await this.apiCall(`/api/hasil/kuota-kelas/${kode}`, 'GET');
            return response.exists;
        } catch (error) {
            return false;
        }
    }
}

// Initialize ketika DOM ready
document.addEventListener('DOMContentLoaded', function() {
    window.kuotaManager = new KuotaKelasManager();
});

// Global functions untuk compatibility dengan onclick handlers
function editKuotaKelas(kode) {
    window.kuotaManager.loadEditData(kode);
}

function deleteKuotaKelas(kode) {
    window.kuotaManager.confirmDelete(kode);
}

function resetKuotaKelas() {
    window.kuotaManager.confirmReset();
}

// Auto refresh data setiap 5 menit
setInterval(function() {
    // Uncomment jika ingin auto refresh
    // console.log('Auto refreshing data...');
    // window.location.reload();
}, 300000); // 5 menit
