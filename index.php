<?php
error_reporting(E_ALL);
set_time_limit(0);

function filterAndDisplay($load, $filter)
{
    echo "<h2>Hasil Pemindaian</h2>";

    if (!empty($load) && !empty($filter)) {
        $mail_explode = array_unique(explode("\r\n", $load));
        $parse_mail = explode(",", $filter);
        $no = 1;

        $results = [];
        foreach ($parse_mail as $parsemails) {
            foreach ($mail_explode as $email) {
                $d = strtolower($email);

                if (strstr($d, $parsemails)) {
                    $results[] = $d;
                }

                $no++;
            }
        }

        echo "<div id='scan-results' style='color: #00ff00; font-size: 18px;'></div>";
        echo "<span style='color: #00ff00; font-size: 18px;'>[Scan] $no Email selesai!!!</span>";

        // Tambahkan tombol untuk menyalin hasil ke clipboard
        echo "<button onclick='copyToClipboard()'>Salin Hasil</button>";
        echo "<script>
            function copyToClipboard() {
                var resultText = document.getElementById('scan-results').innerText.replace(/\[.*\]/g, ''); // Hapus laporan filter
                var tempTextArea = document.createElement('textarea');
                tempTextArea.value = resultText.replace(/<br>/g, ''); // Hapus spasi
                document.body.appendChild(tempTextArea);
                tempTextArea.select();
                document.execCommand('copy');
                document.body.removeChild(tempTextArea);
                alert('Hasil pemindaian telah disalin ke clipboard.');
            }

            // Tampilkan hasil pemindaian di sisi bawah
            document.getElementById('scan-results').innerHTML = '" . implode("<br>", $results) . "';
        </script>";
    } else {
        echo "<span style='color: #ff0000; font-size: 18px;'>Semua kolom harus diisi!</span>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proses form jika data dikirim melalui metode POST
    $load = isset($_POST['load']) ? $_POST['load'] : '';
    $filter = isset($_POST['filter']) ? $_POST['filter'] : '';

    filterAndDisplay($load, $filter);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Mail</title>
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: 'Arial', sans-serif;
            text-align: center;
            margin-top: 50px;
        }

        h1 {
            color: #00ff00;
        }

        form {
            display: inline-block;
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        label, input, textarea {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }

        input[type="text"], input[type="submit"], textarea {
            font-size: 14px;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #00ff00;
            color: #000;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        span {
            display: block;
            margin-top: 10px;
        }

        button {
            font-size: 14px;
            padding: 8px;
            background-color: #00ff00;
            color: #000;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Filter Mail</h1>
    <form method="post">
        <label for="load">Isi Email:</label>
        <textarea name="load" rows="4" cols="50"></textarea>
        <br>

        <label for="filter">Filter:</label>
        <input type="text" name="filter" required>
        <br>

        <input type="submit" value="Submit">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        filterAndDisplay($load, $filter);
    }
    ?>
</body>
</html>