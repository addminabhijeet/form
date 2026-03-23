<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;

class LetterController extends Controller
{

    public function list()
    {
        $invoices = Letter::orderBy('created_at', 'desc')->paginate(10);
        return view('list', compact('invoices'));
    }

    public function create()
    {
        $lastInvoice = Letter::withTrashed()
            ->where('invoice_number', 'like', 'NYS_A%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastSerial = (int) substr($lastInvoice->invoice_number, 6);
            $newSerial = str_pad($lastSerial + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSerial = '0001';
        }

        $invoiceNumber = 'NYS_A' . $newSerial;

        return view('form', compact('invoiceNumber'));
    }

    public function emailcreate()
    {
        return view('emailform');
    }

    public function edit($id)
    {
        $invoice = Letter::findOrFail($id);
        return view('form', compact('invoice'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'invoice_number' => [
                    'required',
                    'string',
                    Rule::unique('invoices', 'invoice_number')->whereNull('deleted_at'),
                ],
                'invoice_date'       => 'required|date',
                'due_date'           => 'date|after_or_equal:invoice_date',
                'candidate_name'     => 'required|string|max:255',
                'candidate_email'    => 'required|email|max:255',
                'candidate_mobile'   => 'required|string',
                'install_amt' => 'nullable|numeric|max:4999',
                'candidate_address'  => 'required|string',
                'package'            => 'required|in:career_starter,growth_package,career_acceleration',
            ]);

            Letter::create($data);

            return redirect()->back()->with('success', 'Letter submitted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    private function generateInvoiceNumber()
    {
        $lastInvoice = Letter::withTrashed()
            ->where('invoice_number', 'like', 'NYS_A%')
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = $lastInvoice
            ? intval(substr($lastInvoice->invoice_number, 5))
            : 0;

        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return 'NYS_A' . $newNumber;
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $invoice = Letter::where('candidate_email', $request->email)
            ->latest()
            ->first();

        if ($invoice) {
            return response()->json([
                'exists' => true,
                'data' => [
                    'id' => $invoice->id,
                ]
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function update(Request $request, $id)
    {
        $invoice = Letter::findOrFail($id);

        $data = $request->validate([
            'invoice_number' => [
                'required',
                'string',
                Rule::unique('invoices', 'invoice_number')
                    ->ignore($invoice->id)
                    ->whereNull('deleted_at'),
            ],
            'invoice_date'       => 'required|date',
            'due_date'           => 'date|after_or_equal:invoice_date',
            'candidate_name'     => 'required|string|max:255',
            'candidate_email'    => 'required|email|max:255',
            'candidate_mobile'   => 'required|string',
            'candidate_address'  => 'required|string',
            'install_amt' => 'nullable|numeric|max:4999',
            'package'            => 'required|in:career_starter,growth_package,career_acceleration',
        ]);


        $invoice->update($data);

        return redirect()->route('invoice.list')->with('success', 'Letter updated successfully!');
    }

    public function destroy($id)
    {
        $invoice = Letter::findOrFail($id);
        $invoice->delete();

        return redirect()->back()->with('success', 'Letter deleted successfully!');
    }

    public function pdf($id)
    {
        $invoice = Letter::findOrFail($id);

        return view('pdf.invoice', compact('invoice'));
    }

    public function pdfone($id)
    {
        $invoice = Letter::findOrFail($id);

        return view('pdf.invoiceone', compact('invoice'));
    }

    public function pdftwo($id)
    {
        $invoice = Letter::findOrFail($id);

        return view('pdf.invoicetwo', compact('invoice'));
    }

    public function download($id)
    {
        $invoice = Letter::findOrFail($id);

        $pdf = Pdf::loadView('invoice.pdf', compact('invoice'));

        return $pdf->download('invoice_' . $invoice->invoice_number . '.pdf');
    }
}
