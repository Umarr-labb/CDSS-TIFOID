<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\Konsultasi;
use App\Models\KonsultasiDetail;
use App\Models\Pasien;
use App\Models\CfUserSkala;
use App\Models\Rule;
use App\Models\HasilDiagnosis;
use App\Models\LogCf;
use App\Models\Rekomendasi;
use Illuminate\Http\Request;
use RuntimeException;
use ZipArchive;

class KonsultasiController extends Controller
{
    public function index()
    {
        $konsultasi = Konsultasi::with(['pasien', 'hasil.penyakit'])
            ->orderByDesc('id_konsultasi')
            ->get();

        return view('konsultasi.index', compact('konsultasi'));
    }

    public function riwayat()
    {
        $riwayat = Konsultasi::with(['pasien', 'hasil.penyakit'])
            ->orderByDesc('tanggal')
            ->paginate(10);

        return view('konsultasi.riwayat', compact('riwayat'));
    }

    public function destroy($id_konsultasi)
    {
        $konsultasi = Konsultasi::findOrFail($id_konsultasi);
        $konsultasi->delete();

        return redirect()->route('konsultasi.riwayat')
            ->with('success', 'Riwayat konsultasi berhasil dihapus.');
    }

    // Form input data pasien
    public function createPasien()
    {
        return view('konsultasi.pasien');
    }

    // Simpan data pasien
    public function storePasien(Request $request)
    {
        $request->validate([
            'no_rm'         => 'required|unique:pasien,no_rm',
            'nama_pasien'   => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'umur'          => 'required|integer',
        ]);

        $pasien = Pasien::create($request->all());

        return redirect()->route('konsultasi.gejala', $pasien->id_pasien);
    }

    // Form pilih gejala
    public function formGejala($id_pasien)
    {
        $pasien  = Pasien::findOrFail($id_pasien);
        $gejala  = Gejala::all();
        $skala   = CfUserSkala::all();

        return view('konsultasi.gejala', compact('pasien', 'gejala', 'skala'));
    }

    // Proses perhitungan CF
    public function proses(Request $request, $id_pasien)
    {
        $pasien = Pasien::findOrFail($id_pasien);

        // Buat sesi konsultasi
        $konsultasi = Konsultasi::create([
            'id_pasien' => $pasien->id_pasien,
            'tanggal'   => now(),
            'status'    => 'proses',
        ]);

        // Simpan detail jawaban gejala
        foreach ($request->gejala as $id_gejala => $nilai_cf) {
            if ($nilai_cf > 0) {
                KonsultasiDetail::create([
                    'id_konsultasi' => $konsultasi->id_konsultasi,
                    'id_gejala'     => $id_gejala,
                    'nilai_cf_user' => $nilai_cf,
                ]);
            }
        }

        // Hitung CF evidence per rule
        $rules = Rule::with('details', 'penyakit')->get();
        $cfPerPenyakit = [];

        foreach ($rules as $rule) {
            $cfUserMin = 1.0;

            foreach ($rule->details as $detail) {
                $detailKonsul = KonsultasiDetail::where('id_konsultasi', $konsultasi->id_konsultasi)
                    ->where('id_gejala', $detail->id_gejala)
                    ->first();

                $nilai = $detailKonsul ? (float) $detailKonsul->nilai_cf_user : 0;

                if ($nilai < $cfUserMin) {
                    $cfUserMin = $nilai;
                }
            }

            $cfEvidence = (float) $rule->cf_pakar * $cfUserMin;

            // Simpan log CF
            LogCf::create([
                'id_konsultasi' => $konsultasi->id_konsultasi,
                'id_rule'       => $rule->id_rule,
                'id_penyakit'   => $rule->id_penyakit,
                'cf_pakar'      => $rule->cf_pakar,
                'cf_user_min'   => $cfUserMin,
                'cf_evidence'   => $cfEvidence,
            ]);

            // Gabungkan CF per penyakit
            $idPenyakit = $rule->id_penyakit;

            if (!isset($cfPerPenyakit[$idPenyakit])) {
                $cfPerPenyakit[$idPenyakit] = $cfEvidence;
            } else {
                $cfLama = $cfPerPenyakit[$idPenyakit];
                // Rumus kombinasi: CF1 + CF2 * (1 - CF1)
                $cfPerPenyakit[$idPenyakit] = $cfLama + $cfEvidence * (1 - $cfLama);
            }
        }

        // Simpan hasil diagnosis
        $diagnosisUtama = null;
        $cfTertinggi     = 0;

        foreach ($cfPerPenyakit as $idPenyakit => $cfGabungan) {
            $persentase = $cfGabungan * 100;
            $kategori   = $this->kategoriCF($cfGabungan);

            HasilDiagnosis::create([
                'id_konsultasi' => $konsultasi->id_konsultasi,
                'id_penyakit'   => $idPenyakit,
                'cf_gabungan'   => $cfGabungan,
                'persentase'    => $persentase,
                'kategori'      => $kategori,
            ]);

            if ($cfGabungan > $cfTertinggi) {
                $cfTertinggi     = $cfGabungan;
                $diagnosisUtama  = $idPenyakit;
            }
        }

        // Update konsultasi
        $konsultasi->update([
            'id_penyakit_hasil' => $diagnosisUtama,
            'cf_hasil'          => $cfTertinggi,
            'status'            => 'selesai',
        ]);

        return redirect()->route('konsultasi.hasil', $konsultasi->id_konsultasi);
    }

