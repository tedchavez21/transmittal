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

    {{-- Modal notification container --}}
    <div id="modalContainer"></div>

    {{-- Convert Laravel flash messages to modals --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded',function(){
            showModalMessage('{{ addslashes(session('success')) }}','success');
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded',function(){
            showModalMessage('{{ addslashes(session('error')) }}','error');
        });
    </script>
    @endif
    @if(session('warning'))
    <script>document.addEventListener('DOMContentLoaded',function(){showModalMessage('{{ addslashes(session('warning')) }}','warning')});</script>
    @endif
    @if(session('info'))
    <script>document.addEventListener('DOMContentLoaded',function(){showModalMessage('{{ addslashes(session('info')) }}','info')});</script>
    @endif

    <script>

        // Modal message system for all notifications
        function showModalMessage(message, type) {
            var modal = document.createElement('dialog');
            modal.className = 'rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(400px,calc(100vw-2rem))]';
            
            // Set colors and icons based on type
            var title, titleColor, iconColor, buttonColor, icon;
            
            switch(type) {
                case 'error':
                    title = 'Error';
                    titleColor = 'text-red-900';
                    iconColor = 'text-red-600';
                    buttonColor = 'bg-red-700 hover:bg-red-800';
                    icon = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
                    break;
                case 'warning':
                    title = 'Warning';
                    titleColor = 'text-yellow-900';
                    iconColor = 'text-yellow-600';
                    buttonColor = 'bg-yellow-700 hover:bg-yellow-800';
                    icon = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>';
                    break;
                case 'info':
                    title = 'Information';
                    titleColor = 'text-blue-900';
                    iconColor = 'text-blue-600';
                    buttonColor = 'bg-blue-700 hover:bg-blue-800';
                    icon = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>';
                    break;
                default: // success
                    title = 'Success';
                    titleColor = 'text-green-900';
                    iconColor = 'text-green-600';
                    buttonColor = 'bg-green-700 hover:bg-green-800';
                    icon = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
            }
            
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

        // Custom confirmation modal function
        function showConfirmDialog(message, onConfirm, onCancel) {
            var modal = document.createElement('dialog');
            modal.className = 'rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(400px,calc(100vw-2rem))]';
            
            modal.innerHTML = 
                '<div class="px-5 pt-5 pb-3 border-b border-gray-100">' +
                    '<h3 class="text-base font-black text-gray-900">Confirm Action</h3>' +
                '</div>' +
                '<div class="px-5 py-4">' +
                    '<div class="flex items-start gap-3">' +
                        '<div class="text-yellow-600 mt-0.5">' +
                            '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>' +
                        '</div>' +
                        '<div class="text-sm text-gray-600">' + message + '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="px-5 py-3 border-t border-gray-100 flex gap-2 justify-end">' +
                    '<button type="button" class="cancelBtn h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Cancel</button>' +
                    '<button type="button" class="confirmBtn h-9 px-4 rounded-lg bg-red-700 hover:bg-red-800 text-white text-xs font-bold transition-colors cursor-pointer">Delete</button>' +
                '</div>';
            
            document.body.appendChild(modal);
            modal.showModal();
            
            // Handle cancel button
            modal.querySelector('.cancelBtn').addEventListener('click', function() {
                modal.close();
                setTimeout(function() { modal.remove(); }, 300);
                if (onCancel) onCancel();
            });
            
            // Handle confirm button
            modal.querySelector('.confirmBtn').addEventListener('click', function() {
                modal.close();
                setTimeout(function() { modal.remove(); }, 300);
                if (onConfirm) onConfirm();
            });
            
            // Close on backdrop click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.close();
                    setTimeout(function() { modal.remove(); }, 300);
                    if (onCancel) onCancel();
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

        // Auto-logout on window close/tab close
        (function() {
            let logoutSent = false;
            
            // Send logout request when user leaves the page
            function sendLogoutRequest() {
                if (logoutSent) return; // Prevent multiple requests
                
                logoutSent = true;
                
                // Use navigator.sendBeacon for reliable delivery during page unload
                const logoutData = new FormData();
                logoutData.append('auto_logout', 'true');
                logoutData.append('channel', getCurrentChannel());
                
                try {
                    navigator.sendBeacon('/auto-logout', logoutData);
                } catch (e) {
                    // Fallback to fetch if sendBeacon fails
                    fetch('/auto-logout', {
                        method: 'POST',
                        body: logoutData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        keepalive: true
                    }).catch(() => {}); // Ignore errors during unload
                }
            }
            
            // Get current user channel
            function getCurrentChannel() {
                const path = window.location.pathname;
                if (path.includes('officer-of-the-day')) return 'OD';
                if (path.includes('email-handler')) return 'Email';
                if (path.includes('facebook-handler')) return 'Facebook';
                if (path.includes('admin')) return 'admin';
                return 'unknown';
            }
            
            // Add event listeners for page unload
            window.addEventListener('beforeunload', sendLogoutRequest);
            window.addEventListener('pagehide', sendLogoutRequest);
            
            // Also handle visibility change (when user switches tabs)
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'hidden') {
                    // User switched to another tab or minimized window
                    // Mark as potentially away, but don't logout immediately
                    const awayData = new FormData();
                    awayData.append('away', 'true');
                    awayData.append('channel', getCurrentChannel());
                    
                    try {
                        navigator.sendBeacon('/update-activity', awayData);
                    } catch (e) {
                        // Ignore errors
                    }
                }
            });
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