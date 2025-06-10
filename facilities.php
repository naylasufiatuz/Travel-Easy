<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Easy - FACILITIES</title>
    <?php require('links.php')?>
    <style>
      .pop:hover{
        border-top-color: var(--teal) !important;
        transform: scale(1.03);
      }
    </style>
</head>
<body class="bg-light">

  <?php require('header.php');?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">OUR FACILITIES</h2>
    <div class="h-line bg-dark"></div>
    <p class="text-center mt-3">
      Nikmati berbagai fasilitas terbaik yang kami sediakan untuk kenyamanan dan pengalaman liburan Anda. Mulai dari akses internet gratis hingga layanan antar jemput, semua dirancang untuk membuat perjalanan Anda lebih praktis dan menyenangkan.
    </p>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-6 mb-5 px-4">
        <div  class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
          <div class="d-flex align-items-center mb-2">
            <img src="asset/orang.jpg" width="40px">
            <h5 class="m-0 ms-3">Travel Guide</h5>
            </div>
            <p>
              Tur lokal dengan pemandu profesional.
            </p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 mb-5 px-4">
        <div  class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
          <div class="d-flex align-items-center mb-2">
            <img src="asset/free parking.jpg" width="40px">
            <h5 class="m-0 ms-3">Parkir Gratis</h5>
            </div>
            <p>
              Layanan parkir gratis
            </p>
          
          
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-5 px-4">
        <div  class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
          <div class="d-flex align-items-center mb-2">
            <img src="asset/info.png" width="40px">
            <h5 class="m-0 ms-3">Informasi Destinasi</h5>
            </div>
            <p>
              Menyediakan informasi mengenai wisata di Yogyakarta
            </p>
          
          
        </div>
      </div>
    </div>
  </div>

   <?php require('footer.php');?>

</body>
</html>