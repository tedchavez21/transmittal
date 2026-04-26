<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title','transmittal')</title>
    <link rel="icon" type="image/svg+xml" href="/images/icon.svg">
    @yield('page-styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Fix pagination SVG icon sizing */
        .pagination svg {
            width: 12px !important;
            height: 12px !important;
            vertical-align: middle;
        }
        .pagination .page-link {
            font-size: 14px;
            padding: 8px 12px;
            line-height: 1.4;
        }
    </style>
    <style media="print">
        @page { size: landscape; margin: 10mm; }
        body { margin: 0; padding: 0; }
        .odHeader { display: none !important; }
        .addRecordButton { display: none !important; }
        .no-print { display: none !important; }
        table { display: table !important; width: 100%; border-collapse: collapse; }
        table, tbody, thead, tr, td, th { display: table !important; width: auto; height: auto; margin: 0; padding: 0; border: 1px solid #000; }
        tr { page-break-inside: avoid; }
        .page-break { page-break-after: always; }
        dialog { display: none !important; }
    </style>
</head>
<body>
    @yield('content')

    {{-- Toast notification container --}}
    <div id="toastContainer" style="position:fixed;top:20px;right:20px;z-index:99999;display:flex;flex-direction:column;gap:10px;pointer-events:none;"></div>

    {{-- Convert Laravel flash messages to toasts --}}
    @if(session('success'))
    <script>document.addEventListener('DOMContentLoaded',function(){showToast('{{ addslashes(session('success')) }}','success')});</script>
    @endif
    @if(session('error'))
    <script>document.addEventListener('DOMContentLoaded',function(){showToast('{{ addslashes(session('error')) }}','error')});</script>
    @endif
    @if(session('warning'))
    <script>document.addEventListener('DOMContentLoaded',function(){showToast('{{ addslashes(session('warning')) }}','warning')});</script>
    @endif
    @if(session('info'))
    <script>document.addEventListener('DOMContentLoaded',function(){showToast('{{ addslashes(session('info')) }}','info')});</script>
    @endif

    <script>
        // Toast notification system
        function showToast(message, type) {
            var container = document.getElementById('toastContainer');
            var toast = document.createElement('div');
            toast.className = 'toast toast-' + (type || 'success');
            var icon = type === 'error'
                ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>'
                : '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
            toast.innerHTML = '<span class="toast-icon">' + icon + '</span><span class="toast-msg">' + message + '</span><button class="toast-close" onclick="this.parentElement.remove()">&times;</button>';
            container.appendChild(toast);
            setTimeout(function() { toast.classList.add('toast-show'); }, 10);
            setTimeout(function() {
                toast.classList.remove('toast-show');
                setTimeout(function() { toast.remove(); }, 300);
            }, 4000);
        }

        // Form validation shake feedback + loading spinner on submit
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    var invalid = form.querySelector('input:invalid, select:invalid, textarea:invalid');
                    if (invalid) {
                        e.preventDefault();
                        invalid.classList.add('shake-invalid');
                        invalid.focus();
                        setTimeout(function() { invalid.classList.remove('shake-invalid'); }, 500);
                        return;
                    }
                    // Show loading spinner on submit buttons
                    var btns = form.querySelectorAll('button[type="submit"], input[type="submit"]');
                    btns.forEach(function(btn) {
                        if (btn.tagName === 'INPUT') {
                            btn.dataset.originalValue = btn.value;
                            btn.value = '...';
                            btn.disabled = true;
                        } else {
                            btn.classList.add('is-loading');
                            btn.disabled = true;
                        }
                    });
                });
            });
        });

        // Animated dialog close — prevent snap-left by fading out before actually closing
        (function() {
            var nativeClose = HTMLDialogElement.prototype.close;
            HTMLDialogElement.prototype.close = function(returnValue) {
                var dlg = this;
                if (!dlg.open) { nativeClose.call(dlg, returnValue); return; }
                dlg.classList.add('closing');
                dlg.addEventListener('transitionend', function handler(e) {
                    if (e.propertyName === 'opacity') {
                        dlg.removeEventListener('transitionend', handler);
                        dlg.classList.remove('closing');
                        nativeClose.call(dlg, returnValue);
                    }
                });
                // Fallback if transition doesn't fire
                setTimeout(function() {
                    if (dlg.classList.contains('closing')) {
                        dlg.classList.remove('closing');
                        nativeClose.call(dlg, returnValue);
                    }
                }, 300);
            };
        })();
    </script>
</body>
</html>