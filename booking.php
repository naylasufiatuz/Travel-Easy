<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Easy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php require('links.php')?>
</head>
<body class="min-h-screen gradient-bg">
    <!-- Header -->
    <header class="bg-white shadow-lg">
         <nav class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand me-5 fw-bold fs-3" href="index.php">Travel Easy</a>
                <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                 <button onclick="history.back()" class="flex items-center space-x-2 text-gray-600 hover:text-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali</span>
                </button>
            </div>
        </nav>   
       
    </header>

    <!-- Destination Selector -->
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
                <h3 class="text-lg font-semibold mb-3 text-gray-800">Pilih Destinasi:</h3>
                <div class="flex flex-wrap gap-2">
                    <a href="#candi-borobudur" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">Candi Borobudur</a>
                    <a href="#museum-affandi" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">Museum Affandi</a>
                    <a href="#keraton-yogya" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors">Keraton Yogyakarta</a>
                    <a href="#candi-prambanan" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">Candi Prambanan</a>
                    <a href="#taman-sari" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">Taman Sari</a>
                    <a href="#pantai-parangtritis" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">Pantai Parangtritis</a>
                    <a href="#goa-jomblang" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">Goa Jomblang</a>


                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 pb-8">
        <div class="max-w-6xl mx-auto">
            
            <!-- Candi Borobudur -->
            <div id="candi-borobudur" class="destination-content default">
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-8 fade-in hover-scale">
                    <div class="relative h-64 ">
                        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <h2 class="text-3xl font-bold mb-2">Candi Borobudur</h2>
                            <p class="text-lg opacity-90">Magelang, dekat Yogyakarta</p>
                        </div>
                        <div class="absolute top-6 right-6 glass-effect rounded-full px-4 py-2">
                            <div class="flex items-center space-x-1 text-yellow-300">
                                <span class="text-lg font-bold">4.5</span>
                                <div class="flex">★★★★☆</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Tentang Candi</h3>
                                <p class="text-gray-600 mb-4">
                                    Candi Buddha terbesar di dunia, warisan UNESCO.
                                </p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-sm text-gray-500">Kamar</div>
                                        <div class="font-semibold">2 Kamar Tidur</div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-sm text-gray-500">Kapasitas</div>
                                        <div class="font-semibold">5 Dewasa, 4 Anak</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Fasilitas</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Free WiFi</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">AC</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Television</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Bathtub</span>
                                    </div>
                                </div>
                            </div>
                            <a href="payment.php" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-orange-600 transition-colors">payment</a>
                        </div>
                    </div>
                </div>
                <div class="booking-section" data-price="450000" data-type="hotel"></div>
            </div>

            <!-- Museum Affandi -->
            <div id="museum-affandi" class="destination-content">
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-8 fade-in hover-scale">
                    <div class="relative h-64 bg-gradient-to-r from-orange-400 to-red-500">
                        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <h2 class="text-3xl font-bold mb-2">Museum Affandi</h2>
                            <p class="text-lg opacity-90">Museum Seni Lukis Terkenal di Yogyakarta</p>
                        </div>
                        <div class="absolute top-6 right-6 glass-effect rounded-full px-4 py-2">
                            <div class="flex items-center space-x-1 text-yellow-300">
                                <span class="text-lg font-bold">4.7</span>
                                <div class="flex">★★★★★</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Tentang Museum</h3>
                                <p class="text-gray-600 mb-4">
                                    Museum Affandi menampilkan koleksi karya seni lukis maestro Indonesia, Affandi. 
                                    Terletak di tepi Sungai Gajah Wong, museum ini menawarkan pengalaman seni yang unik 
                                    dengan arsitektur yang khas dan koleksi yang menginspirasi.
                                </p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-sm text-gray-500">Jam Buka</div>
                                        <div class="font-semibold">09.00 - 15.00</div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-sm text-gray-500">Durasi Kunjungan</div>
                                        <div class="font-semibold">2-3 Jam</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Fasilitas</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Pemandu Wisata</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Toko Souvenir</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Area Parkir</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Galeri Seni</span>
                                    </div>
                                </div>
                            </div>
                            <a href="payment.php" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-orange-600 transition-colors">payment</a>
                        </div>
                    </div>
                </div>
                <div class="booking-section" data-price="25000" data-type="ticket"></div>
            </div>

            <!-- Keraton Yogyakarta -->
            <div id="keraton-yogya" class="destination-content">
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-8 fade-in hover-scale">
                    <div class="relative h-64 bg-gradient-to-r from-yellow-400 to-orange-500">
                        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <h2 class="text-3xl font-bold mb-2">Keraton Yogyakarta</h2>
                            <p class="text-lg opacity-90">Istana Sultan & Pusat Budaya Jawa</p>
                        </div>
                        <div class="absolute top-6 right-6 glass-effect rounded-full px-4 py-2">
                            <div class="flex items-center space-x-1 text-yellow-300">
                                <span class="text-lg font-bold">4.8</span>
                                <div class="flex">★★★★★</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Tentang Keraton</h3>
                                <p class="text-gray-600 mb-4">
                                    Keraton Yogyakarta adalah istana resmi Kesultanan Ngayogyakarta Hadiningrat. 
                                    Menjadi pusat budaya Jawa yang masih aktif, keraton ini menawarkan wisata sejarah, 
                                    budaya, dan arsitektur tradisional Jawa yang memukau.
                                </p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-sm text-gray-500">Jam Buka</div>
                                        <div class="font-semibold">08.00 - 14.00</div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-sm text-gray-500">Durasi Kunjungan</div>
                                        <div class="font-semibold">3-4 Jam</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Fasilitas</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Museum</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Pertunjukan Budaya</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Taman Sari</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Toko Batik</span>
                                    </div>
                                </div>
                            </div>
                            <a href="payment.php" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-orange-600 transition-colors">payment</a>
                        </div>
                    </div>
                </div>
                <div class="booking-section" data-price="15000" data-type="ticket"></div>
            </div>

            <!-- Candi Prambanan -->
            <div id="candi-prambanan" class="destination-content">
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-8 fade-in hover-scale">
                    <div class="relative h-64 bg-gradient-to-r from-pink-400 to-purple-500">
                        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <h2 class="text-3xl font-bold mb-2">Candi Prambanan</h2>
                            <p class="text-lg opacity-90">Sleman, Yogyakarta</p>
                        </div>
                        <div class="absolute top-6 right-6 glass-effect rounded-full px-4 py-2">
                            <div class="flex items-center space-x-1 text-yellow-300">
                                <span class="text-lg font-bold">4.6</span>
                                <div class="flex">★★★★☆</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Tentang Candi</h3>
                                <p class="text-gray-600 mb-4">
                                   Kompleks candi Hindu terbesar di Indonesia
                                </p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-sm text-gray-500">Buka</div>
                                        <div class="font-semibold">24 Jam</div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-sm text-gray-500">Durasi Kunjungan</div>
                                        <div class="font-semibold">Flexible</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Aktivitas</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Toilet</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Candi</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Street Performance</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Foto</span>
                                    </div>
                                </div>
                            </div>
                            <a href="payment.php" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-orange-600 transition-colors">payment</a>
                        </div>
                    </div>
                </div>
                <div class="booking-section" data-price="0" data-type="free"></div>
            </div>

            <!-- Pantai Parangtritis -->
             <div id="pantai-parangtritis" class="destination-content">
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-8 fade-in hover-scale">
                    <div class="relative h-64 bg-gradient-to-r from-pink-400 to-purple-500">
                        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <h2 class="text-3xl font-bold mb-2">Pantai Parangtritis</h2>
                            <p class="text-lg opacity-90">Bantul, Yogyakarta</p>
                        </div>
                        <div class="absolute top-6 right-6 glass-effect rounded-full px-4 py-2">
                            <div class="flex items-center space-x-1 text-yellow-300">
                                <span class="text-lg font-bold">4.6</span>
                                <div class="flex">★★★★☆</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Tentang Pantai</h3>
                                <p class="text-gray-600 mb-4">
                                   Pantai legendaris dengan ombak besar dan sunset.
                                </p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-sm text-gray-500">Buka</div>
                                        <div class="font-semibold">24 Jam</div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-sm text-gray-500">Durasi Kunjungan</div>
                                        <div class="font-semibold">Flexible</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Aktivitas</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Toilet</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Food Court</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Street Performance</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Foto Instagramable</span>
                                    </div>
                                </div>
                            </div>
                            <a href="payment.php" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-orange-600 transition-colors">payment</a>
                        </div>
                    </div>
                </div>
                <div class="booking-section" data-price="0" data-type="free"></div>
            </div>

            <!-- Taman Sari -->
             <div id="taman-sari" class="destination-content">
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-8 fade-in hover-scale">
                    <div class="relative h-64 bg-gradient-to-r from-yellow-400 to-orange-500">
                        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <h2 class="text-3xl font-bold mb-2">Taman Sari</h2>
                            <p class="text-lg opacity-90">Yogyakarta Kota</p>
                        </div>
                        <div class="absolute top-6 right-6 glass-effect rounded-full px-4 py-2">
                            <div class="flex items-center space-x-1 text-yellow-300">
                                <span class="text-lg font-bold">4.8</span>
                                <div class="flex">★★★★★</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Tentang Taman</h3>
                                <p class="text-gray-600 mb-4">
                                    Istana air bekas tempat rekreasi keluarga kerajaan.
                                </p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-sm text-gray-500">Jam Buka</div>
                                        <div class="font-semibold">08.00 - 14.00</div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-sm text-gray-500">Durasi Kunjungan</div>
                                        <div class="font-semibold">3-4 Jam</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Fasilitas</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Museum</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Pertunjukan Budaya</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Taman Sari</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Toko Batik</span>
                                    </div>
                                </div>
                            </div>
                            <a href="payment.php" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-orange-600 transition-colors">payment</a>
                        </div>
                    </div>
                </div>
                <div class="booking-section" data-price="15000" data-type="ticket"></div>
            </div>

            <!-- Goa Jomblang -->
             <div id="goa-jomblang" class="destination-content">
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-8 fade-in hover-scale">
                    <div class="relative h-64 bg-gradient-to-r from-yellow-400 to-orange-500">
                        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <h2 class="text-3xl font-bold mb-2">Goa Jomblang</h2>
                            <p class="text-lg opacity-90">Gunungkidul, Yogyakarta</p>
                        </div>
                        <div class="absolute top-6 right-6 glass-effect rounded-full px-4 py-2">
                            <div class="flex items-center space-x-1 text-yellow-300">
                                <span class="text-lg font-bold">4.8</span>
                                <div class="flex">★★★★★</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Goa Jomblang</h3>
                                <p class="text-gray-600 mb-4">
                                    Goa vertikal dengan cahaya surga yang memukau
                                </p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-sm text-gray-500">Jam Buka</div>
                                        <div class="font-semibold">08.00 - 14.00</div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-sm text-gray-500">Durasi Kunjungan</div>
                                        <div class="font-semibold">3-4 Jam</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-xl font-semibold mb-4 text-gray-800">Fasilitas</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Museum</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Pertunjukan Budaya</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Taman Sari</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm">Toko Batik</span>
                                    </div>
                                </div>
                            </div>
                            <a href="payment.php" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-orange-600 transition-colors">payment</a>
                        </div>
                    </div>
                </div>
                <div class="booking-section" data-price="15000" data-type="ticket"></div>
            </div>

        </div>
    </div>

    <!-- Universal Booking -->
    <script id="booking-template" type="text/template">
        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-2xl p-6 fade-in">
                    <h3 class="text-2xl font-bold mb-6 text-gray-800">Detail Reservasi</h3>
                    
                    <form class="booking-form space-y-6">
                        <div class="grid md:grid-cols-2 gap-4 date-section">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kunjungan</label>
                                <input type="date" name="visit-date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            <div class="checkout-section" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Check-out</label>
                                <input type="date" name="checkout" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Dewasa</label>
                                <select name="adults" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    <option value="1">1 Dewasa</option>
                                    <option value="2" selected>2 Dewasa</option>
                                    <option value="3">3 Dewasa</option>
                                    <option value="4">4 Dewasa</option>
                                    <option value="5">5 Dewasa</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Anak</label>
                                <select name="children" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    <option value="0" selected>0 Anak</option>
                                    <option value="1">1 Anak</option>
                                    <option value="2">2 Anak</option>
                                    <option value="3">3 Anak</option>
                                    <option value="4">4 Anak</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="fullname" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Masukkan nama lengkap" required>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="contoh@email.com" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                                <input type="tel" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Permintaan Khusus (Opsional)</label>
                            <textarea name="special-request" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Tuliskan permintaan khusus Anda..."></textarea>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-2xl p-6 fade-in">
                    <h3 class="text-xl font-bold mb-4 text-gray-800">Ringkasan Harga</h3>
                    
                    <div class="space-y-3 mb-4 price-breakdown">
                        <!-- Price content will be injected here -->
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-2xl p-6 fade-in payment-section">
                    <h3 class="text-xl font-bold mb-4 text-gray-800">Metode Pembayaran</h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                            <input type="radio" name="payment" value="bank" class="mr-3" checked>
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
                            <input type="radio" name="payment" value="ewallet" class="mr-3">
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
                    </div>
                </div>
                
                <button onclick="processBooking(this)" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 rounded-xl font-bold text-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 shadow-xl">
                    Konfirmasi Pemesanan
                </button>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 policy-section">
                    <!-- Policy content will be injected here -->