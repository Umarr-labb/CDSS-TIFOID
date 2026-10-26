<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Konsultasi;
use App\Models\HasilDiagnosis;
use App\Models\Penyakit;
use App\Models\Gejala;
use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistik umum (semua role dapat melihat)
        $stats = [
            'total_pasien'       => Pasien::count(),
            'total_konsultasi'   => Konsultasi::count(),
            'konsultasi_hari_ini'=> Konsultasi::whereDate('tanggal', Carbon::today())->count(),
            'konsultasi_selesai' => Konsultasi::where('status', 'selesai')->count(),
        ];

        // Distribusi diagnosis (top penyakit)
        $distribusiDiagnosis = HasilDiagnosis::select('id_penyakit', DB::raw('COUNT(*) as total'))
            ->with('penyakit:id_penyakit,nama_penyakit')
            ->groupBy('id_penyakit')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Grafik konsultasi 7 hari terakhir
        $grafik7Hari = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');
            $grafik7Hari[] = [
                'tanggal' => Carbon::parse($tanggal)->format('d M'),
                'jumlah'  => Konsultasi::whereDate('tanggal', $tanggal)->count(),
            ];
        }

        // Konsultasi terbaru
        $konsultasiTerbaru = Konsultasi::with(['pasien', 'hasil.penyakit'])
            ->orderByDesc('tanggal')
            ->limit(5)
            ->get();

        // Data khusus per role
        $data = array_merge(
            $stats,
            compact('user', 'stats', 'distribusiDiagnosis', 'grafik7Hari', 'konsultasiTerbaru')
        );

        // Routing view sesuai role
        return match ($user->role) {
            'admin'   => view('dashboard.admin',   $data + $this->dataAdmin()),
            'dokter'  => view('dashboard.dokter',  $data + $this->dataDokter()),
            'perawat' => view('dashboard.perawat', $data + $this->dataPerawat()),
            default   => abort(403),
        };
    }

    private function dataAdmin()
    {
        return [
            'total_user'      => \App\Models\User::count(),
            'total_dokter'    => \App\Models\User::where('role', 'dokter')->count(),
            'total_perawat'   => \App\Models\User::where('role', 'perawat')->count(),
            'total_rule'      => Rule::count(),
            'total_gejala'    => Gejala::count(),
            'total_penyakit'  => Penyakit::count(),
        ];
    }

    private function dataDokter()
    {
        return [
            'konsultasi_hari_ini' => Konsultasi::whereDate('tanggal', Carbon::today())->count(),
            'konsultasi_minggu'   => Konsultasi::whereBetween('tanggal', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ])->count(),
        ];
    }

    private function dataPerawat()
    {
        return [
            'pasien_baru'    => Pasien::whereDate('created_at', Carbon::today())->count(),
            'antrian'        => Konsultasi::where('status', 'proses')->count(),
        ];
    }
}
