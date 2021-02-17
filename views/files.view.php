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
        <file-table selectedfile='<?= json_encode($selectedFile) ?>'></file-table>
    </div>
</body>

<script>
    const {
        createApp,
        h
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
                        <a :href="'/files.php?file=' + file.file_id">
                            {{file.filename}}</a>
                    </td>
                    <td>
                        <button>Download</button>
                        <button>Verwijderen</button>
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
                <tr v-for="row in JSON.parse(selectedfile)">
                    <td>
                            {{row.boekjaar}}
                    </td>
                    <td>
                            {{row.week}}
                    </td>
                    <td>
                            {{row.datum}}
                    </td>
                    <td>
                            {{row.persnr}}
                    </td>
                    <td>
                            {{row.uren}}
                    </td>
                    <td>
                            {{row.uurcode}}
                    </td>
                </tr>
            </tbody>
        </table>
        `
    })





    app.mount('#vue')
</script>

</html>