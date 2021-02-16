<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $config['title'] ?> Inloggen</title>
    <script src="https://unpkg.com/vue@next"></script>
</head>

<body>

    <div id="vue">
        <!-- <input v-model='form.email' placeholder='enter email address...' />
        <input v-model='form.password' placeholder='enter password...' />

        <p>email is: {{ form.email }}</p>
        <p>password is: {{ form.password }}</p> -->
        <login-form />
    </div>

</body>

</html>

<script>
    const app = Vue.createApp({})
    app.component('login-form', {
        data() {
            return {
                form: {
                    email: '',
                    password: '',
                },
            }
        },
        template: `
        <form method="post" action="login.php">
        <input v-model='form.email' placeholder='enter email address...' type='text' name='email'/>
        <br>
        <input v-model='form.password' placeholder='enter password...' type='password' name='password'/>
        <br>
        <button>Submit</button>
        </form>
        <p>email is: {{ form.email }}</p>
        <p>password is: {{ form.password }}</p>
        
        `
    })
    app.mount('#vue')
</script>