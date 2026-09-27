<?php

namespace App\Http\Controllers;

use App\Models\DocumentIssuance;

/**
 * Public, no-auth verification for an issued HR letter - mirrors
 * AcademyCertificateController's verify() so a third party (embassy, new
 * employer, bank) can confirm a letter is genuine before trusting it.
 */
class DocumentVerifyController extends Controller
{
    public function verify(string $code)
    {
        $issuance = DocumentIssuance::with(['template', 'user'])->where('verify_code', $code)->first();

        return view('hr.documents.verify', compact('issuance'));
    }
}
