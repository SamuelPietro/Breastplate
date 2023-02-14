<h1>Update User</h1>
<p><a href="/users">Back to users list</a></p>
<form action="/users/update/<?= $user['id'] ?>" method="post">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <label for="name">Name:</label>    
    <input type="text" name="name" id="name" value="<?= $user['name'] ?>">

    <label for="email">Email:</label>
    <input type="text" name="email" id="email" value="<?= $user['email'] ?>">

    <label for="password">Pass:</label>
    <input type="password" name="password" id="password" value="<?= $user['password'] ?>">

    <input type="submit" value="Update">
</form>
