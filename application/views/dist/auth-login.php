<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="<?php echo base_url(); ?>assets/img/stisla-fill.svg" alt="logo" width="100" class="shadow-light rounded-circle">
            </div>

            <div class="card card-primary">
              <div class="card-header">
                <h3>Login KIDIMAN</h3>
              </div>

              <div class="card-body">
                <form method="POST" action="#" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" class="form-control" name="username" tabindex="1" required autofocus>
                    <div class="invalid-feedback">
                      Please fill in your username
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="d-block">
                      <label for="password" class="control-label">Password</label>
                      <div class="float-right">
                        <a href="<?php echo base_url(); ?>dist/auth_forgot_password" class="text-small">
                          Forgot Password?
                        </a>
                      </div>
                    </div>
                    <div class="input-group">
                      <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                      <span class="input-group-text" id="showHide">
                        <i class="fa fa-eye"></i>
                      </span>
                    </div>
                    <div class="invalid-feedback">
                      please fill in your password
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                      <label class="custom-control-label" for="remember-me">Remember Me</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Login
                    </button>
                  </div>
                </form>

              </div>
            </div>
            <div class="simple-footer">
              Copyright &copy; Stisla 2018
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php $this->load->view('dist/_partials/js'); ?>

  <script>
    const password = document.getElementById('password'); // id dari input password
    const showHide = document.getElementById('showHide'); // id span showHide dalam input group password

    password.type = 'password'; // set type input password menjadi password\
    showHide.innerHTML = '<i class="fa fa-eye"></i>'; // masukan icon eye dalam icon bootstrap 5
    showHide.style.cursor = 'pointer'; // ubah cursor menjadi pointer

    showHide.addEventListener('click', () => {
      // ketika span diclick
      if (password.type === 'password') {
        // jika type inputnya password
        password.type = 'text'; // ubah type menjadi text
        showHide.innerHTML = '<i class="fa fa-eye-slash"></i>'; // ubah icon menjadi eye slash
      } else {
        // jika type bukan password (text)
        showHide.innerHTML = '<i class="fa fa-eye"></i>'; // ubah icon menjadi eye
        password.type = 'password'; // ubah type menjadi password
      }
    });
  </script>