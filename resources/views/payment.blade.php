<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <title>Pembayaran Midtrans (GoPay & QRIS)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
      margin: 0;
      padding: 0;
      color: #1f2937;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .card {
      width: 100%;
      max-width: 480px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 15px 50px rgba(0,0,0,0.15);
      overflow: hidden;
    }
    .header {
      background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
      color: white;
      text-align: center;
      padding: 25px;
    }
    .header h1 {
      margin: 0;
      font-size: 26px;
      font-weight: 800;
    }
    .content {
      padding: 30px;
    }
    label {
      display: block;
      font-weight: 600;
      margin-bottom: 5px;
    }
    input {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 18px;
      border: 2px solid #e5e7eb;
      border-radius: 8px;
      background-color: #f9fafb;
      font-size: 15px;
    }
    input:focus {
      border-color: #4b5563;
      outline: none;
      background-color: #fff;
      box-shadow: 0 0 0 3px rgba(75, 85, 99, 0.1);
    }
    .btn {
      width: 100%;
      padding: 14px;
      margin-bottom: 12px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 16px;
      font-weight: 700;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .btn-gopay {
      background: linear-gradient(135deg, #00b74a 0%, #00e676 100%);
      color: white;
    }
    .btn-qris {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      color: white;
    }
    .error {
      background-color: #fee2e2;
      border: 2px solid #ef4444;
      padding: 15px;
      border-radius: 8px;
      color: #991b1b;
      font-weight: 600;
      margin-top: 10px;
    }
    .loading {
      text-align: center;
      padding: 20px;
    }
    .loading-spinner {
      border: 4px solid rgba(75, 85, 99, 0.2);
      border-top: 4px solid #4b5563;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      animation: spin 1s linear infinite;
      margin: 0 auto 10px;
    }
    @keyframes spin {
      100% { transform: rotate(360deg); }
    }
    #result {
      margin-top: 20px;
      text-align: center;
    }
    .footer {
      background: #f9fafb;
      padding: 15px;
      text-align: center;
      font-size: 12px;
      color: #6b7280;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="header">
      <h1>Kasir Cafe Galaxy Aromanica</h1>
      <p>Sistem Pembayaran GoPay & QRIS</p>
    </div>

    <div class="content">
  <label>Nama Lengkap</label>
  <input id="name" type="text" placeholder="Masukkan nama lengkap" />

  <label>Email</label>
  <input id="email" type="email" placeholder="Masukkan email" />

  <label>Jumlah (IDR)</label>
  <input id="amount" type="number" min="1000" placeholder="Minimal Rp 1.000" />

  <button class="btn btn-gopay" onclick="pay('gopay')">🟢 Bayar dengan GoPay</button>
  <button class="btn btn-qris" onclick="pay('qris')">🔵 Bayar dengan QRIS</button>

  <div id="result"></div>
</div>

    <div class="footer">
      © 2025 Kasir Cafe Galaxy Aromanica | Powered by Midtrans
    </div>
  </div>

  <script>
    async function pay(type) {
      const name = document.getElementById("name").value.trim();
      const email = document.getElementById("email").value.trim();
      const amount = parseInt(document.getElementById("amount").value);
      const resultDiv = document.getElementById("result");

      if (!name || !email || !amount || amount < 1000) {
        resultDiv.innerHTML = `<div class="error">⚠️ Mohon isi semua data dengan benar.</div>`;
        return;
      }

      resultDiv.innerHTML = `
        <div class="loading">
          <div class="loading-spinner"></div>
          <div>Memproses pembayaran...</div>
        </div>
      `;

      try {
        const res = await fetch(window.location.origin + "/payment/process", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify({ name, email, amount, payment_type: type }),
        });

        const data = await res.json();
        console.log("Payment Response:", data);

        if (data.status === "success") {
          if (type === "gopay" && data.data.actions) {
            const deeplink = data.data.actions.find(a => a.name === "deeplink-redirect");
            if (deeplink && deeplink.url) {
              window.location.href = deeplink.url;
            } else {
              resultDiv.innerHTML = `<div class="error">⚠️ Link pembayaran GoPay tidak tersedia.</div>`;
            }
          } else if (type === "qris" && data.data.qr_string) {
            const qrUrl = data.data.qr_string;
            resultDiv.innerHTML = `
              <p><b>📱 Scan QRIS untuk membayar:</b></p>
              <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(qrUrl)}" alt="QRIS Code" />
              <p style="margin-top:10px; color:#6b7280;">Gunakan aplikasi pembayaran seperti  Dana,  .</p>
            `;
          } else {
            resultDiv.innerHTML = `<div class="error">⚠️ Respons tidak dikenali.</div>`;
          }
        } else {
          resultDiv.innerHTML = `<div class="error">⚠️ ${data.status_message || "Gagal memproses pembayaran."}</div>`;
        }
      } catch (err) {
        resultDiv.innerHTML = `<div class="error">⚠️ Gagal terhubung ke server: ${err.message}</div>`;
      }
    }
  </script>
</body>
</html>
