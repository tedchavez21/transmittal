<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    {{-- Convert Laravel flash messages to toasts or modals --}}
    @php
        $currentPath = request()->path();
        $useModal = str_contains($currentPath, 'officer-of-the-day') || str_contains($currentPath, 'facebook-handler') || str_contains($currentPath, 'email-handler');
    @endphp
    
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded',function(){
            @if($useModal)
                showModalMessage('{{ addslashes(session('success')) }}','success');
            @else
                showToast('{{ addslashes(session('success')) }}','success');
            @endif
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded',function(){
            @if($useModal)
                showModalMessage('{{ addslashes(session('error')) }}','error');
            @else
                showToast('{{ addslashes(session('error')) }}','error');
            @endif
        });
    </script>
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

        // Modal message system for OD, Facebook, and Email channels
        function showModalMessage(message, type) {
            var modal = document.createElement('dialog');
            modal.className = 'rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(400px,calc(100vw-2rem))]';
            
            var title = type === 'error' ? 'Error' : 'Success';
            var titleColor = type === 'error' ? 'text-red-900' : 'text-green-900';
            var iconColor = type === 'error' ? 'text-red-600' : 'text-green-600';
            var buttonColor = type === 'error' ? 'bg-red-700 hover:bg-red-800' : 'bg-green-700 hover:bg-green-800';
            
            var icon = type === 'error'
                ? '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>'
                : '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
            
            modal.innerHTML = 
                '<div class="px-5 pt-5 pb-3 border-b border-gray-100">' +
                    '<h3 class="text-base font-black ' + titleColor + '">' + title + '</h3>' +
                '</div>' +
                '<div class="px-5 py-4">' +
                    '<div class="flex items-start gap-3">' +
                        '<div class="' + iconColor + ' mt-0.5">' + icon + '</div>' +
                        '<div class="text-sm text-gray-600">' + message + '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="px-5 py-3 border-t border-gray-100 flex justify-end">' +
                    '<button type="button" class="h-9 px-4 rounded-lg ' + buttonColor + ' text-white text-xs font-bold transition-colors cursor-pointer">OK</button>' +
                '</div>';
            
            document.body.appendChild(modal);
            modal.showModal();
            
            // Handle close button
            modal.querySelector('button').addEventListener('click', function() {
                modal.close();
                setTimeout(function() { modal.remove(); }, 300);
            });
            
            // Close on backdrop click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.close();
                    setTimeout(function() { modal.remove(); }, 300);
                }
            });
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

    <!-- Confirmation Modal -->
    <dialog id="confirmModal" class="rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(400px,calc(100vw-2rem))]">
        <div class="px-5 pt-5 pb-3 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-900">Confirm Record Submission</h3>
        </div>
        <div class="px-5 py-4">
            <p class="text-sm text-gray-600 mb-4">Are you sure you want to add this record? Please review all the data before continuing.</p>
            <p class="text-xs text-gray-500 mb-4">Click "Continue" to submit or "Cancel" to go back and edit.</p>
        </div>
        <div class="px-5 py-3 border-t border-gray-100 flex gap-2 justify-end">
            <button type="button" id="confirmModalCancel" class="h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Cancel</button>
            <button type="button" id="confirmModalContinue" class="h-9 px-4 rounded-lg bg-pcic-700 text-white text-xs font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Continue</button>
        </div>
    </dialog>

    <!-- Assign Transmittal Modal -->
    <dialog id="assignTransmittalModal" class="rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(400px,calc(100vw-2rem))]">
        <div class="px-5 pt-5 pb-3 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-900">Assign Transmittal Number</h3>
        </div>
        <div class="px-5 py-4">
            <p class="text-sm text-gray-600 mb-4" id="assignTransmittalMessage"></p>
        </div>
        <div class="px-5 py-3 border-t border-gray-100 flex gap-2 justify-end">
            <button type="button" id="assignTransmittalCancel" class="h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Cancel</button>
            <button type="button" id="assignTransmittalContinue" class="h-9 px-4 rounded-lg bg-pcic-700 text-white text-xs font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Assign</button>
        </div>
    </dialog>

    <!-- Success Modal -->
    <dialog id="successModal" class="rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(400px,calc(100vw-2rem))]" style="z-index: 10000;">
        <div class="px-5 pt-5 pb-3 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-900">Success</h3>
        </div>
        <div class="px-5 py-4">
            <p class="text-sm text-gray-600 mb-4">Record added successfully!</p>
        </div>
        <div class="px-5 py-3 border-t border-gray-100 flex gap-2 justify-end">
            <button type="button" id="successModalOk" class="h-9 px-4 rounded-lg bg-pcic-700 text-white text-xs font-bold hover:bg-pcic-800 transition-colors cursor-pointer">OK</button>
        </div>
    </dialog>

    @stack('scripts')
    </body>
</html>