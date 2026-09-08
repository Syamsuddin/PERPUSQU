<?php

namespace App\Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Services\TrashService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class TrashController extends Controller
{
    public function __construct(
        protected TrashService $trash,
    ) {}

    public function index(Request $request)
    {
        $type = $request->get('type', 'katalog');

        try {
            $definition = $this->trash->definition($type);
        } catch (InvalidArgumentException) {
            abort(404);
        }

        // Setiap jenis dijaga izin penghapusannya sendiri: melihat isi kotak
        // sampah sama saja melihat data yang dihapus, jadi wewenangnya harus
        // sama dengan wewenang menghapusnya.
        $this->authorizeType($definition['permission']);

        return view('modules.core.trash.index', [
            'type' => $type,
            'definition' => $definition,
            'records' => $this->trash->paginate($type),
            'counts' => $this->trash->counts(),
            'types' => TrashService::TYPES,
        ]);
    }

    public function restore(Request $request, string $type, int $id)
    {
        try {
            $definition = $this->trash->definition($type);
        } catch (InvalidArgumentException) {
            abort(404);
        }

        $this->authorizeType($definition['permission']);

        $record = $this->trash->findTrashed($type, $id);

        try {
            $this->trash->restore($type, $record);
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.trash.index', ['type' => $type])
            ->with('success', $this->trash->describe($type, $record).' berhasil dipulihkan.');
    }

    protected function authorizeType(string $permission): void
    {
        abort_unless(auth()->user()?->can($permission), 403);
    }
}
