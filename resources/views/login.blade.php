<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>title</title>
</head>
<body>
  <form method="POST" action="/ncms/auth/login">
    @csrf
    <input type="text" name="email" placeholder="email" />
    <input type="password" name="password" placeholder="password" />
    <button type="submit">Login</button>
  </form>
</body>
</html>
