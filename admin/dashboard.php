<?php

session_start();

/* ===============================
   ADMIN LOGIN PROTECTION
================================ */

if (empty($_SESSION["is_admin"])) {
    header("Location: admin_login.php");
    exit;
}


/* ===============================
   DATABASE CONNECTION
================================ */

require_once __DIR__ . "/../includes/db.php";


/* ===============================
   GET PAINTINGS
================================ */

$result = $conn->query(
    "SELECT * FROM paintings ORDER BY created_at DESC"
);

$total = $result ? $result->num_rows : 0;

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Athmananda</title>

    <style>
        /* ===== RESET & BASE ===== */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Arial, sans-serif;
            background: #f0ece8;
            color: #1e1a16;
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #1e1410 0%, #2b1b13 100%);
            color: #f0e8e0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            padding: 32px 20px 20px;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.15);
            z-index: 100;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar .logo {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1.5px;
            padding-bottom: 28px;
            margin-bottom: 28px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            line-height: 1.4;
        }

        .sidebar .logo small {
            display: block;
            font-size: 11px;
            font-weight: 400;
            letter-spacing: 2px;
            color: #b8a89a;
            margin-top: 4px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #cfc2b6;
            text-decoration: none;
            padding: 13px 16px;
            margin-bottom: 4px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.07);
            color: #fff;
        }

        .sidebar a .icon {
            font-size: 18px;
            width: 26px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar a.logout {
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding-top: 20px;
            color: #dba58b;
        }

        .sidebar a.logout:hover {
            background: rgba(219, 165, 139, 0.12);
            color: #f5c9b5;
        }

        /* ===== MAIN CONTENT ===== */
        .main {
            margin-left: 260px;
            flex: 1;
            padding: 36px 44px 60px;
            min-height: 100vh;
        }

        /* ===== TOP BAR ===== */
        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 32px;
        }

        .top h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1e1410;
            letter-spacing: -0.5px;
        }

        .top p {
            color: #7a6b60;
            font-size: 15px;
            margin-top: 2px;
        }

        .top-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .add-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #6d3f2b;
            color: #fff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(109, 63, 43, 0.30);
            letter-spacing: 0.3px;
        }

        .add-btn:hover {
            background: #583222;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(109, 63, 43, 0.35);
        }

        .add-btn.secondary {
            background: #4a5a6a;
            box-shadow: 0 4px 14px rgba(74, 90, 106, 0.25);
        }

        .add-btn.secondary:hover {
            background: #3a4a5a;
        }

        /* ===== STATS ===== */
        .stats {
            display: flex;
            gap: 24px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .stat {
            background: #ffffff;
            padding: 26px 32px;
            border-radius: 18px;
            min-width: 200px;
            flex: 1 1 180px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0, 0, 0, 0.03);
            transition: all 0.25s ease;
        }

        .stat:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.07);
        }

        .stat p {
            font-size: 14px;
            font-weight: 500;
            color: #8a7a6e;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 12px;
        }

        .stat h2 {
            font-size: 36px;
            font-weight: 700;
            color: #6d3f2b;
            margin-top: 6px;
            letter-spacing: -1px;
        }

        /* ===== TABLE CARD ===== */
        .table-container {
            background: #ffffff;
            padding: 28px 30px 34px;
            border-radius: 20px;
            box-shadow: 0 6px 28px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0, 0, 0, 0.02);
            overflow-x: auto;
        }

        .table-container h2 {
            font-size: 20px;
            font-weight: 600;
            color: #1e1410;
            margin-bottom: 6px;
        }

        .table-container .sub {
            font-size: 14px;
            color: #8a7a6e;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th {
            text-align: left;
            padding: 16px 14px;
            background: #f7f3ef;
            font-weight: 600;
            color: #3d322a;
            letter-spacing: 0.3px;
            font-size: 12px;
            text-transform: uppercase;
            border-radius: 12px 12px 0 0;
        }

        td {
            padding: 16px 14px;
            border-bottom: 1px solid #f0ebe6;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #fcf9f7;
        }

        .painting-img {
            width: 66px;
            height: 66px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease;
            display: block;
        }

        .painting-img:hover {
            transform: scale(1.04);
        }

        .action-links {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .edit {
            color: #3a6a9e;
            text-decoration: none;
            font-weight: 500;
            padding: 5px 14px;
            border-radius: 30px;
            background: rgba(58, 106, 158, 0.08);
            transition: all 0.2s ease;
            font-size: 13px;
        }

        .edit:hover {
            background: rgba(58, 106, 158, 0.16);
        }

        .delete {
            color: #b13e30;
            text-decoration: none;
            font-weight: 500;
            padding: 5px 14px;
            border-radius: 30px;
            background: rgba(177, 62, 48, 0.08);
            transition: all 0.2s ease;
            font-size: 13px;
        }

        .delete:hover {
            background: rgba(177, 62, 48, 0.16);
        }

        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: #8a7a6e;
        }

        .empty-state .big-icon {
            font-size: 48px;
            margin-bottom: 12px;
            display: block;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 820px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main {
                margin-left: 0;
                padding: 24px 20px 40px;
            }

            .top h1 {
                font-size: 24px;
            }

            .stats .stat {
                min-width: 140px;
                padding: 20px 24px;
            }

            .stat h2 {
                font-size: 28px;
            }

            .table-container {
                padding: 18px 16px 22px;
            }

            th,
            td {
                padding: 12px 10px;
                font-size: 13px;
            }

            .painting-img {
                width: 50px;
                height: 50px;
            }

            .add-btn {
                padding: 10px 18px;
                font-size: 13px;
            }

            /* Mobile hamburger */
            .menu-toggle {
                display: flex !important;
            }
        }

        @media (max-width: 500px) {
            .top {
                flex-direction: column;
                align-items: stretch;
            }

            .top-actions {
                flex-direction: column;
            }

            .top-actions .add-btn {
                justify-content: center;
            }

            .stats {
                flex-direction: column;
            }

            .stat {
                min-width: unset;
            }

            .action-links {
                flex-direction: column;
                gap: 4px;
            }

            .edit,
            .delete {
                text-align: center;
                padding: 6px 10px;
            }
        }

        /* ===== HAMBURGER (hidden by default) ===== */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 28px;
            color: #1e1410;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .menu-toggle:hover {
            background: rgba(0, 0, 0, 0.04);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 90;
        }

        .sidebar-overlay.active {
            display: block;
        }

        @media (max-width: 820px) {
            .menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .top .menu-toggle {
                order: -1;
            }
        }

        /* ===== SCROLLBAR ===== */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>

