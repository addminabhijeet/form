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
        $lastLetter = Letter::withTrashed()
            ->where('letter_number', 'like', 'NYS_A%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastLetter) {
            $lastSerial = (int) substr($lastLetter->letter_number, 6);
            $newSerial = str_pad($lastSerial + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSerial = '0001';
        }

        $letterNumber = 'NYS_A' . $newSerial;

        return view('letter.form', compact('letterNumber'));
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
                'letter_number' => [
                    'required',
                    'string',
                    Rule::unique('letters', 'letter_number')->whereNull('deleted_at'),
                ],
                'letter_date'       => 'required|date',
                'due_date'           => 'date|after_or_equal:letter_date',
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

    private function lettergenerateLetterNumber()
    {
        $lastLetter = Letter::withTrashed()
            ->where('letter_number', 'like', 'NYS_A%')
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = $lastLetter
            ? intval(substr($lastLetter->letter_number, 5))
            : 0;

        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return 'NYS_A' . $newNumber;
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
            'letter_number' => [
                'required',
                'string',
                Rule::unique('letters', 'letter_number')
                    ->ignore($letter->id)
                    ->whereNull('deleted_at'),
            ],
            'letter_date'       => 'required|date',
            'due_date'           => 'date|after_or_equal:letter_date',
            'candidate_name'     => 'required|string|max:255',
            'candidate_email'    => 'required|email|max:255',
            'candidate_mobile'   => 'required|string',
            'candidate_address'  => 'required|string',
            'install_amt' => 'nullable|numeric|max:4999',
            'package'            => 'required|in:career_starter,growth_package,career_acceleration',
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
