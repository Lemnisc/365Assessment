<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $config['title'] ?> Registreren</title>
    <script src="https://unpkg.com/vue@next"></script>
</head>

<body>

    <div id="vue">
        <register-form />
    </div>

</body>

</html>

<script>
    const app = Vue.createApp({})
    app.component('register-form', {
        data() {
            return {
                form: {
                    email: '',
                    password: '',
                    passwordConfirmation: '',
                },
            }
        },
        template: `
        <form method="post" action="register.php">
        <input v-model='form.email' placeholder='enter email address...' type='text' name='email'/>
        <br>
        <input v-model='form.password' placeholder='enter password...' type='password' name='password'/>
        <br>
        <input v-model='form.passwordConfirmation' placeholder='enter password again...' type='password' name='passwordConfirmation'/>
        <br>
        <button>Submit</button>
        </form>
        <p>email is: {{ form.email }}</p>
        <p>passwords match: {{ form.password == form.passwordConfirmation }}</p>
        
        `
    })
    app.mount('#vue')
</script>