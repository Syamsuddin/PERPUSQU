<?php

namespace App\Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\MasterData\Models\Author;
use App\Modules\MasterData\Services\AuthorService;
use App\Modules\MasterData\Http\Requests\StoreAuthorRequest;
use App\Modules\MasterData\Http\Requests\UpdateAuthorRequest;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function __construct(protected AuthorService $service) {}

    public function index(Request $request)
    {
        $items = $this->service->getPaginated($request->all());
        return view('modules.master_data.authors.index', compact('items'));
    }

    public function create()
    {
        return view('modules.master_data.authors.create');
    }

    public function store(StoreAuthorRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.master-data.authors.index')->with('success', 'Pengarang berhasil ditambahkan.');
    }

    public function edit(Author $author)
    {
        return view('modules.master_data.authors.edit', compact('author'));
    }

    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $this->service->update($author, $request->validated());
        return redirect()->route('admin.master-data.authors.index')->with('success', 'Pengarang berhasil diperbarui.');
    }

    public function destroy(Author $author)
    {
        $this->service->delete($author);
        return redirect()->route('admin.master-data.authors.index')->with('success', 'Pengarang berhasil dihapus.');
    }
}