    // Tampilkan hasil
    public function hasil($id_konsultasi)
    {
        $konsultasi = Konsultasi::with(['pasien', 'hasil.penyakit'])->findOrFail($id_konsultasi);
        $rekomendasi = Rekomendasi::where('id_penyakit', $konsultasi->id_penyakit_hasil)->get();

        return view('konsultasi.hasil', compact('konsultasi', 'rekomendasi'));
    }

    public function downloadWord($id_konsultasi)
    {
        $konsultasi = Konsultasi::with(['pasien', 'hasil.penyakit'])->findOrFail($id_konsultasi);
        $hasilDiagnosis = $konsultasi->hasil->sortByDesc('cf_gabungan')->values();
        $rekomendasi = Rekomendasi::where('id_penyakit', $konsultasi->id_penyakit_hasil)->get();
        $document = view('konsultasi.word', compact('konsultasi', 'hasilDiagnosis', 'rekomendasi'))->render();

        $temporaryFile = tempnam(sys_get_temp_dir(), 'cdss-word-');
        if ($temporaryFile === false) {
            throw new RuntimeException('Tidak dapat menyiapkan file unduhan Word.');
        }

        $archive = new ZipArchive();
        $archiveOpened = false;
        try {
            if ($archive->open($temporaryFile, ZipArchive::OVERWRITE) !== true) {
                throw new RuntimeException('Tidak dapat membuat dokumen Word.');
            }

            $archiveOpened = true;
            $entries = [
                '[Content_Types].xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                    . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
                    . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
                    . '<Default Extension="xml" ContentType="application/xml"/>'
                    . '<Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>'
                    . '</Types>',
                '_rels/.rels' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                    . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                    . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>'
                    . '</Relationships>',
                'word/document.xml' => $document,
            ];
            foreach ($entries as $path => $contents) {
                if (!$archive->addFromString($path, $contents)) {
                    throw new RuntimeException('Tidak dapat menambahkan konten ke dokumen Word.');
                }
            }

            if (!$archive->close()) {
                throw new RuntimeException('Tidak dapat menyelesaikan dokumen Word.');
            }
            $archiveOpened = false;
        } catch (\Throwable $exception) {
            if ($archiveOpened) {
                $archive->close();
            }
            unlink($temporaryFile);

            throw $exception;
        }

        return response()->download(
            $temporaryFile,
            'hasil-diagnosis-' . $konsultasi->id_konsultasi . '.docx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
        )->deleteFileAfterSend(true);
    }

    // Fungsi kategori CF
    private function kategoriCF($cf)
    {
        if ($cf >= 0.80) return 'Kuat';
        if ($cf >= 0.60) return 'Sedang';
        if ($cf >= 0.40) return 'Lemah';
        return 'Tidak dapat disimpulkan';
    }
}