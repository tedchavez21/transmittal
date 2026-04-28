@extends('layout.layout')

@section('title', 'Admin')

@section('page-styles')
{{-- Admin styles live in app.css --}}
@endsection

@push('styles')
<style>
.auto-caps {
    text-transform: capitalize;
}

.pagination-link:hover {
    background: linear-gradient(135deg, #005a2d 0%, #006c35 100%) !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 108, 53, 0.2) !important;
}
</style>
@endpush

@section('content')
    <div class="admin-shell">
        <aside class="admin-sidebar no-print">
            <div class="admin-brand">
                <div>
                    <div class="title">PCIC</div>
                    <div class="subtitle">NL Monitoring</div>
                </div>
            </div>

            <div class="admin-sidebar-section-label">Navigation</div>
            <nav class="admin-nav" aria-label="Admin navigation">
                <button type="button" class="active" id="btn-dashboard">
                    <span class="icon" aria-hidden="true"><img src="/images/dashboard.svg" alt="" width="18" height="18"></span>
                    <span>Dashboard</span>
                </button>
                <button type="button" id="btn-nl-records">
                    <span class="icon" aria-hidden="true"><img src="/images/file-svgrepo-com.svg" alt="" width="18" height="18"></span>
                    <span>NL Records</span>
                </button>
            </nav>

            <div class="admin-sidebar-divider"></div>
            <div class="admin-sidebar-section-label">Tools</div>
            <div class="admin-sidebar-actions">
                <button type="button" class="admin-sidebar-tool" id="openUserApprovalsModal" title="Pending user approvals (Email and OD)">
                    <div class="tool-content">
                        <span class="icon" aria-hidden="true"><img src="/images/user.svg" alt="" width="18" height="18"></span>
                        <span class="label">User approvals</span>
                    </div>
                    <span id="pendingBadge" class="pending-badge" style="display:none;"></span>
                </button>
                <button type="button" class="admin-sidebar-tool" id="openActiveUsersModal" title="View active users">
                    <div class="tool-content">
                        <span class="icon" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </span>
                        <span class="label">Active users</span>
                    </div>
                </button>
                <button type="button" class="admin-sidebar-tool" id="openAdminUsersModal" title="Admin Users">
                    <div class="tool-content">
                        <span class="icon" aria-hidden="true"><img src="/images/admin.svg" alt="" width="18" height="18"></span>
                        <span class="label">Admin users</span>
                    </div>
                </button>
            </div>
        </aside>

        <main class="admin-main">
            <div class="admin-topbar no-print">
                <div class="topbar-content">
                    <div class="topbar-left">
                        <div class="topbar-brand">
                            <div class="brand-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                                    <path d="M2 17l10 5 10-5"/>
                                    <path d="M2 12l10 5 10-5"/>
                                </svg>
                            </div>
                            <div class="brand-text">
                                <h1>NL Records Admin</h1>
                                <p>NL monitoring and transmittal</p>
                            </div>
                        </div>
                    </div>
                    <div class="topbar-right">
                        <div class="topbar-actions">
                            <form action="{{ route('admin.logout') }}" method="POST" class="logout-form">
                                @csrf
                                <button type="submit" class="logout-btn" title="Logout">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                                        <polyline points="16,17 21,12 16,7"/>
                                        <line x1="21" y1="12" x2="9" y2="12"/>
                                    </svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

    <!-- User approvals: Email handlers + Officers of the Day -->
    <dialog class="largeModal rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(640px,calc(100vw-2rem))]" id="userApprovalsModal">
        <div class="px-5 pt-5 pb-3 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-900">Pending user approvals</h3>
        </div>
        <div class="px-5 py-4">
        <h4 class="text-sm font-bold text-gray-700 mt-2 mb-2 pb-1.5 border-b border-gray-200">Email (NL entry)</h4>
        @if($pendingEmailHandlers->isEmpty())
            <p class="text-center py-4 text-sm text-gray-400">No pending email handler approvals.</p>
        @else
            <ul class="max-h-72 overflow-y-auto mb-4">
                @foreach($pendingEmailHandlers as $emailHandler)
                    <li class="py-3 flex items-center justify-between border-b border-gray-100 list-none">
                        <span class="text-sm font-semibold text-gray-800">{{ $emailHandler->name }}</span>
                        <form action="{{ route('admin.email-handlers.approve', $emailHandler->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="h-7 px-3 rounded-lg bg-pcic-700 text-white text-xs font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Approve</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif

        <h4 class="text-sm font-bold text-gray-700 mt-2 mb-2 pb-1.5 border-b border-gray-200">Officer of the Day</h4>
        @if($pendingOfficers->isEmpty())
            <p class="text-center py-4 text-sm text-gray-400">No pending officer approvals.</p>
        @else
            <ul class="max-h-72 overflow-y-auto">
                @foreach($pendingOfficers as $officer)
                    <li class="py-3 flex items-center justify-between border-b border-gray-100 list-none">
                        <span class="text-sm font-semibold text-gray-800">{{ $officer->name }}</span>
                        <form action="{{ route('admin.officers.approve', $officer->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="h-7 px-3 rounded-lg bg-pcic-700 text-white text-xs font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Approve</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-5 flex justify-end">
            <button type="button" class="closeUserApprovalsModal h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Close</button>
        </div>
        </div>
    </dialog>

    <!-- Admin Users Modal -->
    <dialog class="largeModal rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(640px,calc(100vw-2rem))]" id="adminUsersModal">
        <div class="px-5 pt-5 pb-3 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-black text-gray-900">Admin Users</h3>
            <button type="button" class="addAdminButton h-8 px-3 rounded-lg bg-pcic-700 text-white text-xs font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Add New Admin</button>
        </div>
        <div class="px-5 py-4">
        @if($admins->isEmpty())
            <p class="text-center py-8 text-sm text-gray-400">No admin users found.</p>
        @else
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left px-3 py-2 text-xs font-bold text-gray-500 w-2/5">Username</th>
                        <th class="text-left px-3 py-2 text-xs font-bold text-gray-500 w-1/3">Password</th>
                        <th class="text-left px-3 py-2 text-xs font-bold text-gray-500 w-1/4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                        <tr class="border-b border-gray-100">
                            <td class="px-3 py-2 text-sm text-gray-800">{{ $admin->username }}</td>
                            <td class="px-3 py-2 text-sm text-gray-400">••••••••</td>
                            <td class="px-3 py-2">
                                <button type="button" class="editAdminButton h-7 px-3 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer" data-id="{{ $admin->id }}" data-username="{{ $admin->username }}">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <div class="mt-5 flex justify-end">
            <button type="button" class="closeAdminUsersModal h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Close</button>
        </div>
        </div>
    </dialog>

    <!-- Active Users Modal -->
    <dialog class="largeModal rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(800px,calc(100vw-2rem))]" id="activeUsersModal">
        <div class="px-5 pt-5 pb-3 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-900">Active Users</h3>
        </div>
        <div class="px-5 py-4">
            <div id="activeUsersContent">
                <div class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-pcic-700"></div>
                    <p class="text-sm text-gray-500 mt-2">Loading active users...</p>
                </div>
            </div>
            <div class="mt-5 flex justify-end">
                <button type="button" class="closeActiveUsersModal h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Close</button>
            </div>
        </div>
    </dialog>

    <!-- Dashboard Section -->
    <div id="dashboard-section">
    <!-- DashboardDD -->
        @php
            $summaryDate = '';
            if(request('dash_date_type') == 'single' && request('dash_date_single')) {
                $summaryDate = date('F j, Y', strtotime(request('dash_date_single')));
            } elseif(request('dash_date_type') == 'range' && (request('dash_date_from') || request('dash_date_to'))) {
                $from = request('dash_date_from') ? date('F j, Y', strtotime(request('dash_date_from'))) : null;
                $to = request('dash_date_to') ? date('F j, Y', strtotime(request('dash_date_to'))) : null;
                if ($from && $to) {
                    $summaryDate = $from . ' to ' . $to;
                } elseif ($from) {
                    $summaryDate = 'From ' . $from;
                } elseif ($to) {
                    $summaryDate = 'Until ' . $to;
                }
            }

            $summaryProgram = request('dash_program') ? request('dash_program') : 'All Programs';
            $summaryLine = request('dash_line') ? request('dash_line') : 'All Lines';

            $dash3MetaText = $summaryProgram . ' • ' . $summaryLine . ' • ' . ($summaryDate ? $summaryDate : 'All dates');

            $provinceTables = [
                'Aurora' => $dashCountsByProvince['Aurora'] ?? [],
                'Nueva Ecija' => $dashCountsByProvince['Nueva Ecija'] ?? [],
            ];
        @endphp

        <div class="dash3-shell">
            <div class="dash3-layout">
            <div class="admin-card no-print">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">NL Dashboard</h3>
                        <p class="card-subtitle">Aurora and Nueva Ecija municipality summary. Click a municipality for barangays.</p>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin') }}" class="dash3-filter-form">
                        <input type="hidden" name="tab" value="dashboard">
                        <div class="dash3-filter-row">
                            <div class="form-field">
                                <label>Program</label>
                                <select name="dash_program">
                                    <option value="">All Programs</option>
                                    @foreach($allPrograms as $program)
                                        <option value="{{ $program }}" {{ request('dash_program') == $program ? 'selected' : '' }}>{{ $program }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-field">
                                <label>Line</label>
                                <select name="dash_line">
                                    <option value="">All Lines</option>
                                    @foreach($allLines as $line)
                                        <option value="{{ $line }}" {{ request('dash_line') == $line ? 'selected' : '' }}>{{ $line }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-field">
                                <label>Date filter</label>
                                <select name="dash_date_type" id="dashDateType" onchange="toggleDashDateFilters()">
                                    <option value="">All Dates</option>
                                    <option value="single" {{ request('dash_date_type') == 'single' ? 'selected' : '' }}>Specific Date</option>
                                    <option value="range" {{ request('dash_date_type') == 'range' ? 'selected' : '' }}>Date Range</option>
                                </select>
                            </div>

                            <div class="form-field dash-date-filter" id="dashSingleDate" style="display: {{ request('dash_date_type') == 'single' ? 'flex' : 'none' }};">
                                <label>Date</label>
                                <input type="date" name="dash_date_single" value="{{ request('dash_date_single') }}">
                            </div>

                            <div class="form-field dash-date-filter" id="dashDateRange" style="display: {{ request('dash_date_type') == 'range' ? 'flex' : 'none' }};">
                                <label>From</label>
                                <input type="date" name="dash_date_from" value="{{ request('dash_date_from') }}">
                            </div>

                            <div class="form-field dash-date-filter" id="dashDateRangeTo" style="display: {{ request('dash_date_type') == 'range' ? 'flex' : 'none' }};">
                                <label>To</label>
                                <input type="date" name="dash_date_to" value="{{ request('dash_date_to') }}">
                            </div>
                        </div>

                        <div class="dash3-actions">
                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                            <a href="{{ route('admin', ['tab' => 'dashboard']) }}" class="btn btn-muted btn-sm">Clear</a>
                        </div>
                    </form>

                    <script>
                        function toggleDashDateFilters() {
                            var dateType = document.getElementById('dashDateType').value;
                            document.querySelectorAll('.dash-date-filter').forEach(function(el) {
                                el.style.display = 'none';
                            });
                            if (dateType === 'single') {
                                document.getElementById('dashSingleDate').style.display = 'flex';
                            } else if (dateType === 'range') {
                                document.getElementById('dashDateRange').style.display = 'flex';
                                document.getElementById('dashDateRangeTo').style.display = 'flex';
                            }
                        }
                    </script>

                    <div class="dash3-summary">
                        <div class="dash3-summary-title">Filters</div>
                        <div class="dash3-summary-sub">
                            {{ $summaryProgram }} • {{ $summaryDate ? $summaryDate : 'All dates' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="dash3-charts-row">
                @php
                    $chartMax = max($recordsByProgram->max() ?? 1, $recordsByLine->max() ?? 1, $recordsByMunicipality->max() ?? 1, 1);
                    $sourceTotal = $recordsBySource->sum();
                    $sourceColors = ['OD' => '#006c35', 'Email' => '#638c08', 'Facebook' => '#008a43'];
                    $sourceConicParts = [];
                    $sourceOffset = 0;
                    foreach (['OD', 'Email', 'Facebook'] as $src) {
                        $count = $recordsBySource->get($src, 0);
                        if ($count > 0 && $sourceTotal > 0) {
                            $pct = ($count / $sourceTotal) * 100;
                            $sourceConicParts[] = $sourceColors[$src] . ' ' . $sourceOffset . '% ' . ($sourceOffset + $pct) . '%';
                            $sourceOffset += $pct;
                        }
                    }
                    $sourceConic = count($sourceConicParts) > 0 ? implode(',', $sourceConicParts) : '#e2e8f0 0% 100%';
                @endphp

                <div class="admin-card dash3-chart-card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">By Program</h3>
                            <p class="card-subtitle">NL count per program</p>
                        </div>
                    </div>
                    <div class="card-body dash3-chart-body">
                        @foreach($recordsByProgram as $program => $count)
                        <div class="dash3-chart-bar-row">
                            <span class="dash3-chart-label">{{ $program }}</span>
                            <div class="dash3-chart-bar-track">
                                <div class="dash3-chart-bar-fill" style="width: {{ $chartMax > 0 ? round($count / $chartMax * 100) : 0 }}%"></div>
                            </div>
                            <span class="dash3-chart-value">{{ number_format($count) }}</span>
                        </div>
                        @endforeach
                        @if($recordsByProgram->isEmpty())
                            <div class="dash3-empty">No data</div>
                        @endif
                    </div>
                </div>

                <div class="admin-card dash3-chart-card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">By Municipality</h3>
                            <p class="card-subtitle">Top municipalities</p>
                        </div>
                    </div>
                    <div class="card-body dash3-chart-body">
                        @foreach($recordsByMunicipality as $municipality => $count)
                        <div class="dash3-chart-bar-row">
                            <span class="dash3-chart-label">{{ $municipality }}</span>
                            <div class="dash3-chart-bar-track">
                                <div class="dash3-chart-bar-fill dash3-chart-bar-fill--muni" style="width: {{ $chartMax > 0 ? round($count / $chartMax * 100) : 0 }}%"></div>
                            </div>
                            <span class="dash3-chart-value">{{ number_format($count) }}</span>
                        </div>
                        @endforeach
                        @if($recordsByMunicipality->isEmpty())
                            <div class="dash3-empty">No data</div>
                        @endif
                    </div>
                </div>

                <div class="admin-card dash3-chart-card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">By Source</h3>
                            <p class="card-subtitle">OD vs Email vs Facebook</p>
                        </div>
                    </div>
                    <div class="card-body dash3-chart-body dash3-donut-body">
                        @if($sourceTotal > 0)
                        <div class="dash3-donut" style="background: conic-gradient({{ $sourceConic }});">
                            <div class="dash3-donut-hole">
                                <span class="dash3-donut-total">{{ number_format($sourceTotal) }}</span>
                                <span class="dash3-donut-label">total</span>
                            </div>
                        </div>
                        <div class="dash3-donut-legend">
                            @foreach(['OD', 'Email', 'Facebook'] as $src)
                            @if($recordsBySource->has($src))
                            <div class="dash3-donut-legend-item">
                                <span class="dash3-donut-dot" style="background:{{ $sourceColors[$src] }}"></span>
                                <span class="dash3-donut-legend-label">{{ $src }}</span>
                                <span class="dash3-donut-legend-value">{{ number_format($recordsBySource->get($src)) }}</span>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @else
                            <div class="dash3-empty">No data</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="dash3-grid">
                @foreach($provinceTables as $province => $rows)
                    @php
                        $provinceTotal = array_sum(array_map(function ($r) { return (int) ($r['count'] ?? 0); }, $rows));
                    @endphp
                    <div class="admin-card">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">{{ $province }}</h3>
                                <p class="card-subtitle">Municipality • Number of NLs</p>
                                <div class="dash3-province-total">Total NLs: <span>{{ number_format($provinceTotal) }}</span></div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="dash3-table-wrap">
                                <table class="dash3-table">
                                    <thead>
                                        <tr>
                                            <th>Municipality</th>
                                            <th class="dash3-num">Number of NLs</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($rows as $row)
                                            <tr>
                                                <td>
                                                    <button type="button" class="dash3-muni-btn" data-province="{{ $province }}" data-municipality="{{ $row['municipality'] }}">
                                                        {{ $row['municipality'] }}
                                                    </button>
                                                </td>
                                                <td class="dash3-num">{{ number_format($row['count']) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="dash3-empty">No data for {{ $province }} with the selected filters.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            </div>

            <dialog id="dash3BarangayDialog" class="dash3-dialog">
                <div class="dash3-dialog-card">
                    <div class="dash3-dialog-header">
                        <div class="dash3-dialog-title" id="dash3DialogTitle">Barangays</div>
                        <button type="button" class="btn btn-muted btn-sm" id="dash3DialogClose">Close</button>
                    </div>
                    <div class="dash3-dialog-meta" id="dash3DialogMeta"></div>
                    <div class="dash3-dialog-body">
                        <div class="dash3-table-wrap">
                            <table class="dash3-table">
                                <thead>
                                    <tr>
                                        <th>Barangay</th>
                                        <th class="dash3-num">Number of NLs</th>
                                    </tr>
                                </thead>
                                <tbody id="dash3BarangayTbody"></tbody>
                            </table>
                        </div>
                        <div id="dash3BarangayEmpty" class="dash3-empty" style="display: none;">No barangay data found.</div>
                    </div>
                </div>
            </dialog>

            <script>
                (function () {
                    var barangayData = @json($dashBarangayBreakdown ?? []);
                    var dialog = document.getElementById('dash3BarangayDialog');
                    var titleEl = document.getElementById('dash3DialogTitle');
                    var metaEl = document.getElementById('dash3DialogMeta');
                    var tbodyEl = document.getElementById('dash3BarangayTbody');
                    var emptyEl = document.getElementById('dash3BarangayEmpty');
                    var closeBtn = document.getElementById('dash3DialogClose');

                    if (!dialog || !titleEl || !metaEl || !tbodyEl || !emptyEl || !closeBtn) {
                        return;
                    }

                    function renderBarangays(rows) {
                        tbodyEl.innerHTML = '';
                        if (!rows || rows.length === 0) {
                            emptyEl.style.display = 'block';
                            return;
                        }
                        emptyEl.style.display = 'none';
                        rows.forEach(function (row) {
                            var tr = document.createElement('tr');
                            var td1 = document.createElement('td');
                            var td2 = document.createElement('td');
                            td1.textContent = row.barangay;
                            td2.textContent = row.count;
                            td2.className = 'dash3-num';
                            tr.appendChild(td1);
                            tr.appendChild(td2);
                            tbodyEl.appendChild(tr);
                        });
                    }

                    function openDialog(province, municipality) {
                        var rows = [];
                        if (barangayData[province] && barangayData[province][municipality]) {
                            rows = barangayData[province][municipality];
                        }
                        titleEl.textContent = municipality + ' — ' + province;
                        metaEl.textContent = @json($dash3MetaText);
                        renderBarangays(rows);
                        if (typeof dialog.showModal === 'function') {
                            dialog.showModal();
                        } else {
                            dialog.setAttribute('open', 'open');
                        }
                    }

                    document.querySelectorAll('.dash3-muni-btn').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            openDialog(btn.dataset.province, btn.dataset.municipality);
                        });
                    });

                    closeBtn.addEventListener('click', function () {
                        dialog.close();
                    });

                    dialog.addEventListener('click', function (e) {
                        if (e.target === dialog) {
                            dialog.close();
                        }
                    });
                })();
            </script>
        </div>

    </div> <!-- END Dashboard Section -->

    <!-- NL Records Section -->
    <div id="nl-records-section" style="display: none;">

    <!-- Transmittal Management -->
    <div class="no-print" style="margin-bottom: 12px; padding: 16px 20px; border-radius: 12px; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);">
        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; margin: 0;">
            <div style="position: relative; width: 48px; height: 24px;">
                <input type="checkbox" id="unassigned-toggle" {{ request('unassigned_only') ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;">
                <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: 0.3s; border-radius: 24px;" id="unassigned-toggle-bg"></span>
                <span style="position: absolute; cursor: pointer; content: ''; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: 0.3s; border-radius: 50%; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" id="unassigned-toggle-dot"></span>
            </div>
            <span style="font-size: 14px; font-weight: 500; color: #1e293b;">Show only records without admin transmittal numbers</span>
        </label>
    </div>

    <script>
        (function () {
            var toggle = document.getElementById('unassigned-toggle');
            var bg = document.getElementById('unassigned-toggle-bg');
            var dot = document.getElementById('unassigned-toggle-dot');
            
            if (toggle && bg && dot) {
                function updateToggle() {
                    if (toggle.checked) {
                        bg.style.backgroundColor = '#006c35';
                        dot.style.transform = 'translateX(24px)';
                    } else {
                        bg.style.backgroundColor = '#cbd5e1';
                        dot.style.transform = 'translateX(0)';
                    }
                }
                
                updateToggle();
                toggle.addEventListener('change', updateToggle);
            }
        })();
    </script>

    <!-- TABLE FILTERS -->
    <div class="no-print table-filters" style="margin-bottom: 16px; padding: 20px; border-radius: 12px; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #006c35 0%, #008a43 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
            </div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #1e293b;">Table Filters</h3>
        </div>

        @php
            $activeFilters = [];
            if(request('farmerName')) $activeFilters['Farmer'] = request('farmerName');
            if(request('encoderName')) $activeFilters['Encoder'] = request('encoderName');
            if(request('program')) $activeFilters['Program'] = request('program');
            if(request('line')) $activeFilters['Line'] = request('line');
            if(request('province')) $activeFilters['Province'] = request('province');
            if(request('municipality')) $activeFilters['Municipality'] = request('municipality');
            if(request('barangay')) $activeFilters['Barangay'] = request('barangay');
            if(request('source')) $activeFilters['Source'] = request('source');
            if(request('modeOfPayment')) $activeFilters['Mode of Payment'] = request('modeOfPayment');
            if(request('accounts')) $activeFilters['Account'] = request('accounts');
            if(request('admin_transmittal_number')) $activeFilters['Admin Transmittal'] = request('admin_transmittal_number');
            if(request('created_at')) $activeFilters['Date Created'] = request('created_at');
            if(request('date_received_type') == 'single' && request('date_single')) $activeFilters['Date Received'] = request('date_single');
            if(request('date_received_type') == 'range' && (request('date_from') || request('date_to'))) {
                $dateRange = '';
                if(request('date_from')) $dateRange .= 'From: ' . request('date_from') . ' ';
                if(request('date_to')) $dateRange .= 'To: ' . request('date_to');
                $activeFilters['Date Received'] = trim($dateRange);
            }
        @endphp

        @if(count($activeFilters) > 0)
        <div style="margin-bottom: 16px; padding: 12px 16px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 8px; color: #166534; font-weight: 600; font-size: 13px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Active Filters:</span>
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                @foreach($activeFilters as $label => $value)
                <span style="padding: 4px 10px; background: #dcfce7; color: #166534; border-radius: 6px; font-size: 12px; font-weight: 500;">
                    <strong>{{ $label }}:</strong> {{ $value }}
                </span>
                @endforeach
            </div>
        </div>
        @endif
        <form method="GET" action="{{ route('admin') }}" style="margin: 0;">
            <input type="hidden" name="tab" value="nl-records">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px; align-items: start;">
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Search Farmer</label>
                    <input type="text" name="farmerName" value="{{ request('farmerName') }}" style="padding: 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);" placeholder="Enter farmer name">
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Search Encoder</label>
                    <input type="text" name="encoderName" value="{{ request('encoderName') }}" style="padding: 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);" placeholder="Enter encoder name">
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px; position: relative;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Program</label>
                    <div style="position: relative;">
                        <select name="program" style="padding: 10px 36px 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); appearance: none; cursor: pointer; width: 100%;">
                            <option value="">All Programs</option>
                            @foreach($allPrograms as $program)
                            <option value="{{ $program }}" {{ request('program') == $program ? 'selected' : '' }}>{{ $program }}</option>
                            @endforeach
                        </select>
                        <svg style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; pointer-events: none; color: #64748b;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px; position: relative;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Line</label>
                    <div style="position: relative;">
                        <select name="line" style="padding: 10px 36px 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); appearance: none; cursor: pointer; width: 100%;">
                            <option value="">All Lines</option>
                            @foreach($allLines as $line)
                            <option value="{{ $line }}" {{ request('line') == $line ? 'selected' : '' }}>{{ $line }}</option>
                            @endforeach
                        </select>
                        <svg style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; pointer-events: none; color: #64748b;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px; position: relative;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Province</label>
                    <div style="position: relative;">
                        <select name="province" id="tableProvince" style="padding: 10px 36px 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); appearance: none; cursor: pointer; width: 100%;">
                            <option value="">All Provinces</option>
                            <option value="Aurora" {{ request('province') == 'Aurora' ? 'selected' : '' }}>Aurora</option>
                            <option value="Nueva Ecija" {{ request('province') == 'Nueva Ecija' ? 'selected' : '' }}>Nueva Ecija</option>
                        </select>
                        <svg style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; pointer-events: none; color: #64748b;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px; position: relative;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Municipality</label>
                    <div style="position: relative;">
                        <select name="municipality" id="tableMunicipality" style="padding: 10px 36px 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); appearance: none; cursor: pointer; width: 100%;">
                            <option value="">All Municipalities</option>
                        </select>
                        <svg style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; pointer-events: none; color: #64748b;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px; position: relative;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Barangay</label>
                    <div style="position: relative;">
                        <select name="barangay" id="tableBarangay" style="padding: 10px 36px 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); appearance: none; cursor: pointer; width: 100%;">
                            <option value="">All Barangays</option>
                        </select>
                        <svg style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; pointer-events: none; color: #64748b;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px; position: relative;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Source</label>
                    <div style="position: relative;">
                        <select name="source" style="padding: 10px 36px 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); appearance: none; cursor: pointer; width: 100%;">
                            <option value="">All Sources</option>
                            @foreach($allSources as $source)
                            <option value="{{ $source }}" {{ request('source') == $source ? 'selected' : '' }}>{{ $source }}</option>
                            @endforeach
                        </select>
                        <svg style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; pointer-events: none; color: #64748b;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px; position: relative;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Mode of Payment</label>
                    <div style="position: relative;">
                        <select name="modeOfPayment" style="padding: 10px 36px 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); appearance: none; cursor: pointer; width: 100%;">
                            <option value="">All Modes</option>
                            @foreach($allModes as $mode)
                            <option value="{{ $mode }}" {{ request('modeOfPayment') == $mode ? 'selected' : '' }}>{{ $mode }}</option>
                            @endforeach
                        </select>
                        <svg style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; pointer-events: none; color: #64748b;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Account</label>
                    <input type="text" name="accounts" value="{{ request('accounts') }}" style="padding: 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);" placeholder="Email / username">
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Admin Transmittal</label>
                    <input type="text" name="admin_transmittal_number" value="{{ request('admin_transmittal_number') }}" style="padding: 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);" placeholder="Enter transmittal #">
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Date Encoded</label>
                    <input type="date" name="created_at" value="{{ request('created_at') }}" style="padding: 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);">
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Date Received</label>
                    <select name="date_received_type" id="tableDateReceivedType" style="padding: 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);">
                        <option value="">All Dates</option>
                        <option value="single" {{ request('date_received_type') == 'single' ? 'selected' : '' }}>Specific Date</option>
                        <option value="range" {{ request('date_received_type') == 'range' ? 'selected' : '' }}>Date Range</option>
                    </select>
                </div>
                <div id="tableDateReceivedSingleWrap" style="display: {{ request('date_received_type') == 'single' ? 'flex' : 'none' }}; flex-direction: column; gap: 6px;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Date</label>
                    <input type="date" name="date_single" value="{{ request('date_single') }}" style="padding: 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);" {{ request('date_received_type') == 'single' ? '' : 'disabled' }}>
                </div>
                <div id="tableDateReceivedFromWrap" style="display: {{ request('date_received_type') == 'range' ? 'flex' : 'none' }}; flex-direction: column; gap: 6px;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" style="padding: 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);" {{ request('date_received_type') == 'range' ? '' : 'disabled' }}>
                </div>
                <div id="tableDateReceivedToWrap" style="display: {{ request('date_received_type') == 'range' ? 'flex' : 'none' }}; flex-direction: column; gap: 6px;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" style="padding: 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);" {{ request('date_received_type') == 'range' ? '' : 'disabled' }}>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Rows per page</label>
                    <select name="per_page" style="padding: 10px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);">
                        <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', '50') == '50' ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #006c35 0%, #008a43 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px; box-shadow: 0 2px 4px rgba(0, 108, 53, 0.2); transition: all 0.2s;">Apply Filters</button>
                <a href="{{ route('admin') }}" style="padding: 12px 24px; background: #f1f5f9; color: #64748b; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; transition: all 0.2s; border: 1px solid #e2e8f0;">Clear All</a>
            </div>
        </form>

        <script>
            (function () {
                var typeEl = document.getElementById('tableDateReceivedType');
                var singleWrap = document.getElementById('tableDateReceivedSingleWrap');
                var fromWrap = document.getElementById('tableDateReceivedFromWrap');
                var toWrap = document.getElementById('tableDateReceivedToWrap');
                if (!typeEl || !singleWrap || !fromWrap || !toWrap) {
                    return;
                }

                function setEnabled(wrap, enabled) {
                    var input = wrap.querySelector('input');
                    if (input) {
                        input.disabled = !enabled;
                    }
                }

                function toggle() {
                    var v = typeEl.value;
                    singleWrap.style.display = (v === 'single') ? 'flex' : 'none';
                    fromWrap.style.display = (v === 'range') ? 'flex' : 'none';
                    toWrap.style.display = (v === 'range') ? 'flex' : 'none';
                    setEnabled(singleWrap, v === 'single');
                    setEnabled(fromWrap, v === 'range');
                    setEnabled(toWrap, v === 'range');
                }

                typeEl.addEventListener('change', toggle);
                toggle();
            })();

            // Location data for cascading dropdowns
            var locationCsv = `BARANGAY,MUNICIPALITY,PROVINCE
Betes,Aliaga,Nueva Ecija
Bibiclat,Aliaga,Nueva Ecija
Bucot,Aliaga,Nueva Ecija
La Purisima,Aliaga,Nueva Ecija
Magsaysay,Aliaga,Nueva Ecija
Macabucod,Aliaga,Nueva Ecija
Pantoc,Aliaga,Nueva Ecija
Poblacion Centro,Aliaga,Nueva Ecija
Poblacion East I,Aliaga,Nueva Ecija
Poblacion East II,Aliaga,Nueva Ecija
Poblacion West III,Aliaga,Nueva Ecija
Poblacion West IV,Aliaga,Nueva Ecija
San Carlos,Aliaga,Nueva Ecija
San Emiliano,Aliaga,Nueva Ecija
San Eustacio,Aliaga,Nueva Ecija
San Felipe Bata,Aliaga,Nueva Ecija
San Felipe Matanda,Aliaga,Nueva Ecija
San Juan,Aliaga,Nueva Ecija
San Pablo Bata,Aliaga,Nueva Ecija
San Pablo Matanda,Aliaga,Nueva Ecija
Santa Monica,Aliaga,Nueva Ecija
Santiago,Aliaga,Nueva Ecija
Santo Rosario,Aliaga,Nueva Ecija
Santo Tomas,Aliaga,Nueva Ecija
Sunson,Aliaga,Nueva Ecija
Umangan,Aliaga,Nueva Ecija
Antipolo,Bongabon,Nueva Ecija
Ariendo,Bongabon,Nueva Ecija
Bantug,Bongabon,Nueva Ecija
Calaanan,Bongabon,Nueva Ecija
Commercial,Bongabon,Nueva Ecija
Cruz,Bongabon,Nueva Ecija
Digmala,Bongabon,Nueva Ecija
Curva,Bongabon,Nueva Ecija
Kaingin,Bongabon,Nueva Ecija
Labi,Bongabon,Nueva Ecija
Larcon,Bongabon,Nueva Ecija
Lusok,Bongabon,Nueva Ecija
Macabaclay,Bongabon,Nueva Ecija
Magtanggol,Bongabon,Nueva Ecija
Mantile,Bongabon,Nueva Ecija
Olivete,Bongabon,Nueva Ecija
Palo Maria,Bongabon,Nueva Ecija
Pesa,Bongabon,Nueva Ecija
Rizal,Bongabon,Nueva Ecija
Sampalucan,Bongabon,Nueva Ecija
San Roque,Bongabon,Nueva Ecija
Santor,Bongabon,Nueva Ecija
Sinipit,Bongabon,Nueva Ecija
Sisilang na Ligaya,Bongabon,Nueva Ecija
Social,Bongabon,Nueva Ecija
Tugatug,Bongabon,Nueva Ecija
Tulay na Bato,Bongabon,Nueva Ecija
Vega,Bongabon,Nueva Ecija
Aduas Centro,City of Cabanatuan,Nueva Ecija
Bagong Sikat,City of Cabanatuan,Nueva Ecija
Bagong Buhay,City of Cabanatuan,Nueva Ecija
Bakero,City of Cabanatuan,Nueva Ecija
Bakod Bayan,City of Cabanatuan,Nueva Ecija
Balite,City of Cabanatuan,Nueva Ecija
Bangad,City of Cabanatuan,Nueva Ecija
Bantug Bulalo,City of Cabanatuan,Nueva Ecija
Bantug Norte,City of Cabanatuan,Nueva Ecija
Barlis,City of Cabanatuan,Nueva Ecija
Barrera District,City of Cabanatuan,Nueva Ecija
Bernardo District,City of Cabanatuan,Nueva Ecija
Bitas,City of Cabanatuan,Nueva Ecija
Bonifacio District,City of Cabanatuan,Nueva Ecija
Buliran,City of Cabanatuan,Nueva Ecija
Caalibangbangan,City of Cabanatuan,Nueva Ecija
Cabu,City of Cabanatuan,Nueva Ecija
Campo Tinio,City of Cabanatuan,Nueva Ecija
Kapitan Pepe,City of Cabanatuan,Nueva Ecija
Cinco-Cinco,City of Cabanatuan,Nueva Ecija
City Supermarket,City of Cabanatuan,Nueva Ecija
Caudillo,City of Cabanatuan,Nueva Ecija
Communal,City of Cabanatuan,Nueva Ecija
Cruz Roja,City of Cabanatuan,Nueva Ecija
Daang Sarile,City of Cabanatuan,Nueva Ecija
Dalampang,City of Cabanatuan,Nueva Ecija
Dicarma,City of Cabanatuan,Nueva Ecija
Dimasalang,City of Cabanatuan,Nueva Ecija
Dionisio S. Garcia,City of Cabanatuan,Nueva Ecija
Fatima,City of Cabanatuan,Nueva Ecija
General Luna,City of Cabanatuan,Nueva Ecija
Ibabao Bana,City of Cabanatuan,Nueva Ecija
Imelda District,City of Cabanatuan,Nueva Ecija
Isla,City of Cabanatuan,Nueva Ecija
Calawagan,City of Cabanatuan,Nueva Ecija
Kalikid Norte,City of Cabanatuan,Nueva Ecija
Kalikid Sur,City of Cabanatuan,Nueva Ecija
Lagare,City of Cabanatuan,Nueva Ecija
M. S. Garcia,City of Cabanatuan,Nueva Ecija
Mabini Extension,City of Cabanatuan,Nueva Ecija
Mabini Homesite,City of Cabanatuan,Nueva Ecija
Macatbong,City of Cabanatuan,Nueva Ecija
Magsaysay District,City of Cabanatuan,Nueva Ecija
Matadero,City of Cabanatuan,Nueva Ecija
Lourdes,City of Cabanatuan,Nueva Ecija
Lawang Kawayan,City of Cabanatuan,Nueva Ecija
Lomboy,City of Cabanatuan,Nueva Ecija
Mabini Extension,City of Cabanatuan,Nueva Ecija
Mabini Homesite,City of Cabanatuan,Nueva Ecija
Mabini Ward,City of Cabanatuan,Nueva Ecija
Mag-Asawa,City of Cabanatuan,Nueva Ecija
Malagena,City of Cabanatuan,Nueva Ecija
Malaria,City of Cabanatuan,Nueva Ecija
Malingting,City of Cabanatuan,Nueva Ecija
Mangga,City of Cabanatuan,Nueva Ecija
Managap,City of Cabanatuan,Nueva Ecija
Manaoag,City of Cabanatuan,Nueva Ecija
Marcos District,City of Cabanatuan,Nueva Ecija
Melting Pot,City of Cabanatuan,Nueva Ecija
Minano,City of Cabanatuan,Nueva Ecija
Narvaez,City of Cabanatuan,Nueva Ecija
Ocampo,City of Cabanatuan,Nueva Ecija
Padre Burgos,City of Cabanatuan,Nueva Ecija
Paltok,City of Cabanatuan,Nueva Ecija
Pangatulan,City of Cabanatuan,Nueva Ecija
Pantay Bata,City of Cabanatuan,Nueva Ecija
Pantay Matayog,City of Cabanatuan,Nueva Ecija
Pob. Central,City of Cabanatuan,Nueva Ecija
Polilio,City of Cabanatuan,Nueva Ecija
Primera,City of Cabanatuan,Nueva Ecija
Quezon District,City of Cabanatuan,Nueva Ecija
Rizal District,City of Cabanatuan,Nueva Ecija
Rizal Extension,City of Cabanatuan,Nueva Ecija
Sanglayang,City of Cabanatuan,Nueva Ecija
San Isidro,City of Cabanatuan,Nueva Ecija
San Jose,City of Cabanatuan,Nueva Ecija
San Juan,City of Cabanatuan,Nueva Ecija
San Roque,City of Cabanatuan,Nueva Ecija
Sampaguita,City of Cabanatuan,Nueva Ecija
Sindulan,City of Cabanatuan,Nueva Ecija
Sumacab,City of Cabanatuan,Nueva Ecija
Valdez,City of Cabanatuan,Nueva Ecija
Villa Piedad,City of Cabanatuan,Nueva Ecija
Villa Verano,City of Cabanatuan,Nueva Ecija
Zabarte,City of Cabanatuan,Nueva Ecija
Mayapyap Norte,City of Cabanatuan,Nueva Ecija
Mayapyap Sur,City of Cabanatuan,Nueva Ecija
Melojavilla,City of Cabanatuan,Nueva Ecija
Obrero,City of Cabanatuan,Nueva Ecija
Padre Crisostomo,City of Cabanatuan,Nueva Ecija
Pagas,City of Cabanatuan,Nueva Ecija
Palagay,City of Cabanatuan,Nueva Ecija
Pamaldan,City of Cabanatuan,Nueva Ecija
Pangatian,City of Cabanatuan,Nueva Ecija
Patalac,City of Cabanatuan,Nueva Ecija
Pula,City of Cabanatuan,Nueva Ecija
Rizdelis,City of Cabanatuan,Nueva Ecija
Samon,City of Cabanatuan,Nueva Ecija
San Isidro,City of Cabanatuan,Nueva Ecija
San Josef Norte,City of Cabanatuan,Nueva Ecija
San Josef Sur,City of Cabanatuan,Nueva Ecija
San Juan Pob.,City of Cabanatuan,Nueva Ecija
San Roque Norte,City of Cabanatuan,Nueva Ecija
San Roque Sur,City of Cabanatuan,Nueva Ecija
Sanbermicristi,City of Cabanatuan,Nueva Ecija
Sangitan,City of Cabanatuan,Nueva Ecija
Santa Arcadia,City of Cabanatuan,Nueva Ecija
Sumacab Norte,City of Cabanatuan,Nueva Ecija
Valdefuente,City of Cabanatuan,Nueva Ecija
Valle Cruz,City of Cabanatuan,Nueva Ecija
Vijandre District,City of Cabanatuan,Nueva Ecija
Villa Ofelia-Caridad,City of Cabanatuan,Nueva Ecija
Zulueta District,City of Cabanatuan,Nueva Ecija
Nabao,City of Cabanatuan,Nueva Ecija
Padre Burgos,City of Cabanatuan,Nueva Ecija
Talipapa,City of Cabanatuan,Nueva Ecija
Aduas Norte,City of Cabanatuan,Nueva Ecija
Aduas Sur,City of Cabanatuan,Nueva Ecija
Sapang,City of Cabanatuan,Nueva Ecija
Sumacab Este,City of Cabanatuan,Nueva Ecija
Sumacab South,City of Cabanatuan,Nueva Ecija
Caridad,City of Cabanatuan,Nueva Ecija
Magsaysay South,City of Cabanatuan,Nueva Ecija
Maria Theresa,City of Cabanatuan,Nueva Ecija
Sangitan East,City of Cabanatuan,Nueva Ecija
Santo Niño,City of Cabanatuan,Nueva Ecija
Bagong Buhay,Cabiao,Nueva Ecija
Bagong Sikat,Cabiao,Nueva Ecija
Bagong Silang,Cabiao,Nueva Ecija
Concepcion,Cabiao,Nueva Ecija
Entablado,Cabiao,Nueva Ecija
Maligaya,Cabiao,Nueva Ecija
Natividad North,Cabiao,Nueva Ecija
Natividad South,Cabiao,Nueva Ecija
Palasinan,Cabiao,Nueva Ecija
San Antonio,Cabiao,Nueva Ecija
San Fernando Norte,Cabiao,Nueva Ecija
San Fernando Sur,Cabiao,Nueva Ecija
San Gregorio,Cabiao,Nueva Ecija
San Juan North,Cabiao,Nueva Ecija
San Juan South,Cabiao,Nueva Ecija
San Roque,Cabiao,Nueva Ecija
San Vicente,Cabiao,Nueva Ecija
Santa Rita,Cabiao,Nueva Ecija
Sinipit,Cabiao,Nueva Ecija
Polilio,Cabiao,Nueva Ecija
San Carlos,Cabiao,Nueva Ecija
Santa Isabel,Cabiao,Nueva Ecija
Santa Ines,Cabiao,Nueva Ecija
R.A.Padilla,Carranglan,Nueva Ecija
Bantug,Carranglan,Nueva Ecija
Bunga,Carranglan,Nueva Ecija
Burgos,Carranglan,Nueva Ecija
Capintalan,Carranglan,Nueva Ecija
Joson,Carranglan,Nueva Ecija
General Luna,Carranglan,Nueva Ecija
Minuli,Carranglan,Nueva Ecija
Piut,Carranglan,Nueva Ecija
Puncan,Carranglan,Nueva Ecija
Putlan,Carranglan,Nueva Ecija
Salazar,Carranglan,Nueva Ecija
San Agustin,Carranglan,Nueva Ecija
T. L. Padilla Pob.,Carranglan,Nueva Ecija
F. C. Otic Pob.,Carranglan,Nueva Ecija
D. L. Maglanoc Pob.,Carranglan,Nueva Ecija
G. S. Rosario Pob.,Carranglan,Nueva Ecija
Baloy,Cuyapo,Nueva Ecija
Bambanaba,Cuyapo,Nueva Ecija
Bantug,Cuyapo,Nueva Ecija
Bentigan,Cuyapo,Nueva Ecija
Bibiclat,Cuyapo,Nueva Ecija
Bonifacio,Cuyapo,Nueva Ecija
Bued,Cuyapo,Nueva Ecija
Bulala,Cuyapo,Nueva Ecija
Burgos,Cuyapo,Nueva Ecija
Cabileo,Cuyapo,Nueva Ecija
Cabatuan,Cuyapo,Nueva Ecija
Cacapasan,Cuyapo,Nueva Ecija
Calancuasan Norte,Cuyapo,Nueva Ecija
Calancuasan Sur,Cuyapo,Nueva Ecija
Colosboa,Cuyapo,Nueva Ecija
Columbitin,Cuyapo,Nueva Ecija
Curva,Cuyapo,Nueva Ecija
District I,Cuyapo,Nueva Ecija
District II,Cuyapo,Nueva Ecija
District IV,Cuyapo,Nueva Ecija
District V,Cuyapo,Nueva Ecija
District VI,Cuyapo,Nueva Ecija
District VII,Cuyapo,Nueva Ecija
District VIII,Cuyapo,Nueva Ecija
Landig,Cuyapo,Nueva Ecija
Latap,Cuyapo,Nueva Ecija
Loob,Cuyapo,Nueva Ecija
Luna,Cuyapo,Nueva Ecija
Malbeg-Patalan,Cuyapo,Nueva Ecija
Malineng,Cuyapo,Nueva Ecija
Matindeg,Cuyapo,Nueva Ecija
Maycaban,Cuyapo,Nueva Ecija
Nagcuralan,Cuyapo,Nueva Ecija
Nagmisahan,Cuyapo,Nueva Ecija
Paitan Norte,Cuyapo,Nueva Ecija
Paitan Sur,Cuyapo,Nueva Ecija
Piglisan,Cuyapo,Nueva Ecija
Pugo,Cuyapo,Nueva Ecija
Rizal,Cuyapo,Nueva Ecija
Sabit,Cuyapo,Nueva Ecija
Salagusog,Cuyapo,Nueva Ecija
San Antonio,Cuyapo,Nueva Ecija
San Jose,Cuyapo,Nueva Ecija
San Juan,Cuyapo,Nueva Ecija
Santa Clara,Cuyapo,Nueva Ecija
Santa Cruz,Cuyapo,Nueva Ecija
Simimbaan,Cuyapo,Nueva Ecija
Tagtagumbao,Cuyapo,Nueva Ecija
Tutuloy,Cuyapo,Nueva Ecija
Ungab,Cuyapo,Nueva Ecija
Villaflores,Cuyapo,Nueva Ecija
Bagong Sikat,Gabaldon,Nueva Ecija
Bagting,Gabaldon,Nueva Ecija
Bantug,Gabaldon,Nueva Ecija
Bitulok,Gabaldon,Nueva Ecija
Bugnan,Gabaldon,Nueva Ecija
Calabasa,Gabaldon,Nueva Ecija
Camachile,Gabaldon,Nueva Ecija
Cuyapa,Gabaldon,Nueva Ecija
Ligaya,Gabaldon,Nueva Ecija
Macasandal,Gabaldon,Nueva Ecija
Malinao,Gabaldon,Nueva Ecija
Pantoc,Gabaldon,Nueva Ecija
Pinamalisan,Gabaldon,Nueva Ecija
South Poblacion,Gabaldon,Nueva Ecija
Sawmill,Gabaldon,Nueva Ecija
Tagumpay,Gabaldon,Nueva Ecija
Bayanihan,City of Gapan,Nueva Ecija
Bulak,City of Gapan,Nueva Ecija
Kapalangan,City of Gapan,Nueva Ecija
Mahipon,City of Gapan,Nueva Ecija
Malimba,City of Gapan,Nueva Ecija
Mangino,City of Gapan,Nueva Ecija
Marelo,City of Gapan,Nueva Ecija
Pambuan,City of Gapan,Nueva Ecija
Parcutela,City of Gapan,Nueva Ecija
San Lorenzo,City of Gapan,Nueva Ecija
San Nicolas,City of Gapan,Nueva Ecija
San Roque,City of Gapan,Nueva Ecija
San Vicente,City of Gapan,Nueva Ecija
Santa Cruz,City of Gapan,Nueva Ecija
Santo Cristo Norte,City of Gapan,Nueva Ecija
Santo Cristo Sur,City of Gapan,Nueva Ecija
Santo Niño,City of Gapan,Nueva Ecija
Makabaclay,City of Gapan,Nueva Ecija
Balante,City of Gapan,Nueva Ecija
Bungo,City of Gapan,Nueva Ecija
Mabunga,City of Gapan,Nueva Ecija
Maburak,City of Gapan,Nueva Ecija
Puting Tubig,City of Gapan,Nueva Ecija
Balangkare Norte,General Mamerto Natividad,Nueva Ecija
Balangkare Sur,General Mamerto Natividad,Nueva Ecija
Balaring,General Mamerto Natividad,Nueva Ecija
Belen,General Mamerto Natividad,Nueva Ecija
Bravo,General Mamerto Natividad,Nueva Ecija
Burol,General Mamerto Natividad,Nueva Ecija
Kabulihan,General Mamerto Natividad,Nueva Ecija
Mag-asawang Sampaloc,General Mamerto Natividad,Nueva Ecija
Manarog,General Mamerto Natividad,Nueva Ecija
Mataas na Kahoy,General Mamerto Natividad,Nueva Ecija
Panacsac,General Mamerto Natividad,Nueva Ecija
Picaleon,General Mamerto Natividad,Nueva Ecija
Pinahan,General Mamerto Natividad,Nueva Ecija
Platero,General Mamerto Natividad,Nueva Ecija
Poblacion,General Mamerto Natividad,Nueva Ecija
Pula,General Mamerto Natividad,Nueva Ecija
Pulong Singkamas,General Mamerto Natividad,Nueva Ecija
Sapang Bato,General Mamerto Natividad,Nueva Ecija
Talabutab Norte,General Mamerto Natividad,Nueva Ecija
Talabutab Sur,General Mamerto Natividad,Nueva Ecija
Bago,General Tinio,Nueva Ecija
Concepcion,General Tinio,Nueva Ecija
Nazareth,General Tinio,Nueva Ecija
Padolina,General Tinio,Nueva Ecija
Pias,General Tinio,Nueva Ecija
San Pedro,General Tinio,Nueva Ecija
Poblacion East,General Tinio,Nueva Ecija
Poblacion West,General Tinio,Nueva Ecija
Rio Chico,General Tinio,Nueva Ecija
Poblacion Central,General Tinio,Nueva Ecija
Pulong Matong,General Tinio,Nueva Ecija
Sampaguita,General Tinio,Nueva Ecija
Palale,General Tinio,Nueva Ecija
Agcano,Guimba,Nueva Ecija
Ayos Lomboy,Guimba,Nueva Ecija
Bacayao,Guimba,Nueva Ecija
Bagong Barrio,Guimba,Nueva Ecija
Balbalino,Guimba,Nueva Ecija
Balingog East,Guimba,Nueva Ecija
Balingog West,Guimba,Nueva Ecija
Banitan,Guimba,Nueva Ecija
Bantug,Guimba,Nueva Ecija
Bulakid,Guimba,Nueva Ecija
Caballero,Guimba,Nueva Ecija
Cabaruan,Guimba,Nueva Ecija
Caingin Tabing Ilog,Guimba,Nueva Ecija
Calem,Guimba,Nueva Ecija
Camiing,Guimba,Nueva Ecija
Cardinal,Guimba,Nueva Ecija
Casongsong,Guimba,Nueva Ecija
Catimon,Guimba,Nueva Ecija
Cavite,Guimba,Nueva Ecija
Cawayan Bugtong,Guimba,Nueva Ecija
Consuelo,Guimba,Nueva Ecija
Culong,Guimba,Nueva Ecija
Escano,Guimba,Nueva Ecija
Faigal,Guimba,Nueva Ecija
Galvan,Guimba,Nueva Ecija
Guiset,Guimba,Nueva Ecija
Lamorito,Guimba,Nueva Ecija
Lennec,Guimba,Nueva Ecija
Macamias,Guimba,Nueva Ecija
Macapabellag,Guimba,Nueva Ecija
Macatcatuit,Guimba,Nueva Ecija
Manacsac,Guimba,Nueva Ecija
Manggang Marikit,Guimba,Nueva Ecija
Maturanoc,Guimba,Nueva Ecija
Maybubon,Guimba,Nueva Ecija
Naglabrahan,Guimba,Nueva Ecija
Nagpandayan,Guimba,Nueva Ecija
Narvacan I,Guimba,Nueva Ecija
Narvacan II,Guimba,Nueva Ecija
Pacac,Guimba,Nueva Ecija
Partida I,Guimba,Nueva Ecija
Partida II,Guimba,Nueva Ecija
Pasong Inchic,Guimba,Nueva Ecija
Saint John District,Guimba,Nueva Ecija
San Agustin,Guimba,Nueva Ecija
San Andres,Guimba,Nueva Ecija
San Bernardino,Guimba,Nueva Ecija
San Marcelino,Guimba,Nueva Ecija
San Miguel,Guimba,Nueva Ecija
San Rafael,Guimba,Nueva Ecija
San Roque,Guimba,Nueva Ecija
Santa Ana,Guimba,Nueva Ecija
Santa Cruz,Guimba,Nueva Ecija
Santa Lucia,Guimba,Nueva Ecija
Santa Veronica District,Guimba,Nueva Ecija
Santo Cristo District,Guimba,Nueva Ecija
Saranay District,Guimba,Nueva Ecija
Sinulatan,Guimba,Nueva Ecija
Subol,Guimba,Nueva Ecija
Tampac I,Guimba,Nueva Ecija
Tampac II & III,Guimba,Nueva Ecija
Triala,Guimba,Nueva Ecija
Yuson,Guimba,Nueva Ecija
Bunol,Guimba,Nueva Ecija
Calabasa,Jaen,Nueva Ecija
Dampulan,Jaen,Nueva Ecija
Hilera,Jaen,Nueva Ecija
Imbunia,Jaen,Nueva Ecija
Imelda Pob.,Jaen,Nueva Ecija
Lambakin,Jaen,Nueva Ecija
Langla,Jaen,Nueva Ecija
Magsalisi,Jaen,Nueva Ecija
Malabon-Kaingin,Jaen,Nueva Ecija
Marawa,Jaen,Nueva Ecija
Don Mariano Marcos,Jaen,Nueva Ecija
San Josef,Jaen,Nueva Ecija
Niyugan,Jaen,Nueva Ecija
Pamacpacan,Jaen,Nueva Ecija
Pakol,Jaen,Nueva Ecija
Pinanggaan,Jaen,Nueva Ecija
Ulanin-Pitak,Jaen,Nueva Ecija
Putlod,Jaen,Nueva Ecija
Ocampo-Rivera District,Jaen,Nueva Ecija
San Jose,Jaen,Nueva Ecija
San Pablo,Jaen,Nueva Ecija
San Roque,Jaen,Nueva Ecija
San Vicente,Jaen,Nueva Ecija
Santa Rita,Jaen,Nueva Ecija
Santo Tomas North,Jaen,Nueva Ecija
Santo Tomas South,Jaen,Nueva Ecija
Sapang,Jaen,Nueva Ecija
Barangay I,Laur,Nueva Ecija
Barangay II,Laur,Nueva Ecija
Barangay III,Laur,Nueva Ecija
Barangay IV,Laur,Nueva Ecija
Betania,Laur,Nueva Ecija
Canantong,Laur,Nueva Ecija
Nauzon,Laur,Nueva Ecija
Pangarulong,Laur,Nueva Ecija
Pinagbayanan,Laur,Nueva Ecija
Sagana,Laur,Nueva Ecija
San Fernando,Laur,Nueva Ecija
San Isidro,Laur,Nueva Ecija
San Josef,Laur,Nueva Ecija
San Juan,Laur,Nueva Ecija
San Vicente,Laur,Nueva Ecija
Siclong,Laur,Nueva Ecija
San Felipe,Laur,Nueva Ecija
Linao,Licab,Nueva Ecija
Poblacion Norte,Licab,Nueva Ecija
Poblacion Sur,Licab,Nueva Ecija
San Casimiro,Licab,Nueva Ecija
San Cristobal,Licab,Nueva Ecija
San Jose,Licab,Nueva Ecija
San Juan,Licab,Nueva Ecija
Santa Maria,Licab,Nueva Ecija
Tabing Ilog,Licab,Nueva Ecija
Villarosa,Licab,Nueva Ecija
Aquino,Licab,Nueva Ecija
A. Bonifacio,Llanera,Nueva Ecija
Caridad Norte,Llanera,Nueva Ecija
Caridad Sur,Llanera,Nueva Ecija
Casile,Llanera,Nueva Ecija
Florida Blanca,Llanera,Nueva Ecija
General Luna,Llanera,Nueva Ecija
General Ricarte,Llanera,Nueva Ecija
Gomez,Llanera,Nueva Ecija
Inanama,Llanera,Nueva Ecija
Ligaya,Llanera,Nueva Ecija
Mabini,Llanera,Nueva Ecija
Murcon,Llanera,Nueva Ecija
Plaridel,Llanera,Nueva Ecija
Bagumbayan,Llanera,Nueva Ecija
San Felipe,Llanera,Nueva Ecija
San Francisco,Llanera,Nueva Ecija
San Nicolas,Llanera,Nueva Ecija
San Vicente,Llanera,Nueva Ecija
Santa Barbara,Llanera,Nueva Ecija
Victoria,Llanera,Nueva Ecija
Villa Viniegas,Llanera,Nueva Ecija
Bosque,Llanera,Nueva Ecija
Agupalo Este,Lupao,Nueva Ecija
Agupalo Weste,Lupao,Nueva Ecija
Alalay Chica,Lupao,Nueva Ecija
Alalay Grande,Lupao,Nueva Ecija
J. U. Tienzo,Lupao,Nueva Ecija
Bagong Flores,Lupao,Nueva Ecija
Balbalungao,Lupao,Nueva Ecija
Burgos,Lupao,Nueva Ecija
Cordero,Lupao,Nueva Ecija
Mapangpang,Lupao,Nueva Ecija
Namulandayan,Lupao,Nueva Ecija
Parista,Lupao,Nueva Ecija
Poblacion East,Lupao,Nueva Ecija
Poblacion North,Lupao,Nueva Ecija
Poblacion South,Lupao,Nueva Ecija
Poblacion West,Lupao,Nueva Ecija
Salvacion I,Lupao,Nueva Ecija
Salvacion II,Lupao,Nueva Ecija
San Antonio Este,Lupao,Nueva Ecija
San Antonio Weste,Lupao,Nueva Ecija
San Isidro,Lupao,Nueva Ecija
San Pedro,Lupao,Nueva Ecija
San Roque,Lupao,Nueva Ecija
Santo Domingo,Lupao,Nueva Ecija
Bagong Sikat,Science City of Muñoz,Nueva Ecija
Balante,Science City of Muñoz,Nueva Ecija
Bantug,Science City of Muñoz,Nueva Ecija
Bical,Science City of Muñoz,Nueva Ecija
Cabisuculan,Science City of Muñoz,Nueva Ecija
Calabalabaan,Science City of Muñoz,Nueva Ecija
Calisitan,Science City of Muñoz,Nueva Ecija
Catalanacan,Science City of Muñoz,Nueva Ecija
Curva,Science City of Muñoz,Nueva Ecija
Franza,Science City of Muñoz,Nueva Ecija
Gabaldon,Science City of Muñoz,Nueva Ecija
Labney,Science City of Muñoz,Nueva Ecija
Licaong,Science City of Muñoz,Nueva Ecija
Linglingay,Science City of Muñoz,Nueva Ecija
Mangandingay,Science City of Muñoz,Nueva Ecija
Magtanggol,Science City of Muñoz,Nueva Ecija
Maligaya,Science City of Muñoz,Nueva Ecija
Mapangpang,Science City of Muñoz,Nueva Ecija
Maragol,Science City of Muñoz,Nueva Ecija
Matingkis,Science City of Muñoz,Nueva Ecija
Naglabrahan,Science City of Muñoz,Nueva Ecija
Palusapis,Science City of Muñoz,Nueva Ecija
Pandalla,Science City of Muñoz,Nueva Ecija
Poblacion East,Science City of Muñoz,Nueva Ecija
Poblacion North,Science City of Muñoz,Nueva Ecija
Poblacion South,Science City of Muñoz,Nueva Ecija
Poblacion West,Science City of Muñoz,Nueva Ecija
Rang-ayan,Science City of Muñoz,Nueva Ecija
Rizal,Science City of Muñoz,Nueva Ecija
San Andres,Science City of Muñoz,Nueva Ecija
San Antonio,Science City of Muñoz,Nueva Ecija
San Felipe,Science City of Muñoz,Nueva Ecija
Sapang Cawayan,Science City of Muñoz,Nueva Ecija
Villa Isla,Science City of Muñoz,Nueva Ecija
Villa Nati,Science City of Muñoz,Nueva Ecija
Villa Santos,Science City of Muñoz,Nueva Ecija
Villa Cuizon,Science City of Muñoz,Nueva Ecija
Alemania,Nampicuan,Nueva Ecija
Ambasador Alzate Village,Nampicuan,Nueva Ecija
Cabaducan East,Nampicuan,Nueva Ecija
Cabaducan West,Nampicuan,Nueva Ecija
Cabawangan,Nampicuan,Nueva Ecija
East Central Poblacion,Nampicuan,Nueva Ecija
Edy,Nampicuan,Nueva Ecija
Maeling,Nampicuan,Nueva Ecija
Mayantoc,Nampicuan,Nueva Ecija
Medico,Nampicuan,Nueva Ecija
Monic,Nampicuan,Nueva Ecija
North Poblacion,Nampicuan,Nueva Ecija
Northwest Poblacion,Nampicuan,Nueva Ecija
Estacion,Nampicuan,Nueva Ecija
West Poblacion,Nampicuan,Nueva Ecija
Recuerdo,Nampicuan,Nueva Ecija
South Central Poblacion,Nampicuan,Nueva Ecija
Southeast Poblacion,Nampicuan,Nueva Ecija
Southwest Poblacion,Nampicuan,Nueva Ecija
Tony,Nampicuan,Nueva Ecija
West Central Poblacion,Nampicuan,Nueva Ecija
Aulo,City of Palayan,Nueva Ecija
Bo. Militar,City of Palayan,Nueva Ecija
Ganaderia,City of Palayan,Nueva Ecija
Maligaya,City of Palayan,Nueva Ecija
Manacnac,City of Palayan,Nueva Ecija
Mapait,City of Palayan,Nueva Ecija
Marcos Village,City of Palayan,Nueva Ecija
Malate,City of Palayan,Nueva Ecija
Sapang Buho,City of Palayan,Nueva Ecija
Singalat,City of Palayan,Nueva Ecija
Atate,City of Palayan,Nueva Ecija
Caballero,City of Palayan,Nueva Ecija
Caimito,City of Palayan,Nueva Ecija
Doña Josefa,City of Palayan,Nueva Ecija
Imelda Valley,City of Palayan,Nueva Ecija
Langka,City of Palayan,Nueva Ecija
Santolan,City of Palayan,Nueva Ecija
Popolon Pagas,City of Palayan,Nueva Ecija
Bagong Buhay,City of Palayan,Nueva Ecija
Cadaclan,Pantabangan,Nueva Ecija
Cambitala,Pantabangan,Nueva Ecija
Conversion,Pantabangan,Nueva Ecija
Ganduz,Pantabangan,Nueva Ecija
Liberty,Pantabangan,Nueva Ecija
Malbang,Pantabangan,Nueva Ecija
Marikit,Pantabangan,Nueva Ecija
Napon-Napon,Pantabangan,Nueva Ecija
Poblacion East,Pantabangan,Nueva Ecija
Poblacion West,Pantabangan,Nueva Ecija
Sampaloc,Pantabangan,Nueva Ecija
San Juan,Pantabangan,Nueva Ecija
Villarica,Pantabangan,Nueva Ecija
Fatima,Pantabangan,Nueva Ecija
Callos,Peñaranda,Nueva Ecija
Las Piñas,Peñaranda,Nueva Ecija
Poblacion I,Peñaranda,Nueva Ecija
Poblacion II,Peñaranda,Nueva Ecija
Poblacion III,Peñaranda,Nueva Ecija
Poblacion IV,Peñaranda,Nueva Ecija
Santo Tomas,Peñaranda,Nueva Ecija
Sinasajan,Peñaranda,Nueva Ecija
San Josef,Peñaranda,Nueva Ecija
San Mariano,Peñaranda,Nueva Ecija
Bertese,Quezon,Nueva Ecija
Doña Lucia,Quezon,Nueva Ecija
Dulong Bayan,Quezon,Nueva Ecija
Ilog Baliwag,Quezon,Nueva Ecija
Barangay I,Quezon,Nueva Ecija
Barangay II,Quezon,Nueva Ecija
Pulong Bahay,Quezon,Nueva Ecija
San Alejandro,Quezon,Nueva Ecija
San Andres I,Quezon,Nueva Ecija
San Andres II,Quezon,Nueva Ecija
San Manuel,Quezon,Nueva Ecija
Santa Clara,Quezon,Nueva Ecija
Santa Rita,Quezon,Nueva Ecija
Santo Cristo,Quezon,Nueva Ecija
Santo Tomas Feria,Quezon,Nueva Ecija
San Miguel,Quezon,Nueva Ecija
Agbannawag,Rizal,Nueva Ecija
Bicos,Rizal,Nueva Ecija
Cabucbucan,Rizal,Nueva Ecija
Calaocan District,Rizal,Nueva Ecija
Canaan East,Rizal,Nueva Ecija
Canaan West,Rizal,Nueva Ecija
Casilagan,Rizal,Nueva Ecija
Aglipay,Rizal,Nueva Ecija
Del Pilar,Rizal,Nueva Ecija
Estrella,Rizal,Nueva Ecija
General Luna,Rizal,Nueva Ecija
Macapsing,Rizal,Nueva Ecija
Maligaya,Rizal,Nueva Ecija
Paco Roman,Rizal,Nueva Ecija
Pag-asa,Rizal,Nueva Ecija
Poblacion Central,Rizal,Nueva Ecija
Poblacion East,Rizal,Nueva Ecija
Poblacion Norte,Rizal,Nueva Ecija
Poblacion Sur,Rizal,Nueva Ecija
Poblacion West,Rizal,Nueva Ecija
Portal,Rizal,Nueva Ecija
San Esteban,Rizal,Nueva Ecija
Santa Monica,Rizal,Nueva Ecija
Villa Labrador,Rizal,Nueva Ecija
Villa Paraiso,Rizal,Nueva Ecija
San Gregorio,Rizal,Nueva Ecija
Buliran,San Antonio,Nueva Ecija
Cama Juan,San Antonio,Nueva Ecija
Julo,San Antonio,Nueva Ecija
Lawang Kupang,San Antonio,Nueva Ecija
Luyos,San Antonio,Nueva Ecija
Maugat,San Antonio,Nueva Ecija
Panabingan,San Antonio,Nueva Ecija
Papaya,San Antonio,Nueva Ecija
Poblacion,San Antonio,Nueva Ecija
San Francisco,San Antonio,Nueva Ecija
San Jose,San Antonio,Nueva Ecija
San Mariano,San Antonio,Nueva Ecija
Santa Cruz,San Antonio,Nueva Ecija
Santo Cristo,San Antonio,Nueva Ecija
Santa Barbara,San Antonio,Nueva Ecija
Tikiw,San Antonio,Nueva Ecija
Alua,San Isidro,Nueva Ecija
Calaba,San Isidro,Nueva Ecija
Malapit,San Isidro,Nueva Ecija
Mangga,San Isidro,Nueva Ecija
Poblacion,San Isidro,Nueva Ecija
Pulo,San Isidro,Nueva Ecija
San Roque,San Isidro,Nueva Ecija
Sto. Cristo,San Isidro,Nueva Ecija
Tabon,San Isidro,Nueva Ecija
A. Pascual,San Jose City,Nueva Ecija
Abar Ist,San Jose City,Nueva Ecija
Abar 2nd,San Jose City,Nueva Ecija
Bagong Sikat,San Jose City,Nueva Ecija
Caanawan,San Jose City,Nueva Ecija
Calaocan,San Jose City,Nueva Ecija
Camanacsacan,San Jose City,Nueva Ecija
Culaylay,San Jose City,Nueva Ecija
Dizol,San Jose City,Nueva Ecija
Kaliwanagan,San Jose City,Nueva Ecija
Kita-Kita,San Jose City,Nueva Ecija
Malasin,San Jose City,Nueva Ecija
Manicla,San Jose City,Nueva Ecija
Palestina,San Jose City,Nueva Ecija
Parang Mangga,San Jose City,Nueva Ecija
Villa Joson,San Jose City,Nueva Ecija
Pinili,San Jose City,Nueva Ecija
Ferdinand E. Marcos Pob.,San Jose City,Nueva Ecija
Canuto Ramos Pob.,San Jose City,Nueva Ecija
Raymundo Eugenio Pob.,San Jose City,Nueva Ecija
Crisanto Sanchez Pob.,San Jose City,Nueva Ecija
Porais,San Jose City,Nueva Ecija
San Agustin,San Jose City,Nueva Ecija
San Juan,San Jose City,Nueva Ecija
San Mauricio,San Jose City,Nueva Ecija
Santo Niño 1st,San Jose City,Nueva Ecija
Santo Niño 2nd,San Jose City,Nueva Ecija
Santo Tomas,San Jose City,Nueva Ecija
Sibut,San Jose City,Nueva Ecija
Sinipit Bubon,San Jose City,Nueva Ecija
Santo Niño 3rd,San Jose City,Nueva Ecija
Tabulac,San Jose City,Nueva Ecija
Tayabo,San Jose City,Nueva Ecija
Tondod,San Jose City,Nueva Ecija
Tulat,San Jose City,Nueva Ecija
Villa Floresca,San Jose City,Nueva Ecija
Villa Marina,San Jose City,Nueva Ecija
Bonifacio District,San Leonardo,Nueva Ecija
Burgos District,San Leonardo,Nueva Ecija
Castellano,San Leonardo,Nueva Ecija
Diversion,San Leonardo,Nueva Ecija
Magpapalayoc,San Leonardo,Nueva Ecija
Mallorca,San Leonardo,Nueva Ecija
Mambangnan,San Leonardo,Nueva Ecija
Nieves,San Leonardo,Nueva Ecija
San Bartolome,San Leonardo,Nueva Ecija
Rizal District,San Leonardo,Nueva Ecija
San Anton,San Leonardo,Nueva Ecija
San Roque,San Leonardo,Nueva Ecija
Tabuating,San Leonardo,Nueva Ecija
Tagumpay,San Leonardo,Nueva Ecija
Tambo Adorable,San Leonardo,Nueva Ecija
Cojuangco,Santa Rosa,Nueva Ecija
La Fuente,Santa Rosa,Nueva Ecija
Liwayway,Santa Rosa,Nueva Ecija
Malacañang,Santa Rosa,Nueva Ecija
Maliolio,Santa Rosa,Nueva Ecija
Mapalad,Santa Rosa,Nueva Ecija
Rizal,Santa Rosa,Nueva Ecija
Rajal Centro,Santa Rosa,Nueva Ecija
Rajal Norte,Santa Rosa,Nueva Ecija
Rajal Sur,Santa Rosa,Nueva Ecija
San Gregorio,Santa Rosa,Nueva Ecija
San Mariano,Santa Rosa,Nueva Ecija
San Pedro,Santa Rosa,Nueva Ecija
Santo Rosario,Santa Rosa,Nueva Ecija
Soledad,Santa Rosa,Nueva Ecija
Valenzuela,Santa Rosa,Nueva Ecija
Zamora,Santa Rosa,Nueva Ecija
Aguinaldo,Santa Rosa,Nueva Ecija
Berang,Santa Rosa,Nueva Ecija
Burgos,Santa Rosa,Nueva Ecija
Del Pilar,Santa Rosa,Nueva Ecija
Gomez,Santa Rosa,Nueva Ecija
Inspector,Santa Rosa,Nueva Ecija
Isla,Santa Rosa,Nueva Ecija
Lourdes,Santa Rosa,Nueva Ecija
Luna,Santa Rosa,Nueva Ecija
Mabini,Santa Rosa,Nueva Ecija
San Isidro,Santa Rosa,Nueva Ecija
San Josep,Santa Rosa,Nueva Ecija
Santa Teresita,Santa Rosa,Nueva Ecija
Sapsap,Santa Rosa,Nueva Ecija
Tagpos,Santa Rosa,Nueva Ecija
Tramo,Santa Rosa,Nueva Ecija
Baloc,Santo Domingo,Nueva Ecija
Buasao,Santo Domingo,Nueva Ecija
Burgos,Santo Domingo,Nueva Ecija
Cabugao,Santo Domingo,Nueva Ecija
Casulucan,Santo Domingo,Nueva Ecija
Comitang,Santo Domingo,Nueva Ecija
Concepcion,Santo Domingo,Nueva Ecija
Dolores,Santo Domingo,Nueva Ecija
General Luna,Santo Domingo,Nueva Ecija
Hulo,Santo Domingo,Nueva Ecija
Mabini,Santo Domingo,Nueva Ecija
Malasin,Santo Domingo,Nueva Ecija
Malayantoc,Santo Domingo,Nueva Ecija
Mambarao,Santo Domingo,Nueva Ecija
Poblacion,Santo Domingo,Nueva Ecija
Malaya,Santo Domingo,Nueva Ecija
Pulong Buli,Santo Domingo,Nueva Ecija
Sagaba,Santo Domingo,Nueva Ecija
San Agustin,Santo Domingo,Nueva Ecija
San Fabian,Santo Domingo,Nueva Ecija
San Francisco,Santo Domingo,Nueva Ecija
San Pascual,Santo Domingo,Nueva Ecija
Santa Rita,Santo Domingo,Nueva Ecija
Santo Rosario,Santo Domingo,Nueva Ecija
Andal Alino,Talavera,Nueva Ecija
Bagong Sikat,Talavera,Nueva Ecija
Bagong Silang,Talavera,Nueva Ecija
Bakal I,Talavera,Nueva Ecija
Bakal II,Talavera,Nueva Ecija
Bakal III,Talavera,Nueva Ecija
Baluga,Talavera,Nueva Ecija
Bantug,Talavera,Nueva Ecija
Bantug Hacienda,Talavera,Nueva Ecija
Bantug Hamog,Talavera,Nueva Ecija
Bugtong na Buli,Talavera,Nueva Ecija
Bulac,Talavera,Nueva Ecija
Burnay,Talavera,Nueva Ecija
Calipahan,Talavera,Nueva Ecija
Campos,Talavera,Nueva Ecija
Casulucan Este,Talavera,Nueva Ecija
Collado,Talavera,Nueva Ecija
Dimasalang Norte,Talavera,Nueva Ecija
Dimasalang Sur,Talavera,Nueva Ecija
Dinarayat,Talavera,Nueva Ecija
Esguerra District,Talavera,Nueva Ecija
Gulod,Talavera,Nueva Ecija
Homestead I,Talavera,Nueva Ecija
Homestead II,Talavera,Nueva Ecija
Cabubulaonan,Talavera,Nueva Ecija
Caaniplahan,Talavera,Nueva Ecija
Caputican,Talavera,Nueva Ecija
Kinalanguyan,Talavera,Nueva Ecija
La Torre,Talavera,Nueva Ecija
Lomboy,Talavera,Nueva Ecija
Mabuhay,Talavera,Nueva Ecija
Maestrang Kikay,Talavera,Nueva Ecija
Mamandil,Talavera,Nueva Ecija
Marcos District,Talavera,Nueva Ecija
Purok Matias,Talavera,Nueva Ecija
Matingkis,Talavera,Nueva Ecija
Minabuyoc,Talavera,Nueva Ecija
Pag-asa,Talavera,Nueva Ecija
Paludpod,Talavera,Nueva Ecija
Pantoc Bulac,Talavera,Nueva Ecija
Pinagpanaan,Talavera,Nueva Ecija
Poblacion Sur,Talavera,Nueva Ecija
Pula,Talavera,Nueva Ecija
Pulong San Miguel,Talavera,Nueva Ecija
Sampaloc,Talavera,Nueva Ecija
San Miguel na Munti,Talavera,Nueva Ecija
San Pascual,Talavera,Nueva Ecija
San Ricardo,Talavera,Nueva Ecija
Sibul,Talavera,Nueva Ecija
Sicsican Matanda,Talavera,Nueva Ecija
Tabacao,Talavera,Nueva Ecija
Tagaytay,Talavera,Nueva Ecija
Valle,Talavera,Nueva Ecija
Alula,Talugtug,Nueva Ecija
Baybayabas,Talugtug,Nueva Ecija
Buted,Talugtug,Nueva Ecija
Cabiangan,Talugtug,Nueva Ecija
Calisitan,Talugtug,Nueva Ecija
Cinense,Talugtug,Nueva Ecija
Culiat,Talugtug,Nueva Ecija
Maasin,Talugtug,Nueva Ecija
Magsaysay,Talugtug,Nueva Ecija
Mayamot I,Talugtug,Nueva Ecija
Mayamot II,Talugtug,Nueva Ecija
Nangabulan,Talugtug,Nueva Ecija
Osmeña,Talugtug,Nueva Ecija
Pangit,Talugtug,Nueva Ecija
Patola,Talugtug,Nueva Ecija
Quezon,Talugtug,Nueva Ecija
Quirino,Talugtug,Nueva Ecija
Roxas,Talugtug,Nueva Ecija
Saguing,Talugtug,Nueva Ecija
Sampaloc,Talugtug,Nueva Ecija
Santa Catalina,Talugtug,Nueva Ecija
Santo Domingo,Talugtug,Nueva Ecija
Saringaya,Talugtug,Nueva Ecija
Saverona,Talugtug,Nueva Ecija
Tandoc,Talugtug,Nueva Ecija
Tibag,Talugtug,Nueva Ecija
Villa Rosario,Talugtug,Nueva Ecija
Villa Boado,Talugtug,Nueva Ecija
Batitang,Zaragoza,Nueva Ecija
Carmen,Zaragoza,Nueva Ecija
Concepcion,Zaragoza,Nueva Ecija
Del Pilar,Zaragoza,Nueva Ecija
General Luna,Zaragoza,Nueva Ecija
H. Romero,Zaragoza,Nueva Ecija
Macarse,Zaragoza,Nueva Ecija
Manaul,Zaragoza,Nueva Ecija
Mayamot,Zaragoza,Nueva Ecija
Pantoc,Zaragoza,Nueva Ecija
San Vicente,Zaragoza,Nueva Ecija
San Isidro,Zaragoza,Nueva Ecija
San Rafael,Zaragoza,Nueva Ecija
Santa Cruz,Zaragoza,Nueva Ecija
Santa Lucia Old,Zaragoza,Nueva Ecija
Santa Lucia Young,Zaragoza,Nueva Ecija
Santo Rosario Old,Zaragoza,Nueva Ecija
Santo Rosario Young,Zaragoza,Nueva Ecija
Valeriana,Zaragoza,Nueva Ecija
Agtipalo,Baler,Aurora
Babat,Baler,Aurora
Bacong,Baler,Aurora
Baliag,Baler,Aurora
Bansaan,Baler,Aurora
Bantay,Baler,Aurora
Bukal,Baler,Aurora
Buru-Buru,Baler,Aurora
Calabuanan,Baler,Aurora
Calantas,Baler,Aurora
Calungayan,Baler,Aurora
Caniogan,Baler,Aurora
Colongcolong,Baler,Aurora
Dibalo,Baler,Aurora
Dibut,Baler,Aurora
Dimalangat,Baler,Aurora
Ditumabo,Baler,Aurora
Duongan,Baler,Aurora
Fulgador,Baler,Aurora
Sabang,Baler,Aurora
San Isidro,Baler,Aurora
San Jose,Baler,Aurora
San Luis,Baler,Aurora
San Pablo,Baler,Aurora
San Pedro,Baler,Aurora
Santa Maria,Baler,Aurora
Suklayin,Baler,Aurora
Suclayin,Baler,Aurora
Zabali,Baler,Aurora
Bato,Casiguran,Aurora
Bibitinan,Casiguran,Aurora
Bilao,Casiguran,Aurora
Biniguni,Casiguran,Aurora
Bulawit,Casiguran,Aurora
Calabgan,Casiguran,Aurora
Calatagan,Casiguran,Aurora
Culaba,Casiguran,Aurora
Dibaraybay,Casiguran,Aurora
Dibet,Casiguran,Aurora
Dikapinisan,Casiguran,Aurora
Dingasan,Casiguran,Aurora
Dita,Casiguran,Aurora
Esteves,Casiguran,Aurora
Hinipaan,Casiguran,Aurora
Ilot,Casiguran,Aurora
Lual,Casiguran,Aurora
Lual Bati,Casiguran,Aurora
Lual Malalim,Casiguran,Aurora
Marikit,Casiguran,Aurora
Molave,Casiguran,Aurora
Pinaglabasan,Casiguran,Aurora
Poblacion,Casiguran,Aurora
San Isidro,Casiguran,Aurora
Tanada,Casiguran,Aurora
Tawag,Casiguran,Aurora
Umiray,Casiguran,Aurora
Agtasa,Dilasag,Aurora
Bacong,Dilasag,Aurora
Bagto,Dilasag,Aurora
Barangay 1,Dilasag,Aurora
Barangay 2,Dilasag,Aurora
Barangay 3,Dilasag,Aurora
Barangay 4,Dilasag,Aurora
Barangay 5,Dilasag,Aurora
Barangay 6,Dilasag,Aurora
Barangay 7,Dilasag,Aurora
Barangay 8,Dilasag,Aurora
Barangay 9,Dilasag,Aurora
Barangay 10,Dilasag,Aurora
Barangay 11,Dilasag,Aurora
Calabagan,Dilasag,Aurora
Dimalangat,Dilasag,Aurora
Diwayan,Dilasag,Aurora
Ditumabo,Dilasag,Aurora
Lawang Kawayan,Dilasag,Aurora
Masese,Dilasag,Aurora
Poblacion,Dilasag,Aurora
San Isidro,Dilasag,Aurora
Tanag,Dilasag,Aurora
Yapara,Dilasag,Aurora
Babat,Dinalungan,Aurora
Bacuit,Dinalungan,Aurora
Bucay,Dinalungan,Aurora
Caragsacan,Dinalungan,Aurora
Dibaraybay,Dinalungan,Aurora
Dibet,Dinalungan,Aurora
Dimalangat,Dinalungan,Aurora
Dipaculao,Dinalungan,Aurora
Gumabat,Dinalungan,Aurora
Lual,Dinalungan,Aurora
Poblacion,Dinalungan,Aurora
Simbahan,Dinalungan,Aurora
Umiray,Dinalungan,Aurora
Yapara,Dinalungan,Aurora
Agtipalo,Dipaculao,Aurora
Babat,Dipaculao,Aurora
Bacong,Dipaculao,Aurora
Baliag,Dipaculao,Aurora
Bansaan,Dipaculao,Aurora
Bantay,Dipaculao,Aurora
Bukal,Dipaculao,Aurora
Buru-Buru,Dipaculao,Aurora
Calabuanan,Dipaculao,Aurora
Calantas,Dipaculao,Aurora
Calungayan,Dipaculao,Aurora
Caniogan,Dipaculao,Aurora
Colongcolong,Dipaculao,Aurora
Dibalo,Dipaculao,Aurora
Dibut,Dipaculao,Aurora
Dimalangat,Dipaculao,Aurora
Ditumabo,Dipaculao,Aurora
Duongan,Dipaculao,Aurora
Fulgador,Dipaculao,Aurora
Gumabat,Dipaculao,Aurora
Poblacion,Dipaculao,Aurora
Simbahan,Dipaculao,Aurora
Umiray,Dipaculao,Aurora
Yapara,Dipaculao,Aurora
Agtipalo,Maria Aurora,Aurora
Babat,Maria Aurora,Aurora
Bacong,Maria Aurora,Aurora
Baliag,Maria Aurora,Aurora
Bansaan,Maria Aurora,Aurora
Bantay,Maria Aurora,Aurora
Bukal,Maria Aurora,Aurora
Buru-Buru,Maria Aurora,Aurora
Calabuanan,Maria Aurora,Aurora
Calantas,Maria Aurora,Aurora
Calungayan,Maria Aurora,Aurora
Caniogan,Maria Aurora,Aurora
Colongcolong,Maria Aurora,Aurora
Dibalo,Maria Aurora,Aurora
Dibut,Maria Aurora,Aurora
Dimalangat,Maria Aurora,Aurora
Ditumabo,Maria Aurora,Aurora
Duongan,Maria Aurora,Aurora
Fulgador,Maria Aurora,Aurora
Gumabat,Maria Aurora,Aurora
Poblacion,Maria Aurora,Aurora
Simbahan,Maria Aurora,Aurora
Umiray,Maria Aurora,Aurora
Yapara,Maria Aurora,Aurora
Agtipalo,San Luis,Aurora
Babat,San Luis,Aurora
Bacong,San Luis,Aurora
Baliag,San Luis,Aurora
Bansaan,San Luis,Aurora
Bantay,San Luis,Aurora
Bukal,San Luis,Aurora
Buru-Buru,San Luis,Aurora
Calabuanan,San Luis,Aurora
Calantas,San Luis,Aurora
Calungayan,San Luis,Aurora
Caniogan,San Luis,Aurora
Colongcolong,San Luis,Aurora
Dibalo,San Luis,Aurora
Dibut,San Luis,Aurora
Dimalangat,San Luis,Aurora
Ditumabo,San Luis,Aurora
Duongan,San Luis,Aurora
Fulgador,San Luis,Aurora
Gumabat,San Luis,Aurora
Poblacion,San Luis,Aurora
Simbahan,San Luis,Aurora
Umiray,San Luis,Aurora
Yapara,San Luis,Aurora
Agtipalo,Dingalan,Aurora
Babat,Dingalan,Aurora
Bacong,Dingalan,Aurora
Baliag,Dingalan,Aurora
Bansaan,Dingalan,Aurora
Bantay,Dingalan,Aurora
Bukal,Dingalan,Aurora
Buru-Buru,Dingalan,Aurora
Calabuanan,Dingalan,Aurora
Calantas,Dingalan,Aurora
Calungayan,Dingalan,Aurora
Caniogan,Dingalan,Aurora
Colongcolong,Dingalan,Aurora
Dibalo,Dingalan,Aurora
Dibut,Dingalan,Aurora
Dimalangat,Dingalan,Aurora
Ditumabo,Dingalan,Aurora
Duongan,Dingalan,Aurora
Fulgador,Dingalan,Aurora
Gumabat,Dingalan,Aurora
Poblacion,Dingalan,Aurora
Simbahan,Dingalan,Aurora
Umiray,Dingalan,Aurora
Yapara,Dingalan,Aurora`;

            function parseLocationData(csv) {
                var data = {};
                var lines = csv.split(/\r?\n/).filter(function(line) { return line.trim().length > 0; });

                for (var i = 1; i < lines.length; i++) {
                    var parts = parseCsvLine(lines[i]);
                    var barangay = parts[0];
                    var municipality = parts[1];
                    var province = parts[2];

                    if (!province || !municipality || !barangay) {
                        continue;
                    }

                    if (!data[province]) {
                        data[province] = {};
                    }
                    if (!data[province][municipality]) {
                        data[province][municipality] = [];
                    }
                    if (data[province][municipality].indexOf(barangay) === -1) {
                        data[province][municipality].push(barangay);
                    }
                }

                for (var province in data) {
                    for (var municipality in data[province]) {
                        data[province][municipality].sort(function(a, b) { return a.localeCompare(b, 'en', { sensitivity: 'base' }); });
                    }
                }

                return data;
            }

            function parseCsvLine(line) {
                var values = [];
                var current = '';
                var inQuotes = false;

                for (var i = 0; i < line.length; i++) {
                    var char = line[i];
                    if (char === '"') {
                        inQuotes = !inQuotes;
                    } else if (char === ',' && !inQuotes) {
                        values.push(current);
                        current = '';
                    } else {
                        current += char;
                    }
                }

                values.push(current);
                return values;
            }

            var locationData = parseLocationData(locationCsv);

            // Cascading dropdown functionality for table filters
            (function () {
                var tableProvince = document.getElementById('tableProvince');
                var tableMunicipality = document.getElementById('tableMunicipality');
                var tableBarangay = document.getElementById('tableBarangay');

                if (!tableProvince || !tableMunicipality || !tableBarangay) {
                    return;
                }

                function populateSelect(selectElement, options, placeholder) {
                    selectElement.innerHTML = '';
                    var defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = placeholder;
                    selectElement.appendChild(defaultOption);

                    options.forEach(function (option) {
                        var optionItem = document.createElement('option');
                        optionItem.value = option;
                        optionItem.textContent = option;
                        selectElement.appendChild(optionItem);
                    });
                }

                function updateMunicipalities() {
                    if (!tableProvince.value) {
                        populateSelect(tableMunicipality, [], 'All Municipalities');
                        populateSelect(tableBarangay, [], 'All Barangays');
                        return;
                    }
                    var municipalities = Object.keys(locationData[tableProvince.value] || {});
                    populateSelect(tableMunicipality, municipalities, 'All Municipalities');
                    populateSelect(tableBarangay, [], 'All Barangays');
                }

                function updateBarangays() {
                    if (!tableProvince.value || !tableMunicipality.value) {
                        populateSelect(tableBarangay, [], 'All Barangays');
                        return;
                    }
                    var barangays = locationData[tableProvince.value]?.[tableMunicipality.value] || [];
                    populateSelect(tableBarangay, barangays, 'All Barangays');
                }

                tableProvince.addEventListener('change', function() {
                    updateMunicipalities();
                });

                tableMunicipality.addEventListener('change', function() {
                    updateBarangays();
                });

                // Initialize on page load based on selected province
                if (tableProvince.value) {
                    updateMunicipalities();
                    if (tableMunicipality.value) {
                        tableMunicipality.value = '{{ request("municipality") }}';
                        updateBarangays();
                        if (tableBarangay.value) {
                            tableBarangay.value = '{{ request("barangay") }}';
                        }
                    }
                }
            })();
        </script>
    </div>

    <div class="no-print" style="margin-bottom: 16px; padding: 20px; border-radius: 12px; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #006c35 0%, #008a43 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #1e293b;">NL Records</h3>
            </div>
            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <button id="delete-multiple" class="btn" style="padding: 10px 16px; background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 13px; box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2); transition: all 0.2s;">Delete Multiple</button>
                <button id="delete-selected" class="btn" disabled style="padding: 10px 16px; background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); color: white; border: none; border-radius: 8px; cursor: not-allowed; font-weight: 600; font-size: 13px; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2); transition: all 0.2s; opacity: 0.6;">Delete Selected</button>
                <button type="button" id="select-records-transmit" class="btn" style="padding: 10px 16px; background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 13px; box-shadow: 0 2px 4px rgba(14, 165, 233, 0.2); transition: all 0.2s;">Select for Transmit</button>
                <button type="button" id="transmit-selected-records" class="btn" disabled style="padding: 10px 16px; background: linear-gradient(135deg, #006c35 0%, #008a43 100%); color: white; border: none; border-radius: 8px; cursor: not-allowed; font-weight: 600; font-size: 13px; box-shadow: 0 2px 4px rgba(0, 108, 53, 0.2); transition: all 0.2s; opacity: 0.6;">Transmit Selected</button>
                <span id="bulk-selected-count" style="padding: 8px 12px; background: #f1f5f9; color: #64748b; border-radius: 8px; font-size: 12px; font-weight: 600; border: 1px solid #e2e8f0; min-width: 80px; text-align: center;">0 selected</span>
            </div>
        </div>
    </div>
    <form id="bulk-form" method="POST" action="{{ route('admin.bulk-delete') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="record_ids" id="selected-record-ids">
        <div id="table-loading-indicator" style="display: none; margin-bottom: 10px; color: #1565C0; font-weight: 600;">Loading records...</div>
        
        <!-- Main table container with proper sticky header support -->
        <div id="table-container">
            <div id="table-wrapper" class="table-wrapper" style="width: 100%; border-radius: 16px; overflow: hidden; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                <x-table :records="$records" :showEncoder="true" :showFilters="false" :showAdminTransmittal="true" :allPrograms="$allPrograms" :allLines="$allLines" :allSources="$allSources" :allModes="$allModes" :showCheckbox="true" />
            </div>
        </div>
        @if($records->isEmpty())
            <div style="padding: 16px; margin-bottom: 12px; border: 1px solid #e0e0e0; background: #fafafa; color: #555;">
                No records found for the current filters.
            </div>
        @endif
        <div class="no-print" style="margin: 10px 0; text-align: center;">
            @if ($records->hasPages())
                <div id="pagination-container" style="display: flex; justify-content: center; align-items: center; gap: 12px;">
                    @if ($records->onFirstPage())
                        <span style="padding: 8px 16px; border-radius: 8px; background: #f1f5f9; color: #94a3b8; font-size: 14px; font-weight: 500; border: 1px solid #e2e8f0;">Previous</span>
                    @else
                        <a href="{{ $records->appends(['tab' => 'nl-records'])->previousPageUrl() }}" class="pagination-link" style="padding: 8px 16px; border-radius: 8px; background: linear-gradient(135deg, #006c35 0%, #008a43 100%); color: white; font-size: 14px; font-weight: 500; text-decoration: none; border: 1px solid #005a2d; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0, 108, 53, 0.1);">Previous</a>
                    @endif
                    
                    <span style="margin: 0 16px; padding: 8px 16px; border-radius: 8px; background: #f8fafc; color: #475569; font-size: 14px; font-weight: 600; border: 1px solid #e2e8f0;">
                        Page {{ $records->currentPage() }} of {{ $records->lastPage() }}
                    </span>
                    
                    @if ($records->hasMorePages())
                        <a href="{{ $records->appends(['tab' => 'nl-records'])->nextPageUrl() }}" class="pagination-link" style="padding: 8px 16px; border-radius: 8px; background: linear-gradient(135deg, #006c35 0%, #008a43 100%); color: white; font-size: 14px; font-weight: 500; text-decoration: none; border: 1px solid #005a2d; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0, 108, 53, 0.1);">Next</a>
                    @else
                        <span style="padding: 8px 16px; border-radius: 8px; background: #f1f5f9; color: #94a3b8; font-size: 14px; font-weight: 500; border: 1px solid #e2e8f0;">Next</span>
                    @endif
                </div>
            @endif
        </div>
    </form>
    <dialog class="editRecordDialog rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(640px,calc(100vw-2rem))]" id="recordEditDialog">
        <div class="px-5 pt-5 pb-3 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-900">Edit Record</h3>
        </div>
        <form class="editRecordform grid grid-cols-[auto_1fr] gap-x-4 gap-y-3 px-5 py-4 items-center" id="recordEditForm" method="POST">
            @csrf
            @method('PUT')
            <label for="farmerName" class="text-xs font-bold text-gray-600 text-right">Farmer Name:</label>
            <input type="text" id="farmerName" name="farmerName" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full auto-caps">
            
            <label for="editProvince" class="text-xs font-bold text-gray-600 text-right">Province:</label>
            <select name="province" id="editProvince" required class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                <option value="">Select Province</option>
                <option value="Aurora">Aurora</option>
                <option value="Nueva Ecija">Nueva Ecija</option>
            </select>
            
            <label for="editMunicipality" class="text-xs font-bold text-gray-600 text-right">Municipality:</label>
            <select name="municipality" id="editMunicipality" required disabled class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-gray-50">
                <option value="">Select Municipality</option>
            </select>
            
            <label for="editBarangay" class="text-xs font-bold text-gray-600 text-right">Barangay:</label>
            <select name="barangay" id="editBarangay" required disabled class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-gray-50">
                <option value="">Select Barangay</option>
            </select>
            
            <input type="hidden" name="address" id="editRecordAddress">
            
            <label for="line" class="text-xs font-bold text-gray-600 text-right">Line:</label>
            <select name="line" id="line" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                <option value="">Select Line</option>
                <option value="rice">rice</option>
                <option value="corn">corn</option>
                <option value="high-value">High-Value Crops</option>
                <option value="clti">CLTI</option>
                <option value="livestock">Livestock</option>
                <option value="non-crop">Non-Crop</option>
                <option value="fisheries">Fisheries</option>
            </select>
            
            <label for="program" class="text-xs font-bold text-gray-600 text-right">Program:</label>
            <select name="program" id="program" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                <option value="">Select Program</option>
                <option value="RSBSA">RSBSA</option>
                <option value="AGRI-SENSO">AGRI-SENSO</option>
                <option value="ACEF">ACEF</option>
                <option value="ANYO">ANYO</option>
                <option value="OTHER-LI LC">OTHER-LI LC</option>
                <option value="OTHER-LBP ACP">OTHER-LBP ACP</option>
                <option value="REGULAR">REGULAR</option>
                <option value="SELF-FINANCED">SELF-FINANCED</option>
                <option value="CFITF">CFITF</option>
            </select>
            
            <label for="source" class="text-xs font-bold text-gray-600 text-right">Source:</label>
            <select name="source" id="source" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                <option value="">Select Source</option>
                <option value="OD">OD</option>
                <option value="Email">Email</option>
                <option value="Facebook">Facebook</option>
            </select>
            
            <label for="causeOfDamage" class="text-xs font-bold text-gray-600 text-right">Cause of Damage:</label>
            <input type="text" id="causeOfDamage" name="causeOfDamage" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full auto-caps">
            
            <label for="modeOfPayment" class="text-xs font-bold text-gray-600 text-right">Mode of payment:</label>
            <select name="modeOfPayment" id="modeOfPayment" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                <option value="">Select Mode of payment</option>
                <option value="check">Check</option>
                <option value="palawan">Palawan Pay</option>
                <option value="gcash">GCash</option>
                <option value="not_indicated">Not indicated</option>
            </select>
            <label for="date_occurrence" class="text-xs font-bold text-gray-600 text-right">Date occurrence:</label>
            <input type="text" id="date_occurrence" name="date_occurrence" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            
            <label for="date_received" class="text-xs font-bold text-gray-600 text-right">Date received:</label>
            <input type="date" id="date_received" name="date_received" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            
            <label for="remarks" class="text-xs font-bold text-gray-600 text-right">Remarks - Care of:</label>
            <input type="text" id="remarks" name="remarks" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full auto-caps">
            
            <label for="transmittal_number" class="text-xs font-bold text-gray-600 text-right">Control Number:</label>
            <input type="text" id="transmittal_number" name="transmittal_number" placeholder="e.g., 2026-0420-001..." class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            
            <label for="admin_transmittal_number" class="text-xs font-bold text-gray-600 text-right">Admin Transmittal #:</label>
            <input type="text" id="admin_transmittal_number" name="admin_transmittal_number" placeholder="e.g., 001, 002, 003..." class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <label for="accounts" class="text-xs font-bold text-gray-600 text-right">Account (sender):</label>
            <input type="text" id="accounts" name="accounts" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full auto-caps">
            <label for="facebook_page_url" class="text-xs font-bold text-gray-600 text-right">FB page link:</label>
            <input type="url" id="facebook_page_url" name="facebook_page_url" placeholder="https://www.facebook.com/..." class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <div></div>
            <label for="clear_admin_transmittal_number" class="flex items-center gap-2 text-xs font-bold text-gray-600">
                <input type="checkbox" id="clear_admin_transmittal_number" name="clear_admin_transmittal_number" value="1" class="w-4 h-4 accent-pcic-700">
                Clear Admin Transmittal Number
            </label>
            
            <div></div>
            <div class="flex gap-2 pt-1">
                <button type="submit" class="h-9 px-4 rounded-lg bg-pcic-700 text-white text-xs font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Update Record</button>
                <button type="button" class="closeEditRecordDialog h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Close</button>
            </div>
        </form>
    </dialog>
    <dialog class="deleteRecordDialog rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(400px,calc(100vw-2rem))]">
        <div class="px-5 pt-5 pb-3 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-900">Delete Record</h3>
        </div>
        <form class="deleteRecordForm px-5 py-4 flex flex-col gap-3" method="POST">
            @csrf
            @method('DELETE')
            <p class="text-sm text-gray-600">Delete this record?</p>
            <p class="deleteRecordMessage text-sm font-semibold text-red-700"></p>
            <div class="flex gap-2 justify-end mt-2">
                <button type="submit" class="h-9 px-4 rounded-lg bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition-colors cursor-pointer">Confirm Delete</button>
                <button type="button" class="cancelDeleteRecord h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Cancel</button>
            </div>
        </form>
    </dialog>

    <!-- Bulk Delete Confirmation Dialog -->
    <dialog class="bulkDeleteDialog rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(440px,calc(100vw-2rem))]">
        <div class="px-5 pt-5 pb-3 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-900">Confirm Bulk Delete</h3>
        </div>
        <div class="px-5 py-4">
            <p class="text-sm text-gray-600 mb-2">The following records will be deleted:</p>
            <ul class="bulk-delete-list max-h-40 overflow-y-auto mb-3"></ul>
            <p class="text-sm font-semibold text-red-700 mb-3">Are you sure you want to proceed?</p>
            <div class="flex gap-2 justify-end">
                <button type="button" id="confirm-bulk-delete" class="h-9 px-4 rounded-lg bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition-colors cursor-pointer">Confirm Delete</button>
                <button type="button" class="cancelBulkDelete h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Cancel</button>
            </div>
        </div>
    </dialog>

    <!-- Edit Admin Dialog -->
    <dialog class="editAdminDialog rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(420px,calc(100vw-2rem))]">
        <form class="editAdminForm" method="POST">
            @csrf
            @method('PUT')
            <div class="px-5 pt-5 pb-3 border-b border-gray-100">
                <h3 class="text-base font-black text-gray-900">Edit Admin Credentials</h3>
            </div>
            <div class="px-5 py-4 flex flex-col gap-3">
                <label for="adminUsername" class="text-xs font-bold text-gray-600">Username:</label>
                <input type="text" id="adminUsername" name="username" required class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
                <label for="adminPassword" class="text-xs font-bold text-gray-600">Password:</label>
                <input type="password" id="adminPassword" name="password" required placeholder="Enter new password" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
                <div class="flex gap-2 justify-end mt-2">
                    <button type="submit" class="h-9 px-4 rounded-lg bg-pcic-700 text-white text-xs font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Update</button>
                    <button type="button" class="closeEditAdminDialog h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Cancel</button>
                </div>
            </div>
        </form>
    </dialog>

    <!-- Approve Officer Dialog -->
    <dialog class="approveOfficerDialog rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(400px,calc(100vw-2rem))]">
        <div class="px-5 pt-5 pb-3 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-900">Confirm Officer Approval</h3>
        </div>
        <div class="px-5 py-4">
            <p class="text-sm text-gray-600 mb-4">Are you sure you want to approve <strong id="approveOfficerName" class="text-gray-900"></strong>?</p>
            <form id="approveOfficerForm" method="POST">
                @csrf
                <div class="flex gap-2 justify-end">
                    <button type="submit" class="h-9 px-4 rounded-lg bg-pcic-700 text-white text-xs font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Confirm Approve</button>
                    <button type="button" class="closeApproveDialog h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Cancel</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Add Admin Dialog -->
    <dialog class="addAdminDialog rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(420px,calc(100vw-2rem))]">
        <form action="{{ route('admin.users.create') }}" method="POST">
            @csrf
            <div class="px-5 pt-5 pb-3 border-b border-gray-100">
                <h3 class="text-base font-black text-gray-900">Add New Admin User</h3>
            </div>
            <div class="px-5 py-4 flex flex-col gap-3">
                <label for="newAdminUsername" class="text-xs font-bold text-gray-600">Username:</label>
                <input type="text" id="newAdminUsername" name="username" required class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
                <label for="newAdminPassword" class="text-xs font-bold text-gray-600">Password:</label>
                <input type="password" id="newAdminPassword" name="password" required placeholder="Minimum 6 characters" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
                <div class="flex gap-2 justify-end mt-2">
                    <button type="submit" class="h-9 px-4 rounded-lg bg-pcic-700 text-white text-xs font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Create Admin</button>
                    <button type="button" class="closeAddAdminDialog h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Cancel</button>
                </div>
            </div>
        </form>
    </dialog>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sidebar Navigation Toggle
        const btnDashboard = document.getElementById('btn-dashboard');
        const btnNlRecords = document.getElementById('btn-nl-records');
        const dashboardSection = document.getElementById('dashboard-section');
        const nlRecordsSection = document.getElementById('nl-records-section');
        const adminActiveTabKey = 'admin_active_tab';
        const tableLoadingIndicator = document.getElementById('table-loading-indicator');

        function setTabInUrl(tabValue) {
            const params = new URLSearchParams(window.location.search);
            params.set('tab', tabValue);
            const nextUrl = `${window.location.pathname}?${params.toString()}`;
            window.history.replaceState({}, '', nextUrl);
        }

        function showDashboard() {
            dashboardSection.style.display = 'block';
            nlRecordsSection.style.display = 'none';
            btnDashboard.classList.add('active');
            btnNlRecords.classList.remove('active');
            localStorage.setItem(adminActiveTabKey, 'dashboard');
            setTabInUrl('dashboard');
        }

        function showNlRecords() {
            dashboardSection.style.display = 'none';
            nlRecordsSection.style.display = 'block';
            btnDashboard.classList.remove('active');
            btnNlRecords.classList.add('active');
            localStorage.setItem(adminActiveTabKey, 'nl-records');
            setTabInUrl('nl-records');
        }

        if (btnDashboard && btnNlRecords && dashboardSection && nlRecordsSection) {
            btnDashboard.addEventListener('click', function (event) {
                event.preventDefault();
                showDashboard();
            });

            btnNlRecords.addEventListener('click', function (event) {
                event.preventDefault();
                showNlRecords();
            });

            const tabFromUrl = new URLSearchParams(window.location.search).get('tab');
            const savedTab = localStorage.getItem(adminActiveTabKey);
            const defaultTab = tabFromUrl || savedTab;
            if (defaultTab === 'nl-records') {
                showNlRecords();
            } else {
                showDashboard();
            }
        } else {
            console.error('Required elements not found:', {
                btnDashboard: !!btnDashboard,
                btnNlRecords: !!btnNlRecords,
                dashboardSection: !!dashboardSection,
                nlRecordsSection: !!nlRecordsSection
            });
        }

        // User approvals modal (Email + OD)
        const openUserApprovalsModal = document.getElementById('openUserApprovalsModal');
        const userApprovalsModal = document.getElementById('userApprovalsModal');
        const closeUserApprovalsModal = document.querySelector('.closeUserApprovalsModal');

        if (openUserApprovalsModal && userApprovalsModal) {
            openUserApprovalsModal.addEventListener('click', function() {
                userApprovalsModal.showModal();
            });
        }

        if (closeUserApprovalsModal && userApprovalsModal) {
            closeUserApprovalsModal.addEventListener('click', function() {
                userApprovalsModal.close();
            });
        }

        // Admin Users Modal
        const openAdminUsersModal = document.getElementById('openAdminUsersModal');
        const adminUsersModal = document.getElementById('adminUsersModal');
        const closeAdminUsersModal = document.querySelector('.closeAdminUsersModal');

        if (openAdminUsersModal && adminUsersModal) {
            openAdminUsersModal.addEventListener('click', function() {
                adminUsersModal.showModal();
            });
        }

        if (closeAdminUsersModal && adminUsersModal) {
            closeAdminUsersModal.addEventListener('click', function() {
                adminUsersModal.close();
            });
        }

        // Active Users Modal
        const openActiveUsersModal = document.getElementById('openActiveUsersModal');
        const activeUsersModal = document.getElementById('activeUsersModal');
        const closeActiveUsersModal = document.querySelector('.closeActiveUsersModal');

        if (openActiveUsersModal && activeUsersModal) {
            openActiveUsersModal.addEventListener('click', function() {
                // Load active users data
                fetch('{{ route('admin.active-users') }}')
                    .then(response => response.json())
                    .then(data => {
                        const content = document.getElementById('activeUsersContent');
                        if (data.error) {
                            content.innerHTML = '<p class="text-sm text-red-600 text-center py-4">Error loading active users.</p>';
                        } else if (data.activeUsers && data.activeUsers.length > 0) {
                            let html = '<table class="w-full text-sm">';
                            html += '<thead><tr class="border-b border-gray-200"><th class="text-left py-2 px-3 font-bold text-gray-700">Name</th><th class="text-left py-2 px-3 font-bold text-gray-700">Channel</th><th class="text-left py-2 px-3 font-bold text-gray-700">Status</th><th class="text-left py-2 px-3 font-bold text-gray-700">Last Activity</th></tr></thead>';
                            html += '<tbody>';
                            data.activeUsers.forEach(user => {
                                html += '<tr class="border-b border-gray-100">';
                                html += '<td class="py-2 px-3 text-gray-800">' + user.name + '</td>';
                                html += '<td class="py-2 px-3 text-gray-600">' + user.channel + '</td>';
                                html += '<td class="py-2 px-3"><span class="px-2 py-1 rounded-full text-xs font-bold ' + (user.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700') + '">' + user.status + '</span></td>';
                                html += '<td class="py-2 px-3 text-gray-600">' + (user.last_activity ? new Date(user.last_activity).toLocaleString() : 'N/A') + '</td>';
                                html += '</tr>';
                            });
                            html += '</tbody></table>';
                            content.innerHTML = html;
                        } else {
                            content.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No active users found.</p>';
                        }
                        activeUsersModal.showModal();
                    })
                    .catch(error => {
                        console.error('Error loading active users:', error);
                        const content = document.getElementById('activeUsersContent');
                        content.innerHTML = '<p class="text-sm text-red-600 text-center py-4">Error loading active users.</p>';
                        activeUsersModal.showModal();
                    });
            });
        }

        if (closeActiveUsersModal && activeUsersModal) {
            closeActiveUsersModal.addEventListener('click', function() {
                activeUsersModal.close();
            });
        }

        // Cascading Dropdowns for Dashboard Filters
        const dashProvince = document.querySelector('select[name="dash_province"]');
        const dashMunicipality = document.querySelector('select[name="dash_municipality"]');
        const dashBarangay = document.querySelector('select[name="dash_barangay"]');

        // Cascading Dropdowns for Table Filters
        const tableProvince = document.querySelector('select[name="province"]');
        const tableMunicipality = document.querySelector('select[name="municipality"]');
        const tableBarangay = document.querySelector('select[name="barangay"]');

        // Location data - removed due to syntax errors
        const locationCsv = '';

        // Transmit-related DOM elements
        const transmitActionBtn = document.getElementById('transmit-selected-records');
        const transmitAllBox = document.getElementById('select-all-transmit');
        const transmitCheckboxes = document.querySelectorAll('.record-checkbox-transmit');
        const bulkSelectedCount = document.getElementById('bulk-selected-count');
        const recordCheckboxes = document.querySelectorAll('.record-checkbox');
        const selectAllBox = document.getElementById('select-all');

        // Get selected IDs from URL parameter
        function getSelectedIdsFromUrl() {
            const params = new URLSearchParams(window.location.search);
            const selectedIds = params.get('selected_transmit_ids');
            return selectedIds ? selectedIds.split(',').filter(id => id) : [];
        }

        // Update URL with selected IDs
        function updateUrlWithSelectedIds(selectedIds) {
            const url = new URL(window.location);
            if (selectedIds.length > 0) {
                url.searchParams.set('selected_transmit_ids', selectedIds.join(','));
            } else {
                url.searchParams.delete('selected_transmit_ids');
            }
            window.history.replaceState({}, '', url);
        }

        // Get selected delete IDs from URL parameter
        function getSelectedDeleteIdsFromUrl() {
            const params = new URLSearchParams(window.location.search);
            const selectedIds = params.get('selected_delete_ids');
            return selectedIds ? selectedIds.split(',').filter(id => id) : [];
        }

        // Update URL with selected delete IDs
        function updateUrlWithSelectedDeleteIds(selectedIds) {
            const url = new URL(window.location);
            if (selectedIds.length > 0) {
                url.searchParams.set('selected_delete_ids', selectedIds.join(','));
            } else {
                url.searchParams.delete('selected_delete_ids');
            }
            window.history.replaceState({}, '', url);
        }

        const updateTransmitButtonState = () => {
            const selectedIds = getSelectedIdsFromUrl();
            const totalSelected = selectedIds.length;
            if (transmitActionBtn) transmitActionBtn.disabled = totalSelected === 0;
            if (bulkSelectedCount) {
                bulkSelectedCount.textContent = totalSelected > 0 ? `${totalSelected} selected` : '';
            }
        };

        const updateDeleteButtonState = () => {
            const selectedIds = getSelectedDeleteIdsFromUrl();
            const totalSelected = selectedIds.length;
            if (deleteSelectedBtn) deleteSelectedBtn.disabled = totalSelected === 0;
            if (bulkSelectedCount) {
                bulkSelectedCount.textContent = totalSelected > 0 ? `${totalSelected} selected` : '';
            }
        };
        transmitCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const selectedIds = getSelectedIdsFromUrl();
                const checkboxValue = cb.value;
                if (this.checked) {
                    if (!selectedIds.includes(checkboxValue)) {
                        selectedIds.push(checkboxValue);
                    }
                } else {
                    const index = selectedIds.indexOf(checkboxValue);
                    if (index > -1) {
                        selectedIds.splice(index, 1);
                    }
                }
                updateUrlWithSelectedIds(selectedIds);
                updateTransmitButtonState();
            });
        });

        // Add event listeners for delete checkboxes
        recordCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const selectedIds = getSelectedDeleteIdsFromUrl();
                const checkboxValue = cb.value;
                if (this.checked) {
                    if (!selectedIds.includes(checkboxValue)) {
                        selectedIds.push(checkboxValue);
                    }
                } else {
                    const index = selectedIds.indexOf(checkboxValue);
                    if (index > -1) {
                        selectedIds.splice(index, 1);
                    }
                }
                updateUrlWithSelectedDeleteIds(selectedIds);
                updateDeleteButtonState();
            });
        });

        function populateSelect(selectElement, options, placeholder) {
            if (!selectElement) return;
            selectElement.innerHTML = '';
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = placeholder;
            selectElement.appendChild(defaultOption);
            options.forEach(option => {
                const optionItem = document.createElement('option');
                optionItem.value = option;
                optionItem.textContent = option;
                selectElement.appendChild(optionItem);
            });
        }

        function updateMunicipalities(provinceSelect, municipalitySelect, barangaySelect) {
            if (!provinceSelect || !municipalitySelect || !barangaySelect) return;
            if (!provinceSelect.value) {
                populateSelect(municipalitySelect, [], 'All Municipalities');
                populateSelect(barangaySelect, [], 'All Barangays');
                return;
            }
            const municipalities = Object.keys(locationData[provinceSelect.value] || {});
            populateSelect(municipalitySelect, municipalities, 'All Municipalities');
            populateSelect(barangaySelect, [], 'All Barangays');
        }

        function updateBarangays(provinceSelect, municipalitySelect, barangaySelect) {
            if (!provinceSelect || !municipalitySelect || !barangaySelect) return;
            if (!provinceSelect.value || !municipalitySelect.value) {
                populateSelect(barangaySelect, [], 'All Barangays');
                return;
            }
            const barangays = locationData[provinceSelect.value]?.[municipalitySelect.value] || [];
            populateSelect(barangaySelect, barangays, 'All Barangays');
        }

        // Dashboard filter cascading
        if (dashProvince && dashMunicipality && dashBarangay) {
            dashProvince.addEventListener('change', function() {
                updateMunicipalities(dashProvince, dashMunicipality, dashBarangay);
            });
            dashMunicipality.addEventListener('change', function() {
                updateBarangays(dashProvince, dashMunicipality, dashBarangay);
            });
            
            // Initialize on page load based on selected province
            if (dashProvince.value) {
                updateMunicipalities(dashProvince, dashMunicipality, dashBarangay);
                if (dashMunicipality.value) {
                    dashMunicipality.value = '{{ request("dash_municipality") }}';
                    updateBarangays(dashProvince, dashMunicipality, dashBarangay);
                    if (dashBarangay.value) {
                        dashBarangay.value = '{{ request("dash_barangay") }}';
                    }
                }
            }
        }

        // Table filter cascading
        if (tableProvince && tableMunicipality && tableBarangay) {
            tableProvince.addEventListener('change', function() {
                updateMunicipalities(tableProvince, tableMunicipality, tableBarangay);
            });
            tableMunicipality.addEventListener('change', function() {
                updateBarangays(tableProvince, tableMunicipality, tableBarangay);
            });
            
            // Initialize on page load based on selected province
            if (tableProvince.value) {
                updateMunicipalities(tableProvince, tableMunicipality, tableBarangay);
                if (tableMunicipality.value) {
                    tableMunicipality.value = '{{ request("municipality") }}';
                    updateBarangays(tableProvince, tableMunicipality, tableBarangay);
                    if (tableBarangay.value) {
                        tableBarangay.value = '{{ request("barangay") }}';
                    }
                }
            }
        }

        //Multiple selections
        const deleteMultipleBtn = document.getElementById('delete-multiple');
        const deleteSelectedBtn = document.getElementById('delete-selected');
        const transmitToggleBtn = document.getElementById('select-records-transmit');
        const bulkDeleteDialog = document.querySelector('.bulkDeleteDialog');
        const checkboxElements = document.querySelectorAll('.col-checkbox');
        const unassignedToggle = document.getElementById('unassigned-toggle');
        const transmitCheckboxElements = document.querySelectorAll('.col-checkbox-transmit');
        const selectedRecordIdsInput = document.getElementById('selected-record-ids');

        function showLoadingIndicator() {
            if (tableLoadingIndicator) {
                tableLoadingIndicator.style.display = 'block';
            }
        }

        // Auto-apply unassigned filter toggle and preserve current query filters
        unassignedToggle?.addEventListener('change', function () {
            const params = new URLSearchParams(window.location.search);
            if (this.checked) {
                params.set('unassigned_only', '1');
            } else {
                params.delete('unassigned_only');
            }
            params.set('tab', 'nl-records');
            showLoadingIndicator();
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        });

        // Toggle checkbox visibility for bulk delete
        deleteMultipleBtn?.addEventListener('click', function() {
            let firstElement = checkboxElements[0];
            let firstCheckbox = recordCheckboxes[0];
            let isHidden = (firstElement && firstElement.style.display === 'none') || 
                           (firstCheckbox && firstCheckbox.style.display === 'none');
            
            // Show/hide the table cells
            checkboxElements.forEach(cl => {
                cl.style.display = isHidden ? 'table-cell' : 'none';
            });
            // Also show/hide the checkbox inputs
            recordCheckboxes.forEach(cb => {
                cb.style.display = isHidden ? 'block' : 'none';
            });
            
            // Also show/hide the select all checkboxes in headers
            const selectAllBoxes = document.querySelectorAll('#select-all-transmit');
            selectAllBoxes.forEach(box => {
                box.style.display = isHidden ? 'block' : 'none';
            });

            if (isHidden) {
                this.textContent = 'Cancel Delete Multiple';
                this.style.backgroundColor = '#6c757d';
            } else {
                this.textContent = 'Delete Multiple';
                this.style.backgroundColor = '';
                clearSelectedDeleteIds();
            }
        });

        // toggle checkbox for transmitting records
        transmitToggleBtn?.addEventListener('click', function() {
            // Check if any checkbox element is visible or if the first checkbox input is visible
            let firstElement = transmitCheckboxElements[0];
            let firstCheckbox = transmitCheckboxes[0];
            let isHidden = (firstElement && firstElement.style.display === 'none') || 
                           (firstCheckbox && firstCheckbox.style.display === 'none');
            
            // Show/hide the table cells
            transmitCheckboxElements.forEach(el => {
                el.style.display = isHidden ? 'table-cell' : 'none';
            });
            // Also show/hide the checkbox inputs
            const currentCheckboxes = document.querySelectorAll('.record-checkbox-transmit');
            currentCheckboxes.forEach(cb => {
                cb.style.display = isHidden ? 'block' : 'none';
            });
            
            // Also show/hide the select all checkboxes in headers
            const selectAllTransmitBoxes = document.querySelectorAll('#select-all-transmit');
            selectAllTransmitBoxes.forEach(box => {
                box.style.display = isHidden ? 'block' : 'none';
            });
            
            // Update button text and style
            if (isHidden) {
                this.textContent = 'Cancel Selection';
                this.style.backgroundColor = '#6c757d'; // Gray out
            } else {
                this.textContent = 'Select Records for Transmit';
                this.style.backgroundColor = ''; // Reset color
                
                // Reset state when canceling
                const currentCheckboxes = document.querySelectorAll('.record-checkbox-transmit');
                currentCheckboxes.forEach(cb => cb.checked = false);
                if(transmitAllBox) transmitAllBox.checked = false;
                if(transmitActionBtn) transmitActionBtn.disabled = true;
                if (bulkSelectedCount) bulkSelectedCount.textContent = '';
            }
        });

        transmitAllBox?.addEventListener('change', function() {
            const currentCheckboxes = document.querySelectorAll('.record-checkbox-transmit');
            let selectedIds = getSelectedIdsFromUrl();
            
            if (this.checked) {
                // Add all current checkboxes to selection
                currentCheckboxes.forEach(cb => {
                    cb.checked = true;
                    if (!selectedIds.includes(cb.value)) {
                        selectedIds.push(cb.value);
                    }
                });
            } else {
                // Remove all current checkboxes from selection
                currentCheckboxes.forEach(cb => {
                    cb.checked = false;
                    const index = selectedIds.indexOf(cb.value);
                    if (index > -1) {
                        selectedIds.splice(index, 1);
                    }
                });
            }
            updateUrlWithSelectedIds(selectedIds);
            updateTransmitButtonState();
        });

        function loadSelectedTransmitIds() {
            const selectedIds = getSelectedIdsFromUrl();
            // Use current checkboxes from the DOM (after AJAX replacement)
            const currentCheckboxes = document.querySelectorAll('.record-checkbox-transmit');
            currentCheckboxes.forEach(cb => {
                // Handle both string and number comparison
                cb.checked = selectedIds.includes(cb.value) || selectedIds.includes(String(cb.value)) || selectedIds.includes(parseInt(cb.value));
            });
            updateTransmitButtonState();
        }

        function loadSelectedDeleteIds() {
            const selectedIds = getSelectedDeleteIdsFromUrl();
            // Use current checkboxes from the DOM (after AJAX replacement)
            const currentCheckboxes = document.querySelectorAll('.record-checkbox');
            currentCheckboxes.forEach(cb => {
                // Handle both string and number comparison
                cb.checked = selectedIds.includes(cb.value) || selectedIds.includes(String(cb.value)) || selectedIds.includes(parseInt(cb.value));
            });
            updateDeleteButtonState();
        }
        
        function updateSelectedRecordIdsInput() {
            const selectedIds = getSelectedIdsFromUrl();
            if (selectedRecordIdsInput) {
                selectedRecordIdsInput.value = selectedIds.join(',');
            }
        }
        
        function clearSelectedTransmitIds() {
            updateUrlWithSelectedIds([]);
            const currentCheckboxes = document.querySelectorAll('.record-checkbox-transmit');
            currentCheckboxes.forEach(cb => cb.checked = false);
            if (transmitAllBox) transmitAllBox.checked = false;
            updateTransmitButtonState();
            updateSelectedRecordIdsInput();
        }

        function clearSelectedDeleteIds() {
            updateUrlWithSelectedDeleteIds([]);
            const currentCheckboxes = document.querySelectorAll('.record-checkbox');
            currentCheckboxes.forEach(cb => cb.checked = false);
            if (selectAllBox) selectAllBox.checked = false;
            updateDeleteButtonState();
        }

        // Load saved selections on page load
        loadSelectedTransmitIds();
        loadSelectedDeleteIds();

        // AJAX Pagination - prevent page refresh
        const paginationLinks = document.querySelectorAll('.pagination-link');
        
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                let url = this.href;
                // Preserve selected transmit IDs in URL
                const selectedIds = getSelectedIdsFromUrl();
                if (selectedIds.length > 0) {
                    const urlObj = new URL(url, window.location.origin);
                    urlObj.searchParams.set('selected_transmit_ids', selectedIds.join(','));
                    url = urlObj.toString();
                }
                // Preserve selected delete IDs in URL
                const selectedDeleteIds = getSelectedDeleteIdsFromUrl();
                if (selectedDeleteIds.length > 0) {
                    const urlObj = new URL(url, window.location.origin);
                    urlObj.searchParams.set('selected_delete_ids', selectedDeleteIds.join(','));
                    url = urlObj.toString();
                }
                showLoadingIndicator();
                
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Parse the HTML to extract the new table content
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Replace table content
                    const newTableWrapper = doc.querySelector('#table-wrapper');
                    const currentTableWrapper = document.getElementById('table-wrapper');
                    if (newTableWrapper && currentTableWrapper) {
                        currentTableWrapper.innerHTML = newTableWrapper.innerHTML;
                    }
                    
                    // Replace pagination
                    const newPagination = doc.querySelector('#pagination-container');
                    const currentPagination = document.getElementById('pagination-container');
                    if (newPagination && currentPagination) {
                        currentPagination.innerHTML = newPagination.innerHTML;
                    }
                    
                    // Update URL without reload
                    window.history.pushState({}, '', url);

                    // Re-attach event listeners and restore checkbox state
                    reinitializeTableElements();
                    loadSelectedTransmitIds();
                    loadSelectedDeleteIds();

                    // Restore checkbox visibility based on toggle button state
                    const toggleBtn = document.getElementById('select-records-transmit');
                    const isCancelSelection = toggleBtn && toggleBtn.textContent.includes('Cancel');

                    if (isCancelSelection) {
                        const colCheckboxes = document.querySelectorAll('.col-checkbox-transmit');
                        const recordCheckboxes = document.querySelectorAll('.record-checkbox-transmit');
                        const selectAllBoxes = document.querySelectorAll('#select-all-transmit');

                        colCheckboxes.forEach(el => {
                            el.style.display = 'table-cell';
                        });
                        recordCheckboxes.forEach(cb => {
                            cb.style.display = 'block';
                        });
                        selectAllBoxes.forEach(box => {
                            box.style.display = 'block';
                        });
                    }

                    // Restore delete checkbox visibility based on toggle button state
                    const deleteToggleBtn = document.getElementById('delete-multiple');
                    const isCancelDelete = deleteToggleBtn && deleteToggleBtn.textContent.includes('Cancel');

                    if (isCancelDelete) {
                        const colDeleteCheckboxes = document.querySelectorAll('.col-checkbox');
                        const deleteRecordCheckboxes = document.querySelectorAll('.record-checkbox');
                        const selectAllDeleteBoxes = document.querySelectorAll('#select-all');

                        colDeleteCheckboxes.forEach(el => {
                            el.style.display = 'table-cell';
                        });
                        deleteRecordCheckboxes.forEach(cb => {
                            cb.style.display = 'block';
                        });
                        selectAllDeleteBoxes.forEach(box => {
                            box.style.display = 'block';
                        });
                    }
                    
                    // Re-attach pagination listeners
                    document.querySelectorAll('.pagination-link').forEach(link => {
                        link.addEventListener('click', arguments.callee);
                    });
                })
                .catch(error => {
                    window.location.href = url; // Fallback to regular navigation
                });
            });
        });
        
        function reinitializeTableElements() {
            // Re-select elements after table update
            const newTransmitCheckboxes = document.querySelectorAll('.record-checkbox-transmit');
            const newTransmitCheckboxElements = document.querySelectorAll('.col-checkbox-transmit');
            const newTransmitAllBox = document.getElementById('select-all-transmit');
            const newTransmitActionBtn = document.getElementById('transmit-selected-records');
            const newBulkSelectedCount = document.getElementById('bulk-selected-count');
            const newTransmitToggleBtn = document.getElementById('select-records-transmit');
            const newDeleteCheckboxes = document.querySelectorAll('.record-checkbox');
            const newDeleteCheckboxElements = document.querySelectorAll('.col-checkbox');
            const newSelectAllBox = document.getElementById('select-all');
            const newDeleteSelectedBtn = document.getElementById('delete-selected');

            // Re-attach event listeners
            newTransmitCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const selectedIds = getSelectedIdsFromUrl();
                    const checkboxValue = cb.value;
                    if (this.checked) {
                        if (!selectedIds.includes(checkboxValue)) {
                            selectedIds.push(checkboxValue);
                        }
                    } else {
                        const index = selectedIds.indexOf(checkboxValue);
                        if (index > -1) {
                            selectedIds.splice(index, 1);
                        }
                    }
                    updateUrlWithSelectedIds(selectedIds);
                    updateTransmitButtonState();
                });
            });

            newTransmitAllBox?.addEventListener('change', function() {
                let selectedIds = getSelectedIdsFromUrl();

                if (this.checked) {
                    // Add all current checkboxes to selection
                    newTransmitCheckboxes.forEach(cb => {
                        cb.checked = true;
                        if (!selectedIds.includes(cb.value)) {
                            selectedIds.push(cb.value);
                        }
                    });
                } else {
                    // Remove all current checkboxes from selection
                    newTransmitCheckboxes.forEach(cb => {
                        cb.checked = false;
                        const index = selectedIds.indexOf(cb.value);
                        if (index > -1) {
                            selectedIds.splice(index, 1);
                        }
                    });
                }
                updateUrlWithSelectedIds(selectedIds);
                updateTransmitButtonState();
            });

            // Re-attach event listeners for delete checkboxes
            newDeleteCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const selectedIds = getSelectedDeleteIdsFromUrl();
                    const checkboxValue = cb.value;
                    if (this.checked) {
                        if (!selectedIds.includes(checkboxValue)) {
                            selectedIds.push(checkboxValue);
                        }
                    } else {
                        const index = selectedIds.indexOf(checkboxValue);
                        if (index > -1) {
                            selectedIds.splice(index, 1);
                        }
                    }
                    updateUrlWithSelectedDeleteIds(selectedIds);
                    updateDeleteButtonState();
                });
            });

            newSelectAllBox?.addEventListener('change', function() {
                let selectedIds = getSelectedDeleteIdsFromUrl();

                if (this.checked) {
                    // Add all current checkboxes to selection
                    newDeleteCheckboxes.forEach(cb => {
                        cb.checked = true;
                        if (!selectedIds.includes(cb.value)) {
                            selectedIds.push(cb.value);
                        }
                    });
                } else {
                    // Remove all current checkboxes from selection
                    newDeleteCheckboxes.forEach(cb => {
                        cb.checked = false;
                        const index = selectedIds.indexOf(cb.value);
                        if (index > -1) {
                            selectedIds.splice(index, 1);
                        }
                    });
                }
                updateUrlWithSelectedDeleteIds(selectedIds);
                updateDeleteButtonState();
            });

            newTransmitToggleBtn?.addEventListener('click', function() {
                // Toggle logic here (same as before)
                let firstElement = newTransmitCheckboxElements[0];
                let firstCheckbox = newTransmitCheckboxes[0];
                let isHidden = (firstElement && firstElement.style.display === 'none') || 
                               (firstCheckbox && firstCheckbox.style.display === 'none');
                
                newTransmitCheckboxElements.forEach(el => {
                    el.style.display = isHidden ? 'table-cell' : 'none';
                });
                newTransmitCheckboxes.forEach(cb => {
                    cb.style.display = isHidden ? 'block' : 'none';
                });
                
                const selectAllTransmitBoxes = document.querySelectorAll('#select-all-transmit');
                selectAllTransmitBoxes.forEach(box => {
                    box.style.display = isHidden ? 'block' : 'none';
                });
                
                if (isHidden) {
                    this.textContent = 'Cancel Selection';
                    this.style.backgroundColor = '#6c757d';
                } else {
                    this.textContent = 'Select Records for Transmit';
                    this.style.backgroundColor = '';
                    newTransmitCheckboxes.forEach(cb => cb.checked = false);
                    if(newTransmitAllBox) newTransmitAllBox.checked = false;
                    if(newTransmitActionBtn) newTransmitActionBtn.disabled = true;
                    if (newBulkSelectedCount) newBulkSelectedCount.textContent = '';
                }
            });
        }

        // Clear selections when canceling
        transmitToggleBtn?.addEventListener('click', function() {
            const currentCheckboxElements = document.querySelectorAll('.col-checkbox-transmit');
            const isHidden = (currentCheckboxElements[0]?.style.display === 'none') || 
                           (transmitCheckboxes[0]?.style.display === 'none');
            if (!isHidden) {
                clearSelectedTransmitIds();
            }
        });

        // 4. Handle Submission to Print Preview - OPEN IN NEW TAB
        transmitActionBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            const selectedIds = getSelectedIdsFromUrl();

            if (selectedIds.length > 0) {
                // Clear URL selections before transmitting
                updateUrlWithSelectedIds([]);

                // Open print preview in new tab with IDs as URL parameter
                const printPreviewUrl = "{{ route('admin.print-preview') }}?ids=" + encodeURIComponent(selectedIds.join(','));
                window.open(printPreviewUrl, '_blank');
                this.style.backgroundColor = '#6c757d'; // Gray out
            } else {
                this.textContent = 'Delete Multiple';
                this.style.backgroundColor = ''; // Reset color
                
                // Reset state when canceling
                recordCheckboxes.forEach(cb => cb.checked = false);
                if(selectAllBox) selectAllBox.checked = false;
                if(deleteSelectedBtn) deleteSelectedBtn.disabled = true;
            }
        });

        // 3. Select All Logic
        selectAllBox?.addEventListener('change', function() {
            let selectedIds = getSelectedDeleteIdsFromUrl();
            const currentCheckboxes = document.querySelectorAll('.record-checkbox');

            if (this.checked) {
                // Add all current checkboxes to selection
                currentCheckboxes.forEach(cb => {
                    cb.checked = true;
                    if (!selectedIds.includes(cb.value)) {
                        selectedIds.push(cb.value);
                    }
                });
            } else {
                // Remove all current checkboxes from selection
                currentCheckboxes.forEach(cb => {
                    cb.checked = false;
                    const index = selectedIds.indexOf(cb.value);
                    if (index > -1) {
                        selectedIds.splice(index, 1);
                    }
                });
            }
            updateUrlWithSelectedDeleteIds(selectedIds);
            updateDeleteButtonState();
        });

        // 4. Verification Prompt (Your existing dialog logic)
        deleteSelectedBtn?.addEventListener('click', function() {
            if (!this.disabled) {
                const selectedIds = getSelectedDeleteIdsFromUrl();
                if (selectedIds.length > 0) {
                    // Show count instead of names to avoid delay
                    const listElement = document.querySelector('.bulk-delete-list');
                    listElement.innerHTML = '';
                    const li = document.createElement('li');
                    li.textContent = `${selectedIds.length} record(s) will be deleted`;
                    li.className = 'text-sm text-gray-700 py-1 font-semibold';
                    listElement.appendChild(li);
                    bulkDeleteDialog?.showModal();
                }
            }
        });

        document.getElementById('confirm-bulk-delete')?.addEventListener('click', () => {
            const selectedIds = getSelectedDeleteIdsFromUrl();
            if (selectedRecordIdsInput) {
                selectedRecordIdsInput.value = selectedIds.join(',');
            }
            // Clear URL selections before deleting
            updateUrlWithSelectedDeleteIds([]);
            document.getElementById('bulk-form')?.submit();
        });

        document.querySelector('.cancelBulkDelete')?.addEventListener('click', () => {
            bulkDeleteDialog?.close();
        });

        // Show inline loading state for table-related submit actions
        document.querySelectorAll('form[action="{{ route('admin') }}"], #bulk-form').forEach(formEl => {
            formEl.addEventListener('submit', showLoadingIndicator);
        });

        // Quality-of-life shortcuts: D (dashboard), N (NL), / (focus farmer search)
        document.addEventListener('keydown', function (event) {
            if (event.target && ['INPUT', 'TEXTAREA', 'SELECT'].includes(event.target.tagName)) {
                return;
            }
            if (event.key.toLowerCase() === 'd') {
                showDashboard();
            } else if (event.key.toLowerCase() === 'n') {
                showNlRecords();
            } else if (event.key === '/') {
                event.preventDefault();
                showNlRecords();
                document.querySelector('input[name="farmerName"]')?.focus();
            }
        });

        // Edit Record Modal - Use event delegation to handle dynamically loaded buttons
        const editRecordDialog = document.getElementById('recordEditDialog');
        const closeEditRecordModal = document.querySelector('.closeEditRecordDialog');
        const editRecordForm = document.getElementById('recordEditForm');

        // Use event delegation on the document to handle edit button clicks
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('editButton') || e.target.closest('.editButton')) {
                const button = e.target.classList.contains('editButton') ? e.target : e.target.closest('.editButton');

                if (!editRecordDialog || !editRecordForm) {
                    console.error('Edit dialog or form not found');
                    return;
                }

                try {
                    const recordId = button.getAttribute('data-id');
                    const farmerName = button.getAttribute('data-farmer-name');
                    const province = button.getAttribute('data-province');
                    const municipality = button.getAttribute('data-municipality');
                    const barangay = button.getAttribute('data-barangay');
                    const address = button.getAttribute('data-address');
                    const program = button.getAttribute('data-program');
                    const line = button.getAttribute('data-line');
                    const causeOfDamage = button.getAttribute('data-cause-of-damage');
                    const modeOfPayment = button.getAttribute('data-mode-of-payment');
                    const accounts = button.getAttribute('data-accounts');
                    const fbPageUrl = button.getAttribute('data-fb-page-url');
                    const dateOccurrence = button.getAttribute('data-date-occurrence');
                    const dateReceived = button.getAttribute('data-date-received');
                    const remarks = button.getAttribute('data-remarks');
                    const source = button.getAttribute('data-source');
                    const transmittalNumber = button.getAttribute('data-transmittal-number');
                    const adminTransmittalNumber = button.getAttribute('data-admin-transmittal-number');

                    // Populate form fields
                    const farmerNameField = editRecordForm.querySelector('#farmerName');
                    const editProvinceField = editRecordForm.querySelector('#editProvince');
                    const editMunicipalityField = editRecordForm.querySelector('#editMunicipality');
                    const editBarangayField = editRecordForm.querySelector('#editBarangay');
                    const addressField = editRecordForm.querySelector('#editRecordAddress');
                    const programField = editRecordForm.querySelector('#program');
                    const lineField = editRecordForm.querySelector('#line');
                    const causeOfDamageField = editRecordForm.querySelector('#causeOfDamage');
                    const modeOfPaymentField = editRecordForm.querySelector('#modeOfPayment');
                    const accountsField = editRecordForm.querySelector('#accounts');
                    const fbPageUrlField = editRecordForm.querySelector('#facebook_page_url');
                    const dateOccurrenceField = editRecordForm.querySelector('#date_occurrence');
                    const dateReceivedField = editRecordForm.querySelector('#date_received');
                    const remarksField = editRecordForm.querySelector('#remarks');
                    const transmittalNumberField = editRecordForm.querySelector('#transmittal_number');
                    const adminTransmittalNumberField = editRecordForm.querySelector('#admin_transmittal_number');
                    const sourceField = editRecordForm.querySelector('#source');

                    if (farmerNameField) farmerNameField.value = farmerName || '';
                    if (editProvinceField) editProvinceField.value = province || '';
                    if (editMunicipalityField) editMunicipalityField.value = municipality || '';
                    if (editBarangayField) editBarangayField.value = barangay || '';
                    if (addressField) addressField.value = address || '';
                    if (programField) programField.value = program || '';
                    if (lineField) lineField.value = line || '';
                    if (causeOfDamageField) causeOfDamageField.value = causeOfDamage || '';
                    if (modeOfPaymentField) modeOfPaymentField.value = modeOfPayment || '';
                    if (accountsField) accountsField.value = accounts || '';
                    if (fbPageUrlField) fbPageUrlField.value = fbPageUrl || '';
                    if (dateOccurrenceField) dateOccurrenceField.value = dateOccurrence || '';
                    if (dateReceivedField) dateReceivedField.value = dateReceived || '';
                    if (remarksField) remarksField.value = remarks || '';
                    if (transmittalNumberField) transmittalNumberField.value = transmittalNumber || '';
                    if (adminTransmittalNumberField) adminTransmittalNumberField.value = adminTransmittalNumber || '';
                    if (sourceField) sourceField.value = source || '';

                    // Set form action
                    editRecordForm.action = '/records/' + recordId;

                    // Enable municipality and barangay selects based on province
                    if (editProvinceField && editMunicipalityField && editBarangayField) {
                        if (editProvinceField.value) {
                            editMunicipalityField.disabled = false;
                            // Trigger municipality update
                            const event = new Event('change');
                            editProvinceField.dispatchEvent(event);

                            if (editMunicipalityField.value) {
                                editBarangayField.disabled = false;
                                // Trigger barangay update
                                const municipalityEvent = new Event('change');
                                editMunicipalityField.dispatchEvent(municipalityEvent);
                            }
                        }
                    }

                    editRecordDialog.showModal();
                } catch (error) {
                    console.error('Error opening edit dialog:', error);
                }
            }
        });

        if (closeEditRecordModal && editRecordDialog) {
            closeEditRecordModal.addEventListener('click', function() {
                editRecordDialog.close();
            });
        }

        // Handle form submission for edit record
        if (editRecordForm) {
            editRecordForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Update hidden address field before submission
                const editProvinceField = editRecordForm.querySelector('#editProvince');
                const editMunicipalityField = editRecordForm.querySelector('#editMunicipality');
                const editBarangayField = editRecordForm.querySelector('#editBarangay');
                const addressField = editRecordForm.querySelector('#editRecordAddress');
                if (editProvinceField && editMunicipalityField && editBarangayField && addressField) {
                    addressField.value = [editBarangayField.value, editMunicipalityField.value, editProvinceField.value]
                        .filter(Boolean)
                        .join(', ');
                }

                const formData = new FormData(editRecordForm);
                // Ensure _method parameter is included for PUT request
                if (!formData.has('_method')) {
                    formData.append('_method', 'PUT');
                }
                const formAction = editRecordForm.action;

                console.log('Form action:', formAction);
                console.log('Form data:', Array.from(formData.entries()));

                // Show loading state
                const submitButton = editRecordForm.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Updating...';
                }

                fetch(formAction, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(function(response) {
                    console.log('Response status:', response.status);
                    console.log('Response ok:', response.ok);
                    return response.text().then(function(text) {
                        console.log('Response text:', text);
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Failed to parse JSON:', e);
                            throw new Error('Invalid JSON response');
                        }
                    });
                })
                .then(function(data) {
                    console.log('Parsed data:', data);
                    if (data.success) {
                        // Close modal
                        editRecordDialog.close();
                        // Reload page to show updated data
                        window.location.reload();
                    } else {
                        alert('Error updating record: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    alert('Error updating record. Please try again.');
                })
                .finally(function() {
                    // Reset button state
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.textContent = 'Update Record';
                    }
                });
            });
        }

    });
        
    // Dashboard province slicer (Aurora / Nueva Ecija)
        document.addEventListener('click', function (e) {
            const btn = e.target?.closest?.('.dashProvinceSlicer');
            if (!btn) return;
            const select = document.getElementById('dashProvince');
            if (!select) return;
            select.value = btn.getAttribute('data-value') ?? '';
            // Submit the dashboard filter form (the one containing dash_province)
            const form = select.closest('form');
            form?.submit();
        });

    // Automatic logout on browser/tab close
    window.addEventListener('beforeunload', function(e) {
        // Send logout request using navigator.sendBeacon for reliable delivery
        navigator.sendBeacon('{{ route('admin.logout') }}', new FormData());
    });
</script>

    {{-- Real-time pending approvals polling - removed due to syntax errors --}}

    </div> <!-- END NL Records Section -->

        </main>
    </div>

@endsection

