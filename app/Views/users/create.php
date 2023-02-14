<h1>Create a New User</h1>
<p><a href="/users">Back to users list</a></p>
<form action="/users/store" method="post">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name">

    <label for="email">Email:</label>
    <input type="text" name="email" id="email">

    <label for="password">Pass:</label>
    <input type="password" name="password" id="password">
    
    <input type="submit" value="Create">
</form>
