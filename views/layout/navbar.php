<link rel="stylesheet" href="views/layout/css/navbar.css">
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
  <div class="position-sticky pt-3">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link active disabled" aria-current="page" href="#">
          <span data-feather="home"></span>
          Tables
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="index.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-user"></i> Users</a>
        <ul class="dropdown-menu dropdown-menu-dark">
          <li><a class="dropdown-item" href="index.php?table=user&action=create">Add New</a></li>
          <li><a class="dropdown-item" href="index.php?table=user&action=list">List </a></li>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="index.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="assets/img/logoMonochrome.png" class="iconDigimon" style="height: 20px; width:20px; margin-left:-3px; margin-right:3px">Classes</a>
        <ul class="dropdown-menu dropdown-menu-dark">
          <li><a class="dropdown-item" href="index.php?table=class&action=create">Add New</a></li>
          <li><a class="dropdown-item" href="index.php?table=class&action=list">List </a></li>
          <li><a class="dropdown-item" href="index.php?table=class&action=search">Search </a></li>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="index.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="assets/img/logoMonochrome.png" class="iconDigimon" style="height: 20px; width:20px; margin-left:-3px; margin-right:3px">Skills</a>
        <ul class="dropdown-menu dropdown-menu-dark">
          <li><a class="dropdown-item" href="index.php?table=skill&action=create">Add New</a></li>
          <li><a class="dropdown-item" href="index.php?table=skill&action=list">List </a></li>
          <li><a class="dropdown-item" href="index.php?table=skill&action=search">Search </a></li>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="index.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="assets/img/logoMonochrome.png" class="iconDigimon" style="height: 20px; width:20px; margin-left:-3px; margin-right:3px">Units</a>
        <ul class="dropdown-menu dropdown-menu-dark">
          <li><a class="dropdown-item" href="index.php?table=unit&action=create">Add New</a></li>
          <li><a class="dropdown-item" href="index.php?table=unit&action=list">List </a></li>
          <li><a class="dropdown-item" href="index.php?table=unit&action=search">Search </a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>