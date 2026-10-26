<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
    <w:body>
        <w:p>
            <w:pPr><w:jc w:val="center"/><w:spacing w:after="120"/></w:pPr>
            <w:r><w:rPr><w:b/><w:sz w:val="32"/></w:rPr><w:t>HASIL DIAGNOSIS KONSULTASI</w:t></w:r>
        </w:p>
        <w:p>
            <w:pPr><w:jc w:val="center"/><w:spacing w:after="300"/></w:pPr>
            <w:r><w:rPr><w:sz w:val="20"/></w:rPr><w:t>Sistem Pendukung Keputusan Klinis Demam Tifoid</w:t></w:r>
        </w:p>

        <w:p><w:pPr><w:spacing w:before="120" w:after="100"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="24"/></w:rPr><w:t>Informasi Pasien</w:t></w:r></w:p>
        <w:tbl>
            <w:tblPr><w:tblBorders>
                <w:top w:val="single" w:sz="4" w:color="B7C9D0"/><w:left w:val="single" w:sz="4" w:color="B7C9D0"/>
                <w:bottom w:val="single" w:sz="4" w:color="B7C9D0"/><w:right w:val="single" w:sz="4" w:color="B7C9D0"/>
                <w:insideH w:val="single" w:sz="4" w:color="B7C9D0"/><w:insideV w:val="single" w:sz="4" w:color="B7C9D0"/>
            </w:tblBorders></w:tblPr>
            <w:tblGrid><w:gridCol w:w="2600"/><w:gridCol w:w="6500"/></w:tblGrid>
            @foreach([
                'Nama pasien' => $konsultasi->pasien->nama_pasien ?? '-',
                'Nomor rekam medis' => $konsultasi->pasien->no_rm ?? '-',
                'Jenis kelamin' => ($konsultasi->pasien->jenis_kelamin ?? null) === 'L' ? 'Laki-laki' : (($konsultasi->pasien->jenis_kelamin ?? null) === 'P' ? 'Perempuan' : '-'),
                'Umur' => isset($konsultasi->pasien->umur) ? $konsultasi->pasien->umur . ' tahun' : '-',
                'Tanggal konsultasi' => $konsultasi->tanggal ? \Carbon\Carbon::parse($konsultasi->tanggal)->format('d/m/Y H:i') : '-',
            ] as $label => $value)
                <w:tr>
                    <w:tc><w:tcPr><w:tcW w:w="2600" w:type="dxa"/></w:tcPr><w:p><w:r><w:rPr><w:b/></w:rPr><w:t>{{ $label }}</w:t></w:r></w:p></w:tc>
                    <w:tc><w:tcPr><w:tcW w:w="6500" w:type="dxa"/></w:tcPr><w:p><w:r><w:t>{{ $value }}</w:t></w:r></w:p></w:tc>
                </w:tr>
            @endforeach
        </w:tbl>

        <w:p><w:pPr><w:spacing w:before="260" w:after="100"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="24"/></w:rPr><w:t>Diagnosis Diferensial</w:t></w:r></w:p>
        <w:tbl>
            <w:tblPr><w:tblBorders>
                <w:top w:val="single" w:sz="4" w:color="B7C9D0"/><w:left w:val="single" w:sz="4" w:color="B7C9D0"/>
                <w:bottom w:val="single" w:sz="4" w:color="B7C9D0"/><w:right w:val="single" w:sz="4" w:color="B7C9D0"/>
                <w:insideH w:val="single" w:sz="4" w:color="B7C9D0"/><w:insideV w:val="single" w:sz="4" w:color="B7C9D0"/>
            </w:tblBorders></w:tblPr>
            <w:tblGrid><w:gridCol w:w="600"/><w:gridCol w:w="4200"/><w:gridCol w:w="1500"/><w:gridCol w:w="1400"/><w:gridCol w:w="1400"/></w:tblGrid>
            <w:tr>
                @foreach(['#', 'Diagnosis', 'CF', 'Kecocokan', 'Kategori'] as $heading)
                    <w:tc><w:tcPr><w:shd w:fill="E7F6F5"/></w:tcPr><w:p><w:r><w:rPr><w:b/></w:rPr><w:t>{{ $heading }}</w:t></w:r></w:p></w:tc>
                @endforeach
            </w:tr>
            @forelse($hasilDiagnosis as $hasil)
                <w:tr>
                    @foreach([
                        (string) $loop->iteration,
                        $hasil->penyakit->nama_penyakit ?? 'Penyakit tidak tersedia',
                        number_format($hasil->cf_gabungan, 4),
                        number_format($hasil->persentase, 1) . '%',
                        $hasil->kategori ?: '-',
                    ] as $value)
                        <w:tc><w:p><w:r><w:t>{{ $value }}</w:t></w:r></w:p></w:tc>
                    @endforeach
                </w:tr>
            @empty
                <w:tr><w:tc><w:tcPr><w:gridSpan w:val="5"/></w:tcPr><w:p><w:r><w:t>Belum ada hasil diagnosis untuk konsultasi ini.</w:t></w:r></w:p></w:tc></w:tr>
            @endforelse
        </w:tbl>

        <w:p><w:pPr><w:spacing w:before="260" w:after="100"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="24"/></w:rPr><w:t>Rekomendasi Klinis</w:t></w:r></w:p>
        @forelse($rekomendasi as $item)
            <w:p><w:pPr><w:pStyle w:val="ListParagraph"/></w:pPr><w:r><w:t xml:space="preserve">• {{ ucfirst($item->kategori ?: 'Umum') }}: {{ $item->isi_rekomendasi }}</w:t></w:r></w:p>
        @empty
            <w:p><w:r><w:t>Belum ada rekomendasi khusus yang tercatat untuk diagnosis ini.</w:t></w:r></w:p>
        @endforelse

        <w:p><w:pPr><w:spacing w:before="280"/></w:pPr><w:r><w:rPr><w:i/><w:sz w:val="18"/></w:rPr><w:t>Hasil ini merupakan pendukung keputusan klinis, bukan pengganti pemeriksaan dan diagnosis dokter. Evaluasi hasil bersama tenaga medis yang berwenang.</w:t></w:r></w:p>
        <w:sectPr><w:pgSz w:w="11906" w:h="16838"/><w:pgMar w:top="1440" w:right="1440" w:bottom="1440" w:left="1440"/></w:sectPr>
    </w:body>
</w:document>
