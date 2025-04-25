<?php
session_start();
require_once ('../config/database.php');

// Redirect if not logged in
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get cart data from database
$sql = "SELECT ci.*, p.name, p.price, p.image
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total += $row['price'] * $row['quantity'];
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سلة التسوق - ROSE SHOP</title>
    <link rel="stylesheet" href="../css/style2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <style>
        .cart-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        #local-cart-items {
            display: none;
        }
        body {
            padding-top: 20px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h2 class="mb-4 text-center">🛒 سلة التسوق الخاصة بك</h2>

        <!-- Database Cart -->
        <div id="db-cart">
            <?php if (count($cart_items) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered bg-white shadow-sm">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>الصورة</th>
                                <th>المنتج</th>
                                <th>السعر</th>
                                <th>الكمية</th>
                                <th>المجموع</th>
                                <th>إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $row): 
                                $subtotal = $row['price'] * $row['quantity'];
                            ?>
                            <tr class="text-center align-middle">
                                <td><img src="../images/<?= htmlspecialchars($row['image']) ?>" class="cart-img rounded" alt="<?= htmlspecialchars($row['name']) ?>"></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td>$<?= number_format($row['price'], 2) ?></td>
                                <td>
                                    <form action="update_cart.php" method="post" class="d-inline">
                                        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                                        <input type="number" name="quantity" value="<?= $row['quantity'] ?>" min="1" class="form-control form-control-sm d-inline" style="width: 70px;">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">تحديث</button>
                                    </form>
                                </td>
                                <td>$<?= number_format($subtotal, 2) ?></td>
                                <td>
                                    <a href="remove_from_cart.php?id=<?= $row['product_id'] ?>" class="btn btn-sm btn-danger">حذف</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="text-end">
                                <th colspan="4">الإجمالي الكلي:</th>
                                <th colspan="2">$<?= number_format($total, 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-center mt-4">
                    <a href="products.php" class="btn btn-secondary">استمر في التسوق</a>
                    <a href="checkout.php" class="btn btn-success">إتمام الشراء</a>
                </div>

            <?php else: ?>
                <div class="alert alert-info text-center py-4">
                    <p class="mb-0">سلتك فارغة حاليًا 🛒</p>
                    <a href="/index.php" class="btn btn-primary mt-3">Back to Homepage</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Display localStorage cart for non-logged-in users
        <?php if (!isset($_SESSION['user_logged_in'])): ?>
            function displayCart() {
                let cart = JSON.parse(localStorage.getItem("cart")) || [];
                let cartContainer = document.getElementById("local-cart-items");
                let actionsContainer = document.getElementById("local-cart-actions");
                let total = 0;
                
                if (cart.length === 0) {
                    cartContainer.innerHTML = '<div class="alert alert-info text-center py-4"><p class="mb-0">سلتك فارغة حاليًا 🛍️</p><a href="products.php" class="btn btn-primary mt-3">تصفح المنتجات</a></div>';
                    actionsContainer.style.display = 'none';
                    return;
                }
                
                let html = '<div class="table-responsive"><table class="table table-bordered bg-white shadow-sm"><thead class="table-dark text-center"><tr><th>الصورة</th><th>المنتج</th><th>السعر</th><th>الكمية</th><th>المجموع</th><th>إجراء</th></tr></thead><tbody>';
                
                cart.forEach(item => {
                    let itemTotal = item.price * item.quantity;
                    total += itemTotal;
                    
                    html += `
                        <tr class="text-center align-middle">
                            <td><img src="../images/${item.image}" class="cart-img rounded" alt="${item.name}"></td>
                            <td>${item.name}</td>
                            <td>$${item.price.toFixed(2)}</td>
                            <td>
                                <input type="number" value="${item.quantity}" min="1" 
                                       onchange="updateLocalCart('${item.id}', this.value)" 
                                       class="form-control form-control-sm d-inline" style="width: 70px;">
                            </td>
                            <td>$${itemTotal.toFixed(2)}</td>
                            <td><button class="btn btn-danger btn-sm" onclick="removeFromLocalCart('${item.id}')">حذف</button></td>
                        </tr>
                    `;
                });
                
                html += `</tbody><tfoot><tr class="text-end"><th colspan="4">الإجمالي الكلي:</th><th colspan="2">$${total.toFixed(2)}</th></tr></tfoot></table></div>`;
                
                cartContainer.innerHTML = html;
                actionsContainer.style.display = 'block';
                document.getElementById('db-cart').style.display = 'none';
                document.getElementById('local-cart-items').style.display = 'block';
            }
            
            function updateLocalCart(productId, quantity) {
                let cart = JSON.parse(localStorage.getItem("cart")) || [];
                cart = cart.map(item => {
                    if (item.id === productId) {
                        item.quantity = parseInt(quantity);
                    }
                    return item;
                });
                localStorage.setItem("cart", JSON.stringify(cart));
                displayCart();
            }
            
            function removeFromLocalCart(productId) {
                let cart = JSON.parse(localStorage.getItem("cart")) || [];
                cart = cart.filter(item => item.id !== productId);
                localStorage.setItem("cart", JSON.stringify(cart));
                displayCart();
            }
            
            window.onload = displayCart;
        <?php endif; ?>
    </script>
</body>
</html>