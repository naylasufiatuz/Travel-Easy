<?php
// sr.php - Halaman hasil pencarian
// Ambil parameter pencarian dari URL
$destination = isset($_GET['destination']) ? htmlspecialchars($_GET['destination']) : '';
$checkin = isset($_GET['checkin']) ? htmlspecialchars($_GET['checkin']) : '';
$checkout = isset($_GET['checkout']) ? htmlspecialchars($_GET['checkout']) : '';
$guests = isset($_GET['guests']) ? htmlspecialchars($_GET['guests']) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian - <?php echo $destination; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .search-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .search-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            border: none;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .search-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .destination-image {
            height: 250px;
            object-fit: cover;
            width: 100%;
        }
        
        .price-tag {
            background: linear-gradient(45deg, #ff6b6b, #feca57);
            color: white;
            padding: 8px 15px;
            border-radius: 25px;
            font-weight: bold;
            position: absolute;
            top: 15px;
            right: 15px;
        }
        
        .rating-badge {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 5px 10px;
            position: absolute;
            bottom: 15px;
            left: 15px;
        }
        
        .amenity-tag {
            background: #e3f2fd;
            color: #1976d2;
            border-radius: 15px;
            padding: 4px 12px;
            font-size: 0.85rem;
            margin: 2px;
            display: inline-block;
        }
        
        .search-form-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .btn-search {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
            color: white;
        }
        
        .btn-book {
            background: linear-gradient(45deg, #ff6b6b, #feca57);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-book:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,107,107,0.4);
            color: white;
        }
    </style>
</head>
<body style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh;">
    <div class="container py-4">
        <!-- Form Pencarian Ulang -->
        <div class="search-form-container">
            <h5 class="mb-3"><i class="bi bi-search"></i> Ubah Pencarian</h5>
            <form id="searchForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Destinasi</label>
                        <input type="text" class="form-control" id="destination" name="destination" 
                               value="<?php echo $destination; ?>" placeholder="Mau ke mana?">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Check-in</label>
                        <input type="date" class="form-control" id="checkin" name="checkin" 
                               value="<?php echo $checkin; ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Check-out</label>
                        <input type="date" class="form-control" id="checkout" name="checkout" 
                               value="<?php echo $checkout; ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tamu</label>
                        <select class="form-control" id="guests" name="guests">
                            <option value="1" <?php echo $guests == '1' ? 'selected' : ''; ?>>1 Tamu</option>
                            <option value="2" <?php echo $guests == '2' ? 'selected' : ''; ?>>2 Tamu</option>
                            <option value="3" <?php echo $guests == '3' ? 'selected' : ''; ?>>3 Tamu</option>
                            <option value="4" <?php echo $guests == '4' ? 'selected' : ''; ?>>4+ Tamu</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-search w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Ringkasan Pencarian -->
        <?php if($destination): ?>
        <div class="search-summary">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-2"><i class="bi bi-geo-alt"></i> Hasil pencarian untuk "<?php echo $destination; ?>"</h4>
                    <p class="mb-0">
                        <i class="bi bi-calendar-event"></i> 
                        <?php if($checkin && $checkout): ?>
                            <?php echo date('d M Y', strtotime($checkin)); ?> - <?php echo date('d M Y', strtotime($checkout)); ?>
                        <?php else: ?>
                            Tanggal fleksibel
                        <?php endif; ?>
                        
                        <?php if($guests): ?>
                            <span class="ms-3"><i class="bi bi-people"></i> <?php echo $guests; ?> Tamu</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <h5 class="mb-0">12 destinasi ditemukan</h5>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Hasil Pencarian -->
        <div class="row">
            <?php
            // ============ KONEKSI DATABASE ============//
            $host = 'localhost';
            $username = 'root';
            $password = '';
            $database = 'traveleasyversi2'; // Ganti dengan nama database Anda
            
            $connection = mysqli_connect($host, $username, $password, $database);
            
            // Cek koneksi
            if (!$connection) {
                die("Koneksi gagal: " . mysqli_connect_error());
            }
            
            // ============ QUERY DATABASE ============
            $destinations = [];
            
            if($destination) {
                // Query pencarian berdasarkan nama destinasi dan lokasi
                $searchTerm = mysqli_real_escape_string($connection, $destination);
                $query = "SELECT * FROM destination
                         WHERE name LIKE '%$searchTerm%' 
                         OR location LIKE '%$searchTerm%' 
                         ORDER BY rating DESC";
                
                $result = mysqli_query($connection, $query);
                
                if($result && mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $destinations[] = $row;
                    }
                } else {
                    // Jika tidak ada hasil, tampilkan pesan atau data default
                    echo '<div class="col-12 text-center py-5">
                            <h4>Tidak ada destinasi yang ditemukan</h4>
                            <p>Coba gunakan kata kunci yang berbeda</p>
                          </div>';
                }
            } else {
                // Jika tidak ada pencarian, tampilkan semua destinasi
                $query = "SELECT * FROM destination ORDER BY rating DESC LIMIT 12";
                $result = mysqli_query($connection, $query);
                
                if($result && mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $destinations[] = $row;
                    }
                }
            }
            
            // ============ TAMPILKAN HASIL ============
            foreach($destinations as $dest): 
                // Konversi amenities dari JSON string ke array (jika disimpan sebagai JSON)
                $amenities = isset($dest['amenities']) ? json_decode($dest['amenities'], true) : [];
                if(!is_array($amenities)) {
                    $amenities = ['WiFi Gratis', 'Fasilitas Lengkap']; // Default jika tidak ada
                }
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card search-card h-100">
                    <div class="position-relative">
                        <img src="<?php echo $dest['image']; ?>" class="destination-image" alt="<?php echo $dest['name']; ?>">
                        <div class="price-tag">
                            Rp <?php echo number_format($dest['price'], 0, ',', '.'); ?>/malam
                        </div>
                        <div class="rating-badge">
                            <i class="bi bi-star-fill text-warning"></i>
                            <strong><?php echo $dest['rating']; ?></strong>
                            <small>(<?php echo $dest['reviews']; ?>)</small>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title mb-2"><?php echo $dest['name']; ?></h5>
                        <p class="text-muted mb-3">
                            <i class="bi bi-geo-alt"></i> <?php echo $dest['location']; ?>
                        </p>
                        
                        <div class="mb-3">
                            <?php foreach($amenities as $amenity): ?>
                                <span class="amenity-tag"><?php echo htmlspecialchars($amenity); ?></span>
                            <?php endforeach; ?>
            
            <?php
            // Tutup koneksi database
            
            ?>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Mulai dari</small><br>
                                <strong class="h6">Rp <?php echo number_format($dest['price'], 0, ',', '.'); ?></strong>
                            </div>
                            <button class="btn btn-book">
                                <a href="payment.php" class="bi bi-calendar-check">Pesan </a> 
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            <nav>
                <ul class="pagination">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Sebelumnya</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Selanjutnya</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Handle form pencarian ulang
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const destination = document.getElementById('destination').value;
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;
            const guests = document.getElementById('guests').value;
            
            if (!destination.trim()) {
                alert('Silakan masukkan destinasi yang ingin dicari');
                return;
            }
            
            const searchParams = new URLSearchParams({
                destination: destination,
                checkin: checkin,
                checkout: checkout,
                guests: guests
            });
            
            window.location.href = `sr.php?${searchParams.toString()}`;
        });

        // Handle tombol pesan
        document.querySelectorAll('.btn-book').forEach(btn => {
            btn.addEventListener('click', function() {
                const cardTitle = this.closest('.card').querySelector('.card-title').textContent;
                alert(`Anda akan memesan: ${cardTitle}`);
                // Redirect ke halaman booking atau buka modal booking
            });
        });
    </script>
</body>
</html>