<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kost Assistant - Chat Cerdas</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome untuk ikon -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #57A438 0%, #F09F38 100%);
      --secondary-gradient: linear-gradient(135deg, #57A438 0%, #F09F38 100%);
      --success-gradient: linear-gradient(135deg, #57A438 0%, #F09F38 100%);
      --card-shadow: 0 10px 40px rgba(0,0,0,0.1);
      --hover-shadow: 0 15px 50px rgba(0,0,0,0.15);
      --border-radius: 16px;
      --animation-speed: 0.3s;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #57A438 0%, #F09F38 50%, #57A438 100%);
      min-height: 100vh;
      margin: 0;
      padding: 20px;
      background-attachment: fixed;
    }

    .main-container {
      width: 100%;
      max-width: 900px;
      margin: 0 auto;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: var(--border-radius);
      box-shadow: var(--card-shadow);
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .header {
      background: var(--primary-gradient);
      padding: 25px 30px;
      text-align: center;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .header::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(180deg); }
    }

    .header h1 {
      margin: 0;
      font-size: 2.2rem;
      font-weight: 700;
      text-shadow: 0 2px 10px rgba(0,0,0,0.2);
      position: relative;
      z-index: 1;
    }

    .header p {
      margin: 8px 0 0 0;
      opacity: 0.9;
      font-size: 1.1rem;
      font-weight: 300;
      position: relative;
      z-index: 1;
    }

    .chat-content {
      padding: 30px;
    }

    .faq-section {
      margin-bottom: 30px;
    }

    .faq-title {
      color: #333;
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .faq-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 12px;
      margin-bottom: 20px;
    }

    .faq-question {
      background: linear-gradient(135deg, #f8f9ff 0%, #e6f3ff 100%);
      border: 2px solid transparent;
      border-radius: 12px;
      padding: 15px 18px;
      cursor: pointer;
      transition: all var(--animation-speed) ease;
      font-weight: 500;
      color: #333;
      position: relative;
      overflow: hidden;
    }

    .faq-question::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
      transition: left 0.6s;
    }

    .faq-question:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(87, 164, 56, 0.15);
      border-color: rgba(87, 164, 56, 0.3);
    }

    .faq-question:hover::before {
      left: 100%;
    }

    .faq-question:active {
      transform: translateY(0);
    }

    .chat-container {
      background: #f8f9fa;
      border-radius: 12px;
      height: 450px;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      border: 1px solid #e9ecef;
      margin-bottom: 20px;
    }

    .chat-header {
      background: var(--success-gradient);
      color: white;
      padding: 15px 20px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    #chat-box {
      flex: 1;
      overflow-y: auto;
      padding: 20px;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .message {
      max-width: 75%;
      padding: 12px 18px;
      border-radius: 18px;
      font-size: 0.95rem;
      line-height: 1.4;
      position: relative;
      animation: messageSlide 0.4s ease-out;
    }

    @keyframes messageSlide {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .message.user {
      background: var(--primary-gradient);
      color: white;
      align-self: flex-end;
      border-bottom-right-radius: 6px;
      box-shadow: 0 4px 15px rgba(87, 164, 56, 0.3);
    }

    .message.bot {
      background: white;
      color: #333;
      align-self: flex-start;
      border-bottom-left-radius: 6px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      border: 1px solid #e9ecef;
    }

    .input-container {
      display: flex;
      gap: 12px;
      align-items: flex-end;
    }

    .input-wrapper {
      flex: 1;
      position: relative;
    }

    #question-input {
      width: 100%;
      padding: 15px 20px;
      border: 2px solid #e9ecef;
      border-radius: 25px;
      font-size: 1rem;
      transition: all var(--animation-speed) ease;
      background: white;
      resize: none;
      font-family: inherit;
      min-height: 55px;
      max-height: 120px;
    }

    #question-input:focus {
      outline: none;
      border-color: #57A438;
      box-shadow: 0 0 0 3px rgba(87, 164, 56, 0.1);
    }

    #send-button {
      width: 55px;
      height: 55px;
      border: none;
      border-radius: 50%;
      background: var(--primary-gradient);
      color: white;
      cursor: pointer;
      transition: all var(--animation-speed) ease;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      box-shadow: 0 4px 15px rgba(87, 164, 56, 0.3);
    }

    #send-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(87, 164, 56, 0.4);
    }

    #send-button:active {
      transform: translateY(0);
    }

    .typing-indicator {
      display: none;
      align-self: flex-start;
      background: white;
      padding: 15px 20px;
      border-radius: 18px;
      border-bottom-left-radius: 6px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      border: 1px solid #e9ecef;
    }

    .typing-dots {
      display: flex;
      gap: 4px;
    }

    .typing-dots span {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #57A438;
      animation: typing 1.4s infinite;
    }

    .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
    .typing-dots span:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
      0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
      30% { transform: translateY(-10px); opacity: 1; }
    }

    .status-indicator {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.9rem;
      color: #6c757d;
      margin-top: 10px;
    }

    .status-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #28a745;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { opacity: 1; }
      50% { opacity: 0.5; }
      100% { opacity: 1; }
    }

    /* Scrollbar styling */
    #chat-box::-webkit-scrollbar {
      width: 6px;
    }

    #chat-box::-webkit-scrollbar-track {
      background: #f1f3f4;
      border-radius: 10px;
    }

    #chat-box::-webkit-scrollbar-thumb {
      background: #57A438;
      border-radius: 10px;
    }

    #chat-box::-webkit-scrollbar-thumb:hover {
      background: #F09F38;
    }

    /* Responsive design */
    @media (max-width: 768px) {
      body {
        padding: 10px;
      }
      
      .header h1 {
        font-size: 1.8rem;
      }
      
      .chat-content {
        padding: 20px;
      }
      
      .faq-grid {
        grid-template-columns: 1fr;
      }
      
      .chat-container {
        height: 400px;
      }
      
      .message {
        max-width: 85%;
      }
    }

    @media (max-width: 480px) {
      .header {
        padding: 20px;
      }
      
      .chat-content {
        padding: 15px;
      }
      
      .chat-container {
        height: 350px;
      }
    }
  </style>
