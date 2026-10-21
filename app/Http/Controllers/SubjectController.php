<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!in_array(auth()->user()->role, ['admin', 'headmaster', 'curriculum'])) {
                abort(403, 'Akses ditolak. Pengelolaan mata pelajaran hanya untuk Administrator dan Tim Kurikulum.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $subjects = Subject::orderBy('order_num', 'asc')->orderBy('name', 'asc')->get();
        return view('curriculum.subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'nullable|string|max:20',
            'category' => 'required|string|max:100',
            'kkm' => 'required|numeric|min:0|max:100',
            'order_num' => 'nullable|integer',
        ]);

        Subject::create([
            'name' => $request->name,
            'code' => strtoupper($request->code ?? ''),
            'category' => $request->category,
            'kkm' => $request->kkm,
            'order_num' => $request->order_num ?? 0,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'nullable|string|max:20',
            'category' => 'required|string|max:100',
            'kkm' => 'required|numeric|min:0|max:100',
            'order_num' => 'nullable|integer',
        ]);

        $subject->update([
            'name' => $request->name,
            'code' => strtoupper($request->code ?? ''),
            'category' => $request->category,
            'kkm' => $request->kkm,
            'order_num' => $request->order_num ?? 0,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : false,
        ]);

        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->scores()->count() > 0) {
            return redirect()->route('subjects.index')->with('error', 'Mata pelajaran tidak dapat dihapus karena sudah memiliki riwayat nilai siswa.');
        }

        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
