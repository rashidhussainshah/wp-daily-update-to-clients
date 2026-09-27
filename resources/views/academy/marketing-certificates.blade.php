@extends('voyager::master')

@section('page_title', 'Academy Certificates')

@section('page_header')
    <h1 class="page-title"><i class="voyager-medal"></i> Academy Certificates</h1>
@stop

@section('content')
<div class="page-content browse container-fluid">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <p class="text-muted">Every certificate issued so far - use "Copy Share Text" for a ready-to-paste social
                media post with the public verification link. No LinkedIn auto-posting yet; that needs your LinkedIn
                app credentials to wire up.</p>

            <table class="table">
                <thead>
                    <tr><th>Recipient</th><th>Certificate</th><th>Type</th><th>Issued</th><th>Verify Code</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($certificates as $cert)
                        @php
                            $verifyUrl = route('academy.certificate.verify', $cert->verify_code);
                            $shareText = "🎓 {$cert->recipient_name} just earned the {$cert->title} certificate from WebPenter IT Academy! Verify it here: {$verifyUrl}";
                        @endphp
                        <tr>
                            <td>{{ $cert->recipient_name }}</td>
                            <td>{{ $cert->title }}</td>
                            <td><span class="label {{ $cert->type === 'track' ? 'label-success' : 'label-info' }}">{{ ucfirst($cert->type) }}</span></td>
                            <td>{{ $cert->issued_at?->format('d M Y') }}</td>
                            <td><code>{{ $cert->verify_code }}</code></td>
                            <td>
                                <a href="{{ $verifyUrl }}" target="_blank" class="btn btn-primary btn-xs">View</a>
                                <button type="button" class="btn btn-default btn-xs copy-share-btn" data-text="{{ $shareText }}">Copy Share Text</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $certificates->links() }}
        </div>
    </div>
</div>
@stop

@section('javascript')
<script>
document.querySelectorAll('.copy-share-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        navigator.clipboard.writeText(btn.dataset.text).then(function () {
            var original = btn.textContent;
            btn.textContent = 'Copied!';
            setTimeout(function () { btn.textContent = original; }, 1500);
        });
    });
});
</script>
@stop
