<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    public function home()
    {
        return $this->create();
    }


    public function list()
    {
        $invoices = Invoice::orderBy('created_at', 'desc')->paginate(10);
        return view('list', compact('invoices'));
    }

    public function create()
    {
        $lastInvoice = Invoice::withTrashed()
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


    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
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
                'due_date'           => 'required|date|after_or_equal:invoice_date',
                'candidate_name'     => 'required|string|max:255',
                'candidate_email'    => 'required|email|max:255',
                'candidate_mobile'   => 'required|digits:10',
                'candidate_address'  => 'required|string',
                'package'            => 'required|in:career_starter,growth_package,career_acceleration',
            ]);

            Invoice::create($data);

            return redirect()->back()->with('success', 'Invoice submitted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }


    private function generateInvoiceNumber()
    {
        $lastInvoice = Invoice::withTrashed()
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
        $email = $request->query('email');

        $invoice = Invoice::where('candidate_email', $email)->latest()->first();

        if ($invoice) {
            return response()->json([
                'exists' => true,
                'data' => $invoice
            ]);
        }

        return response()->json(['exists' => false]);
    }



    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $data = $request->validate([
            'invoice_number' => [
                'required',
                'string',
                Rule::unique('invoices', 'invoice_number')
                    ->ignore($invoice->id)
                    ->whereNull('deleted_at'),
            ],
            'invoice_date'       => 'required|date',
            'due_date'           => 'required|date|after_or_equal:invoice_date',
            'candidate_name'     => 'required|string|max:255',
            'candidate_email'    => 'required|email|max:255',
            'candidate_mobile'   => 'required|digits:10',
            'candidate_address'  => 'required|string',
            'package'            => 'required|in:career_starter,growth_package,career_acceleration',
        ]);


        $invoice->update($data);

        return redirect()->route('invoice.list')->with('success', 'Invoice updated successfully!');
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->back()->with('success', 'Invoice deleted successfully!');
    }

    public function pdf($id)
    {
        $invoice = Invoice::findOrFail($id);

        return view('pdf.invoice', compact('invoice'));
    }

    public function pdfone($id)
    {
        $invoice = Invoice::findOrFail($id);

        return view('pdf.invoiceone', compact('invoice'));
    }

    public function pdftwo($id)
    {
        $invoice = Invoice::findOrFail($id);

        return view('pdf.invoicetwo', compact('invoice'));
    }

    public function download($id)
    {
        $invoice = Invoice::findOrFail($id);

        $pdf = Pdf::loadView('invoice.pdf', compact('invoice'));

        return $pdf->download('invoice_' . $invoice->invoice_number . '.pdf');
    }
}
