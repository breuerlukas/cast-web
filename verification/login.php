<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cast - Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Bitter:wght@400;700&display=swap" rel="stylesheet">
  <link rel=icon type="image/png" href="/img/logo.png" sizes="32x32">
  <link rel="stylesheet" href="/css/bootstrap.css" crossorigin="anonymous">
  <link rel="stylesheet" href="/css/main.css">
  <link rel="stylesheet" href="/css/login.css">
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/0.10.0/lodash.min.js"></script>
  <script src="/js/cookie.js"></script>
  <script src="/js/httprequest.js"></script>
</head>
<body>
  <form>
    <p id="castLabel">Cast</p>
    <div class="form-outline mb-4">
      <input type="username" id="usernameForm" class="form-control" placeholder="Username">
    </div>
    <div class="form-outline mb-4">
      <input type="password" id="passwordForm" class="form-control" placeholder="Password"/>
    </div>
    <button type="button" id="loginButton" class="btn btn-primary btn-block mb-4">LOG IN</button>
    <div>
      <h id="authentificationFailed" class="text-danger"></h>
    </div>
  </form>
</body>
</html>

<script>
document.getElementById("passwordForm").addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    document.getElementById("loginButton").click();
  }
});

document.getElementById('loginButton').onclick = function() {
  let request = new HttpRequest("http://lukasbreuer.de:8080/verification/login", "POST", [{key: "Content-Type", value: "application/json"}],
    {username: document.getElementById("usernameForm").value, password: document.getElementById("passwordForm").value});
  request.send(function (status, responseText) {
    if (status == 200) {
      Cookie.create("token", JSON.parse(responseText).apiKey, 60 * 60);
      Cookie.create("tokenExpiration", +new Date() + 1000 * 60 * 60, 60 * 60);
      location.reload();
    } else {
      document.getElementById("authentificationFailed").innerHTML = "Authentification Failed!";
    }
  });
};
</script>