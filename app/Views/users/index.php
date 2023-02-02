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
                        <a href="/users/<?= $user['id'] ?>">Show </a>
                        <a href="/users/edit/<?= $user['id'] ?>"> Update </a>
                        <a href="/users/delete/<?= $user['id'] ?>"> Delete </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
