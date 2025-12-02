<?php
session_start();
require_once '../config/koneksi.php'; 
require_once dirname(__DIR__) . '/vendor/autoload.php'; 

use Midtrans\Config;
use Midtrans\Snap;

// 1. Konfigurasi Midtrans
Config::$serverKey = 'SB-Mid-server-F2bp1WB4iTLx00j-hFCWrx0t'; // GANTI SERVER KEY ANDA
Config::$isProduction = false;
Config::$isSanitized = true;
Config::$is3ds = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    
    // PERBAIKAN 1: Logika ambil nomor WA (Manual atau Terdaftar)
    // Hapus tanda $ di dalam kurung siku $_POST
    $no_whatsapp = !empty($_POST['phone_manual']) ? $_POST['phone_manual'] : $_POST['phone_registered'];

    // Buat Order ID Unik
    $order_id = 'ORD-' . time() . '-' . rand(100,999);
    
    // 2. Hitung Total & Siapkan Item Details untuk Midtrans
    // JANGAN INPUT KE DATABASE DI SINI (Nanti Error Foreign Key)
    $total_amount = 0;
    $item_details = [];
    
    foreach ($_SESSION['cart'] as $id_produk => $item) {
        $subtotal = $item['price'] * $item['qty'];
        $total_amount += $subtotal;
        
        $item_details[] = [
            'id' => $id_produk,
            'price' => $item['price'],
            'quantity' => $item['qty'],
            'name' => substr($item['name'], 0, 50) 
        ];
    }

    // 3. Siapkan Parameter Snap Midtrans
    $transaction_details = [
        'order_id' => $order_id,
        'gross_amount' => $total_amount,
    ];

    $customer_details = [
        'first_name' => $nama,
        'email' => $email,
        'phone' => $no_whatsapp, // PERBAIKAN: Key harus 'phone', bukan '$no_whatsapp'
        'billing_address' => ['address' => $alamat],
        'shipping_address' => ['address' => $alamat]
    ];

    $params = [
        'transaction_details' => $transaction_details,
        'customer_details' => $customer_details,
        'item_details' => $item_details,
    ];

    try {
        // 4. Minta Snap Token
        $snapToken = Snap::getSnapToken($params);

        // 5. Simpan Order ke Database Utama (TABEL ORDERS) - LAKUKAN INI DULUAN
        // PERBAIKAN: Nama kolom di database adalah 'customer_phone', bukan 'customer_$no_whatsapp'
        $query_order = "INSERT INTO orders (id, customer_name, customer_email, customer_phone, customer_address, total_amount, status, snap_token) 
                        VALUES ('$order_id', '$nama', '$email', '$no_whatsapp', '$alamat', '$total_amount', 'pending', '$snapToken')";
        
        if (mysqli_query($koneksi, $query_order)) {
            
            // 6. BARU Simpan Item ke Database (TABEL ORDER_ITEMS)
            // Sekarang aman karena Order ID sudah ada di tabel orders
            foreach ($_SESSION['cart'] as $id_produk => $item) {
                $q_item = "INSERT INTO order_items (order_id, product_id, product_name, price, quantity) 
                           VALUES ('$order_id', '$id_produk', '{$item['name']}', '{$item['price']}', '{$item['qty']}')";
                mysqli_query($koneksi, $q_item);
            }

            // Kosongkan keranjang
            unset($_SESSION['cart']);
            
            // Redirect ke halaman detail order
            // Pastikan path header benar (karena file ini ada di folder action, maka naik satu level ../)
            header("Location: ../pages/order_detail.php?id=$order_id");
            exit;

        } else {
            echo "Error Database Orders: " . mysqli_error($koneksi);
        }

    } catch (Exception $e) {
        echo "Error Midtrans: " . $e->getMessage();
    }
}
?>