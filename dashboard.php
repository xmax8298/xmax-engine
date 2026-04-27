<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php");
    exit();
}
$my_username = htmlspecialchars($_SESSION['username']);
$my_role     = strtoupper($_SESSION['role']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XMAX ENGINE - Premium Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* SEMUA CSS MASUK DI SINI BIAR GAK MENTAL */
        :root {
            --accent: #f0d481;
            --accent-glow: rgba(240, 212, 129, 0.4);
            --bg-base: #070709;
            --surface: rgba(20, 20, 25, 0.75);
            --border: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-muted: #8a8a93;
            --sidebar-width: 260px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            background-color: var(--bg-base); 
            color: var(--text-main); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            overflow-x: hidden;
            background-image: radial-gradient(circle at 0% 0%, var(--accent-glow) 0%, transparent 40%);
        }

        .app-container { display: flex; min-height: 100vh; width: 100%; }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--surface);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column; padding: 25px; transition: 0.3s;
        }

        .sidebar h2 { font-family: 'Orbitron', sans-serif; font-size: 20px; margin-bottom: 40px; text-align: center; }
        .accent-text { color: var(--accent); text-shadow: 0 0 10px var(--accent-glow); }

        .nav-title { font-size: 10px; color: var(--text-muted); font-weight: bold; letter-spacing: 1px; margin: 20px 0 10px 5px; }
        .nav-item {
            display: flex; align-items: center; gap: 15px; padding: 12px 15px;
            color: var(--text-muted); text-decoration: none; border-radius: 12px; transition: 0.3s; font-size: 14px;
        }
        .nav-item i { width: 20px; text-align: center; color: var(--accent); }
        .nav-item:hover, .nav-item.active { background: rgba(240, 212, 129, 0.15); color: #fff; transform: translateX(5px); }

        /* MAIN CONTENT */
        .main-content { flex: 1; padding: 30px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .menu-trigger { background: none; border: none; color: #fff; font-size: 24px; cursor: pointer; display: none; }

        /* PANELS */
        .glass-panel { 
            background: var(--surface); 
            backdrop-filter: blur(20px); 
            border: 1px solid var(--border); 
            border-radius: 20px; 
            padding: 25px; 
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .welcome-banner h1 { font-size: 28px; margin-bottom: 10px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .stat-box { display: flex; align-items: center; gap: 20px; }
        .stat-icon { font-size: 30px; color: var(--accent); text-shadow: 0 0 15px var(--accent-glow); }

        /* LOG TERMINAL STYLE */
        .log-area { font-family: 'Courier New', Courier, monospace; background: rgba(0,0,0,0.4); padding: 15px; border-radius: 10px; font-size: 13px; }
        .log-green { color: #0f0; }

        /* MOBILE */
        @media (max-width: 768px) {
            .sidebar { position: fixed; left: -100%; top: 0; height: 100vh; z-index: 1000; }
            .sidebar.active { left: 0; }
            .menu-trigger { display: block; }
            .main-content { padding: 20px; }
        }
    </style>
</head>
<body>

    <div class="app-container">
        <aside class="sidebar" id="sidebar">
            <button style="background:none; border:none; color:#fff; position:absolute; right:15px; top:15px;" id="closeSidebar" class="menu-trigger">×</button>
            <h2>XMAX<span class="accent-text">ENGINE</span></h2>
            
            <nav class="sidebar-nav">
                <p class="nav-title">MAIN CLUSTER</p>
                <a href="#" class="nav-item active"><i class="fas fa-satellite-dish"></i> Command Center</a>
                <a href="#" class="nav-item"><i class="fas fa-radar"></i> Live Radar</a>
                
                <p class="nav-title">ADMINISTRATION</p>
                <a href="#" class="nav-item"><i class="fas fa-users-cog"></i> Access Control</a>
                <a href="logout.php" class="nav-item" style="color:#ff4757;"><i class="fas fa-power-off"></i> Terminate</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <button class="menu-trigger" id="openSidebar"><i class="fas fa-bars"></i></button>
                <div class="user-info">
                    <span style="color:var(--text-muted); font-size:12px;">ACTIVE OPERATOR:</span>
                    <span style="color:var(--accent); font-weight:bold; margin-left:5px;"><?php echo $my_username; ?></span>
                </div>
            </header>

            <div class="glass-panel welcome-banner">
                <h1>System <span class="accent-text">Online</span></h1>
                <p>Welcome back, Operative. All nodes are functioning at optimal capacity.</p>
            </div>

            <div class="stats-grid">
                <div class="glass-panel stat-box">
                    <div class="stat-icon"><i class="fas fa-server"></i></div>
                    <div><h3>1,024</h3><p style="font-size:12px; color:var(--text-muted);">Domains</p></div>
                </div>
                <div class="glass-panel stat-box">
                    <div class="stat-icon"><i class="fas fa-wifi"></i></div>
                    <div><h3>99.9%</h3><p style="font-size:12px; color:var(--text-muted);">Uptime</p></div>
                </div>
            </div>

            <div class="glass-panel">
                <h3 style="margin-bottom:15px;"><i class="fas fa-terminal"></i> Terminal Log</h3>
                <div class="log-area">
                    <p class="log-green">> Connection established to main cluster...</p>
                    <p>> User <?php echo $my_username; ?> authenticated as <?php echo $my_role; ?>.</p>
                    <p class="log-green">> Ready for commands.</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');
        openBtn.onclick = () => sidebar.classList.add('active');
        closeBtn.onclick = () => sidebar.classList.remove('active');
    </script>
</body>
</html>