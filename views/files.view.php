<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $config['title'] ?> Bestanden</title>
    <script src="https://unpkg.com/vue@next"></script>
</head>

<body>

    <h1>Bestanden</h1>
    <h1><a href="/auth/logout.php">Log out</a></h1>

    <form method="post" action="files.php" enctype="multipart/form-data">
        <label for="file-upload">Upload een nieuw bestand:</label>
        <input type="file" id="file-upload" name="file-upload" accept=".csv">
        <button>Uploaden</button>
    </form>


    <div id="vue">
        <files-table files='<?= json_encode($files) ?>'></files-table>
        <div v-if="'<?= $file->selectedFile ?>'!=''">
            <file-table selectedfile='<?= json_encode($file->selectedFile) ?>'></file-table>
        </div>
    </div>
</body>

<script>
    const {
        createApp,

    } = Vue
    const app = createApp({})
    app.component('files-table', {
        props: {
            files: JSON
        },
        template: `
        <table>
            <thead>
                <tr>
                    <td>Bestand</td>
                    <td>Acties</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="file in JSON.parse(files)">
                    <td>
                            {{file.filename}}
                    </td>
                    <td>
                        <form method="POST">
                            <button :value="file.file_id" name=edit>Bewerken</button>
                            <button :value="file.file_id" name=download>Downloaden</button>
                            <button :value="file.file_id" name=delete>Verwijderen</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
        `
    })

    app.component('file-table', {
        props: {
            selectedfile: JSON
        },

        data() {
            return {
                editing: [],
                form: JSON.parse(this.selectedfile),
            }
        },
        methods: {
            editCell(data) {
                this.form = data;
                console.log(this.form);
            }
        },
        // <tr v-for="row in JSON.parse(selectedfile)" @click.prevent="editCell(JSON.parse(selectedfile))">
        template: `
        <table>
            <thead>
                <tr>
                    <td>Boekjaar</td>
                    <td>Week</td>
                    <td>Datum</td>
                    <td>Persnr</td>
                    <td>Uren</td>
                    <td>Uurcode</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in form">
                    
                    <td>
                    <input type="number" v-model="row.boekjaar">  
                    </td>
                    <td>
                    <input type="number" v-model="row.week"> 
                    </td>
                    <td>
                    <input type="date" v-model="row.datum"> 
                    </td>
                    <td>
                    <input type="number" v-model="row.persnr"> 
                    </td>
                    <td>
                    <input type="number" step='0.01' v-model="row.uren"> 
                    </td>
                    <td>
                    <input type="text" v-model="row.uurcode"> 
                    </td>
                    <td><button>Submit</button></td>
                </tr>
            </tbody>
        </table>
        `
    })





    app.mount('#vue')
</script>

</html>