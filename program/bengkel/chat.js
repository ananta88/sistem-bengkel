function toggleChat() {
    const chatPopup = document.getElementById('chatPopup');
    const chatIcon = document.querySelector('.chat-icon');

    // Periksa apakah chat sedang terbuka atau tidak
    if (chatPopup.style.display === 'flex') {
        chatPopup.style.display = 'none'; // Tutup chat
        chatIcon.classList.remove('hidden'); // Tampilkan ikon chat
    } else {
        chatPopup.style.display = 'flex'; // Tampilkan chat
        chatIcon.classList.add('hidden'); // Sembunyikan ikon chat
    }
}

function sendMessage() {
    const userInput = document.getElementById('userInput');
    const chatBody = document.getElementById('chatBody');

    if (userInput.value.trim() !== "") {
        // Tambahkan pesan pengguna ke antarmuka chat
        const userMessage = document.createElement('div');
        userMessage.classList.add('message', 'user');
        userMessage.innerText = userInput.value;
        chatBody.appendChild(userMessage);

        // Balasan chatbot sederhana
        setTimeout(() => {
            const botMessage = document.createElement('div');
            botMessage.classList.add('message', 'bot');
            botMessage.innerText = "Terima kasih atas informasinya. Untuk bantuan lebih lanjut, Anda dapat mengklik 'Lanjutkan di WhatsApp'.";
            chatBody.appendChild(botMessage);

            chatBody.scrollTop = chatBody.scrollHeight; // Scroll otomatis ke pesan terakhir
        }, 1000);

        userInput.value = ""; // Kosongkan input
        chatBody.scrollTop = chatBody.scrollHeight; // Scroll otomatis ke pesan terakhir
    }
}

function continueOnWhatsApp() {
    const phoneNumber = "62895379229956"; // Ganti dengan nomor WhatsApp Anda
    const message = "Halo, saya ingin melanjutkan konsultasi terkait layanan perbaikan mobil.";
    window.open(`https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`, '_blank');
}