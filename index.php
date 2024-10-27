<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homework Back End Fundamentals</title>
</head>
<body>
    <!-- daftar harga tiket -->
    <h2>Form Pemesanan Tiket Bioskop</h2>
    <p><strong>Daftar Harga Tiket:</strong></p>
    <ul>
        <li>Tiket Dewasa: Rp50.000</li>
        <li>Tiket Anak-anak: Rp30.000</li>
        <li>Biaya Tambahan Akhir Pekan: Rp10.000 per tiket</li>
    </ul>

    <!-- form jenis tiket -->
    <p><strong>Pesan Tiket:</strong></p>
    <form method="POST" action="">
        <label for="jenisTiket">Jenis Tiket:</label>
        <select name="jenisTiket" id="jenisTiket" required>
            <option value="dewasa">Dewasa</option>
            <option value="anak">Anak-anak</option>
        </select><br><br>

        <!-- jumlah tiket -->
         <label for="jumlahTiket">Jumlah:</label>
         <input type="number" id="jumlahTiket" min="1" required><br><br>

        <!-- button untuk tambah jenis tiket -->
         <button type="button" onclick="tambahTiket()">Tambah Tiket</button><br>
    </form>

    <!-- rincian jenis tiket yg telah dipilih -->
    <p><strong>Rincian Tiket yang Dipilih:</strong></p>
    <div id="tiketDipilih"></div>

    <!-- pilih hari -->
    <form method="POST" action="">
        <input type="hidden" name="pilihHari" id="inputPilihHari">
        <label for="hari">Hari Pemesanan:</label>
        <select name="hari" id="hari">
            <option value="senin">Senin</option>
            <option value="selasa">Selasa</option>
            <option value="rabu">Rabu</option>
            <option value="kamis">Kamis</option>
            <option value="jumat">Jumat</option>
            <option value="sabtu">Sabtu</option>
            <option value="minggu">Minggu</option>
        </select><br><br>
        <button type="submit" onclick="kirimForm()">Submit</button>
    </form>

    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // harga tiket
            $hargaDewasa = 50000;
            $hargaAnak = 30000;
            $weekend = 10000;

            $pilihHari = json_decode($_POST['pilihHari'],true);
            $hari = strtolower($_POST['hari']);

            // harga weekend
            $tambahanBiaya = (in_array($hari, ['sabtu', 'minggu'])) ? $weekend : 0;
            $totalHarga = 0;

            // rincian pemesanan
            echo "<h3>Rincian Pemesanan</h3>";
            foreach ($pilihHari as $tiket) {
                $hargaTiket = ($tiket['jenis'] === 'dewasa') ? $hargaDewasa : $hargaAnak;
                $hargaTiket += $tambahanBiaya;
                $subTotal = $hargaTiket * $tiket['jumlah'];
                $totalHarga += $subTotal;

                echo "<p>Tiket {$tiket['jenis']}: {$tiket['jumlah']} x Rp" . number_format($hargaTiket, 0, ',', '.') . " = Rp" . number_format($subTotal, 0, ',', '.') . "</p>";
            }

            // diskon 10% jika total harga melebihi Rp150.000
            if ($totalHarga > 150000) {
                $diskon = $totalHarga * 0.1;
                $totalHarga -= $diskon;
                echo "<p>Diskon 10%: Rp" . number_format($diskon, 0, ',', '.') . "</p>";
            }

            echo "<p><strong>Total Harga: Rp" . number_format($totalHarga, 0, ',', '.') . "</strong></p>";
        }
    ?>

    <script>
        // untuk menyimpan data tiket yg dipilih
        let pilihHari = [];

        function tambahTiket() {
            let jenisTiket = document.getElementById('jenisTiket').value;
            let jumlahTiket = parseInt(document.getElementById('jumlahTiket').value);

            let tiketIndex = pilihHari.findIndex(tiket => tiket.jenis === jenisTiket);

            if (tiketIndex !== -1) {
                pilihHari[tiketIndex].jumlah += jumlahTiket;
            }else {
                pilihHari.push({jenis: jenisTiket, jumlah: jumlahTiket});
            }

            // reset input
            document.getElementById('jumlahTiket').value = '';

            tampilkanPilihan();
        }

        function tampilkanPilihan() {
            let pilihanDiv = document.getElementById('tiketDipilih');
            pilihanDiv.innerHTML = '';

            // menampilkan setiap tiket yg dipilih
            pilihHari.forEach(tiket => {
                let item = document.createElement('p');
                item.textContent = `Tiket ${tiket.jenis} - Jumlah: ${tiket.jumlah}`;
                pilihanDiv.appendChild(item);
            })
        }

        function kirimForm() {
            document.getElementById('inputPilihHari'). value = JSON.stringify(pilihHari);
        }
    </script>
</body>
</html>