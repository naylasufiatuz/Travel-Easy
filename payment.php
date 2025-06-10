<?php 
require("links.php");
require('db_config.php');

// Handle form submission
if ($_POST && isset($_POST['process_booking'])) {
    // Get form data
    $visit_date = mysqli_real_escape_string($con, $_POST['visit_date']);
    $visitors_count = (int)$_POST['visitors_count'];
    $fullname = mysqli_real_escape_string($con, $_POST['fullname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $special_request = mysqli_real_escape_string($con, $_POST['special_request']);
    $payment_method = mysqli_real_escape_string($con, $_POST['payment_method']);
    
    // Get destination data (you might want to pass this from previous page or set default)
    $destination_id = isset($_GET['destination_id']) ? (int)$_GET['destination_id'] : 1; // Default destination
    
    // Get destination info for pricing
    $dest_query = "SELECT * FROM destination WHERE id = ?";
    $dest_stmt = mysqli_prepare($con, $dest_query);
    mysqli_stmt_bind_param($dest_stmt, "i", $destination_id);
    mysqli_stmt_execute($dest_stmt);
    $dest_result = mysqli_stmt_get_result($dest_stmt);
    $destination = mysqli_fetch_assoc($dest_result);
    
    if (!$destination) {
        // Set default values if destination not found
        $price_per_person = 450000;
        $destination_name = "Default Destination";
    } else {
        $price_per_person = $destination['price_per_person'] ?? 450000;
        $destination_name = $destination['name'];
    }
    
    // Calculate pricing
    $subtotal = $price_per_person * $visitors_count;
    $admin_fee = $subtotal * 0.1; // 10% admin fee
    $total_amount = $subtotal + $admin_fee;
    
    // Generate booking code
    $booking_code = 'BK' . date('Ymd') . rand(1000, 9999);
    
    // Check if customer exists or create new one
    $customer_query = "SELECT id FROM customers WHERE email = ?";
    $customer_stmt = mysqli_prepare($con, $customer_query);
    mysqli_stmt_bind_param($customer_stmt, "s", $email);
    mysqli_stmt_execute($customer_stmt);
    $customer_result = mysqli_stmt_get_result($customer_stmt);
    
    if (mysqli_num_rows($customer_result) > 0) {
        $customer = mysqli_fetch_assoc($customer_result);
        $customer_id = $customer['id'];
        
        // Update customer info
        $update_customer = "UPDATE customers SET fullname = ?, phone = ? WHERE id = ?";
        $update_stmt = mysqli_prepare($con, $update_customer);
        mysqli_stmt_bind_param($update_stmt, "ssi", $fullname, $phone, $customer_id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);
    } else {
        // Create new customer
        $insert_customer = "INSERT INTO customers (fullname, email, phone, password, created_at) VALUES (?, ?, ?, ?, NOW())";
        $insert_stmt = mysqli_prepare($con, $insert_customer);
        $default_password = password_hash('123456', PASSWORD_DEFAULT); // Default password
        mysqli_stmt_bind_param($insert_stmt, "ssss", $fullname, $email, $phone, $default_password);
        mysqli_stmt_execute($insert_stmt);
        $customer_id = mysqli_insert_id($con);
        mysqli_stmt_close($insert_stmt);
    }
    
    // Insert booking
    $booking_query = "INSERT INTO bookings (
        booking_code, customer_id, destination_id, visit_date, visitors_count, 
        price_per_person, subtotal, admin_fee, total_amount, payment_method, 
        special_request, status, created_at, updated_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW(), NOW())";
    
    $booking_stmt = mysqli_prepare($con, $booking_query);
    mysqli_stmt_bind_param($booking_stmt, "siisiddddss", 
        $booking_code, $customer_id, $destination_id, $visit_date, $visitors_count,
        $price_per_person, $subtotal, $admin_fee, $total_amount, $payment_method, $special_request
    );
    
    if (mysqli_stmt_execute($booking_stmt)) {
        $booking_id = mysqli_insert_id($con);
        $success_message = "Booking berhasil dibuat dengan kode: " . $booking_code;
        $booking_success = true;
    } else {
        $error_message = "Terjadi kesalahan saat membuat booking: " . mysqli_error($con);
    }
    
    mysqli_stmt_close($booking_stmt);
    mysqli_stmt_close($customer_stmt);
    mysqli_stmt_close($dest_stmt);
}

// Get destination info if destination_id is provided
$destination_info = null;
if (isset($_GET['destination_id'])) {
    $destination_id = (int)$_GET['destination_id'];
    $dest_query = "SELECT * FROM destination WHERE id = ?";
    $dest_stmt = mysqli_prepare($con, $dest_query);
    mysqli_stmt_bind_param($dest_stmt, "i", $destination_id);
    mysqli_stmt_execute($dest_stmt);
    $dest_result = mysqli_stmt_get_result($dest_stmt);
    $destination_info = mysqli_fetch_assoc($dest_result);
    mysqli_stmt_close($dest_stmt);
}

// Set default price if no destination found
$default_price = $destination_info['price_per_person'] ?? 450000;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - Konfirmasi Reservasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php require("links.php") ?>
</head>
<body class="min-h-screen gradient-bg">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full"></div>
                    <h1 class="text-2xl font-bold text-gray-800">Travel Easy</h1>
                </div>
                <button onclick="goBack()" class="flex items-center space-x-2 text-gray-600 hover:text-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali</span>
                </button>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            
            <?php if (isset($success_message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <?php echo $success_message; ?>
                </div>
                <div class="mt-2">
                    <a href="bookings_crud.php" class="text-green-800 underline">Lihat semua booking</a> |
                    <button onclick="window.print()" class="text-green-800 underline">Cetak konfirmasi</button>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?php echo $error_message; ?>
            </div>
            <?php endif; ?>

            <!-- Destination Info -->
            <?php if ($destination_info): ?>
            <div class="bg-white rounded-2xl shadow-2xl p-6 mb-8 fade-in">
                <h2 class="text-2xl font-bold mb-4 text-gray-800"><?php echo htmlspecialchars($destination_info['name']); ?></h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600"><strong>Lokasi:</strong> <?php echo htmlspecialchars($destination_info['location']); ?></p>
                        <p class="text-gray-600"><strong>Harga per orang:</strong> Rp <?php echo number_format($destination_info['price_per_person'], 0, ',', '.'); ?></p>
                    </div>
                    <div>
                        <?php if ($destination_info['description']): ?>
                        <p class="text-gray-600"><?php echo htmlspecialchars($destination_info['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Booking Form -->
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Form Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-2xl p-6 fade-in">
                        <h3 class="text-2xl font-bold mb-6 text-gray-800">Detail Reservasi</h3>
                        
                        <form method="POST" id="bookingForm" class="space-y-6">
                            <input type="hidden" name="process_booking" value="1">
                            <?php if (isset($_GET['destination_id'])): ?>
                            <input type="hidden" name="destination_id" value="<?php echo (int)$_GET['destination_id']; ?>">
                            <?php endif; ?>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kunjungan</label>
                                <input type="date" name="visit_date" id="visitDate" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Pengunjung</label>
                                <select name="visitors_count" id="visitorsCount" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    <option value="">Pilih jumlah pengunjung</option>
                                    <option value="1">1 Orang</option>
                                    <option value="2">2 Orang</option>
                                    <option value="3">3 Orang</option>
                                    <option value="4">4 Orang</option>
                                    <option value="5">5 Orang</option>
                                    <option value="6">6 Orang</option>
                                    <option value="7">7 Orang</option>
                                    <option value="8">8 Orang</option>
                                    <option value="9">9 Orang</option>
                                    <option value="10">10 Orang</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="fullname" id="fullname" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                       placeholder="Masukkan nama lengkap">
                            </div>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" id="email" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                           placeholder="contoh@email.com">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                                    <input type="tel" name="phone" id="phone" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                           placeholder="08xxxxxxxxxx">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Permintaan Khusus (Opsional)</label>
                                <textarea name="special_request" id="specialRequest" rows="3" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                          placeholder="Tuliskan permintaan khusus Anda..."></textarea>
                            </div>
                            
                            <!-- Payment Method -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                                <div class="space-y-3">
                                    <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                        <input type="radio" name="payment_method" value="bank" class="mr-3" checked required>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-blue-500 rounded flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium">Transfer Bank</div>
                                                <div class="text-sm text-gray-500">BCA, Mandiri, BNI, BRI</div>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                        <input type="radio" name="payment_method" value="ewallet" class="mr-3" required>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-green-500 rounded flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                                    <path d="M3 4a1 1 0 00-1 1v1a1 1 0 001 1h1a1 1 0 001-1V5a1 1 0 00-1-1H3zM3 10a1 1 0 00-1 1v1a1 1 0 001 1h1a1 1 0 001-1v-1a1 1 0 00-1-1H3z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium">E-Wallet</div>
                                                <div class="text-sm text-gray-500">GoPay, OVO, DANA, ShopeePay</div>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                        <input type="radio" name="payment_method" value="credit" class="mr-3" required>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-purple-500 rounded flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v2H4V6zm0 4h12v4H4v-4z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium">Kartu Kredit</div>
                                                <div class="text-sm text-gray-500">Visa, Mastercard, JCB</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Pricing & Payment -->
                <div class="space-y-6">
                    <!-- Price Summary -->
                    <div class="bg-white rounded-2xl shadow-2xl p-6 fade-in">
                        <h3 class="text-xl font-bold mb-4 text-gray-800">Ringkasan Harga</h3>
                        
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Harga per orang</span>
                                <span class="font-semibold" id="pricePerPerson">Rp <?php echo number_format($default_price, 0, ',', '.'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Jumlah pengunjung</span>
                                <span class="font-semibold" id="totalVisitors">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold" id="subtotal">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Biaya Admin (10%)</span>
                                <span class="font-semibold" id="adminFee">Rp 0</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total</span>
                                    <span class="text-blue-600" id="totalPrice">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Book Button -->
                    <button type="submit" form="bookingForm" id="bookingButton" 
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 rounded-xl font-bold text-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 shadow-xl">
                        Konfirmasi Pemesanan
                    </button>
                    
                    <!-- Cancellation Policy -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="font-medium text-yellow-800 mb-2">Kebijakan Pembatalan</h4>
                        <p class="text-sm text-yellow-700">
                            Pembatalan gratis hingga 24 jam sebelum tanggal kunjungan. 
                            Pembatalan setelah itu akan dikenakan biaya 50% dari total harga.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const pricePerPerson = <?php echo $default_price; ?>;
        
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('visitDate').min = today;

        // Calculate total price
        function calculateTotal() {
            const visitorsCount = parseInt(document.getElementById('visitorsCount').value) || 0;
            
            const subtotal = pricePerPerson * visitorsCount;
            const adminFee = subtotal * 0.1;
            const total = subtotal + adminFee;
            
            document.getElementById('totalVisitors').textContent = visitorsCount + ' orang';
            document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('adminFee').textContent = 'Rp ' + adminFee.toLocaleString('id-ID');
            document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        document.getElementById('visitorsCount').addEventListener('change', calculateTotal);

        // Form submission handling
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const button = document.getElementById('bookingButton');
            
            // Validate required fields
            const visitDate = document.getElementById('visitDate').value;
            const visitorsCount = document.getElementById('visitorsCount').value;
            const fullname = document.getElementById('fullname').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            
            if (!visitDate || !visitorsCount || !fullname || !email || !phone) {
                e.preventDefault();
                alert('Mohon lengkapi semua data yang diperlukan!');
                return;
            }
            
            // Show loading state
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...';
            button.disabled = true;
        });

        function goBack() {
            window.history.back();
        }

        // Initialize
        calculateTotal();
    </script>
</body>
</html>