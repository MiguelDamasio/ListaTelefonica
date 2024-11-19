<div class="row">
  <nav class="navbar navbar-expand-lg">
    <div class="container secondary-container" style="margin-top: 0px">
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100 d-flex justify-content-between align-items-center">
          <li class="nav-item text-center">
            <a class="nav-link" style="text-decoration:none;">
              <h1>Lista Telefónica</h1>
            </a>
          </li>
          <li class="nav-item">
            <div class="d-flex align-items-center">
              <span class="nav-link " style="color:white; font-size: 20px">Utilizador: <?php echo $_SESSION["user"] ?></span>
              <a href="logout.php" class="btn ms-3 btn-success btn-primary" style="font-size: 20px; text-decoration:none;">Logout</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</div>
