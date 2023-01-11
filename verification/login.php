<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cast - Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Bitter:wght@400;700&display=swap" rel="stylesheet">
  <link rel=icon type="image/png" href="/img/logo.png" sizes="32x32">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="/css/main.css">
  <link rel="stylesheet" href="/css/login.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/0.10.0/lodash.min.js"></script>
  <script src="/js/cookie.js"></script>
  <script src="/js/httprequest.js"></script>
</head>
<body>
  <form>
    <p id="cast-label">Cast</p>
    <div class="form-outline mb-4">
      <input type="username" id="username" class="form-control" placeholder="Username">
    </div>
    <div class="form-outline mb-4">
      <input type="password" id="password" class="form-control" placeholder="Password"/>
    </div>
    <button type="button" id="login" class="btn btn-primary mb-4">LOG IN</button>
    <div>
      <h id="authentification-failed" class="text-danger"></h>
    </div>
  </form>
</body>
</html>

<script>
document.getElementById("password").addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    document.getElementById("login").click();
  }
});

document.getElementById('login').onclick = function() {
  let request = new HttpRequest("http://lukasbreuer.de:8080/verification/login", "POST", [{key: "Content-Type", value: "application/json"}],
    {username: document.getElementById("username").value, password: document.getElementById("password").value});
  request.send(function (status, responseText) {
    if (status == 200) {
      Cookie.create("token", JSON.parse(responseText).apiKey, 60 * 60);
      Cookie.create("tokenExpiration", +new Date() + 1000 * 60 * 60, 60 * 60);
      location.reload();
    } else {
      document.getElementById("authentification-failed").innerHTML = "Authentification Failed!";
    }
  });
};
</script>