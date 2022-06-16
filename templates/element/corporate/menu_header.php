

<?php if (isset($_SESSION['username'])) { ?>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>

      <li class="nav-item dnone d-sm-inline-block">
        <a href="#" class="nav-link">Last Login: <?php echo $this->element('customer_last_login'); ?> [IP: <?php echo $_SESSION["ip_address"];?>]</a>
      </li>
	  
	  <li class="nav-item dnone d-sm-inline-block">
        <a href="../LIMS4.2" class="nav-link">Go to LIMS</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3 search-bx">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search Application - Status" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>


  </nav>
  <!-- /.navbar -->
<?php } ?>