<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function home()
    {
        return view('form');
    }

    public function list()
    {
        return view('list');
    }

    public function create()
    {
        return view('invoice_form');
    }

    public function store(Request $request)
    {
        // Validate input (aligned with form)
        $data = $request->validate([
            'invoice_number'     => 'required|string|unique:invoices,invoice_number',
            'invoice_date'       => 'required|date',
            'due_date'           => 'required|date|after_or_equal:invoice_date',
            'candidate_name'     => 'required|string|max:255',
            'candidate_email'    => 'required|email|max:255',
            'candidate_address'  => 'required|string',
            'package'            => 'required|in:career_starter,growth_package,career_acceleration',
        ]);

        // Save to database
        Invoice::create($data);

        return redirect()->back()->with('success', 'Invoice submitted successfully!');
    }


    public function pdf()
    {
        return view('pdf.invoice');
    }
}
