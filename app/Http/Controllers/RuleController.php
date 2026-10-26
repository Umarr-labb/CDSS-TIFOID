<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Rule;
use App\Models\RuleDetail;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function index()
    {
        $rules = Rule::with(['penyakit', 'details.gejala'])
            ->orderBy('kode_rule')
            ->paginate(10);

        return view('admin.rule.index', compact('rules'));
    }

    public function create()
    {
        $penyakit = Penyakit::orderBy('nama_penyakit')->get();
        $gejala = Gejala::orderBy('kode_gejala')->get();

        return view('admin.rule.create', compact('penyakit', 'gejala'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_rule' => ['required', 'string', 'max:255', 'unique:rule,kode_rule'],
            'id_penyakit' => ['required', 'exists:penyakit,id_penyakit'],
            'cf_pakar' => ['required', 'numeric', 'between:0,1'],
            'keterangan' => ['nullable', 'string'],
            'id_gejala' => ['nullable', 'array'],
            'id_gejala.*' => ['exists:gejala,id_gejala'],
        ]);

        $rule = Rule::create([
            'kode_rule' => $validated['kode_rule'],
            'id_penyakit' => $validated['id_penyakit'],
            'cf_pakar' => $validated['cf_pakar'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        $this->syncGejala($rule, $validated['id_gejala'] ?? []);

        return redirect()->route('rule.index')->with('success', 'Rule berhasil ditambahkan.');
    }

    public function show(Rule $rule)
    {
        return redirect()->route('rule.edit', $rule);
    }

    public function edit(Rule $rule)
    {
        $rule->load('details');
        $penyakit = Penyakit::orderBy('nama_penyakit')->get();
        $gejala = Gejala::orderBy('kode_gejala')->get();
        $selectedGejala = $rule->details->pluck('id_gejala')->all();

        return view('admin.rule.edit', compact('rule', 'penyakit', 'gejala', 'selectedGejala'));
    }

    public function update(Request $request, Rule $rule)
    {
        $validated = $request->validate([
            'kode_rule' => ['required', 'string', 'max:255', 'unique:rule,kode_rule,' . $rule->id_rule . ',id_rule'],
            'id_penyakit' => ['required', 'exists:penyakit,id_penyakit'],
            'cf_pakar' => ['required', 'numeric', 'between:0,1'],
            'keterangan' => ['nullable', 'string'],
            'id_gejala' => ['nullable', 'array'],
            'id_gejala.*' => ['exists:gejala,id_gejala'],
        ]);

        $rule->update([
            'kode_rule' => $validated['kode_rule'],
            'id_penyakit' => $validated['id_penyakit'],
            'cf_pakar' => $validated['cf_pakar'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        $rule->details()->delete();
        $this->syncGejala($rule, $validated['id_gejala'] ?? []);

        return redirect()->route('rule.index')->with('success', 'Rule berhasil diperbarui.');
    }

    public function destroy(Rule $rule)
    {
        $rule->delete();

        return redirect()->route('rule.index')->with('success', 'Rule berhasil dihapus.');
    }

    private function syncGejala(Rule $rule, array $gejala): void
    {
        foreach (array_unique($gejala) as $idGejala) {
            RuleDetail::create([
                'id_rule' => $rule->id_rule,
                'id_gejala' => $idGejala,
            ]);
        }
    }
}
