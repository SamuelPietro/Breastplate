<h1>All Users</h1>
<p><a href="/users/create">Create a new user</a></p>
<table>
    <tr>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['name'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td>
                        
                        <form method="post" action="/users/delete/<?= $user['id'] ?>">
                            <a href="/users/show/<?= $user['id'] ?>"><button type="button">Show</button> </a>
                            <a href="/users/edit/<?= $user['id'] ?>"><button type="button">Update</button>  </a>
                            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                            <button type="submit" value="Delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
