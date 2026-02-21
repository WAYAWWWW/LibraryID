<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;

class ReportController extends Controller
{
    protected function buildLoansHistoryQuery(Request $request)
    {
        Loan::syncOverdueFines();
        Loan::syncReturnedFines();

        $query = Loan::with(['user', 'book']);

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return $query;
    }

    protected function streamLoansHistoryExcel($loans, string $filenamePrefix)
    {
        $filename = $filenamePrefix . '_' . now()->format('Ymd_His') . '.xlsx';

        $headers = [
            'Peminjam',
            'Email',
            'Buku',
            'Tanggal Pengajuan',
            'Tanggal Peminjaman',
            'Jatuh Tempo',
            'Tanggal Kembali',
            'Denda',
            'Status'
        ];

        $rows = [];
        foreach ($loans as $loan) {
            $rows[] = [
                $loan->user->name ?? 'N/A',
                $loan->user->email ?? 'N/A',
                $loan->book->title ?? 'N/A',
                optional($loan->created_at)->format('d/m/Y H:i'),
                $loan->borrow_date ? \Carbon\Carbon::parse($loan->borrow_date)->format('d/m/Y') : '',
                $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') : '',
                $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') : '',
                $loan->fine ?? 0,
                $loan->status
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Peminjaman');

        $sheet->fromArray($headers, null, 'A1');
        if (!empty($rows)) {
            $sheet->fromArray($rows, null, 'A2');
        }

        $lastRow = max(1, count($rows) + 1);
        $lastCol = chr(ord('A') + count($headers) - 1);
        $tableRange = 'A1:' . $lastCol . $lastRow;

        $table = new Table($tableRange, 'RiwayatPeminjaman');
        $tableStyle = new TableStyle();
        $tableStyle->setTheme(TableStyle::TABLE_STYLE_MEDIUM9);
        $tableStyle->setShowRowStripes(true);
        $table->setStyle($tableStyle);
        $sheet->addTable($table);

        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function loansPdf(Request $request)
    {
        $loans = Loan::with(['user','book'])->get();
        $pdf = null;
        try{
            $pdf = PDF::loadView('reports.loans_print', compact('loans'));
            return $pdf->download('laporan_peminjaman.pdf');
        } catch (\Exception $e){
            return view('reports.loans_print', compact('loans'));
        }
    }

    public function loansHistory(Request $request)
    {
        $loans = $this->buildLoansHistoryQuery($request)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        $users = \App\Models\User::where('role', 'user')->get();
        
        return view('admin.reports.loans-history', compact('loans', 'users'));
    }

    public function loansHistoryExcel(Request $request)
    {
        $loans = $this->buildLoansHistoryQuery($request)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->streamLoansHistoryExcel($loans, 'riwayat_peminjaman_admin');
    }

    public function loansHistoryPetugas(Request $request)
    {
        $loans = $this->buildLoansHistoryQuery($request)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        $users = \App\Models\User::where('role', 'user')->get();
        
        return view('petugas.reports.loans-history', compact('loans', 'users'));
    }

    public function loansHistoryExcelPetugas(Request $request)
    {
        $loans = $this->buildLoansHistoryQuery($request)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->streamLoansHistoryExcel($loans, 'riwayat_peminjaman_petugas');
    }

}
