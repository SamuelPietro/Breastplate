<h1>Update User</h1>
<p><a href="/users">Back to users list</a></p>
<form action="/users/edit/<?= $user['id'] ?>" method="post">
    <label for="name">Name:</label>    <input type="text" name="name" id="name" value="<?= $user['name'] ?>">

    <label for="email">Email:</label>
    <input type="text" name="email" id="email" value="<?= $user['email'] ?>">

    <label for="pass">Pass:</label>
    <input type="password" name="pass" id="pass" value="<?= $user['pass'] ?>">

    <input type="submit" value="Update">
</form>
