  <!-- -------- START FOOTER 5 w/ DARK BACKGROUND ------- -->
  <footer class="footer py-5 bg-gradient-dark position-relative overflow-hidden">
    <img src="./assets/img/shapes/waves-white.svg" alt="pattern-lines" class="position-absolute start-0 top-0 w-100 opacity-6">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 me-auto mb-lg-0 mb-4 text-lg-start text-center">
          <h6 class="text-white font-weight-bolder text-uppercase mb-lg-4 mb-2">Listify</h6>
          <p class="text-sm opacity-8 mb-0 text-white mb-2">Listify is a business listing app that allows you to list your business.</p>
          <p class="text-sm text-white opacity-8 mb-0">
            Copyright © <span id="year"></span> Listify by Sahil and Farah.
          </p>
        </div>
        <div class="col-lg-6 ms-auto text-lg-end text-center">

        </div>
      </div>
    </div>
  </footer>
  <script>
    const yearElement = document.getElementById('year');
    const currentYear = new Date().getFullYear();
    yearElement.textContent = currentYear;
  </script>
