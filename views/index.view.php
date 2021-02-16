<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>365 Assessment</title>
    <script src="https://unpkg.com/vue@next"></script>
</head>

<body>

    <h1>Index</h1>
    <div id="vue">
        <users-table users = '<?=json_encode($users)?>'></users-table>
    </div>

</body>

<script>
    const app = Vue.createApp({})
    app.component('users-table', {
        props: {users: JSON},
        template: `
        <table>
            <thead>
                <tr>
                    <td>id</td>
                    <td>email</td>
                    <td>password</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in JSON.parse(users)">
                    <td>{{ user.id }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.password }}</td>
                </tr>
            </tbody>
        </table>`
    })
    app.mount('#vue')
</script>

</html>