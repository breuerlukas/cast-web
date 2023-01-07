<nav class="navbar navbar-expand-sm navbar-dark bg-dark" id="navbar">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbar-container">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pageName, 'home')) { echo 'active'; }?>" aria-current="page" href="?page=home">Home</a></li>
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pageName, 'portfolio')) { echo 'active'; }?>" href="?page=portfolio/">Portfolio</a></li>
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pageName, 'trades')) { echo 'active'; }?>" href="?page=trades/">Trades</a></li>
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pageName, 'stock')) { echo 'active'; }?>" href="?page=stock/">Stock</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbar-dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Training
          </a>
          <div class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbar-dropdown">
            <a class="dropdown-item" href="#">Section</a>
            <a class="dropdown-item" href="#">Another action</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>