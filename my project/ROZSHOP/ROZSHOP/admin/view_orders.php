<?php
include('../config/database.php');
session_start();

// التحقق من تسجيل دخول المدير
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!$db) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}
// 1. استعلام الطلبات الأساسي
$orders_query = "SELECT o.*, 
                COALESCE(u.username, u.email, CONCAT('user-', o.user_id)) as user_name,
                u.email,
                o.status
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY o.order_date DESC";

$orders_result = mysqli_query($db, $orders_query);




// 2. استعلام الإحصائيات المعدل
$stats = [
    'completed' => 0,
    'pending' => 0,
    'cancelled' => 0,
    'processing' => 0
];

// الطريقة الأولى: باستخدام GROUP BY
$stats_query = "SELECT status, COUNT(*) as count FROM orders GROUP BY status";
$stats_result = mysqli_query($db, $stats_query);
if ($stats_result) {
    while ($row = mysqli_fetch_assoc($stats_result)) {
        $status = strtolower($row['status']);
        if (array_key_exists($status, $stats)) {
            $stats[$status] = $row['count'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الطلبات - نظام الإدارة - ROSE SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <i class="fa fa-sellsy" aria-hidden="true"></i>>
    <style>
        :root {
            --primary: #6c5ce7;
            --primary-light: #a29bfe;
            --primary-dark: #5649c0;
            --secondary: #00cec9;
            --success: #00b894;
            --warning: #fdcb6e;
            --danger: #d63031;
            --info: #0984e3;
            --light: #f8f9fa;
            --dark: #2d3436;
            --purple-gradient: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            --teal-gradient: linear-gradient(135deg, #00cec9 0%, #81ecec 100%);
        }
        body {
    background-color: #f5f6fa;
    font-family: 'Tajawal', sans-serif;
    color: var(--dark);
}

.glass-header {
    background: rgba(108, 92, 231, 0.9);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    color: white;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border-radius: 0 0 20px 20px;
    box-shadow: 0 10px 30px rgba(108, 92, 231, 0.3);
    position: relative;
    overflow: hidden;
    border-bottom: 3px solid rgba(255,255,255,0.2);
}

.glass-header::before {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
    z-index: -1;
}

.order-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    margin-bottom: 1.5rem;
    border: none;
    overflow: hidden;
    border-left: 4px solid var(--primary);
}

.order-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.status-badge {
    padding: 0.5em 1em;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.8em;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.status-pending {
    background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
    color: #7d6608;
}

.status-completed {
    background: linear-gradient(135deg, #55efc4 0%, #00b894 100%);
    color: #004d3d;
}

.status-cancelled {
    background: linear-gradient(135deg, #ff7675 0%, #d63031 100%);
    color: #7a0b0b;
}

.status-processing {
    background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
    color: #003d7a;
}

.action-btn {
    padding: 0.5rem 0.8rem;
    font-size: 0.9rem;
    border-radius: 10px;
    margin: 0 3px;
    transition: all 0.3s;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    border: none;
    font-weight: 600;
}

.action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.btn-view {
    background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
    color: white;
}

.btn-edit {
    background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%);
    color: white;
}

.btn-delete {
    background: linear-gradient(135deg, #ff7675 0%, #d63031 100%);
    color: white;
}

.search-box {
    position: relative;
    margin-bottom: 2rem;
}

.search-box i {
    position: absolute;
    top: 50%;
    right: 20px;
    transform: translateY(-50%);
    color: var(--primary);
    opacity: 0.7;
}

.table-responsive {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background: var(--purple-gradient);
    color: white;
    font-weight: 700;
    border: none;
    padding: 1.2rem;
    font-size: 1rem;
    text-align: center;
}

.table tbody tr {
    transition: all 0.3s;
}

.table tbody tr:hover {
    background-color: rgba(108, 92, 231, 0.05);
    transform: scale(1.005);
}

.table tbody td {
    vertical-align: middle;
    padding: 1.2rem;
    border-color: #f0f0f0;
    text-align: center;
}

.no-orders {
    padding: 3rem;
    text-align: center;
    background-color: white;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.05);
}

.no-orders img {
    height: 180px;
    opacity: 0.9;
    margin-bottom: 1.5rem;
    transition: all 0.4s;
}

.no-orders:hover img {
    transform: scale(1.1) rotate(-5deg);
    opacity: 1;
}

.card-header {
    background: white;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    padding: 1.5rem;
    border-radius: 15px 15px 0 0 !important;
}

.form-select {
    border-radius: 10px;
    padding: 0.6rem 1.2rem;
    border: 2px solid #e0e0e0;
    box-shadow: none;
    transition: all 0.3s;
    font-weight: 500;
}

.form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.25rem rgba(108, 92, 231, 0.25);
}
.form-control {
    border-radius: 10px;
    padding: 0.8rem 1.5rem;
    border: 2px solid #e0e0e0;
    box-shadow: none;
    transition: all 0.3s;
    font-weight: 500;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.25rem rgba(108, 92, 231, 0.25);
}

.btn-outline-light:hover {
    color: var(--primary);
    background: white;
}

.customer-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: var(--teal-gradient);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
    box-shadow: 0 4px 8px rgba(0, 206, 201, 0.3);
}

.amount-badge {
    background: rgba(0, 184, 148, 0.1);
    color: var(--success);
    padding: 0.6em 1.2em;
    border-radius: 50px;
    font-weight: 700;
    border: 1px dashed rgba(0, 184, 148, 0.3);
}

.pagination {
    justify-content: center;
    margin-top: 1.5rem;
}

.page-item.active .page-link {
    background: var(--purple-gradient);
    border-color: var(--primary);
    box-shadow: 0 4px 8px rgba(108, 92, 231, 0.3);
}

.page-link {
    color: var(--primary);
    border-radius: 10px;
    margin: 0 5px;
    border: none;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    font-weight: 600;
    transition: all 0.3s;
}

.page-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    color: var(--primary-dark);
}

.dashboard-title {
    font-weight: 900;
    text-shadow: 0 2px 5px rgba(0,0,0,0.1);
    letter-spacing: -0.5px;
}

.stat-card {
    border-radius: 15px;
    padding: 1.5rem;
    color: white;
    margin-bottom: 1.5rem;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    transition: all 0.3s;
    border: none;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.stat-card i {
    font-size: 2.5rem;
    opacity: 0.8;
}

.stat-card .count {
    font-size: 2rem;
    font-weight: 900;
    margin: 0.5rem 0;
}

.stat-card .label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.total-orders {
    background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
}

.completed-orders {
    background: linear-gradient(135deg, #00b894 0%, #55efc4 100%);
}

.pending-orders {
    background: linear-gradient(135deg, #fdcb6e 0%, #ffeaa7 100%);
}

.cancelled-orders {
    background: linear-gradient(135deg, #d63031 0%, #ff7675 100%);
}

@media (max-width: 768px) {
    .table-responsive {
        border-radius: 0;
    }

    .glass-header {
        padding: 1rem;
    }

    .table thead {
        display: none;
    }

    .table tbody tr {
        display: block;
        margin-bottom: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-align: right;
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .table tbody td::before {
        content: attr(data-label);
        font-weight: bold;
        margin-left: 1rem;
        color: var(--primary);
    }

    .action-btns {
        justify-content: center !important;
    }
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animated {
    animation: fadeIn 0.6s ease-out forwards;
}

.delay-1 { animation-delay: 0.1s; }
.delay-2 { animation-delay: 0.2s; }
.delay-3 { animation-delay: 0.3s; }
.delay-4 { animation-delay: 0.4s; }
.delay-5 { animation-delay: 0.5s; }    
    </style>
</head>
<body>
    <div class="glass-header animated delay-1">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <h1 class="dashboard-title text-white mb-0">
                    <i class="fas fa-clipboard-list me-2"></i> إدارة الطلبات
                </h1>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-outline-light rounded-pill">
                    <i class="fas fa-arrow-right me-1"></i> العودة للوحة التحكم
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- إحصائيات الطلبات -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card total-orders text-center animated delay-2">
                    <i class="fas fa-shopping-cart"></i>
                    <div class="count"><?= $total_orders ?></div>
                    <div class="label">إجمالي الطلبات</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card completed-orders text-center animated delay-3">
                    <i class="fas fa-check-circle"></i>
                    <div class="count"><?= $stats['completed'] ?></div>
                    <div class="label">طلبات مكتملة</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card pending-orders text-center animated delay-4">
                    <i class="fas fa-clock"></i>
                    <div class="count"><?= $stats['pending'] ?></div>
                    <div class="label">طلبات قيد الانتظار</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card cancelled-orders text-center animated delay-5">
                    <i class="fas fa-times-circle"></i>
                    <div class="count"><?= $stats['cancelled'] ?></div>
                    <div class="label">طلبات ملغاة</div>
                </div>
            </div>
        </div>

        <!-- جدول الطلبات -->
        <div class="card shadow-sm animated delay-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">قائمة الطلبات</h5>
                <div class="search-box w-25">
                    <input type="text" class="form-control" placeholder="ابحث عن طلب..." id="searchInput">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="ordersTable">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>العميل</th>
                                <th>التاريخ</th>
                                <th>المبلغ</th>
                                <th>حالة الطلب</th>
                                <th>العنوان</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($total_orders > 0): ?>
                                <?php while($order = mysqli_fetch_assoc($orders_result)): ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($order['id']) ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="customer-avatar me-2">
                                                    <?= mb_substr(htmlspecialchars($order['full_name']), 0, 1) ?>
                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?= htmlspecialchars($order['full_name']) ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($order['email']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= date('Y/m/d', strtotime($order['order_date'])) ?></td>
                                        <td>
                                            <span class="amount-badge">
                                                <?= number_format($order['total_amount'], 2) ?> ر.س
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $status_info = [
                                                'completed' => ['class' => 'status-completed', 'text' => 'مكتمل'],
                                                'pending' => ['class' => 'status-pending', 'text' => 'قيد الانتظار'],
                                                'cancelled' => ['class' => 'status-cancelled', 'text' => 'ملغى'],
                                                'processing' => ['class' => 'status-processing', 'text' => 'قيد المعالجة']
                                            ];
                                            $status = $order['status'];
                                            ?>
                                            <span class="status-badge <?= $status_info[$status]['class'] ?? 'status-pending' ?>">
                                                <?= $status_info[$status]['text'] ?? $status ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small><?= htmlspecialchars($order['address']) ?></small>
                                        </td>
                                        <td>
                                            <div class="d-flex action-btns">
                                                <a href="order_details.php?id=<?= $order['id'] ?>" class="action-btn btn-view">
                                                    <i class="fas fa-eye"></i> عرض
                                                </a>
                                                <a href="edit_order.php?id=<?= $order['id'] ?>" class="action-btn btn-edit">
                                                    <i class="fas fa-edit"></i> تعديل
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">
                                        <div class="no-orders">
                                            <img src="https://cdn.dribbble.com/users/5107895/screenshots/14532312/media/a7e6c2e9333d0989e3a54c95dd8321d7.gif" alt="لا توجد طلبات">
                                            <h4 class="mt-3">لا توجد طلبات متاحة</h4>
                                            <p class="text-muted">لم يتم العثور على أي طلبات في النظام</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- الترقيم الصفحي -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-4">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">السابق</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">التالي</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // بحث فوري في الطلبات
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#ordersTable tbody tr');
            
            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchValue) ? '' : 'none';
            });
        });
    </script>
</body>
</html>