@php($selectedGejala = $selectedGejala ?? old('id_gejala', []))

<div class="row g-3">
    <div class="col-md-6">
        <label for="kode_rule" class="form-label">Kode Rule</label>
        <input id="kode_rule" name="kode_rule" type="text" class="form-control" value="{{ old('kode_rule', $rule->kode_rule ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label for="id_penyakit" class="form-label">Penyakit</label>
        <select id="id_penyakit" name="id_penyakit" class="form-select" required>
            <option value="">Pilih penyakit</option>
            @foreach($penyakit as $item)
                <option value="{{ $item->id_penyakit }}" @selected((string) old('id_penyakit', $rule->id_penyakit ?? '') === (string) $item->id_penyakit)>
                    {{ $item->kode_penyakit }} - {{ $item->nama_penyakit }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="cf_pakar" class="form-label">CF Pakar</label>
        <input id="cf_pakar" name="cf_pakar" type="number" class="form-control" min="0" max="1" step="0.0001" value="{{ old('cf_pakar', $rule->cf_pakar ?? '') }}" required>
    </div>
    <div class="col-12">
        <label for="keterangan" class="form-label">Keterangan</label>
        <textarea id="keterangan" name="keterangan" class="form-control" rows="3">{{ old('keterangan', $rule->keterangan ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Gejala Rule</label>
        <div class="row g-2">
            @forelse($gejala as $item)
                <div class="col-md-6 col-lg-4">
                    <label class="form-check border rounded p-2">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="id_gejala[]" value="{{ $item->id_gejala }}" @checked(in_array($item->id_gejala, $selectedGejala))>
                        <span>{{ $item->kode_gejala }} - {{ $item->nama_gejala }}</span>
                    </label>
                </div>
            @empty
                <div class="col-12"><p class="text-muted mb-0">Belum ada data gejala.</p></div>
            @endforelse
        </div>
    </div>
</div>
