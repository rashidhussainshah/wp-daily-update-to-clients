@extends('voyager::master')
@section('page_title', isset($signature) ? 'Edit Signature' : 'New Signature')

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title">
        <i class="voyager-edit-copy"></i>
        {{ isset($signature) ? 'Edit Signature — ' . $signature->display_name : 'New Email Signature' }}
    </h1>
    <a href="{{ route('email-signatures.index') }}" class="btn btn-default btn-sm">
        <i class="voyager-angle-left"></i> All Signatures
    </a>
    @if(isset($signature))
    <a href="{{ route('email-signatures.preview', $signature->id) }}" target="_blank" class="btn btn-info btn-sm">
        <i class="voyager-browser"></i> Preview
    </a>
    @endif
</div>
@stop

@section('content')
<div class="page-content container-fluid">

@if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin:0;padding-left:18px;">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST"
      action="{{ isset($signature) ? route('email-signatures.update', $signature->id) : route('email-signatures.store') }}"
      enctype="multipart/form-data">
    @csrf
    @if(isset($signature)) @method('PUT') @endif

    <div class="row">

        {{-- ── Left column: fields ─────────────────────────────────────────── --}}
        <div class="col-md-7">

            <div class="panel panel-bordered">
                <div class="panel-heading"><h3 class="panel-title">Sender Details</h3></div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sender Email <span class="text-danger">*</span></label>
                                @if(count($allowedSenders ?? []))
                                <select name="sender_email" class="form-control" required>
                                    <option value="">-- Select sender --</option>
                                    @foreach($allowedSenders as $email)
                                    <option value="{{ $email }}"
                                        {{ old('sender_email', $signature->sender_email ?? '') === $email ? 'selected' : '' }}>
                                        {{ $email }}
                                    </option>
                                    @endforeach
                                </select>
                                @else
                                <input type="email" name="sender_email" class="form-control"
                                       value="{{ old('sender_email', $signature->sender_email ?? '') }}"
                                       placeholder="sales@webpenter.com" required>
                                @endif
                                <p class="help-block" style="font-size:11px;">
                                    Must match the campaign's From Email.
                                    <a href="/admin/settings#email-signatures" target="_blank">Manage allowed senders</a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Display Name <span class="text-danger">*</span></label>
                                <input type="text" name="display_name" class="form-control"
                                       value="{{ old('display_name', $signature->display_name ?? '') }}"
                                       placeholder="Rashid Bukhari" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Designation / Title</label>
                                <input type="text" name="designation" class="form-control"
                                       value="{{ old('designation', $signature->designation ?? '') }}"
                                       placeholder="CEO, Webpenter">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tagline <small class="text-muted">(optional short slogan)</small></label>
                                <input type="text" name="tagline" class="form-control"
                                       value="{{ old('tagline', $signature->tagline ?? '') }}"
                                       placeholder="Building the web, one site at a time">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ old('phone', $signature->phone ?? '') }}"
                                       placeholder="+92 300 0000000">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Contact Email <small class="text-muted">(shown in sig)</small></label>
                                <input type="email" name="contact_email" class="form-control"
                                       value="{{ old('contact_email', $signature->contact_email ?? '') }}"
                                       placeholder="rashid@webpenter.com">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Website</label>
                                <input type="url" name="website" class="form-control"
                                       value="{{ old('website', $signature->website ?? '') }}"
                                       placeholder="https://webpenter.com">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>LinkedIn URL</label>
                                <input type="url" name="linkedin" class="form-control"
                                       value="{{ old('linkedin', $signature->linkedin ?? '') }}"
                                       placeholder="https://linkedin.com/in/yourprofile">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Active</label>
                                <div style="padding-top:6px;">
                                    <label style="font-weight:normal;cursor:pointer;">
                                        <input type="checkbox" name="is_active" value="1"
                                               {{ old('is_active', $signature->is_active ?? true) ? 'checked' : '' }}>
                                        &nbsp;Send with campaigns
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Template picker ──────────────────────────────────────────── --}}
            <div class="panel panel-bordered">
                <div class="panel-heading"><h3 class="panel-title">Template Style</h3></div>
                <div class="panel-body">
                    <div class="row" id="template-picker">

                        @php $currentTemplate = old('template', $signature->template ?? 'classic'); @endphp

                        {{-- Classic --}}
                        <div class="col-md-4">
                            <label style="cursor:pointer;width:100%;">
                                <input type="radio" name="template" value="classic" class="tpl-radio"
                                       {{ $currentTemplate === 'classic' ? 'checked' : '' }} style="display:none;">
                                <div class="tpl-card {{ $currentTemplate === 'classic' ? 'tpl-selected' : '' }}"
                                     style="border:2px solid #ddd;border-radius:8px;padding:14px;transition:border .15s;">
                                    {{-- Mini preview --}}
                                    <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:8px;">
                                        <div style="width:32px;height:32px;border-radius:50%;background:#1a1a2e;flex-shrink:0;"></div>
                                        <div style="flex:1;">
                                            <div style="height:8px;background:#1a1a2e;border-radius:2px;margin-bottom:4px;width:80%;"></div>
                                            <div style="height:5px;background:#ccc;border-radius:2px;margin-bottom:3px;width:60%;"></div>
                                            <div style="height:5px;background:#eee;border-radius:2px;width:70%;"></div>
                                        </div>
                                    </div>
                                    <div style="font-size:12px;font-weight:600;color:#333;">Classic</div>
                                    <div style="font-size:11px;color:#888;">Photo left · Info right</div>
                                </div>
                            </label>
                        </div>

                        {{-- Minimal --}}
                        <div class="col-md-4">
                            <label style="cursor:pointer;width:100%;">
                                <input type="radio" name="template" value="minimal" class="tpl-radio"
                                       {{ $currentTemplate === 'minimal' ? 'checked' : '' }} style="display:none;">
                                <div class="tpl-card {{ $currentTemplate === 'minimal' ? 'tpl-selected' : '' }}"
                                     style="border:2px solid #ddd;border-radius:8px;padding:14px;transition:border .15s;">
                                    <div style="margin-bottom:8px;border-top:1px solid #ccc;padding-top:8px;">
                                        <div style="height:8px;background:#1a1a2e;border-radius:2px;margin-bottom:4px;width:70%;"></div>
                                        <div style="height:5px;background:#ccc;border-radius:2px;margin-bottom:3px;width:50%;"></div>
                                        <div style="height:4px;background:#eee;border-radius:2px;width:80%;"></div>
                                    </div>
                                    <div style="font-size:12px;font-weight:600;color:#333;">Minimal</div>
                                    <div style="font-size:11px;color:#888;">Text only · Clean</div>
                                </div>
                            </label>
                        </div>

                        {{-- Bold --}}
                        <div class="col-md-4">
                            <label style="cursor:pointer;width:100%;">
                                <input type="radio" name="template" value="bold" class="tpl-radio"
                                       {{ $currentTemplate === 'bold' ? 'checked' : '' }} style="display:none;">
                                <div class="tpl-card {{ $currentTemplate === 'bold' ? 'tpl-selected' : '' }}"
                                     style="border:2px solid #ddd;border-radius:8px;padding:14px;transition:border .15s;">
                                    <div style="display:flex;gap:0;align-items:flex-start;margin-bottom:8px;border-top:3px solid #1a1a2e;padding-top:8px;">
                                        <div style="width:4px;background:#1a1a2e;border-radius:2px;margin-right:8px;align-self:stretch;min-height:36px;"></div>
                                        <div style="width:28px;height:28px;border-radius:4px;background:#64748b;flex-shrink:0;margin-right:8px;"></div>
                                        <div style="flex:1;">
                                            <div style="height:8px;background:#1a1a2e;border-radius:2px;margin-bottom:4px;width:90%;"></div>
                                            <div style="height:5px;background:#555;border-radius:2px;margin-bottom:3px;width:65%;"></div>
                                            <div style="height:4px;background:#eee;border-radius:2px;width:75%;"></div>
                                        </div>
                                    </div>
                                    <div style="font-size:12px;font-weight:600;color:#333;">Bold</div>
                                    <div style="font-size:11px;color:#888;">Colour bar · Strong</div>
                                </div>
                            </label>
                        </div>

                    </div>

                    <div class="row" style="margin-top:16px;">
                        <div class="col-md-4">
                            <label>Accent Colour</label>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <input type="color" name="accent_color" id="accent_color"
                                       value="{{ old('accent_color', $signature->accent_color ?? '#1a1a2e') }}"
                                       style="width:44px;height:36px;border:1px solid #ccc;border-radius:4px;cursor:pointer;padding:2px;">
                                <input type="text" id="accent_hex"
                                       value="{{ old('accent_color', $signature->accent_color ?? '#1a1a2e') }}"
                                       style="width:90px;" class="form-control input-sm"
                                       placeholder="#1a1a2e">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- ── Right column: photos ─────────────────────────────────────────── --}}
        <div class="col-md-5">

            <div class="panel panel-bordered">
                <div class="panel-heading"><h3 class="panel-title">Profile Photo</h3></div>
                <div class="panel-body">

                    @if(isset($signature) && $signature->photo_url)
                    <div style="margin-bottom:14px;">
                        <img src="{{ $signature->photo_url }}" id="photo-preview"
                             style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #e2e8f0;display:block;">
                        <label style="margin-top:8px;font-weight:normal;cursor:pointer;font-size:12px;color:#e74c3c;">
                            <input type="checkbox" name="remove_photo" value="1"> Remove photo
                        </label>
                    </div>
                    @else
                    <div style="margin-bottom:14px;">
                        <img src="" id="photo-preview"
                             style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px dashed #e2e8f0;display:none;">
                    </div>
                    @endif

                    <div class="form-group">
                        <label style="font-size:12px;">Upload photo <span class="text-muted">(JPG/PNG, max 2MB)</span></label>
                        <input type="file" name="photo" accept="image/*" id="photo-input"
                               style="display:block;font-size:13px;">
                    </div>
                    <p class="help-block" style="font-size:11px;">Square photo works best. Will be displayed as circle in Classic &amp; Bold templates.</p>
                </div>
            </div>

            <div class="panel panel-bordered">
                <div class="panel-heading">
                    <h3 class="panel-title">Handwritten Signature <small style="font-weight:normal;color:#888;">(optional)</small></h3>
                </div>
                <div class="panel-body">

                    @if(isset($signature) && $signature->handwritten_url)
                    <div style="margin-bottom:14px;background:#f8fafc;padding:10px;border-radius:6px;">
                        <img src="{{ $signature->handwritten_url }}" id="handwritten-preview"
                             style="max-height:50px;display:block;">
                        <label style="margin-top:6px;font-weight:normal;cursor:pointer;font-size:12px;color:#e74c3c;">
                            <input type="checkbox" name="remove_handwritten" value="1"> Remove
                        </label>
                    </div>
                    @else
                    <div style="margin-bottom:14px;background:#f8fafc;padding:10px;border-radius:6px;min-height:60px;">
                        <img src="" id="handwritten-preview" style="max-height:50px;display:none;">
                    </div>
                    @endif

                    <div class="form-group">
                        <label style="font-size:12px;">Upload signature image <span class="text-muted">(PNG with transparent bg recommended)</span></label>
                        <input type="file" name="handwritten" accept="image/*" id="handwritten-input"
                               style="display:block;font-size:13px;">
                    </div>
                </div>
            </div>

            {{-- Save button --}}
            <div class="panel panel-bordered" style="border-color:#27ae60;">
                <div class="panel-body">
                    <button type="submit" class="btn btn-success btn-block btn-lg">
                        <i class="voyager-save"></i>
                        {{ isset($signature) ? 'Save Changes' : 'Create Signature' }}
                    </button>
                    @if(isset($signature))
                    <a href="{{ route('email-signatures.preview', $signature->id) }}" target="_blank"
                       class="btn btn-info btn-block" style="margin-top:8px;">
                        <i class="voyager-browser"></i> Preview in browser
                    </a>
                    @endif
                </div>
            </div>

        </div>
    </div>

</form>
</div>
@stop

@section('css')
<style>
.tpl-selected {
    border-color: #1a1a2e !important;
    background: #f0f4ff;
}
.tpl-card:hover {
    border-color: #94a3b8 !important;
}
</style>
@stop

@section('javascript')
<script>
// Template card selection
document.querySelectorAll('.tpl-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.tpl-card').forEach(function(c) {
            c.classList.remove('tpl-selected');
        });
        this.closest('label').querySelector('.tpl-card').classList.add('tpl-selected');
    });
});

// Colour picker ↔ hex input sync
var picker = document.getElementById('accent_color');
var hexBox = document.getElementById('accent_hex');
picker.addEventListener('input', function() { hexBox.value = this.value; });
hexBox.addEventListener('input', function() {
    if (/^#[0-9a-fA-F]{6}$/.test(this.value)) picker.value = this.value;
});

// Photo preview
document.getElementById('photo-input').addEventListener('change', function() {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var img = document.getElementById('photo-preview');
        img.src = e.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(file);
});

// Handwritten preview
document.getElementById('handwritten-input').addEventListener('change', function() {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var img = document.getElementById('handwritten-preview');
        img.src = e.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(file);
});
</script>
@stop
