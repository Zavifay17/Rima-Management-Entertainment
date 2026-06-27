<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rima Entertainment (RME) - Jasa penyewaan alat event premium terlengkap di Indonesia. Sound system konser, lighting panggung, LED videotron, & rigging berkualitas tinggi untuk event spektakuler Anda.">
    <meta name="keywords" content="rima entertainment, penyewaan alat event, sewa sound system, sewa lighting konser, sewa led videotron, sewa panggung, rental event organizer">
    <meta name="author" content="Rima Entertainment">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rima Entertainment | Premium Event Equipment Rental</title>
    
    <!-- Google Fonts: Outfit (Heading) & Inter (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <!-- Space Glow Elements -->
    <div class="glow-orb orb-1"></div>
    <div class="glow-orb orb-2"></div>
    <div class="glow-orb orb-3"></div>

    <!-- Header & Navigasi -->
    <header class="main-header" id="header">
        <div class="container header-container">
            <a href="#" class="logo">
                <span class="logo-text">RIMA</span>
                <span class="logo-subtext">ENTERTAINMENT</span>
            </a>
            
            <nav class="nav-menu" id="navMenu">
                <a href="#hero" class="nav-link active">Home</a>
                <a href="#categories" class="nav-link">Layanan</a>
                <a href="#catalog" class="nav-link">Katalog & Paket</a>
                <a href="#how-it-works" class="nav-link">Cara Sewa</a>
                <a href="#testimonials" class="nav-link">Ulasan</a>
                <a href="#rent-form-section" class="btn btn-secondary nav-btn-mobile">Sewa Sekarang</a>
            </nav>
            
            <div class="header-actions">
                <a href="#rent-form-section" class="btn btn-primary nav-btn-desktop">Sewa Sekarang</a>
                <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle Menu">
                    <i data-lucide="menu" id="menuIcon"></i>
                </button>
            </div>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section id="hero" class="hero-section">
            <div class="container hero-container">
                <div class="hero-content">
                    <span class="badge">PRO EVENT EQUIPMENT RENTAL</span>
                    <h1 class="hero-title">Mewujudkan Event Spektakuler Anda Menjadi <span class="gradient-text">Nyata</span></h1>
                    <p class="hero-desc">Penyewaan sound system profesional, lighting panggung megah, LED screen ultra-jernih, dan rigging panggung modular berkelas premium untuk segala jenis event Anda.</p>
                    <div class="hero-actions">
                        <a href="#catalog" class="btn btn-primary btn-lg">Jelajahi Paket</a>
                        <a href="#rent-form-section" class="btn btn-outline btn-lg">Sewa Alat Sekarang</a>
                    </div>
                    
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-num">500+</span>
                            <span class="stat-label">Event Sukses</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-num">99%</span>
                            <span class="stat-label">Klien Puas</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-num">24/7</span>
                            <span class="stat-label">Support Teknis</span>
                        </div>
                    </div>
                </div>
                
                <div class="hero-visual">
                    <div class="visual-card glassmorphism">
                        <div class="card-glow"></div>
                        <div class="visual-header">
                            <div class="window-dots">
                                <span></span><span></span><span></span>
                            </div>
                            <span class="visual-tag"><i data-lucide="zap" class="tag-icon"></i> Live Status</span>
                        </div>
                        <div class="visual-body">
                            <h3>RME Concert Rig</h3>
                            <p>Premium Stage Lighting & Sound System</p>
                            <div class="spec-list">
                                <div class="spec-item"><i data-lucide="check-circle" class="spec-icon"></i> Sound System Paket 20000W</div>
                                <div class="spec-item"><i data-lucide="check-circle" class="spec-icon"></i> Lighting Panggung Paket Mewah</div>
                                <div class="spec-item"><i data-lucide="check-circle" class="spec-icon"></i> Panggung Modular 10x8m</div>
                            </div>
                            <div class="visual-price">
                                <span class="price-label">Mulai dari</span>
                                <span class="price-val">Rp 15.000.000 <small>/hari</small></span>
                            </div>
                            <button onclick="selectDirectPackage('concert-mega')" class="btn btn-primary btn-block">Pilih Paket Ini</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Kategori Layanan -->
        <section id="categories" class="categories-section">
            <div class="container">
                <div class="section-header text-center">
                    <span class="badge">LAYANAN KAMI</span>
                    <h2 class="section-title">Pilihan Alat Event Terlengkap</h2>
                    <p class="section-desc">Rima Entertainment menyediakan armada peralatan terlengkap dengan standar industri hiburan profesional.</p>
                </div>
                
                <div class="categories-grid">
                    <div class="category-card glassmorphism">
                        <div class="category-icon-wrapper sound">
                            <i data-lucide="volume-2" class="category-icon"></i>
                        </div>
                        <h3>Professional Sound System</h3>
                        <p>Line Array speaker berteknologi tinggi, digital audio mixer, dan wireless microphone premium untuk kejernihan suara tanpa batas.</p>
                    </div>
                    
                    <div class="category-card glassmorphism">
                        <div class="category-icon-wrapper light">
                            <i data-lucide="lightbulb" class="category-icon"></i>
                        </div>
                        <h3>Stage & Concert Lighting</h3>
                        <p>Kombinasi dinamis moving beam, par LED wash, lasers, follow spot, dan hazer/smoke machine untuk memukau visual mata penonton.</p>
                    </div>
                    
                    <div class="category-card glassmorphism">
                        <div class="category-icon-wrapper stage">
                            <i data-lucide="layers" class="category-icon"></i>
                        </div>
                        <h3>Stage & Rigging System</h3>
                        <p>Desain panggung modular berstandar keamanan tinggi, rigging kokoh, barikade penonton, dan tenda roder berskala besar.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Katalog & Paket Populer -->
        <section id="catalog" class="catalog-section">
            <div class="container">
                <div class="section-header text-center">
                    <span class="badge">KATALOG PAKET</span>
                    <h2 class="section-title">Paket Event Terpopuler</h2>
                    <p class="section-desc">Pilih paket terbaik yang kami rancang khusus untuk kemudahan dan efisiensi anggaran event Anda.</p>
                </div>
                
                <!-- Tab Navigation -->
                <div class="catalog-tabs">
                    <button class="tab-btn active" data-tab="all">Semua Paket</button>
                    <button class="tab-btn" data-tab="sound">Sound System</button>
                    <button class="tab-btn" data-tab="lighting">Lighting</button>
                    <button class="tab-btn" data-tab="stage">Panggung Modular</button>
                </div>
                
                <!-- Catalog Grid -->
                <div class="catalog-grid" id="catalogGrid">
                    <!-- Item 1: Sound 5000W -->
                    <div class="catalog-item glassmorphism" data-category="sound">
                        <div class="catalog-header">
                            <span class="item-tag tag-sound">Sound</span>
                            <h3>Sound System Paket 5000W</h3>
                        </div>
                        <div class="catalog-body">
                            <p class="item-desc">Sangat cocok untuk seminar, syukuran, pernikahan kecil, pameran booth, dan gathering kantor skala menengah.</p>
                            <ul class="item-features">
                                <li><i data-lucide="check" class="feature-icon"></i> 4 Unit Speaker Huper Aktif</li>
                                <li><i data-lucide="check" class="feature-icon"></i> 2 Unit Monitor Huper Aktif</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Mixer Digital 16 Channel</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Set Microphone Wireless Premium</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Setup & Operator standby</li>
                            </ul>
                            <div class="item-price-wrapper">
                                <span class="price-val">Rp 2.000.000 <small>/hari</small></span>
                            </div>
                            <button onclick="selectDirectPackage('sound-5000w')" class="btn btn-outline btn-block select-pkg-btn" data-id="sound-5000w">Pilih Paket</button>
                        </div>
                    </div>
                    
                    <!-- Item 2: Sound 10000W -->
                    <div class="catalog-item glassmorphism highlighted" data-category="sound">
                        <div class="item-popular-badge">Paling Populer</div>
                        <div class="catalog-header">
                            <span class="item-tag tag-sound">Sound</span>
                            <h3>Sound System Paket 10000W</h3>
                        </div>
                        <div class="catalog-body">
                            <p class="item-desc">Paket profesional untuk mini konser, festival musik sekolah, gathering besar, dan gathering outdoor skala menengah.</p>
                            <ul class="item-features">
                                <li><i data-lucide="check" class="feature-icon"></i> FOH 12" x 4 Box</li>
                                <li><i data-lucide="check" class="feature-icon"></i> FOH 15" x 4 Box</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Subwoofer 18" x 4 Box</li>
                                <li><i data-lucide="check" class="feature-icon"></i> 4 Unit Monitor Aktif</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Crew & Sound Engineer Profesional</li>
                            </ul>
                            <div class="item-price-wrapper">
                                <span class="price-val">Rp 3.000.000 <small>/hari</small></span>
                            </div>
                            <button onclick="selectDirectPackage('sound-10000w')" class="btn btn-primary btn-block select-pkg-btn" data-id="sound-10000w">Pilih Paket</button>
                        </div>
                    </div>

                    <!-- Item 3: Sound 20000W -->
                    <div class="catalog-item glassmorphism" data-category="sound">
                        <div class="catalog-header">
                            <span class="item-tag tag-sound">Sound</span>
                            <h3>Sound System Paket 20000W</h3>
                        </div>
                        <div class="catalog-body">
                            <p class="item-desc">Spesifikasi konser outdoor masif, festival musik besar, dan panggung utama bertaraf nasional.</p>
                            <ul class="item-features">
                                <li><i data-lucide="check" class="feature-icon"></i> FOH 12" x 8 Box</li>
                                <li><i data-lucide="check" class="feature-icon"></i> FOH 15" x 8 Box</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Subwoofer 18" x 4 Box</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Power Management System</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Sound Designer & Full Crew RME</li>
                            </ul>
                            <div class="item-price-wrapper">
                                <span class="price-val">Rp 4.500.000 <small>/hari</small></span>
                            </div>
                            <button onclick="selectDirectPackage('sound-20000w')" class="btn btn-outline btn-block select-pkg-btn" data-id="sound-20000w">Pilih Paket</button>
                        </div>
                    </div>

                    <!-- Item 4: Lighting Hemat -->
                    <div class="catalog-item glassmorphism" data-category="lighting">
                        <div class="catalog-header">
                            <span class="item-tag tag-lighting">Lighting</span>
                            <h3>Lighting Paket Hemat</h3>
                        </div>
                        <div class="catalog-body">
                            <p class="item-desc">Menghadirkan suasana mewah & romantis untuk resepsi pernikahan, gala dinner, atau peluncuran produk sederhana.</p>
                            <ul class="item-features">
                                <li><i data-lucide="check" class="feature-icon"></i> 5 Unit Moving Beam</li>
                                <li><i data-lucide="check" class="feature-icon"></i> 2 Box Par LED (8 Unit)</li>
                                <li><i data-lucide="check" class="feature-icon"></i> 3 Unit Lampu Fresnel</li>
                                <li><i data-lucide="check" class="feature-icon"></i> 1 Unit Mesin Asap (Smoke Machine)</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Operator & Kabel instalasi</li>
                            </ul>
                            <div class="item-price-wrapper">
                                <span class="price-val">Rp 2.000.000 <small>/hari</small></span>
                            </div>
                            <button onclick="selectDirectPackage('light-hemat')" class="btn btn-outline btn-block select-pkg-btn" data-id="light-hemat">Pilih Paket</button>
                        </div>
                    </div>

                    <!-- Item 5: Lighting Menengah -->
                    <div class="catalog-item glassmorphism highlighted" data-category="lighting">
                        <div class="item-popular-badge">Lighting Terlaris</div>
                        <div class="catalog-header">
                            <span class="item-tag tag-lighting">Lighting</span>
                            <h3>Lighting Paket Menengah</h3>
                        </div>
                        <div class="catalog-body">
                            <p class="item-desc">Kombinasi optimal untuk panggung gathering megah, konser musik mini, dan dekorasi lampu dinamis.</p>
                            <ul class="item-features">
                                <li><i data-lucide="check" class="feature-icon"></i> 7 Unit Moving Beam</li>
                                <li><i data-lucide="check" class="feature-icon"></i> 3 Box Par LED (12 Unit)</li>
                                <li><i data-lucide="check" class="feature-icon"></i> 3 Unit Lampu Fresnel</li>
                                <li><i data-lucide="check" class="feature-icon"></i> 1 Unit Mesin Asap & 1 Hazer</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Lighting Designer & Programmer</li>
                            </ul>
                            <div class="item-price-wrapper">
                                <span class="price-val">Rp 3.000.000 <small>/hari</small></span>
                            </div>
                            <button onclick="selectDirectPackage('light-menengah')" class="btn btn-primary btn-block select-pkg-btn" data-id="light-menengah">Pilih Paket</button>
                        </div>
                    </div>

                    <!-- Item 6: Lighting Mewah -->
                    <div class="catalog-item glassmorphism" data-category="lighting">
                        <div class="catalog-header">
                            <span class="item-tag tag-lighting">Lighting</span>
                            <h3>Lighting Paket Mewah</h3>
                        </div>
                        <div class="catalog-body">
                            <p class="item-desc">Pertunjukan tata cahaya berskala masif untuk konser besar, pesta outdoor megah, dan festival EDM spektakuler.</p>
                            <ul class="item-features">
                                <li><i data-lucide="check" class="feature-icon"></i> 8 Unit Moving Beam</li>
                                <li><i data-lucide="check" class="feature-icon"></i> 4 Box Par LED (16 Unit)</li>
                                <li><i data-lucide="check" class="feature-icon"></i> 4 Unit Lampu Fresnel</li>
                                <li><i data-lucide="check" class="feature-icon"></i> 2 Unit Mesin Asap</li>
                                <li><i data-lucide="check" class="feature-icon"></i> 1 Unit Ball Mirror Klasik</li>
                            </ul>
                            <div class="item-price-wrapper">
                                <span class="price-val">Rp 4.500.000 <small>/hari</small></span>
                            </div>
                            <button onclick="selectDirectPackage('light-mewah')" class="btn btn-outline btn-block select-pkg-btn" data-id="light-mewah">Pilih Paket</button>
                        </div>
                    </div>

                    <!-- Item 7: Panggung 6x5 -->
                    <div class="catalog-item glassmorphism" data-category="stage">
                        <div class="catalog-header">
                            <span class="item-tag tag-visual">Panggung</span>
                            <h3>Panggung Modular 6x5m</h3>
                        </div>
                        <div class="catalog-body">
                            <p class="item-desc">Ukuran kompak untuk pertunjukan intim, pameran mall, seminar sekolah, dan panggung akustik.</p>
                            <ul class="item-features">
                                <li><i data-lucide="check" class="feature-icon"></i> Alas Panggung Modular 6m x 5m</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Karpet Alas Merah / Hitam Premium</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Kaki Leveling Modular Stabil</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Tangga Akses & Leveling</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Termasuk Biaya Kirim & Bongkar Pasang</li>
                            </ul>
                            <div class="item-price-wrapper">
                                <span class="price-val">Rp 3.000.000 <small>/hari</small></span>
                            </div>
                            <button onclick="selectDirectPackage('stage-6x5')" class="btn btn-outline btn-block select-pkg-btn" data-id="stage-6x5">Pilih Paket</button>
                        </div>
                    </div>

                    <!-- Item 8: Panggung 8x6 -->
                    <div class="catalog-item glassmorphism highlighted" data-category="stage">
                        <div class="item-popular-badge">Paling Sering Disewa</div>
                        <div class="catalog-header">
                            <span class="item-tag tag-visual">Panggung</span>
                            <h3>Panggung Modular 8x6m</h3>
                        </div>
                        <div class="catalog-body">
                            <p class="item-desc">Standar panggung sedang untuk pentas seni sekolah, pesta pernikahan, dan corporate gathering outdoor.</p>
                            <ul class="item-features">
                                <li><i data-lucide="check" class="feature-icon"></i> Alas Panggung Modular 8m x 6m</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Leveling Karpet Halus Pilihan</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Tangga Akses & Guardrail Samping</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Konstruksi Kerangka Besi Kokoh</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Termasuk Biaya Kirim & Setup Cepat</li>
                            </ul>
                            <div class="item-price-wrapper">
                                <span class="price-val">Rp 4.500.000 <small>/hari</small></span>
                            </div>
                            <button onclick="selectDirectPackage('stage-8x6')" class="btn btn-primary btn-block select-pkg-btn" data-id="stage-8x6">Pilih Paket</button>
                        </div>
                    </div>

                    <!-- Item 9: Panggung 10x8 -->
                    <div class="catalog-item glassmorphism" data-category="stage">
                        <div class="catalog-header">
                            <span class="item-tag tag-visual">Panggung</span>
                            <h3>Panggung Modular 10x8m</h3>
                        </div>
                        <div class="catalog-body">
                            <p class="item-desc">Panggung megah konser utama, festival berskala besar, upacara kelulusan universitas, dan kampanye.</p>
                            <ul class="item-features">
                                <li><i data-lucide="check" class="feature-icon"></i> Alas Panggung Modular 10m x 8m</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Konstruksi Heavy Duty Besi Rigging</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Tangga Akses & Jalur Kabel Rapi</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Karpet Premium Full Cover</li>
                                <li><i data-lucide="check" class="feature-icon"></i> Jaminan Keamanan Standar Konser</li>
                            </ul>
                            <div class="item-price-wrapper">
                                <span class="price-val">Rp 6.000.000 <small>/hari</small></span>
                            </div>
                            <button onclick="selectDirectPackage('stage-10x8')" class="btn btn-outline btn-block select-pkg-btn" data-id="stage-10x8">Pilih Paket</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bagaimana Kami Bekerja -->
        <section id="how-it-works" class="how-it-works-section">
            <div class="container">
                <div class="section-header text-center">
                    <span class="badge">ALUR PENYEWAAN</span>
                    <h2 class="section-title">Proses Mudah & Cepat</h2>
                    <p class="section-desc">Empat langkah mudah untuk memastikan kebutuhan perlengkapan acara Anda terpenuhi secara profesional.</p>
                </div>
                
                <div class="steps-grid">
                    <div class="step-card">
                        <div class="step-num">01</div>
                        <h3>Pilih Paket & Alat</h3>
                        <p>Pilih paket event yang tersedia di atas atau pilih alat secara custom sesuai kebutuhan teknis acara Anda.</p>
                    </div>
                    
                    <div class="step-card">
                        <div class="step-num">02</div>
                        <h3>Isi Formulir Sewa</h3>
                        <p>Lengkapi formulir di bawah secara detail dengan menyertakan tanggal acara, paket pilihan, serta informasi kontak Anda.</p>
                    </div>
                    
                    <div class="step-card">
                        <div class="step-num">03</div>
                        <h3>Konfirmasi & DP</h3>
                        <p>Tim RME akan menghubungi Anda melalui WhatsApp untuk mendiskusikan teknis lapangan dan memproses tanda jadi (DP).</p>
                    </div>
                    
                    <div class="step-card">
                        <div class="step-num">04</div>
                        <h3>Instalasi & Event</h3>
                        <p>Kru profesional kami akan mengantar, merakit, menguji, dan mengoperasikan peralatan hingga acara Anda sukses besar.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimoni Klien -->
        <section id="testimonials" class="testimonials-section">
            <div class="container">
                <div class="section-header text-center">
                    <span class="badge">TESTIMONIAL</span>
                    <h2 class="section-title">Apa Kata Klien Kami?</h2>
                    <p class="section-desc">Ulasan asli dari klien yang telah mempercayakan kesuksesan event mereka kepada Rima Entertainment.</p>
                </div>
                
                <div class="testimonials-slider">
                    <div class="testimonial-card glassmorphism">
                        <div class="testimonial-header">
                            <div class="stars">
                                <i data-lucide="star" class="star-icon active"></i>
                                <i data-lucide="star" class="star-icon active"></i>
                                <i data-lucide="star" class="star-icon active"></i>
                                <i data-lucide="star" class="star-icon active"></i>
                                <i data-lucide="star" class="star-icon active"></i>
                            </div>
                            <span class="quote-mark">“</span>
                        </div>
                        <p class="testimonial-text">"Sewa sound dan lighting konser sekolah di Rima Entertainment hasilnya luar biasa megah. Suaranya bersih, lighting-nya sinkron banget sama lagu. Kru stand-by dan sangat ramah membantu!"</p>
                        <div class="testimonial-user">
                            <div class="user-avatar text-avatar">AN</div>
                            <div class="user-info">
                                <h4>Amanda N.</h4>
                                <span>Ketua Panitia Pentas Seni SMA</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial-card glassmorphism">
                        <div class="testimonial-header">
                            <div class="stars">
                                <i data-lucide="star" class="star-icon active"></i>
                                <i data-lucide="star" class="star-icon active"></i>
                                <i data-lucide="star" class="star-icon active"></i>
                                <i data-lucide="star" class="star-icon active"></i>
                                <i data-lucide="star" class="star-icon active"></i>
                            </div>
                            <span class="quote-mark">“</span>
                        </div>
                        <p class="testimonial-text">"Sangat profesional! Kami menyewa panggung modular, sound dan lighting untuk corporate gathering. Instalasinya cepat, panggungnya sangat kokoh, dan tidak ada kendala sama sekali."</p>
                        <div class="testimonial-user">
                            <div class="user-avatar text-avatar">BS</div>
                            <div class="user-info">
                                <h4>Budi Santoso</h4>
                                <span>Project Manager PT Multi Global</span>
                            </div>
                        </div>
                    </div>

                    <div class="testimonial-card glassmorphism">
                        <div class="testimonial-header">
                            <div class="stars">
                                <i data-lucide="star" class="star-icon active"></i>
                                <i data-lucide="star" class="star-icon active"></i>
                                <i data-lucide="star" class="star-icon active"></i>
                                <i data-lucide="star" class="star-icon active"></i>
                                <i data-lucide="star" class="star-icon active"></i>
                            </div>
                            <span class="quote-mark">“</span>
                        </div>
                        <p class="testimonial-text">"Terima kasih Rima Entertainment atas support sound system dan lighting ambient di hari resepsi pernikahan kami. Dekorasinya bertambah elegan berkat penataan lampu yang tepat. Sangat recommended!"</p>
                        <div class="testimonial-user">
                            <div class="user-avatar text-avatar">RL</div>
                            <div class="user-info">
                                <h4>Rian & Laras</h4>
                                <span>Pasangan Pengantin</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Formulir Penyewaan -->
        <section id="rent-form-section" class="rent-form-section">
            <div class="container">
                <div class="rent-grid">
                    <div class="rent-info-content">
                        <span class="badge">SEWA SEKARANG</span>
                        <h2>Siap Membuat Event Anda Menjadi Spektakuler?</h2>
                        <p class="rent-intro-text">Lengkapi formulir di samping untuk mengirimkan pengajuan sewa. Live estimator kami akan secara otomatis menghitung perkiraan biaya sewa berdasarkan pilihan Anda.</p>
                        
                        <div class="rent-features-list">
                            <div class="rent-feature-item">
                                <div class="icon-box"><i data-lucide="calculator" class="info-icon"></i></div>
                                <div>
                                    <h4>Estimasi Instan</h4>
                                    <p>Sistem kalkulator dinamis langsung menghitung biaya sesuai pilihan paket dan durasi sewa.</p>
                                </div>
                            </div>
                            
                            <div class="rent-feature-item">
                                <div class="icon-box"><i data-lucide="phone-call" class="info-icon"></i></div>
                                <div>
                                    <h4>Fast Response WhatsApp</h4>
                                    <p>Setelah pengajuan terkirim, tim teknis RME akan segera menghubungi untuk konfirmasi detail teknis lapangan.</p>
                                </div>
                            </div>

                            <div class="rent-feature-item">
                                <div class="icon-box"><i data-lucide="shield-check" class="info-icon"></i></div>
                                <div>
                                    <h4>Garansi Alat Nyala & Aman</h4>
                                    <p>Semua alat diuji sebelum dikirim dan didampingi operator profesional berpengalaman.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="rent-form-card glassmorphism">
                        <div class="form-header">
                            <h3>Formulir Penyewaan</h3>
                            <p>Silakan isi informasi penyewaan secara lengkap.</p>
                        </div>
                        
                        <form id="bookingForm" action="{{ route('booking.store') }}" method="POST" novalidate>
                            @csrf
                            <div class="form-group">
                                <label for="fullName">Nama Lengkap / Instansi <span class="req">*</span></label>
                                <div class="input-wrapper">
                                    <i data-lucide="user" class="input-icon"></i>
                                    <input type="text" id="fullName" name="fullName" placeholder="Masukkan nama Anda atau nama perusahaan" required>
                                </div>
                                <span class="error-msg" id="fullNameError">Nama wajib diisi</span>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="whatsapp">Nomor WhatsApp <span class="req">*</span></label>
                                    <div class="input-wrapper">
                                        <i data-lucide="phone" class="input-icon"></i>
                                        <input type="tel" id="whatsapp" name="whatsapp" placeholder="Contoh: 08123456789" required>
                                    </div>
                                    <span class="error-msg" id="whatsappError">Nomor WhatsApp tidak valid</span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Alamat Email <span class="req">*</span></label>
                                    <div class="input-wrapper">
                                        <i data-lucide="mail" class="input-icon"></i>
                                        <input type="email" id="email" name="email" placeholder="Contoh: nama@domain.com" required>
                                    </div>
                                    <span class="error-msg" id="emailError">Format email tidak valid</span>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="eventDate">Tanggal Mulai Acara <span class="req">*</span></label>
                                    <div class="input-wrapper">
                                        <i data-lucide="calendar" class="input-icon"></i>
                                        <input type="date" id="eventDate" name="eventDate" required>
                                    </div>
                                    <span class="error-msg" id="eventDateError">Tanggal harus di masa depan</span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="duration">Durasi Sewa (Hari) <span class="req">*</span></label>
                                    <div class="input-wrapper">
                                        <i data-lucide="clock" class="input-icon"></i>
                                        <input type="number" id="duration" name="duration" min="1" max="30" value="1" required>
                                    </div>
                                    <span class="error-msg" id="durationError">Durasi minimal 1 hari</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Pilih Paket Alat Event <span class="req">*</span></label>
                                <span class="label-desc">Anda dapat memilih satu atau lebih paket sekaligus:</span>
                                
                                <div class="packages-selection">
                                    <!-- Sound Section -->
                                    <label class="package-checkbox-card">
                                        <input type="checkbox" name="selectedPackages[]" value="sound-5000w" data-price="2000000" id="pkg-sound-5000w">
                                        <div class="checkbox-custom"></div>
                                        <div class="package-checkbox-content">
                                            <span class="pkg-title">Sound System Paket 5000W</span>
                                            <span class="pkg-price">Rp 2.000.000 /hari</span>
                                        </div>
                                    </label>

                                    <label class="package-checkbox-card">
                                        <input type="checkbox" name="selectedPackages[]" value="sound-10000w" data-price="3000000" id="pkg-sound-10000w">
                                        <div class="checkbox-custom"></div>
                                        <div class="package-checkbox-content">
                                            <span class="pkg-title">Sound System Paket 10000W</span>
                                            <span class="pkg-price">Rp 3.000.000 /hari</span>
                                        </div>
                                    </label>

                                    <label class="package-checkbox-card">
                                        <input type="checkbox" name="selectedPackages[]" value="sound-20000w" data-price="4500000" id="pkg-sound-20000w">
                                        <div class="checkbox-custom"></div>
                                        <div class="package-checkbox-content">
                                            <span class="pkg-title">Sound System Paket 20000W</span>
                                            <span class="pkg-price">Rp 4.500.000 /hari</span>
                                        </div>
                                    </label>

                                    <!-- Lighting Section -->
                                    <label class="package-checkbox-card">
                                        <input type="checkbox" name="selectedPackages[]" value="light-hemat" data-price="2000000" id="pkg-light-hemat">
                                        <div class="checkbox-custom"></div>
                                        <div class="package-checkbox-content">
                                            <span class="pkg-title">Lighting Paket Hemat</span>
                                            <span class="pkg-price">Rp 2.000.000 /hari</span>
                                        </div>
                                    </label>

                                    <label class="package-checkbox-card">
                                        <input type="checkbox" name="selectedPackages[]" value="light-menengah" data-price="3000000" id="pkg-light-menengah">
                                        <div class="checkbox-custom"></div>
                                        <div class="package-checkbox-content">
                                            <span class="pkg-title">Lighting Paket Menengah</span>
                                            <span class="pkg-price">Rp 3.000.000 /hari</span>
                                        </div>
                                    </label>

                                    <label class="package-checkbox-card">
                                        <input type="checkbox" name="selectedPackages[]" value="light-mewah" data-price="4500000" id="pkg-light-mewah">
                                        <div class="checkbox-custom"></div>
                                        <div class="package-checkbox-content">
                                            <span class="pkg-title">Lighting Paket Mewah</span>
                                            <span class="pkg-price">Rp 4.500.000 /hari</span>
                                        </div>
                                    </label>

                                    <!-- Stage Section -->
                                    <label class="package-checkbox-card">
                                        <input type="checkbox" name="selectedPackages[]" value="stage-6x5" data-price="3000000" id="pkg-stage-6x5">
                                        <div class="checkbox-custom"></div>
                                        <div class="package-checkbox-content">
                                            <span class="pkg-title">Panggung Modular 6x5m</span>
                                            <span class="pkg-price">Rp 3.000.000 /hari</span>
                                        </div>
                                    </label>

                                    <label class="package-checkbox-card">
                                        <input type="checkbox" name="selectedPackages[]" value="stage-8x6" data-price="4500000" id="pkg-stage-8x6">
                                        <div class="checkbox-custom"></div>
                                        <div class="package-checkbox-content">
                                            <span class="pkg-title">Panggung Modular 8x6m</span>
                                            <span class="pkg-price">Rp 4.500.000 /hari</span>
                                        </div>
                                    </label>

                                    <label class="package-checkbox-card">
                                        <input type="checkbox" name="selectedPackages[]" value="stage-10x8" data-price="6000000" id="pkg-stage-10x8">
                                        <div class="checkbox-custom"></div>
                                        <div class="package-checkbox-content">
                                            <span class="pkg-title">Panggung Modular 10x8m</span>
                                            <span class="pkg-price">Rp 6.000.000 /hari</span>
                                        </div>
                                    </label>
                                </div>
                                <span class="error-msg" id="packagesError">Harap pilih minimal satu paket penyewaan</span>
                            </div>

                            <div class="form-group">
                                <label for="specialRequests">Catatan Khusus / Custom Request</label>
                                <div class="input-wrapper">
                                    <textarea id="specialRequests" name="specialRequests" placeholder="Sebutkan jika ada kebutuhan rigging panggung custom, generator silent, kursi VIP tambahan, dll..." rows="3"></textarea>
                                </div>
                            </div>

                            <!-- Real-time Cost Estimation Display -->
                            <div class="live-estimator-display">
                                <div class="estimator-row">
                                    <span>Estimasi Paket:</span>
                                    <span id="estSubtotal">Rp 0</span>
                                </div>
                                <div class="estimator-row">
                                    <span>Durasi Sewa:</span>
                                    <span id="estDuration">1 Hari</span>
                                </div>
                                <div class="estimator-row divider"></div>
                                <div class="estimator-row total">
                                    <span>Total Estimasi Biaya:</span>
                                    <span id="estTotal">Rp 0</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg" id="submitBtn">
                                <i data-lucide="send" class="btn-icon"></i> Ajukan Penyewaan Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <a href="#" class="logo">
                    <span class="logo-text">RIMA</span>
                    <span class="logo-subtext">ENTERTAINMENT</span>
                </a>
                <p class="footer-desc">Partner terpercaya penyedia kelengkapan panggung, tata suara, tata cahaya, dan visual berstandar tinggi di Indonesia.</p>
                <div class="social-links">
                    <a href="#" aria-label="Instagram"><i data-lucide="instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i data-lucide="youtube"></i></a>
                    <a href="#" aria-label="Facebook"><i data-lucide="facebook"></i></a>
                    <a href="#" aria-label="Twitter"><i data-lucide="twitter"></i></a>
                </div>
            </div>
            
            <div class="footer-links">
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="#hero">Beranda</a></li>
                    <li><a href="#categories">Layanan Kami</a></li>
                    <li><a href="#catalog">Katalog Paket</a></li>
                    <li><a href="#how-it-works">Cara Sewa</a></li>
                    <li><a href="#testimonials">Ulasan Klien</a></li>
                </ul>
            </div>

            <div class="footer-links">
                <h4>Kategori Alat</h4>
                <ul>
                    <li><a href="#categories">Sound System</a></li>
                    <li><a href="#categories">Stage Lighting</a></li>
                    <li><a href="#categories">Panggung Modular</a></li>
                </ul>
            </div>

            <div class="footer-contact">
                <h4>Hubungi Kami</h4>
                <ul class="contact-list">
                    <li>
                        <i data-lucide="map-pin" class="contact-icon"></i>
                        <span>Jl. Panggung Megah No. 88, Jakarta Selatan, Indonesia</span>
                    </li>
                    <li>
                        <i data-lucide="phone" class="contact-icon"></i>
                        <span>0812-3456-7890 (CS Rima)</span>
                    </li>
                    <li>
                        <i data-lucide="mail" class="contact-icon"></i>
                        <span>info@rimaentertainment.com</span>
                    </li>
                    <li>
                        <i data-lucide="clock" class="contact-icon"></i>
                        <span>Setiap Hari: 24 Jam Non-Stop</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container footer-bottom-container">
                <p>&copy; 2026 Rima Entertainment. Hak Cipta Dilindungi Undang-Undang.</p>
                <div class="footer-legal">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Success Modal Overlay -->
    <div class="modal-overlay" id="successModal">
        <div class="modal-card glassmorphism">
            <div class="modal-check-icon">
                <i data-lucide="check" class="check-svg"></i>
            </div>
            <h2>Pemesanan Berhasil Terdaftar!</h2>
            <p>Terima kasih telah mempercayakan acara Anda kepada <strong>Rima Entertainment</strong>. Berikut ringkasan estimasi pemesanan Anda:</p>
            
            <div class="modal-receipt">
                <div class="receipt-row">
                    <span>ID Order:</span>
                    <strong id="receiptOrderId">-</strong>
                </div>
                <div class="receipt-row">
                    <span>Nama Pelanggan:</span>
                    <strong id="receiptName">-</strong>
                </div>
                <div class="receipt-row">
                    <span>No. HP/WhatsApp:</span>
                    <strong id="receiptWhatsapp">-</strong>
                </div>
                <div class="receipt-row">
                    <span>Tanggal Sewa:</span>
                    <strong id="receiptDate">-</strong>
                </div>
                <div class="receipt-row">
                    <span>Durasi Sewa:</span>
                    <strong id="receiptDuration">-</strong>
                </div>
                <div class="receipt-row">
                    <span>Paket Dipilih:</span>
                    <strong id="receiptPackages" class="receipt-pkg-list">-</strong>
                </div>
                <div class="receipt-row divider"></div>
                <div class="receipt-row total">
                    <span>Total Estimasi:</span>
                    <strong id="receiptTotal">Rp 0</strong>
                </div>
            </div>

            <p class="modal-note"><i data-lucide="info" class="note-icon"></i> Pesanan Anda telah tersimpan secara resmi di tabel Orders database Supabase Rima Entertainment. Tim sales kami akan segera menghubungi Anda dalam waktu 15 menit.</p>

            <div class="modal-actions">
                <button class="btn btn-secondary btn-block" id="closeModalBtn">Kembali</button>
                <a href="#" target="_blank" class="btn btn-whatsapp btn-block" id="whatsappDirectBtn">
                    <i data-lucide="message-square" class="btn-icon"></i> Hubungi CS via WhatsApp
                </a>
            </div>
        </div>
    </div>

    <!-- Script file -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
