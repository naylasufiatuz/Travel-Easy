<?php
// Koneksi database
require('db_config.php'); // File konfigurasi database

$message_sent = false;
$error_message = '';

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $subject = mysqli_real_escape_string($con, $_POST['subject']);
    $message = mysqli_real_escape_string($con, $_POST['message']);
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : NULL;
    
    // Validasi input
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = "Semua field harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format email tidak valid!";
    } else {
        // Insert ke database dengan status 'approved' agar langsung tampil
        // Atau bisa tetap 'pending' tapi tambahkan logika untuk menampilkan pesan terbaru
        $query = "INSERT INTO reviews (name, email, subject, message, rating, status) VALUES (?, ?, ?, ?, ?, 'approved')";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ssssi", $name, $email, $subject, $message, $rating);
        
        if (mysqli_stmt_execute($stmt)) {
            $message_sent = true;
            // Clear form data setelah berhasil
            $_POST = array();
        } else {
            $error_message = "Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Ambil review yang sudah disetujui untuk ditampilkan
$approved_reviews_query = "SELECT name, message, rating, created_at FROM reviews WHERE status = 'approved' ORDER BY created_at DESC LIMIT 6";
$approved_reviews_result = mysqli_query($con, $approved_reviews_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Easy - CONTACT</title>
    <?php require('links.php')?>
    <style>
        .star-rating {
            color: #ffc107;
        }
        .alert {
            margin-top: 20px;
        }
        .review-card {
            border-left: 4px solid #007bff;
            background: #f8f9fa;
        }
        .new-review {
            border-left: 4px solid #28a745;
            animation: fadeIn 1s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-light">

  <?php require('header.php');?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">CONTACT US</h2>
    <div class="h-line bg-dark"></div>
    <p class="text-center mt-3">
      Hubungi kami untuk pertanyaan, saran, atau bantuan terkait layanan Travel Easy. 
      <br>
      Tim kami siap membantu Anda dengan pelayanan terbaik.
    </p>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-6 mb-5 px-4">

        <div class="bg-white rounded shadow p-4">
        <iframe class="w-100 rounded mb-4" height="320px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63245.98371948572!2d110.33364493041586!3d-7.803163418859884!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5787bd5b6bc5%3A0x21723fd4d3684f71!2sYogyakarta%2C%20Yogyakarta%20City%2C%20Special%20Region%20of%20Yogyakarta!5e0!3m2!1sen!2sid!4v1747567718097!5m2!1sen!2sid" loading="lazy"></iframe>

        <h5>Address</h5>
        <a href="https://maps.app.goo.gl/tELKFqbtdqboDcYS9" target="_blank" class="d-inline block text-decoration-none text-dark mb-2">
          <i class="bi bi-geo-alt-fill"></i> Patehan, Kraton, Yogyakarta City, Special Region of Yogyakarta 55133
        </a>

        <h5 class="mt-4">Call Us</h5>
          <a href="https://wa.me/625740277090" class="d-inline-block mb-2 text-decoration-none text-dark" target="_blank">
            Hubungi Kami via WhatsApp
            <br>
            <i class="bi bi-whatsapp"></i> Chat via WhatsApp
          </a>

          <h5 class="mt-4">Email</h5>
          <a href="mailto:ask.traveleasy@gmail.com" class="d-inline-block mb-2 text-decoration-none text-dark">
            <i class="bi bi-envelope-fill"></i> ask.traveleasy@gmail.com
          </a>

          <h5 class="mt-4">Follow Us</h5>
          <a href="https://www.instagram.com/nyshafl" class="d-inline-block mb-3 text-dark fs-5 me-2">
              <i class="bi bi-instagram"></i>
          </a>
          <a href="https://x.com/sufiatuz" class="d-inline-block text-dark fs-5">
              <i class="bi bi-twitter"></i>
          </a>
        </div>
      </div>
      
      <div class="col-lg-6 col-md-6 px-4">
        <div class="bg-white rounded shadow p-4">
          
          <!-- Alert Messages -->
          <?php if ($message_sent): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> Pesan Anda berhasil dikirim dan akan segera tampil di bagian review!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          <?php endif; ?>

          <?php if (!empty($error_message)): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          <?php endif; ?>

          <form method="POST" action="">
            <h5>Send a message</h5>
            
            <div class="mt-3">
              <label class="form-label" style="font-weight: 500;">Name *</label>
              <input type="text" name="name" class="form-control shadow-none" required value="<?php echo ($message_sent ? '' : (isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '')); ?>">
            </div>
            
            <div class="mt-3">
              <label class="form-label" style="font-weight: 500;">Email *</label>
              <input type="email" name="email" class="form-control shadow-none" required value="<?php echo ($message_sent ? '' : (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '')); ?>">
            </div>
            
            <div class="mt-3">
              <label class="form-label" style="font-weight: 500;">Subject *</label>
              <input type="text" name="subject" class="form-control shadow-none" required value="<?php echo ($message_sent ? '' : (isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : '')); ?>">
            </div>
            
            <div class="mt-3">
              <label class="form-label" style="font-weight: 500;">Rating (Optional)</label>
              <select name="rating" class="form-control shadow-none">
                <option value="">Pilih Rating</option>
                <option value="5" <?php echo (!$message_sent && isset($_POST['rating']) && $_POST['rating'] == '5') ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ Excellent</option>
                <option value="4" <?php echo (!$message_sent && isset($_POST['rating']) && $_POST['rating'] == '4') ? 'selected' : ''; ?>>⭐⭐⭐⭐ Good</option>
                <option value="3" <?php echo (!$message_sent && isset($_POST['rating']) && $_POST['rating'] == '3') ? 'selected' : ''; ?>>⭐⭐⭐ Average</option>
                <option value="2" <?php echo (!$message_sent && isset($_POST['rating']) && $_POST['rating'] == '2') ? 'selected' : ''; ?>>⭐⭐ Poor</option>
                <option value="1" <?php echo (!$message_sent && isset($_POST['rating']) && $_POST['rating'] == '1') ? 'selected' : ''; ?>>⭐ Very Poor</option>
              </select>
            </div>
            
            <div class="mt-3">
              <label class="form-label" style="font-weight: 500;">Message *</label>
              <textarea name="message" class="form-control shadow-none" rows="5" style="resize-none" required><?php echo ($message_sent ? '' : (isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '')); ?></textarea>
            </div>
            
            <button type="submit" class="btn text-white custom-bg mt-3">
              <i class="bi bi-send"></i> SEND MESSAGE
            </button>
          </form>
        </div>
      </div>
    </div>

   
    <?php if (mysqli_num_rows($approved_reviews_result) > 0): ?>
    <div class="row mt-5">
      <div class="col-12">
        <h3 class="text-center mb-4">What Our Customers Say</h3>
        <div class="row">
          <?php 
          $is_first = true;
          while ($review = mysqli_fetch_assoc($approved_reviews_result)): 
           
            $is_new = (time() - strtotime($review['created_at'])) < 60;
          ?>
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 <?php echo $is_new ? 'new-review' : 'review-card'; ?>">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="card-title mb-0">
                    <?php echo htmlspecialchars($review['name']); ?>
                    <?php if ($is_new): ?>
                      <span class="badge bg-success ms-2">New!</span>
                    <?php endif; ?>
                  </h6>
                  <?php if ($review['rating']): ?>
                  <div class="star-rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                      <?php if ($i <= $review['rating']): ?>
                        <i class="bi bi-star-fill"></i>
                      <?php else: ?>
                        <i class="bi bi-star"></i>
                      <?php endif; ?>
                    <?php endfor; ?>
                  </div>
                  <?php endif; ?>
                </div>
                <p class="card-text"><?php echo htmlspecialchars(substr($review['message'], 0, 150)) . (strlen($review['message']) > 150 ? '...' : ''); ?></p>
                <small class="text-muted"><?php echo date('d M Y H:i', strtotime($review['created_at'])); ?></small>
              </div>
            </div>
          </div>
          <?php 
          $is_first = false;
          endwhile; 
          ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>

   <?php require('footer.php');?>

   <script>
   // Auto-scroll ke section review jika pesan baru berhasil dikirim
   <?php if ($message_sent): ?>
   setTimeout(function() {
       document.querySelector('.row.mt-5').scrollIntoView({ 
           behavior: 'smooth',
           block: 'start'
       });
   }, 1500);
   <?php endif; ?>
   </script>

</body>
</html>