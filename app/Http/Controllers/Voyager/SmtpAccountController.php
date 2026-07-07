<?php

namespace App\Http\Controllers\Voyager;

use App\Models\SmtpAccount;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

class SmtpAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['campaign.access']);
    }

    public function index()
    {
        $accounts = SmtpAccount::latest()->get();
        return view('vendor.voyager.smtp-accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('vendor.voyager.smtp-accounts.edit-add');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['password'] = encrypt($data['password']);
        SmtpAccount::create($data);

        return redirect()->route('smtp-accounts.index')
            ->with('success', 'SMTP account added.');
    }

    public function edit(int $id)
    {
        $account = SmtpAccount::findOrFail($id);
        return view('vendor.voyager.smtp-accounts.edit-add', compact('account'));
    }

    public function update(Request $request, int $id)
    {
        $account = SmtpAccount::findOrFail($id);
        $data = $this->validated($request, $id);

        // Only re-encrypt password if a new one was submitted
        if (!empty($data['password'])) {
            $data['password'] = encrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $account->update($data);

        return redirect()->route('smtp-accounts.index')
            ->with('success', 'SMTP account updated.');
    }

    public function destroy(int $id)
    {
        SmtpAccount::findOrFail($id)->delete();
        return redirect()->route('smtp-accounts.index')
            ->with('success', 'SMTP account deleted.');
    }

    public function sendTest(Request $request, int $id)
    {
        $account = SmtpAccount::findOrFail($id);
        $request->validate(['test_email' => 'required|email']);

        try {
            $key = 'acct_' . $account->id;
            config(["mail.mailers.{$key}" => [
                'transport'  => 'smtp',
                'host'       => $account->host,
                'port'       => (int) $account->port,
                'encryption' => $account->encryption,
                'username'   => $account->username,
                'password'   => $account->decrypted_password,
                'timeout'    => null,
                'auth_mode'  => null,
            ]]);

            $from    = $account->from_address;
            $name    = $account->from_name;
            $to      = $request->test_email;

            Mail::mailer($key)
                ->to($to)
                ->send(new \App\Mail\SmtpTestMail($from, $name));

            return back()->with('success', "Test email sent to {$to} via {$account->username}.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Send failed: ' . $e->getMessage());
        }
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'         => 'required|string|max:255',
            'host'         => 'required|string|max:255',
            'port'         => 'required|integer|min:1|max:65535',
            'encryption'   => 'required|in:ssl,tls,starttls',
            'username'     => 'required|email',
            'password'     => $ignoreId ? 'nullable|string|min:1' : 'required|string|min:1',
            'from_address' => 'required|email',
            'from_name'    => 'required|string|max:255',
            'is_active'    => 'boolean',
        ]);
    }
}
