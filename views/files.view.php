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
    <h1><a href="/files.php">Bestanden</a></h1>

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
                oldForm: JSON.parse(this.selectedfile),
                changedRows: new Set(),
            }
        },
        methods: {
            addChangedRow(id) {
                this.changedRows.add(id);
            },
            handleUpdate() {
                const rowsToUpdate = new FormData();
                const datata=[];
                for (let id of this.changedRows) {
                    datata.push(this.form.find(row => row.id === id))
                }
                rowsToUpdate.append('update', JSON.stringify(datata));
                fetch("/files.php", {
                    method: "POST",
                    body: rowsToUpdate
                })
            },
        },
        computed: {},

        template: `
        <table>
            <button @click=handleUpdate()>Submit changes</button>
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
                    <input type="number" v-model="row.boekjaar" v-on:change="addChangedRow(row.id)">  
                    </td>
                    <td>
                    <input type="number" v-model="row.week" v-on:change="addChangedRow(row.id)"> 
                    </td>
                    <td>
                    <input type="date" v-model="row.datum" v-on:change="addChangedRow(row.id)">
                    </td>
                    <td>
                    <input type="number" v-model="row.persnr" v-on:change="addChangedRow(row.id)">
                    </td>
                    <td>
                    <input type="number" step='0.01' v-model="row.uren" v-on:change="addChangedRow(row.id)">
                    </td>
                    <td>
                    <input type="text" v-model="row.uurcode" v-on:change="addChangedRow(row.id)">
                    </td>
                </tr>
            </tbody>
        </table>
        `
    })





    app.mount('#vue')
</script>

</html>