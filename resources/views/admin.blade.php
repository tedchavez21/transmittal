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
                <div class="heading">
                    <h1>PCIC Admin Portal</h1>
                    <p>Monitoring • approvals • transmittals</p>
                </div>
                <div class="actions">
                    <form action="{{ route('admin.logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="h-8 px-3 rounded-lg border border-gray-200 bg-white text-xs font-bold text-gray-600 hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition-colors cursor-pointer" title="Logout">Logout</button>
                    </form>
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

            $dash3MetaText = $summaryProgram . ' • ' . ($summaryDate ? $summaryDate : 'All dates');

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
                            <h3 class="card-title">By Line</h3>
                            <p class="card-subtitle">NL count per line</p>
                        </div>
                    </div>
                    <div class="card-body dash3-chart-body">
                        @foreach($recordsByLine as $line => $count)
                        <div class="dash3-chart-bar-row">
                            <span class="dash3-chart-label">{{ $line }}</span>
                            <div class="dash3-chart-bar-track">
                                <div class="dash3-chart-bar-fill dash3-chart-bar-fill--line" style="width: {{ $chartMax > 0 ? round($count / $chartMax * 100) : 0 }}%"></div>
                            </div>
                            <span class="dash3-chart-value">{{ number_format($count) }}</span>
                        </div>
                        @endforeach
                        @if($recordsByLine->isEmpty())
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
    <div class="admin-card" style="margin-bottom: 5px;">
        <div class="card-body" style="padding: 8px 12px;">
            <label class="admin-toggle" style="font-size: 12px; margin: 0;">
                <input type="checkbox" id="unassigned-toggle" {{ request('unassigned_only') ? 'checked' : '' }}>
                <span style="font-size: 12px;">Show only records without admin transmittal numbers</span>
            </label>
        </div>
    </div>

    <!-- TABLE FILTERS -->
    <div class="no-print table-filters" style="margin-bottom: 5px; padding: 8px 12px; border: 1px solid #ccc; background: #fff;">
        <h3 style="margin: 0 0 8px 0; font-size: 14px;">TABLE FILTERS</h3>
        <form method="GET" action="{{ route('admin') }}" style="margin: 0;">
            <input type="hidden" name="tab" value="nl-records">
            <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Search Farmer</label>
                    <input type="text" name="farmerName" value="{{ request('farmerName') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Search Encoder</label>
                    <input type="text" name="encoderName" value="{{ request('encoderName') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Program</label>
                    <select name="program" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Programs</option>
                        @foreach($allPrograms as $program)
                        <option value="{{ $program }}" {{ request('program') == $program ? 'selected' : '' }}>{{ $program }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Line</label>
                    <select name="line" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Lines</option>
                        @foreach($allLines as $line)
                        <option value="{{ $line }}" {{ request('line') == $line ? 'selected' : '' }}>{{ $line }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Province</label>
                    <select name="province" id="tableProvince" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Provinces</option>
                        <option value="Aurora" {{ request('province') == 'Aurora' ? 'selected' : '' }}>Aurora</option>
                        <option value="Nueva Ecija" {{ request('province') == 'Nueva Ecija' ? 'selected' : '' }}>Nueva Ecija</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Municipality</label>
                    <select name="municipality" id="tableMunicipality" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Municipalities</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Barangay</label>
                    <select name="barangay" id="tableBarangay" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Barangays</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Source</label>
                    <select name="source" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Sources</option>
                        @foreach($allSources as $source)
                        <option value="{{ $source }}" {{ request('source') == $source ? 'selected' : '' }}>{{ $source }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Mode of Payment</label>
                    <select name="modeOfPayment" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Modes</option>
                        @foreach($allModes as $mode)
                        <option value="{{ $mode }}" {{ request('modeOfPayment') == $mode ? 'selected' : '' }}>{{ $mode }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Account</label>
                    <input type="text" name="accounts" value="{{ request('accounts') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;" placeholder="Email / username">
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Control #</label>
                    <input type="text" name="transmittal_number" value="{{ request('transmittal_number') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Admin Transmittal</label>
                    <input type="text" name="admin_transmittal_number" value="{{ request('admin_transmittal_number') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Date Received</label>
                    <select name="date_received_type" id="tableDateReceivedType" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Dates</option>
                        <option value="single" {{ request('date_received_type') == 'single' ? 'selected' : '' }}>Specific Date</option>
                        <option value="range" {{ request('date_received_type') == 'range' ? 'selected' : '' }}>Date Range</option>
                    </select>
                </div>
                <div id="tableDateReceivedSingleWrap" style="display: {{ request('date_received_type') == 'single' ? 'flex' : 'none' }}; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Date</label>
                    <input type="date" name="date_single" value="{{ request('date_single') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;" {{ request('date_received_type') == 'single' ? '' : 'disabled' }}>
                </div>
                <div id="tableDateReceivedFromWrap" style="display: {{ request('date_received_type') == 'range' ? 'flex' : 'none' }}; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;" {{ request('date_received_type') == 'range' ? '' : 'disabled' }}>
                </div>
                <div id="tableDateReceivedToWrap" style="display: {{ request('date_received_type') == 'range' ? 'flex' : 'none' }}; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;" {{ request('date_received_type') == 'range' ? '' : 'disabled' }}>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Cause of Damage</label>
                    <input type="text" name="causeOfDamage" value="{{ request('causeOfDamage') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Remarks</label>
                    <input type="text" name="remarks" value="{{ request('remarks') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Date of occurrence</label>
                    <input type="text" name="date_occurrence" value="{{ request('date_occurrence') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;" placeholder="Search text">
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Rows per page</label>
                    <select name="per_page" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', '50') == '50' ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <button type="submit" style="padding: 8px 16px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Filter Table</button>
                <a href="{{ route('admin') }}" style="padding: 8px 16px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block; line-height: 20px;">Clear</a>
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
        </script>
    </div>

    <div class="admin-card no-print" style="margin-bottom: 14px;">
        <div class="card-body">
            <div class="admin-toolbar">
                <div class="admin-toolbar-title">NL Records</div>
                <div class="admin-toolbar-actions">
                    <button id="delete-multiple" class="btn btn-danger">Delete Multiple</button>
                    <button id="delete-selected" class="btn btn-warning" disabled>Delete Selected</button>
                    <button type="button" id="select-records-transmit" class="btn btn-info">Select for Transmit</button>
                    <button type="button" id="transmit-selected-records" class="btn btn-success" disabled>Transmit Selected</button>
                    <span id="bulk-selected-count" class="admin-toolbar-count"></span>
                </div>
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
                <div id="pagination-container" style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                    @if ($records->onFirstPage())
                        <span style="color: #ccc;">Previous</span>
                    @else
                        <a href="{{ $records->appends(['tab' => 'nl-records'])->previousPageUrl() }}" class="pagination-link" style="color: #007bff; text-decoration: none;">Previous</a>
                    @endif
                    
                    <span style="margin: 0 10px;">
                        Page {{ $records->currentPage() }} of {{ $records->lastPage() }}
                    </span>
                    
                    @if ($records->hasMorePages())
                        <a href="{{ $records->appends(['tab' => 'nl-records'])->nextPageUrl() }}" class="pagination-link" style="color: #007bff; text-decoration: none;">Next</a>
                    @else
                        <span style="color: #ccc;">Next</span>
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
                // Load active users functionality removed due to syntax error
                activeUsersModal.showModal();
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

        const updateTransmitButtonState = () => {
            const selectedIds = getSelectedIdsFromUrl();
            const totalSelected = selectedIds.length;
            if (transmitActionBtn) transmitActionBtn.disabled = totalSelected === 0;
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
        const recordCheckboxes = document.querySelectorAll('.record-checkbox');
        const selectAllBox = document.getElementById('select-all');
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
                recordCheckboxes.forEach(cb => cb.checked = false);
                if (selectAllBox) selectAllBox.checked = false;
                if (deleteSelectedBtn) deleteSelectedBtn.disabled = true;
                if (selectedRecordIdsInput) selectedRecordIdsInput.value = '';
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

        // Load saved selections on page load
        loadSelectedTransmitIds();

        // AJAX Pagination - prevent page refresh
        const paginationLinks = document.querySelectorAll('.pagination-link');
        
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                let url = this.href;
                // Preserve selected IDs in URL
                const selectedIds = getSelectedIdsFromUrl();
                if (selectedIds.length > 0) {
                    const urlObj = new URL(url, window.location.origin);
                    urlObj.searchParams.set('selected_transmit_ids', selectedIds.join(','));
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

        // 2. Enable/Disable "Delete Selected" based on checkmarks
        const updateDeleteButtonState = () => {
            const selectedCount = Array.from(recordCheckboxes).filter(cb => cb.checked).length;
            
            if (deleteSelectedBtn) {
                deleteSelectedBtn.disabled = selectedCount === 0;
            }
            
            if (bulkSelectedCount) {
                bulkSelectedCount.textContent = selectedCount > 0 ? selectedCount + " selected" : "";
            }
        };

        recordCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateDeleteButtonState);
        });

        // 3. Select All Logic
        selectAllBox?.addEventListener('change', function() {
            recordCheckboxes.forEach(cb => cb.checked = this.checked);
            updateDeleteButtonState();
        });

        // 4. Verification Prompt (Your existing dialog logic)
        deleteSelectedBtn?.addEventListener('click', function() {
            if (!this.disabled) bulkDeleteDialog?.showModal();
        });

        document.getElementById('confirm-bulk-delete')?.addEventListener('click', () => {
            const selectedIds = Array.from(document.querySelectorAll('.record-checkbox:checked')).map(cb => cb.value);
            if (selectedRecordIdsInput) {
                selectedRecordIdsInput.value = selectedIds.join(',');
            }
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
</script>

    {{-- Real-time pending approvals polling - removed due to syntax errors --}}

    </div> <!-- END NL Records Section -->

        </main>
    </div>

@endsection

