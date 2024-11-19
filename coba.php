<?php
// Menginisialisasi data kehadiran (bisa diambil dari database)
$data_kehadiran = [
    '2023-01' => ['Alice' => 20, 'Bob' => 18, 'Charlie' => 22],
    '2023-02' => ['Alice' => 19, 'Bob' => 20, 'Charlie' => 21],
    // Tambahkan data sesuai kebutuhan
];

// Fungsi untuk mengenerate laporan Excel
function generateExcel($data, $periode) {
    // Membuat file Excel
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Laporan_{$periode}.xls");
    echo "Nama\tJumlah Kehadiran\n";
    
    foreach ($data as $nama => $kehadiran) {
        echo "{$nama}\t{$kehadiran}\n";
    }
    exit;
}

// Fungsi untuk menggenerate laporan PDF
function generatePDF($data, $periode) {
    require('fpdf/fpdf.php'); // Pastikan fpdf library sudah di-download dan ada di folder yang sama
    
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(40, 10, "Laporan Kehadiran Bulan $periode");
    $pdf->Ln(10);
    
    $pdf->SetFont('Arial', '', 12);
    foreach ($data as $nama => $kehadiran) {
        $pdf->Cell(40, 10, "$nama: $kehadiran hari");
        $pdf->Ln();
    }

    $pdf->Output('D', "Laporan_{$periode}.pdf");
    exit;
}

// Memproses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $format = $_POST['format'];
    $periode = "$tahun-$bulan";

    // Cek apakah data kehadiran tersedia
    if (isset($data_kehadiran[$periode])) {
        $data = $data_kehadiran[$periode];

        if ($format == 'excel') {
            generateExcel($data, $periode);
        } elseif ($format == 'pdf') {
            generatePDF($data, $periode);
        }
    } else {
        $error = "Data tidak tersedia untuk periode ini.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Laporan Bulanan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Form Generate Laporan Bulanan</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="bulan">Pilih Bulan:</label>
                <select class="form-control" id="bulan" name="bulan" required>
                    <option value="">-- Pilih Bulan --</option>
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <!-- Tambah opsi hingga Desember -->
                </select>
            </div>
            <div class="form-group">
                <label for="tahun">Pilih Tahun:</label>
                <select class="form-control" id="tahun" name="tahun" required>
                    <option value="">-- Pilih Tahun --</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <!-- Tambah opsi sesuai kebutuhan -->
                </select>
            </div>
            <div class="form-group">
                <label for="format">Pilih Format Laporan:</label>
                <select class="form-control" id="format" name="format" required>
                    <option value="excel">Excel</option>
                    <option value="pdf">PDF</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Generate Laporan</button>
        </form>
    </div>
</body>
</html>
