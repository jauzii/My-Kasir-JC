// Fungsi untuk Edit Barang
function editBarang(button) {
    const id = button.dataset.id;
    const namaProduk = button.dataset.nama;
    const kategori = button.dataset.kategori;
    const hargaBeli = button.dataset.hargaBeli;
    const hargaJual = button.dataset.hargaJual;
    const stok = button.dataset.stok;

    // Set nilai ke form input
    document.getElementById("NamaProduk").value = namaProduk;
    document.getElementById("Kategori").value = kategori;
    document.getElementById("HargaBeli").value = hargaBeli;
    document.getElementById("HargaJual").value = hargaJual;
    document.getElementById("Stok").value = stok;

    // Ubah title form
    document.getElementById("form-title").textContent = "Edit Barang";

    // Tampilkan tombol batal dan ubah action form
    document.getElementById("btnBatal").style.display = "inline-block";
    document.getElementById("btnSubmit").textContent = "Update Produk";

    // Ubah action form ke route update
    const form = document.getElementById("barangForm");
    form.action = `/barang/${id}`;
    form.method = "POST";

    // Tambahkan method PUT untuk update
    if (!form.querySelector('input[name="_method"]')) {
        const methodInput = document.createElement("input");
        methodInput.type = "hidden";
        methodInput.name = "_method";
        methodInput.value = "PUT";
        form.appendChild(methodInput);
    }

    // Scroll ke form
    document
        .querySelector(".custom-card")
        .scrollIntoView({ behavior: "smooth", block: "start" });
}

// Fungsi untuk Batal Edit
function batalEdit() {
    // Reset form
    document.getElementById("barangForm").reset();

    // Kembalikan ke mode tambah
    document.getElementById("form-title").textContent = "Tambah Barang Baru";
    document.getElementById("btnBatal").style.display = "none";
    document.getElementById("btnSubmit").textContent = "Simpan Produk";

    // Reset action form
    const form = document.getElementById("barangForm");
    form.action = "/barang/simpan";
    form.method = "POST";

    // Hapus method input jika ada
    const methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) {
        methodInput.remove();
    }
}

// Fungsi untuk Hapus Barang
function hapusBarang(button) {
    if (confirm("Yakin ingin menghapus produk ini?")) {
        const id = button.dataset.id;

        // Buat form untuk delete
        const form = document.createElement("form");
        form.method = "POST";
        form.action = `/barang/${id}`;

        // Tambahkan CSRF token
        const csrfToken =
            document.querySelector('meta[name="csrf-token"]')?.content ||
            document.querySelector('input[name="_token"]')?.value;
        if (csrfToken) {
            const tokenInput = document.createElement("input");
            tokenInput.type = "hidden";
            tokenInput.name = "_token";
            tokenInput.value = csrfToken;
            form.appendChild(tokenInput);
        }

        // Tambahkan method DELETE
        const methodInput = document.createElement("input");
        methodInput.type = "hidden";
        methodInput.name = "_method";
        methodInput.value = "DELETE";
        form.appendChild(methodInput);

        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
}
