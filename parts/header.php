<nav class="navbar navbar-expand-sm navbar-dark bg-dark" id="navbar">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pagename, 'home')) { echo 'active'; }?>" aria-current="page" href="?page=home">Home</a></li>
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pagename, 'calendar')) { echo 'active'; }?>" href="?page=calendar/">Calendar</a></li>
        <li class="nav-item">
          <div id="navbar-school-regular">
            <a class="nav-link <?php if(!strcmp($pagename, 'school')) { echo 'active'; }?>" href="?page=school/homework/">School</a>
          </div>
          <div id="navbar-school-small">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              School
            </a>
            <ul class="dropdown-menu bg-dark dropdown-menu-dark" aria-labelledby="navbarDropdownMenuLink">
              <li><a class="dropdown-item" href="?page=school/timetable/">Timetable</a></li>
              <li><a class="dropdown-item" href="?page=school/homework/">Homework</a></li>
              <li><a class="dropdown-item" href="#">Exam</a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item"><a class="nav-link <?php if(!strcmp($pagename, 'development')) { echo 'active'; }?>" href="?page=development/">Development</a></li>
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