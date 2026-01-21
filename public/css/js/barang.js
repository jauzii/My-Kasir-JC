function editBarang(id, nama, kategori, hargaBeli, hargaJual, stok) {
    // Ubah judul form
    document.getElementById("form-title").textContent = "Edit Barang";

    // Ubah action form
    document.getElementById("barangForm").action = "/barang/" + id;

    // Tambahkan input hidden untuk method PUT
    let methodInput = document.getElementById("methodInput");
    if (!methodInput) {
        methodInput = document.createElement("input");
        methodInput.type = "hidden";
        methodInput.name = "_method";
        methodInput.value = "PUT";
        methodInput.id = "methodInput";
        document.getElementById("barangForm").appendChild(methodInput);
    } else {
        methodInput.value = "PUT";
    }

    // Isi form dengan data yang akan diedit
    document.getElementById("NamaProduk").value = nama;
    document.getElementById("Kategori").value = kategori;
    document.getElementById("HargaBeli").value = hargaBeli;
    document.getElementById("HargaJual").value = hargaJual;
    document.getElementById("Stok").value = stok;

    // Ubah teks tombol submit
    document.getElementById("btnSubmit").textContent = "Update Produk";

    // Tampilkan tombol batal
    document.getElementById("btnBatal").style.display = "inline-block";

    // Scroll ke form
    window.scrollTo({ top: 0, behavior: "smooth" });
}

function batalEdit() {
    // Reset form
    document.getElementById("barangForm").reset();
    document.getElementById("barangForm").action = barangStoreRoute;

    // Hapus method PUT
    let methodInput = document.getElementById("methodInput");
    if (methodInput) {
        methodInput.remove();
    }

    // Reset tampilan
    document.getElementById("form-title").textContent = "Tambah Barang Baru";
    document.getElementById("btnSubmit").textContent = "Simpan Produk";
    document.getElementById("btnBatal").style.display = "none";
}
