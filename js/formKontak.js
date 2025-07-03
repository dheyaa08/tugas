document.getElementById("formKontak").addEventListener("submit", function(event) {
  event.preventDefault();
  const form = event.target;
  const nama = form.nama.value.trim();
  const email = form.email.value.trim();
  const pesan = form.pesan.value.trim();
  const status = document.getElementById("status");

  if (!nama || !email || !pesan) {
    status.textContent = "Semua kolom wajib diisi.";
    status.style.color = "red"; // 🛠️ warna harus dalam string
    return;
  }

  status.textContent = "Mengirim...";
  status.style.color = "black";

  setTimeout(() => {
    status.textContent = "Pesan berhasil dikirim. Terima kasih!";
    status.style.color = "green";
    form.reset();
  }, 1500);
});
