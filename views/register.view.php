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
        <register-form 
            errors='<?= json_encode($errors) ?>'  
            oldinput='<?=json_encode($oldInput)?>'
        />
    </div>
</body>

</html>

<script>
    const app = Vue.createApp({})
    app.component('register-form', {
        props: {
            errors: JSON,
            oldinput: JSON,
        },
        data() {
            return {
                form: {
                    email: JSON.parse(this.oldinput)['email'],
                    password: JSON.parse(this.oldinput)['password'],
                    passwordConfirmation: '',
                },
            }
        },
        template: `
        <form method="post" action="register.php">
        <input v-model='form.email' placeholder='enter email address...' type='text' name='email'/>
        {{JSON.parse(errors)['email']}}
        <br>
        <input v-model='form.password' placeholder='enter password...' type='password' name='password'/>
        {{JSON.parse(errors)['password']}}
        <br>
        <input v-model='form.passwordConfirmation' placeholder='enter password again...' type='password' name='passwordConfirmation'/>
        
        {{JSON.parse(errors)['passwordConfirmation']}}
        <br>
        <button>Submit</button>
        </form>
        <p>email is: {{ form.email }}</p>
        <p>{{form.password == form.passwordConfirmation? "Passwords match!":""}}</p>
        
        `
    })
    app.mount('#vue')
</script>