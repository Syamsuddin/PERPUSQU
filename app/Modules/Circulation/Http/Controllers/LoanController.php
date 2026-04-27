<?php

namespace App\Modules\Circulation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Circulation\Http\Requests\RenewLoanRequest;
use App\Modules\Circulation\Http\Requests\StoreLoanRequest;
use App\Modules\Circulation\Models\Loan;
use App\Modules\Circulation\Services\LoanRenewalService;
use App\Modules\Circulation\Services\LoanTransactionService;
use App\Modules\Member\Models\Member;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct(
        protected LoanTransactionService $loanService,
        protected LoanRenewalService $renewalService,
    ) {}

    public function create()
    {
        $members = Member::active()->where('is_blocked', false)->orderBy('name')->get();

        return view('modules.circulation.loans.create', compact('members'));
    }

    public function store(StoreLoanRequest $request)
    {
        try {
            $loan = $this->loanService->createLoan($request->member_id, $request->barcode, $request->notes);

            return redirect()->route('admin.circulation.loans.active')->with('success', "Peminjaman berhasil. Item {$request->barcode} dipinjam.");
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function active(Request $request)
    {
        $items = $this->loanService->getActiveLoans($request->all());

        return view('modules.circulation.loans.active', compact('items'));
    }

    public function show(Loan $loan)
    {
        $loan->load(['member', 'physicalItem.bibliographicRecord', 'loanedBy', 'closedBy', 'returnTransaction', 'fine', 'renewals.renewedBy']);

        return view('modules.circulation.loans.show', compact('loan'));
    }

    public function history(Request $request)
    {
        $items = $this->loanService->getHistory($request->all());

        return view('modules.circulation.loans.history', compact('items'));
    }

    public function renew(RenewLoanRequest $request, Loan $loan)
    {
        try {
            $this->renewalService->renew($loan, $request->notes);

            return redirect()->route('admin.circulation.loans.show', $loan)->with('success', 'Pinjaman berhasil diperpanjang.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