</head>

<body>

    <!-- Overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ===== SIDEBAR ===== -->
    <div class="sidebar" id="sidebar">

        <div class="logo">
            ATHMANANDA
            <small>ADMIN PANEL</small>
        </div>

        <a href="dashboard.php">
            <span class="icon">&#9679;</span> Dashboard
        </a>

        <a href="add_painting.php">
            <span class="icon">&#43;</span> Add Painting
        </a>

        <a href="../collections.php" target="_blank">
            <span class="icon">&#128065;</span> View Store
        </a>

        <a href="Admin_logout.php" class="logout">
            <span class="icon">&#11013;</span> Logout
        </a>

    </div>

    <!-- ===== MAIN ===== -->
    <div class="main">

        <div class="top">

            <div>
                <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
                    &#9776;
                </button>
                <h1>Dashboard</h1>
                <p>Manage your art collection</p>
            </div>

            <div class="top-actions">
                <a href="add_painting.php" class="add-btn">
                    &#43; Add New Painting
                </a>
                <a href="inquiry.php" class="add-btn secondary">
                    &#128172; View Inquiries
                </a>
            </div>

        </div>

        <!-- ===== STATS ===== -->
        <div class="stats">

            <div class="stat">
                <p>&#128396; Total Paintings</p>
                <h2><?php echo $total; ?></h2>
            </div>

        </div>

        <!-- ===== TABLE ===== -->
        <div class="table-container">

            <h2>Art Collection</h2>
            <div class="sub">All paintings in your gallery</div>

            <?php if ($result && $result->num_rows > 0) { ?>

                <table>

                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td>
                                    <img class="painting-img"
                                    src="../uploads/<?php echo htmlspecialchars($row['image']); ?>"
                                    alt="<?php echo htmlspecialchars($row['title']); ?>">
                                </td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td>&#8377;<?php echo number_format($row['price'], 2); ?></td>
                                <td>
                                    <div class="action-links">
                                        <a class="edit" href="edit_painting.php?id=<?php echo $row['id']; ?>">
                                            &#9998; Edit
                                        </a>
                                        <a class="delete" href="delete_painting.php?id=<?php echo $row['id']; ?>"
                                        onclick="return confirm('Delete this painting?')">
                                        &#128465; Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>

        <?php } else { ?>

            <div class="empty-state">
                <span class="big-icon">&#128444;</span>
                <p>No paintings found. Start by adding your first artwork!</p>
            </div>

        <?php } ?>

    </div>

</div>

<!-- ===== SIDEBAR TOGGLE SCRIPT ===== -->
<script>
    (function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.getElementById('menuToggle');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        if (toggle) {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                if (sidebar.classList.contains('open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }

        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSidebar();
        });

        // Auto-close on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 820) {
                closeSidebar();
            }
        });
    })();
</script>

</body>
</html>