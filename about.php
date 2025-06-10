<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Easy - ABOUT</title>
    <?php require('links.php')?>
    <style>
      .box{
        border-top-color: var(--teal) !important;
      }

      body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 40px;
      background-color: #f8f9fa;
    }

    h2 {
      text-align: center;
      
      margin-bottom: 30px;
    }

    .team-container {
      display: flex;
      overflow-x: auto;
      gap: 20px;
      padding: 10px;
      scroll-snap-type: x mandatory;
      -webkit-overflow-scrolling: touch;
    }

    .team-card {
      flex: 0 0 auto;
      width: 250px;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      text-align: center;
      scroll-snap-align: start;
    }

    .team-card img {
      width: 100%;
      height: 300px;
      object-fit: cover;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
    }

    .team-card p {
      margin: 10px 0 20px;
      font-weight: bold;
    }


    </style>
</head>
<body class="bg-light">

  <?php require('header.php');?>

   <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">ABOUT US</h2>
    <div class="h-line bg-dark"></div>
  </div>
  <div class="container">
    <div class="row justify-content-between align-items-center">
      <div class="col-lg-6 col-md-5 mb-4 oreder-lg-1 order-md-1 order-2">
        <h3 class="mb-3">Travel Easy</h3>
      
      <p>
        Travel Easy adalah platform informasi pariwisata dan kuliner yang hadir untuk memudahkan Anda menjelajahi keindahan destinasi lokal maupun nasional. Kami menyediakan panduan lengkap seputar tempat wisata menarik, pusat oleh-oleh khas, hingga rekomendasi kuliner yang wajib dicoba.
        Dengan semangat memperkenalkan kekayaan budaya dan keindahan alam Indonesia, kami berkomitmen memberikan informasi akurat, inspiratif, dan mudah diakses bagi siapa pun yang ingin merencanakan perjalanan yang tak terlupakan.
        Temukan tempat favorit baru, nikmati cita rasa khas daerah, dan jelajahi pengalaman yang tidak terlupakan — semuanya bersama Travel Easy.
      </p>
    </div>
    <div class="col-lg-5 col-md-5 mb-4 oreder-lg-2 order-md-2 order-1">
      <img src="asset/obelix sea view.jpg" class="w-100">
    </div>
    </div>
  </div>
  
  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box"> 
          <img src="asset/obelix sea view.jpg" width="70px">
          <h4 class="mt-3">100+ DESTINATION</h4>
        </div>
        
      </div>
       <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box"> 
          <img src="asset/obelix sea view.jpg" width="70px">
          <h4 class="mt-3">100+ <br> CUSTOMERS</h4>
        </div>  
      </div>
       <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box"> 
          <img src="asset/obelix sea view.jpg" width="70px">
          <h4 class="mt-3">100+ <br> REVIEWS</h4>
        </div>
        
      </div>
       <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box"> 
          <img src="asset/obelix sea view.jpg" width="70px">
          <h4 class="mt-3">100+ TEAM</h4>
        </div>
        
      </div>
    </div>
  </div>

  <h3 class="my-5 fw-bold h-font text-center">MANAGEMENT TEAM</h3>

 <div class="team-container">
    <div class="team-card">
      <img src="managementteam.jpg" alt="Team Member">
      <p>Aldo Danindra</p>
    </div>
    <div class="team-card">
      <img src="managementteam.jpg" alt="Team Member">
      <p>Rina Nayra</p>
    </div>
    <div class="team-card">
      <img src="managementteam.jpg" alt="Team Member">
      <p>Risna Nina</p>
    </div>
    <div class="team-card">
      <img src="managementteam.jpg" alt="Team Member">
      <p>Bobby Adrean</p>
    </div>
    <div class="team-card">
      <img src="managementteam.jpg" alt="Team Member">
      <p>Ghina Nashu</p>
    </div>
    <div class="team-card">
      <img src="managementteam.jpg" alt="Team Member">
      <p>Siska</p>
    </div>
  </div>

   <?php require('footer.php');?>

</body>
</html>