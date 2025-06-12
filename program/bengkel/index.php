<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Perbaikan Mobil Kanaya Garage</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <img src="assets/logobmw.png" alt="Logo" class="logo">
            <h1 class="service-name">Layanan Perbaikan Mobil Kanaya Garage</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="layanan-detail.php">Layanan</a></li>
                    <li><a href="#testimonials">Testimoni</a></li>
                    <li><a href="#contact">Kontak</a></li>
                    <li><a href="login.php">Login/Daftar</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container">
            <img src="assets/ilustrasibmw.jpg" alt="Ilustrasi Mobil" class="hero-image">
            <div class="hero-content">
                <h2>Solusi Perbaikan Mobil Anda, Mudah dan Cepat!</h2>
                <p>Kami menyediakan layanan perbaikan mobil dengan teknisi profesional dan berpengalaman. Hubungi kami sekarang untuk memperbaiki kendaraan Anda.</p>
                <a href="form-pemesanan.php" class="btn">Pemesanan Layanan</a>
                <a href="#contact" class="btn secondary">Hubungi Kami</a>
            </div>
        </div>
    </section>

    <!-- Keunggulan Layanan -->
    <section id="services" class="features">
        <div class="container">
            <h2>Keunggulan Layanan Kami</h2>
            <div class="features-list">
                <div class="feature-item">
                    <h3>Perbaikan Cepat</h3>
                    <p>Kami memastikan proses perbaikan kendaraan Anda dilakukan dengan cepat dan tepat waktu.</p>
                </div>
                <div class="feature-item">
                    <h3>Teknisi Terpercaya</h3>
                    <p>Tim kami terdiri dari teknisi berpengalaman yang siap memberikan layanan terbaik untuk Anda.</p>
                </div>
                <div class="feature-item">
                    <h3>Dukungan 24/7</h3>
                    <p>Layanan dukungan pelanggan kami tersedia 24 jam sehari, 7 hari seminggu untuk menjawab semua pertanyaan Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Cara Kerja -->
    <section id="how-it-works" class="how-it-works">
        <div class="container">
            <h2>Cara Kerja</h2>
            <ol>
                <li><strong>Kirim Permintaan:</strong> Isi formulir permintaan perbaikan melalui website kami atau hubungi kami langsung.</li>
                <li><strong>Konsultasi:</strong> Tim kami akan menghubungi Anda untuk melakukan konsultasi mengenai masalah kendaraan Anda.</li>
                <li><strong>Perbaikan Selesai:</strong> Kendaraan Anda akan segera diperbaiki dan siap digunakan kembali.</li>
            </ol>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <p>&copy; 2024 Layanan Perbaikan Mobil. Semua Hak Dilindungi.</p>
            <p>Hubungi kami di: <a href="mailto:info@layananmobil.com">info@layananmobil.com</a> | Telepon: 62895379229956</p>
        </div>
    </footer>

    <script>
        function sendToWhatsApp() {
            const merk = document.getElementById('merk').value;
            const model = document.getElementById('model').value;
            const tahun = document.getElementById('tahun').value;
            const masalah = document.getElementById('masalah').value;
            const jadwal = document.getElementById('jadwal').value;

            // Format pesan yang akan dikirim ke WhatsApp
            const message = `Halo, saya ingin memesan layanan perbaikan mobil.%0A
                Merk: ${merk}%0A
                Model: ${model}%0A
                Tahun: ${tahun}%0A
                Masalah: ${masalah}%0A
                Jadwal: ${jadwal}%0A
                Terima kasih.`;

            // Nomor WhatsApp yang dituju (ganti dengan nomor Anda)
            const phoneNumber = "62895379229956";

            // Mengarahkan ke WhatsApp
            window.open(`https://wa.me/${phoneNumber}?text=${message}`, '_blank');
        }
    </script>

     <!-- Pop-up Chat -->
    <div class="chat-popup" id="chatPopup">
        <div class="chat-header">
            <h4>Chat Kami</h4>
            <button class="close-btn" onclick="toggleChat()">×</button>
        </div>
        <div class="chat-body" id="chatBody">
            <div class="message bot">Halo! Apa yang bisa kami bantu terkait mobil Anda?</div>
        </div>
        <div class="chat-input">
            <div class="input-row">
                <input type="text" id="userInput" placeholder="Ketik pesan Anda...">
                <button onclick="sendMessage()">Kirim</button>
            </div>
            <button class="whatsapp-btn" onclick="continueOnWhatsApp()">Lanjutkan di WhatsApp</button>
        </div>
    </div>
    <div class="chat-icon" onclick="toggleChat()">
        <img src="assets/chat.png" alt="Chat">
    </div>

    <script src="chat.js"></script>
</body>
</html>