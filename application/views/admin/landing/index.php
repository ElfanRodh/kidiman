<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />

  <title>Kidiman Perang</title>
  <meta content="" name="description" />
  <meta content="" name="keywords" />

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon" />
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet" />

  <!-- Vendor CSS Files -->
  <link href="<?= base_url("assets/landing/modules/aos/aos.css"); ?>" rel="stylesheet" />
  <link href="<?= base_url("assets/landing/modules/bootstrap/css/bootstrap.min.css"); ?>" rel="stylesheet" />
  <link href="<?= base_url("assets/landing/modules/bootstrap-icons/bootstrap-icons.css"); ?>" rel=" stylesheet" />
  <link href="<?= base_url("assets/landing/modules/boxicons/css/boxicons.min.css"); ?>" rel=" stylesheet" />
  <link href="<?= base_url("assets/landing/modules/glightbox/css/glightbox.min.css"); ?>" rel=" stylesheet" />
  <link href="<?= base_url("assets/landing/modules/remixicon/remixicon.css"); ?>" rel=" stylesheet" />
  <link href="<?= base_url("assets/landing/modules/swiper/swiper-bundle.min.css"); ?>" rel=" stylesheet" />

  <!-- Template Main CSS File -->
  <link href=<?= base_url("assets/landing/css/style.css"); ?> rel="stylesheet" />
</head>

