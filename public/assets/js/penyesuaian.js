// Function to generate unique ID for penyesuaian
function generatePenyesuaianID() {
    const timestamp = new Date().getTime();
    return `PNY-${timestamp}`;
}

// Function to toggle stok tercatat visibility
function toggleStokTercatat(show) {
    const stokTercatatCells = document.querySelectorAll('.stok-tercatat');
    stokTercatatCells.forEach(cell => {
        cell.style.display = show ? 'table-cell' : 'none';
    });
}

// Function to save stok fisik
function saveStokFisik(id, value) {
    return fetch('/admin/penyesuaian/updatestok', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            id: id,
            stok_fisik: value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Stok fisik berhasil disimpan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
}