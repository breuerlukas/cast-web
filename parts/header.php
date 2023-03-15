<nav class="navbar navbar-expand-sm navbar-dark bg-dark" id="navbar">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-content"
            aria-controls="navbar-content" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbar-content">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pageName, 'home')) { echo 'active'; }?>" aria-current="page" href="/home/">Home</a></li>
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pageName, 'dashboard')) { echo 'active'; }?>" href="/dashboard/">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pageName, 'finances')) { echo 'active'; }?>" href="/finances/">Finances</a></li>
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pageName, 'portfolio')) { echo 'active'; }?>" href="/portfolio/">Portfolio</a></li>
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pageName, 'trades')) { echo 'active'; }?>" href="/trades/">Trades</a></li>
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pageName, 'stock')) { echo 'active'; }?>" href="/stock/">Stock</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?php if(!strcmp($pageName, 'preset') || !strcmp($pageName, 'execution')) { echo 'active'; }?>"
             href="#" id="navbar-dropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Train</a>
          <div class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbar-dropdown">
            <a class="dropdown-item" href="/train/preset/">Preset</a>
            <a class="dropdown-item" href="/train/execution">Execution</a>
          </div>
        </li>
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pageName, 'tends')) { echo 'active'; }?>" href="/trends/">Trends</a></li>
      </ul>
    </div>
  </div>
</nav>