<body>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">
      <h1 class="logo me-auto"><a href="#">KIDIMAN PERANG</a></h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index2.html" class="logo me-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
          <li><a class="nav-link scrollto" href="#about">Tentang</a></li>
          <li><a class="nav-link scrollto" href="#services">Kegiatan</a></li>
          <li><a class="nav-link scrollto" href="#team">Perangkat</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>
      <!-- .navbar -->
    </div>
  </header>
  <!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1" data-aos="fade-up" data-aos-delay="200">
          <h1>KIDIMAN PERANG </h1>
          <h2 class="text-white">Koreksi Disiplin Mandiri Perangkat Desa </h2>
          <h2>
            Solusi inovatif yang dirancang khusus untuk membantu meningkatkan efisiensi dan efektivitas tugas-tugas administratif di tingkat desa.
          </h2>
          <div class="d-flex justify-content-center justify-content-lg-start">
            <a href="<?= site_url('admin/home'); ?>" class="btn-get-started scrollto"><?php echo $login ?></a>
          </div>
        </div>
        <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
          <img src="assets/img/hero-img.png" class="img-fluid animated" alt="" />
        </div>
      </div>
    </div>
  </section>
  <!-- End Hero -->

  <main id="main">
    <!-- ======= About Us Section ======= -->
    <section id="about" class="about">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Tentang Kidiman</h2>
        </div>

        <div class="row content">
          <div class="col-lg-12">
            <p class="text-center">
              Aplikasi ini dibuat dengan fokus utama
              pada pemantauan dan peningkatan kedisiplinan para perangkat desa agar mampu menjalankan tugas-tugas mereka dengan lebih baik.
            </p>
            <p>
              Fitur utama dari aplikasi KIDIMAN PERANG mencakup:
            </p>
            <ul>
              <li>
                <i class="ri-team-line"></i> Data Perangkat Desa
              </li>
              <li>
                <i class="ri-stack-line"></i> Data Tugas Pokok Perangkat Desa
              </li>
              <li>
                <i class="ri-settings-line"></i> Data Fungsi Perangkat Desa
              </li>
              <li>
                <i class="ri-list-check-3"></i> Data Kegiatan Perangkat Desa
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <!-- End About Us Section -->

    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Kegiatan</h2>
          <p>
            Kegiatan Perangkat Desa
          </p>
        </div>

        <div class="row">
          <div class="col-xl-3 col-md-6 align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-list-check"></i></div>
              <h4><a href="">Kegiatan A</a></h4>
              <p>
                Fungsi Kegiatan A
              </p>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-list-check"></i></div>
              <h4><a href="">Kegiatan B</a></h4>
              <p>
                Fungsi Kegiatan B
              </p>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 align-items-stretch mt-4 mt-xl-0" data-aos="zoom-in" data-aos-delay="300">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-list-check"></i></div>
              <h4><a href="">Kegiatan C</a></h4>
              <p>
                Fungsi Kegiatan C
              </p>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 align-items-stretch mt-4 mt-xl-0" data-aos="zoom-in" data-aos-delay="400">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-list-check"></i></div>
              <h4><a href="">Kegiatan D</a></h4>
              <p>
                Fungsi Kegiatan D
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Services Section -->

    <!-- ======= Team Section ======= -->
    <section id="team" class="team section">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Tim Kidiman</h2>
          <p>
            Data Perangkat Desa KIDIMAN PERANG
          </p>
        </div>

        <div class="row">
          <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="100">
            <div class="member d-flex align-items-start">
              <div class="pic">
                <img src="<?php echo base_url(); ?>assets/img/avatar/team-a.png" class="img-fluid" alt="" />
              </div>
              <div class="member-info">
                <h4>Perangkat A</h4>
                <span>Chief Executive Officer</span>
                <p>
                  Explicabo voluptatem mollitia et repellat qui dolorum quasi
                </p>
                <div class="social">
                  <a href=""><i class="ri-twitter-fill"></i></a>
                  <a href=""><i class="ri-facebook-fill"></i></a>
                  <a href=""><i class="ri-instagram-fill"></i></a>
                  <a href=""> <i class="ri-linkedin-box-fill"></i> </a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6 mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="200">
            <div class="member d-flex align-items-start">
              <div class="pic">
                <img src="<?php echo base_url(); ?>assets/img/avatar/team-b.png" class="img-fluid" alt="" />
              </div>
              <div class="member-info">
                <h4>Perangkat B</h4>
                <span>Product Manager</span>
                <p>
                  Aut maiores voluptates amet et quis praesentium qui senda
                  para
                </p>
                <div class="social">
                  <a href=""><i class="ri-twitter-fill"></i></a>
                  <a href=""><i class="ri-facebook-fill"></i></a>
                  <a href=""><i class="ri-instagram-fill"></i></a>
                  <a href=""> <i class="ri-linkedin-box-fill"></i> </a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6 mt-4" data-aos="zoom-in" data-aos-delay="300">
            <div class="member d-flex align-items-start">
              <div class="pic">
                <img src="<?php echo base_url(); ?>assets/img/avatar/team-a.png" class="img-fluid" alt="" />
              </div>
              <div class="member-info">
                <h4>Perangkat C</h4>
                <span>CTO</span>
                <p>
                  Quisquam facilis cum velit laborum corrupti fuga rerum quia
                </p>
                <div class="social">
                  <a href=""><i class="ri-twitter-fill"></i></a>
                  <a href=""><i class="ri-facebook-fill"></i></a>
                  <a href=""><i class="ri-instagram-fill"></i></a>
                  <a href=""> <i class="ri-linkedin-box-fill"></i> </a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6 mt-4" data-aos="zoom-in" data-aos-delay="400">
            <div class="member d-flex align-items-start">
              <div class="pic">
                <img src="<?php echo base_url(); ?>assets/img/avatar/team-b.png" class="img-fluid" alt="" />
              </div>
              <div class="member-info">
                <h4>Perangkat D</h4>
                <span>Accountant</span>
                <p>
                  Dolorum tempora officiis odit laborum officiis et et
                  accusamus
                </p>
                <div class="social">
                  <a href=""><i class="ri-twitter-fill"></i></a>
                  <a href=""><i class="ri-facebook-fill"></i></a>
                  <a href=""><i class="ri-instagram-fill"></i></a>
                  <a href=""> <i class="ri-linkedin-box-fill"></i> </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Team Section -->
  </main>
  <!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container footer-bottom clearfix">
      <div class="copyright">
        Copyright &copy; 2023
      </div>
    </div>
  </footer>
  <!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="<?= base_url("assets/landing/modules/aos/aos.js"); ?>"></script>
  <script src="<?= base_url("assets/landing/modules/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
  <script src="<?= base_url("assets/landing/modules/glightbox/js/glightbox.min.js"); ?>"></script>
  <script src="<?= base_url("assets/landing/modules/isotope-layout/isotope.pkgd.min.js"); ?>"></script>
  <script src="<?= base_url("assets/landing/modules/swiper/swiper-bundle.min.js"); ?>"></script>
  <script src="<?= base_url("assets/landing/modules/waypoints/noframework.waypoints.js"); ?>"></script>

  <!-- Template Main JS File -->
  <script src="<?= base_url("assets/landing/js/main.js"); ?>">
  </script>
</body>

</html>