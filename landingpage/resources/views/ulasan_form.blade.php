<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berikan Ulasan - RME Entertainment</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png?v=7') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { background: #0f172a; color: white; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 2rem; font-family: 'Inter', sans-serif; margin: 0;}
        .review-container { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 3rem; max-width: 600px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); }
        .header { text-align: center; margin-bottom: 2rem; }
        .header img { width: 150px; margin-bottom: 1rem; }
        .header h2 { font-family: 'Outfit', sans-serif; font-size: 2rem; margin-bottom: 0.5rem; }
        .order-info { background: rgba(0,0,0,0.3); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; font-size: 0.9rem; color: #cbd5e1; line-height: 1.5; }
        .order-info strong { color: white; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        .stars-input { display: flex; gap: 0.5rem; font-size: 2.5rem; color: #4b5563; cursor: pointer; flex-direction: row-reverse; justify-content: center; }
        .stars-input input { display: none; }
        .stars-input label { cursor: pointer; transition: color 0.2s; margin: 0; }
        .stars-input label:hover, .stars-input label:hover ~ label, .stars-input input:checked ~ label { color: #fbbf24; }
        textarea { width: 100%; background: rgba(15,23,42,0.6); border: 1px solid rgba(255,255,255,0.2); border-radius: 10px; padding: 1rem; color: white; font-family: inherit; font-size: 1rem; resize: vertical; min-height: 120px; transition: border-color 0.3s; box-sizing: border-box; }
        textarea:focus { outline: none; border-color: #3b82f6; }
        .btn-submit { display: block; width: 100%; background: linear-gradient(135deg, #001f82 0%, #e60012 100%); color: white; border: none; padding: 1rem; border-radius: 10px; font-weight: 600; font-size: 1.1rem; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; font-family: 'Outfit', sans-serif; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 20px -10px rgba(230,0,18,0.5); }
        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 500; text-align: center; }
        .alert-error { background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); }
        .alert-success { background: rgba(34, 197, 94, 0.2); color: #86efac; border: 1px solid rgba(34, 197, 94, 0.3); }
    </style>
</head>
<body>

    <div class="review-container">
        <div class="header">
            <img src="{{ asset('logo.png') }}" alt="RME Entertainment">
            <h2>Bagaimana Pelayanan Kami?</h2>
            <p>Terima kasih telah mempercayakan event Anda kepada kami. Berikan ulasan Anda!</p>
        </div>

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="order-info">
            <p><strong>Nama Klien:</strong> {{ $order->nama_pelanggan }}</p>
            <p><strong>Tanggal Sewa:</strong> {{ \Carbon\Carbon::parse($order->tgl_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($order->tgl_selesai)->format('d M Y') }}</p>
            <p><strong>Lokasi Event:</strong> {{ $order->lokasi_alamat }}</p>
        </div>

        <form action="{{ url('/ulasan/'.$order->id_order) }}" method="POST">
            @csrf
            <div class="form-group" style="text-align: center;">
                <label>Berikan Rating Bintang</label>
                <div class="stars-input">
                    <input type="radio" id="star5" name="rating" value="5" required />
                    <label for="star5" title="5 Bintang">?</label>
                    <input type="radio" id="star4" name="rating" value="4" />
                    <label for="star4" title="4 Bintang">?</label>
                    <input type="radio" id="star3" name="rating" value="3" />
                    <label for="star3" title="3 Bintang">?</label>
                    <input type="radio" id="star2" name="rating" value="2" />
                    <label for="star2" title="2 Bintang">?</label>
                    <input type="radio" id="star1" name="rating" value="1" />
                    <label for="star1" title="1 Bintang">?</label>
                </div>
            </div>

            <div class="form-group">
                <label for="comment">Tulis Ulasan Anda</label>
                <textarea id="comment" name="comment" placeholder="Ceritakan pengalaman Anda menyewa alat atau panggung di RME Entertainment..." required></textarea>
            </div>

            <button type="submit" class="btn-submit">Kirim Ulasan</button>
        </form>
    </div>

</body>
</html>
