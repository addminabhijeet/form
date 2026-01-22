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
        return view('form');
    }

    public function list()
    {
        $invoices = Invoice::orderBy('created_at', 'desc')->paginate(10);
        return view('list', compact('invoices'));
    }

    public function create()
    {
        // Generate unique 6-digit invoice number
        do {
            $invoiceNumber = mt_rand(100000, 999999);
        } while (Invoice::where('invoice_number', $invoiceNumber)->exists());

        // Pass the variable to the correct view
        return view('form', compact('invoiceNumber')); // <-- make sure this matches your Blade file
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
                'candidate_name'     => 'required|string|max:255', // FIXED
                'candidate_email'    => 'required|email|max:255',
                'candidate_address'  => 'required|string',
                'package'            => 'required|in:career_starter,growth_package,career_acceleration',
            ]);

            Invoice::create($data);

            return redirect()->back()->with('success', 'Invoice submitted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage()); // SHOW REAL ERROR
        }
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $data = $request->validate([
            'invoice_number'     => 'required|string|unique:invoices,invoice_number,' . $invoice->id,
            'invoice_date'       => 'required|date',
            'due_date'           => 'required|date|after_or_equal:invoice_date',
            'candidate_name'     => 'required|string|max:255',
            'candidate_email'    => 'required|email|max:255',
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

    public function download($id)
    {
        $invoice = Invoice::findOrFail($id);

        $pdf = Pdf::loadView('invoice.pdf', compact('invoice'));

        return $pdf->download('invoice_' . $invoice->invoice_number . '.pdf');
    }
}
