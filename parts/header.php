<nav class="navbar navbar-expand-sm navbar-dark bg-dark" id="navbar">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pagename, 'home')) { echo 'active'; }?>" aria-current="page" href="?page=home">Home</a></li>
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pagename, 'portfolio')) { echo 'active'; }?>" href="?page=portfolio/">Portfolio</a></li>
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pagename, 'stock')) { echo 'active'; }?>" href="?page=stock/">Stock</a></li>
      </ul>
    </div>
  </div>
</nav>

<script>
  $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
    if (!$(this).next().hasClass('show')) {
      $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
    }
    var $subMenu = $(this).next('.dropdown-menu');
    $subMenu.toggleClass('show');
    $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
      $('.dropdown-submenu .show').removeClass('show');
    });
    return false;
  });
</script>