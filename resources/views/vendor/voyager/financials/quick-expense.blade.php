<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Quick Add Expense</title>
<style>
  :root{
    --primary:#4361ee;
    --primary-dark:#2f45c5;
    --bg:#f2f4f8;
    --card:#ffffff;
    --text:#1c1f2a;
    --muted:#6b7280;
    --border:#e2e5ec;
    --success:#1d9d5f;
    --success-bg:#e8f8ef;
    --danger:#e0393e;
    --danger-bg:#fdeceb;
  }
  *{box-sizing:border-box;}
  body{
    margin:0;
    font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
    background:var(--bg);
    color:var(--text);
    padding:16px;
    padding-bottom:40px;
  }
  .wrap{max-width:480px;margin:0 auto;}
  h1{
    font-size:19px;
    font-weight:700;
    margin:4px 0 16px;
    display:flex;
    align-items:center;
    gap:8px;
  }
  .card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:14px;
    padding:18px;
    box-shadow:0 1px 3px rgba(0,0,0,0.04);
  }
  .alert{
    padding:12px 14px;
    border-radius:10px;
    font-size:14px;
    margin-bottom:14px;
    font-weight:600;
  }
  .alert-success{background:var(--success-bg);color:var(--success);}
  .alert-danger{background:var(--danger-bg);color:var(--danger);}
  label{
    display:block;
    font-size:12px;
    font-weight:600;
    color:var(--muted);
    text-transform:uppercase;
    letter-spacing:.03em;
    margin-bottom:6px;
  }
  .field{margin-bottom:16px;}
  .row{display:flex;gap:12px;}
  .row .field{flex:1;}
  input:not([type=checkbox]), select{
    width:100%;
    padding:12px 12px;
    font-size:16px;
    border:1px solid var(--border);
    border-radius:10px;
    background:#fbfbfd;
    color:var(--text);
    appearance:none;
    -webkit-appearance:none;
  }
  input:not([type=checkbox]):focus, select:focus{
    outline:none;
    border-color:var(--primary);
    background:#fff;
  }
  optgroup{font-style:normal;font-weight:700;}
  .checkbox-field{display:flex;align-items:center;gap:8px;margin-bottom:16px;}
  .checkbox-field input[type=checkbox]{
    width:20px;
    height:20px;
    accent-color:var(--primary);
    flex-shrink:0;
  }
  .checkbox-field label{margin:0;text-transform:none;font-size:14px;color:var(--text);letter-spacing:0;}
  .divider{border:none;border-top:1px dashed var(--border);margin:18px 0;}
  .section-label{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.03em;margin-bottom:10px;}
  button[type=submit]{
    width:100%;
    padding:15px;
    font-size:16px;
    font-weight:700;
    color:#fff;
    background:var(--primary);
    border:none;
    border-radius:12px;
    margin-top:4px;
  }
  button[type=submit]:active{background:var(--primary-dark);}
  .hint{font-size:12px;color:var(--muted);margin-top:10px;text-align:center;}
  @media (prefers-color-scheme: dark){
    :root{
      --bg:#0f1115;
      --card:#181b22;
      --text:#eef0f4;
      --muted:#9aa0ac;
      --border:#2a2e38;
      --success-bg:#123625;
      --danger-bg:#3a1a1b;
    }
    input, select{background:#12141a;}
    input:focus, select:focus{background:#12141a;}
  }
</style>
</head>
<body>
<div class="wrap">
  <h1>💸 Quick Add Expense</h1>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <div class="card">
    <form method="POST" action="{{ route('financials.store-expense') }}" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <div class="field">
          <label>Month</label>
          <input type="month" name="month" value="{{ now()->format('Y-m') }}" required>
        </div>
        <div class="field">
          <label>Amount Paid (PKR)</label>
          <input type="number" name="amount_pkr" placeholder="leave blank if only setting Expected Total below" min="0" step="1" inputmode="numeric">
        </div>
      </div>

      <div class="field">
        <label>Category</label>
        <select name="category" required>
          @foreach(\App\Models\MonthlyExpense::$categories as $key => $lbl)
            @if(!in_array($key, ['rent','claude_accounts','internet_moon','internet_prime']))
              <option value="{{ $key }}">{{ $lbl }}</option>
            @endif
          @endforeach
          <optgroup label="─ Fixed (use Auto-fill on desktop instead) ─">
            <option value="rent">Office Rent</option>
            <option value="claude_accounts">Claude AI Accounts</option>
            <option value="internet_moon">Internet — Moon</option>
            <option value="internet_prime">Internet — Prime</option>
          </optgroup>
        </select>
      </div>

      <div class="row">
        <div class="field">
          <label>Paid From</label>
          <select name="paid_from">
            <option value="">— not specified —</option>
            @foreach(\App\Models\MonthlyExpense::$bankAccounts as $k => $v)
              <option value="{{ $k }}">{{ $v }}</option>
            @endforeach
          </select>
        </div>
        <div class="field">
          <label>Note (optional)</label>
          <input type="text" name="note" placeholder="e.g. Jun bill">
        </div>
      </div>

      <div class="checkbox-field">
        <input type="checkbox" name="is_advance" id="is_advance" value="1">
        <label for="is_advance">This is an advance payment</label>
      </div>

      <div class="field">
        <label>Attachment (optional)</label>
        <input type="file" name="attachments[]" accept="image/*,.pdf" capture="environment" multiple>
      </div>

      @if($openBills->count())
      <hr class="divider">
      <div class="section-label">Settling a pending bill?</div>
      <div class="field">
        <label>Link to existing bill (optional)</label>
        <select name="parent_expense_id">
          <option value="">— none, this is a standalone expense —</option>
          @foreach($openBills as $bill)
            <option value="{{ $bill->id }}">
              {{ $bill->category_label }} ({{ $bill->month }}) — Rs {{ number_format($bill->pending_amount,0) }} pending
            </option>
          @endforeach
        </select>
      </div>
      @endif

      <hr class="divider">
      <div class="section-label">Or create a new bill to track</div>
      <div class="field">
        <label>Expected Total (optional)</label>
        <input type="number" name="expected_amount_pkr" placeholder="e.g. 16500 — full rent amount" min="0" step="1" inputmode="numeric">
      </div>
      <div class="hint" style="margin-top:-8px;margin-bottom:14px;text-align:left;">
        Fill this in instead of "Amount" above to create a trackable bill with nothing paid yet. Come back later and link payments to it as they happen.
      </div>

      <button type="submit">Add Expense</button>
    </form>
  </div>

  <div class="hint">Signed in as {{ auth()->user()->name ?? auth()->user()->email }}</div>
</div>
</body>
</html>
