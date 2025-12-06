<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Notion — Trang chủ (Mockup)</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>

    :root{
      --bg:#f6f7f9;
      --panel:#ffffff;
      --muted:#6b7280;
      --accent:#111827;
      --hover:#f3f4f6;
      --active:#eef2ff;
      --shadow: 0 6px 18px rgba(15,23,42,0.06);
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    }

    *{box-sizing:border-box}
    html,body{height:100%;margin:0;background:var(--bg);color:var(--accent)}

    .app{display:flex;min-height:100vh}

    /* Sidebar */
    .sidebar{width:280px;background:var(--panel);padding:20px;border-right:1px solid #eaedf0;box-shadow:var(--shadow);}
    .brand{display:flex;align-items:center;gap:12px;margin-bottom:18px}
    .avatar{width:40px;height:40px;border-radius:8px;background:linear-gradient(135deg,#e6eefc,#dbeafe);display:flex;align-items:center;justify-content:center;font-weight:700;color:#0f172a}
    .title{font-size:16px;font-weight:600}

    .menu{display:flex;flex-direction:column;gap:8px}
    .menu button{display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;background:transparent;border:0;cursor:pointer;text-align:left;font-size:15px;color:var(--accent)}
    .menu button:hover{background:var(--hover)}
    .menu button.active{background:var(--active);font-weight:600}

    .icon{width:20px;height:20px;display:inline-flex;align-items:center;justify-content:center;opacity:0.9}

    /* Main content */
    .main{flex:1;padding:28px}
    .header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px}
    .header h1{margin:0;font-size:20px}
    .card{background:var(--panel);padding:18px;border-radius:12px;box-shadow:var(--shadow);}

    /* Small screens */
    @media (max-width:720px){
      .sidebar{width:100%;display:flex;gap:12px;overflow:auto}
      .app{flex-direction:column}
      .main{padding:16px}
    }
  </style>
</head>
<body>
  <div class="app">
    <aside class="sidebar" role="navigation" aria-label="Thanh menu chính">
      <div class="brand">
        <div class="avatar">N</div>
        <div>
          <div class="title">Notion của </div>
          <div style="font-size:12px;color:var(--muted)">Trang chủ</div>
        </div>
      </div>

      <nav class="menu" aria-label="Menu">
        <button id="btn-search">
          <span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span>
            <!-- magnifier -->
          
          Tìm kiếm
        </button>

        <button id="btn-home" class="active">
          <span class="icon"><i class="fa-solid fa-house"></i></span>
            <!-- home -->
          
          Trang chủ
        </button>

        <button id="btn-inbox">
          <span class="icon"><i class="fa-solid fa-inbox"></i></span>
            <!-- inbox -->
          
          Hộp thư đến
        </button>
      </nav>
    </aside>

    <main class="main">
      <div class="header">
        <h1 id="page-title">Trang chủ</h1>
        <div style="color:var(--muted);font-size:14px">Chào!</div>
      </div>

      <section class="card">
        <h3 style="margin-top:0">Nội dung chính</h3>
        
      </section>
    </main>
  </div>

  <script>
    // Simple interactivity: toggle active state and update title
    const buttons = document.querySelectorAll('.menu button');
    const title = document.getElementById('page-title');

    buttons.forEach(btn=>{
      btn.addEventListener('click', ()=>{
        buttons.forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
        title.textContent = btn.textContent.trim();
      })
    })
  </script>
</body>
</html>