</head>
<body>

<div class="main-container">
  <div class="header">
    <h1><i class="fas fa-home"></i> Kost Assistant</h1>
    <p>Chat cerdas untuk informasi kost terbaik</p>
  </div>

  <div class="chat-content">
    <!-- FAQ Section -->
    <div class="faq-section">
      <h3 class="faq-title">
        <i class="fas fa-question-circle"></i>
        Pertanyaan Populer
      </h3>
      <div class="faq-grid">
        <div class="faq-question">💰 Berapa harga sewa per bulan?</div>
        <div class="faq-question">🏠 Apa saja fasilitas yang tersedia?</div>
        <div class="faq-question">🚿 Apakah kamar mandi di dalam atau luar?</div>
        <div class="faq-question">👥 Apakah kost campur, khusus putra, atau khusus putri?</div>
        <div class="faq-question">🕐 Apakah ada jam malam?</div>
        <div class="faq-question">📅 Apakah bisa sewa harian/mingguan/bulanan?</div>
        <div class="faq-question">📝 Apakah bisa booking dulu sebelum bayar?</div>
        <div class="faq-question">💳 Bagaimana cara bayar dan sistem sewa?</div>
        <div class="faq-question">🏧 Apakah bisa bayar via transfer?</div>
        <div class="faq-question">📞 Bagaimana cara memesan kamar?</div>
        <div class="faq-question">🐕 Boleh tidak membawa hewan peliharaan?</div>
        <div class="faq-question">👨‍🍳 Apakah tersedia dapur untuk memasak?</div>
        <div class="faq-question">🍽️ Apakah semua kost menyediakan dapur umum?</div>
      </div>
    </div>

    <!-- Chat Container -->
    <div class="chat-container">
      <div class="chat-header">
        <i class="fas fa-comments"></i>
        <span>Live Chat</span>
        <div class="ms-auto status-indicator">
          <div class="status-dot"></div>
          <span>Online</span>
        </div>
      </div>
      
      <div id="chat-box">
        <div class="message bot">
          <i class="fas fa-robot" style="margin-right: 8px; opacity: 0.7;"></i>
          Halo! Selamat datang di <strong>Kost Assistant</strong>! 🏠<br><br>
          Saya adalah asisten virtual yang siap membantu Anda menemukan informasi tentang kost kami. Silakan pilih pertanyaan di atas atau tanya langsung tentang:
          <br><br>
          💰 Harga sewa dan fasilitas<br>
          🏠 Tipe kamar dan ketersediaan<br>
          📝 Cara booking dan pembayaran<br>
          📞 Kontak dan alamat lengkap<br>
          <br>
          Tanya apa saja, saya siap membantu! 😊
        </div>
        
        <div class="typing-indicator" id="typing-indicator">
          <div class="typing-dots">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Input Form -->
    <form id="input-form" class="input-container">
      <div class="input-wrapper">
        <textarea 
          id="question-input"
          class="form-control"
          placeholder="Ketik pertanyaan Anda di sini..."
          rows="1"
          style="min-height: 55px; max-height: 120px;"
          required
        ></textarea>
      </div>
      <button type="submit" id="send-button">
        <i class="fas fa-paper-plane"></i>
      </button>
    </form>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Menggunakan algoritma asli Anda dengan tampilan yang lebih modern
  const chatBox = document.getElementById('chat-box');
  const form = document.getElementById('input-form');
  const input = document.getElementById('question-input');
  const typingIndicator = document.getElementById('typing-indicator');

  // Auto-resize textarea
  input.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
  });

  // Handle Enter key
  input.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      form.dispatchEvent(new Event('submit'));
    }
  });

  function addMessage(text, sender) {
    const message = document.createElement('div');
    message.classList.add('message', sender);
    
    if (sender === 'bot') {
      message.innerHTML = `<i class="fas fa-robot" style="margin-right: 8px; opacity: 0.7;"></i>${text}`;
    } else {
      message.textContent = text;
    }
    
    chatBox.appendChild(message);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  function showTypingIndicator() {
    typingIndicator.style.display = 'block';
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  function hideTypingIndicator() {
    typingIndicator.style.display = 'none';
  }

  // Menggunakan fungsi sendQuestion asli Anda dengan sedikit modifikasi untuk typing indicator
  function sendQuestion(question) {
    addMessage(question, 'user');
    showTypingIndicator();
    
    $.ajax({
      url: 'http://127.0.0.1:5000/predict',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ question: question }),
      dataType: 'json',
      success: function(data) {
        setTimeout(() => {
          hideTypingIndicator();
          addMessage(data.answer, 'bot');
        }, 1000 + Math.random() * 1000);
      },
      error: function(xhr, status, error) {
        setTimeout(() => {
          hideTypingIndicator();
          addMessage("Terjadi kesalahan. Coba lagi.", 'bot');
        }, 800);
        console.error("Error:", error);
      }
    });
  }

  // Event listener form submit (algoritma asli)
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const question = input.value.trim();
    if (!question) return;
    
    input.value = '';
    input.style.height = '55px';
    sendQuestion(question);
  });

  // Event listener untuk FAQ questions (algoritma asli dengan sedikit modifikasi)
  document.querySelectorAll('.faq-question').forEach(item => {
    item.addEventListener('click', () => {
      const question = item.textContent.replace(/^[^\s]+\s/, ''); // Remove emoji
      sendQuestion(question);
    });
  });

  // Add some interactive effects
  document.querySelectorAll('.faq-question').forEach(item => {
    item.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-2px) scale(1.02)';
    });
    
    item.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0) scale(1)';
    });
  });
</script>

</body>
</html>