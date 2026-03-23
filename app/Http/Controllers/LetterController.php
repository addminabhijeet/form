<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Letter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;

class LetterController extends Controller
{

    public function letterlist()
    {
        $letters = Letter::orderBy('created_at', 'desc')->paginate(10);
        return view('letter.list', compact('letters'));
    }

    public function lettercreate()
    {
        return view('letter.form');
    }

    public function emailcreate()
    {
        return view('letter.form');
    }

    public function letteremailcreate()
    {
        return view('letter.emailform');
    }

    public function letteredit($id)
    {
        $letter = Letter::findOrFail($id);
        return view('letter.form', compact('letter'));
    }

    public function letterstore(Request $request)
    {
        try {
            $data = $request->validate([
                'letter_date'       => 'required|date',
                'due_date'           => 'date|after_or_equal:letter_date',
                'candidate_name'     => 'required|string|max:255',
                'candidate_address'  => 'required|string',
            ]);

            Letter::create($data);

            return redirect()->back()->with('success', 'Letter submitted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function lettercheckEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $letter = Letter::where('candidate_email', $request->email)
            ->latest()
            ->first();

        if ($letter) {
            return response()->json([
                'exists' => true,
                'data' => [
                    'id' => $letter->id,
                ]
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function letterupdate(Request $request, $id)
    {
        $letter = Letter::findOrFail($id);

        $data = $request->validate([
            'letter_date'       => 'required|date',
            'due_date'           => 'date|after_or_equal:letter_date',
            'candidate_name'     => 'required|string|max:255',
            'candidate_address'  => 'required|string',
        ]);


        $letter->update($data);

        return redirect()->route('letter.list')->with('success', 'Letter updated successfully!');
    }

    public function letterdestroy($id)
    {
        $letter = Letter::findOrFail($id);
        $letter->delete();

        return redirect()->back()->with('success', 'Letter deleted successfully!');
    }

    public function letterpdf($id)
    {
        $letter = Letter::findOrFail($id);

        return view('letter.pdf', compact('letter'));
    }

    public function letterpdfone($id)
    {
        $letter = Letter::findOrFail($id);

        return view('letter.pdfone', compact('letter'));
    }

    public function letterpdftwo($id)
    {
        $letter = Letter::findOrFail($id);

        return view('letter.pdftwo', compact('letter'));
    }

    public function letterdownload($id)
    {
        $letter = Letter::findOrFail($id);

        $pdf = Pdf::loadView('letter.pdf', compact('letter'));

        return $pdf->download('letter_' . $letter->letter_number . '.pdf');
    }
}
