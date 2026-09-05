<?php

namespace App\Http\Controllers;

use App\Models\AcademyCertificate;

/**
 * A4/A9 - public, no-auth certificate verification. Lets a client confirm a
 * certificate is real before trusting it.
 */
class AcademyCertificateController extends Controller
{
    public function verify(string $code)
    {
        $certificate = AcademyCertificate::where('verify_code', $code)->first();

        return view('academy.verify', compact('certificate'));
    }
}
