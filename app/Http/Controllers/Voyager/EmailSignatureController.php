<?php

namespace App\Http\Controllers\Voyager;

use App\Models\EmailSignature;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmailSignatureController extends Controller
{
    public function __construct()
    {
        $this->middleware(['campaign.access']);
    }

    public function index()
    {
        $signatures = EmailSignature::orderBy('display_name')->get();
        return view('vendor.voyager.email-signatures.index', compact('signatures'));
    }

    public function create()
    {
        $allowedSenders = $this->getAllowedSenders();
        return view('vendor.voyager.email-signatures.edit-add', compact('allowedSenders'));
    }

    public function store(Request $request)
    {
        $data = $this->validate($request);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $this->storeUpload($request->file('photo'), 'email-signatures/photos');
        }
        if ($request->hasFile('handwritten')) {
            $data['handwritten_path'] = $this->storeUpload($request->file('handwritten'), 'email-signatures/handwritten');
        }

        EmailSignature::create($data);

        return redirect()->route('email-signatures.index')
            ->with('success', "Signature for {$data['sender_email']} created.");
    }

    public function edit(int $id)
    {
        $signature      = EmailSignature::findOrFail($id);
        $allowedSenders = $this->getAllowedSenders();
        return view('vendor.voyager.email-signatures.edit-add', compact('signature', 'allowedSenders'));
    }

    public function update(Request $request, int $id)
    {
        $signature = EmailSignature::findOrFail($id);
        $data = $this->validate($request, $id);

        if ($request->hasFile('photo')) {
            if ($signature->photo_path) Storage::disk('public')->delete($signature->photo_path);
            $data['photo_path'] = $this->storeUpload($request->file('photo'), 'email-signatures/photos');
        }
        if ($request->hasFile('handwritten')) {
            if ($signature->handwritten_path) Storage::disk('public')->delete($signature->handwritten_path);
            $data['handwritten_path'] = $this->storeUpload($request->file('handwritten'), 'email-signatures/handwritten');
        }
        if ($request->input('remove_photo')) {
            if ($signature->photo_path) Storage::disk('public')->delete($signature->photo_path);
            $data['photo_path'] = null;
        }
        if ($request->input('remove_handwritten')) {
            if ($signature->handwritten_path) Storage::disk('public')->delete($signature->handwritten_path);
            $data['handwritten_path'] = null;
        }

        $signature->update($data);

        return redirect()->route('email-signatures.index')
            ->with('success', "Signature for {$signature->sender_email} updated.");
    }

    public function destroy(int $id)
    {
        $sig = EmailSignature::findOrFail($id);
        if ($sig->photo_path) Storage::disk('public')->delete($sig->photo_path);
        if ($sig->handwritten_path) Storage::disk('public')->delete($sig->handwritten_path);
        $sig->delete();

        return redirect()->route('email-signatures.index')
            ->with('success', 'Signature deleted.');
    }

    public function preview(int $id)
    {
        $sig = EmailSignature::findOrFail($id);
        $html = $sig->renderHtml();

        return response('<!DOCTYPE html><html><head><meta charset="UTF-8">
            <style>body{font-family:Arial,sans-serif;padding:30px;background:#f5f5f5;}
            .wrap{background:#fff;padding:30px;max-width:620px;border-radius:8px;}</style>
            </head><body><div class="wrap">
            <p style="color:#888;font-size:12px;border-bottom:1px solid #eee;padding-bottom:8px;">
            Email body ends here &mdash; signature below</p>'
            . $html . '</div></body></html>')->header('Content-Type', 'text/html');
    }

    // Uses move_uploaded_file() directly — bypasses Flysystem path resolution entirely,
    // which throws "Path cannot be empty" on some Windows/Laragon setups.
    private function storeUpload(\Illuminate\Http\UploadedFile $file, string $folder): string
    {
        $ext  = $file->getClientOriginalExtension() ?: 'jpg';
        $name = Str::random(40) . '.' . strtolower($ext);
        $dest = storage_path('app/public/' . $folder);

        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        $file->move($dest, $name);
        return $folder . '/' . $name;
    }

    private function getAllowedSenders(): array
    {
        $raw = setting('email-signatures.allowed_senders') ?? '';
        return array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $raw))));
    }

    private function validate(Request $request, ?int $excludeId = null): array
    {
        $allowed = $this->getAllowedSenders();

        return $request->validate([
            'sender_email'   => [
                'required', 'email',
                'unique:email_signatures,sender_email' . ($excludeId ? ",{$excludeId}" : ''),
                function ($attr, $value, $fail) use ($allowed) {
                    if ($allowed && !in_array($value, $allowed)) {
                        $fail('This email is not in the allowed senders list. Add it in Settings → Email Signatures first.');
                    }
                },
            ],
            'display_name'   => 'required|string|max:100',
            'designation'    => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:30',
            'contact_email'  => 'nullable|email|max:150',
            'website'        => 'nullable|url|max:200',
            'linkedin'       => 'nullable|url|max:200',
            'tagline'        => 'nullable|string|max:150',
            'template'       => 'required|in:classic,minimal,bold',
            'accent_color'   => 'required|string|max:20',
            'is_active'      => 'nullable|boolean',
            'photo'          => 'nullable|image|max:2048',
            'handwritten'    => 'nullable|image|max:2048',
        ]) + ['is_active' => $request->has('is_active')];
    }
}
