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
                        <span class="icon" aria-hidden="true"><img src="/images/active-users.svg" alt="" width="18" height="18"></span>
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
        
        <!-- Horizontal scrollbar container - sticky position -->
        <div id="horizontal-scrollbar-container" style="position: sticky; top: 0; width: 100%; height: 20px; overflow-x: scroll; overflow-y: hidden; margin-bottom: 10px; background: #f5f5f5; border: 1px solid #ddd; z-index: 10;">
            <div id="horizontal-scrollbar-content" style="height: 1px; width: 2000px;"></div>
        </div>
        
        <!-- Main table container with proper sticky header support -->
        <div id="table-container" style="width: 100%; margin-bottom: 20px; border: 1px solid #ccc; position: relative; padding: 0;">
            <div id="table-wrapper" class="table-wrapper" style="width: 100%;">
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
                <div style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                    @if ($records->onFirstPage())
                        <span style="color: #ccc;">Previous</span>
                    @else
                        <a href="{{ $records->previousPageUrl() }}" style="color: #007bff; text-decoration: none;">Previous</a>
                    @endif
                    
                    <span style="margin: 0 10px;">
                        Page {{ $records->currentPage() }} of {{ $records->lastPage() }}
                    </span>
                    
                    @if ($records->hasMorePages())
                        <a href="{{ $records->nextPageUrl() }}" style="color: #007bff; text-decoration: none;">Next</a>
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
                loadActiveUsers();
                activeUsersModal.showModal();
            });
        }

        if (closeActiveUsersModal && activeUsersModal) {
            closeActiveUsersModal.addEventListener('click', function() {
                activeUsersModal.close();
            });
        }

        function loadActiveUsers() {
            const contentDiv = document.getElementById('activeUsersContent');
            
            // Show loading state
            contentDiv.innerHTML = `
                <div class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-pcic-700"></div>
                    <p class="text-sm text-gray-500 mt-2">Loading active users...</p>
                </div>
            `;

            // Fetch active users from server
            fetch('/admin/active-users')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.activeUsers && data.activeUsers.length > 0) {
                        displayActiveUsers(data.activeUsers);
                    } else if (data.success && (!data.activeUsers || data.activeUsers.length === 0)) {
                        contentDiv.innerHTML = `
                            <div class="text-center py-8">
                                <div class="text-gray-400 mb-2">
                                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-500">No active users found</p>
                            </div>
                        `;
                    } else {
                        // Show error message from server if available
                        const errorMessage = data.error || data.message || 'Unknown error occurred';
                        contentDiv.innerHTML = `
                            <div class="text-center py-8">
                                <div class="text-red-400 mb-2">
                                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-red-600 font-medium">Error: ${errorMessage}</p>
                                <p class="text-xs text-gray-500 mt-1">Please try again or contact support</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading active users:', error);
                    contentDiv.innerHTML = `
                        <div class="text-center py-8">
                            <div class="text-red-400 mb-2">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-red-600 font-medium">Network Error</p>
                            <p class="text-xs text-gray-500 mt-1">${error.message}</p>
                            <button onclick="loadActiveUsers()" class="mt-3 px-4 py-2 bg-pcic-700 text-white text-sm rounded-lg hover:bg-pcic-800 transition-colors">
                                Retry
                            </button>
                        </div>
                    `;
                });
        }

        function displayActiveUsers(activeUsers) {
            const contentDiv = document.getElementById('activeUsersContent');
            
            let html = `
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2 px-3 font-semibold text-gray-700">User</th>
                                <th class="text-left py-2 px-3 font-semibold text-gray-700">Channel</th>
                                <th class="text-left py-2 px-3 font-semibold text-gray-700">Last Activity</th>
                                <th class="text-left py-2 px-3 font-semibold text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            activeUsers.forEach(user => {
                const channelIcon = getChannelIcon(user.channel);
                const statusBadge = getStatusBadge(user.status);
                const lastActivity = formatLastActivity(user.last_activity);
                
                html += `
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-pcic-100 flex items-center justify-center">
                                    <span class="text-xs font-semibold text-pcic-700">${user.name.charAt(0).toUpperCase()}</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">${user.name}</div>
                                    <div class="text-xs text-gray-500">${user.email}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-3">
                            <div class="flex items-center gap-2">
                                ${channelIcon}
                                <span class="text-gray-700">${user.channel}</span>
                            </div>
                        </td>
                        <td class="py-3 px-3 text-gray-600 text-sm">${lastActivity}</td>
                        <td class="py-3 px-3">${statusBadge}</td>
                    </tr>
                `;
            });

            html += `
                        </tbody>
                    </table>
                </div>
            `;

            contentDiv.innerHTML = html;
        }

        function getChannelIcon(channel) {
            const icons = {
                'Admin': '<svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>',
                'Facebook': '<svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path></svg>',
                'Email': '<svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
                'Officer of the Day': '<svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>'
            };
            return icons[channel] || '<svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>';
        }

        function getStatusBadge(status) {
            const badges = {
                'online': '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Online</span>',
                'active': '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Active</span>',
                'idle': '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Idle</span>',
                'away': '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Away</span>'
            };
            return badges[status] || badges['idle'];
        }

        function formatLastActivity(lastActivity) {
            if (!lastActivity) return 'Unknown';
            
            const date = new Date(lastActivity);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            
            if (diffMins < 1) return 'Just now';
            if (diffMins < 60) return `${diffMins} min ago`;
            
            const diffHours = Math.floor(diffMins / 60);
            if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
            
            const diffDays = Math.floor(diffHours / 24);
            if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
            
            return date.toLocaleDateString();
        }

        // Transmit Selected Records - open print preview in new tab
        const transmitSelectedButton = document.getElementById('transmit-selected-records');
        if (transmitSelectedButton) {
            transmitSelectedButton.addEventListener('click', function(e) {
                // Prevent default form submission
                e.preventDefault();
                e.stopImmediatePropagation();
                
                // Get actually selected transmit checkboxes
                const selectedIds = Array.from(document.querySelectorAll('.record-checkbox-transmit:checked'))
                                        .map(cb => cb.value);
                
                if (selectedIds.length > 0) {
                    // Open admin print preview page in new tab with selected records
                    window.open('{{ route("admin.print-preview") }}?ids=' + encodeURIComponent(selectedIds.join(',')), '_blank');
                }
                
                return false;
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

        // Location data from app.js
        const locationCsv = `BARANGAY,MUNICIPALITY,PROVINCE
Betes,Aliaga,Nueva Ecija
Bibiclat,Aliaga,Nueva Ecija
Bucot,Aliaga,Nueva Ecija
La Purisima,Aliaga,Nueva Ecija
Magsaysay,Aliaga,Nueva Ecija
Macabucod,Aliaga,Nueva Ecija
Pantoc,Aliaga,Nueva Ecija
San Andres,Aliaga,Nueva Ecija
San Juan,Aliaga,Nueva Ecija
San Pablo,Aliaga,Nueva Ecija
Santa Cruz,Aliaga,Nueva Ecija
Santa Rosa,Aliaga,Nueva Ecija
Santo Domingo,Aliaga,Nueva Ecija
Tabuating,Aliaga,Nueva Ecija
Villaverde,Aliaga,Nueva Ecija
Abulalas,Bongabon,Nueva Ecija
Bayanihan,Bongabon,Nueva Ecija
Benguet,Bongabon,Nueva Ecija
Bibranch,Bongabon,Nueva Ecija
Cabitnongan,Bongabon,Nueva Ecija
Camanangpour,Bongabon,Nueva Ecija
Camanangtimog,Bongabon,Nueva Ecija
Casile,Bongabon,Nueva Ecija
Dicola,Bongabon,Nueva Ecija
Dimalco,Bongabon,Nueva Ecija
Gandela,Bongabon,Nueva Ecija
La Purisima,Bongabon,Nueva Ecija
Lomboy,Bongabon,Nueva Ecija
Luna,Bongabon,Nueva Ecija
Magtanggol,Bongabon,Nueva Ecija
Malinao,Bongabon,Nueva Ecija
Maragol,Bongabon,Nueva Ecija
Olango,Bongabon,Nueva Ecija
Palayan City,Bongabon,Nueva Ecija
Poblacion,Bongabon,Nueva Ecija
Prinzavor,Bongabon,Nueva Ecija
San Josef,Bongabon,Nueva Ecija
San Juan Norte,Bongabon,Nueva Ecija
San Juan Sur,Bongabon,Nueva Ecija
San Miguel,Bongabon,Nueva Ecija
San Pedro,Bongabon,Nueva Ecija
San Roque,Bongabon,Nueva Ecija
Santa Rosa,Bongabon,Nueva Ecija
Santo Cristo,Bongabon,Nueva Ecija
Santo Niño,Bongabon,Nueva Ecija
Tamarong,Bongabon,Nueva Ecija
Triunfo,Bongabon,Nueva Ecija
Villa Cruz,Bongabon,Nueva Ecija
Villa Ibaba,Bongabon,Nueva Ecija
Villa Ilaya,Bongabon,Nueva Ecija
Bongabon,Cabanatuan City,Nueva Ecija
Buliran,Cabanatuan City,Nueva Ecija
Cabiao,Cabanatuan City,Nueva Ecija
Caridad,Cabanatuan City,Nueva Ecija
Cojuangco,Cabanatuan City,Nueva Ecija
Cruz,Cabanatuan City,Nueva Ecija
Daang Sarile,Cabanatuan City,Nueva Ecija
Dulong Bayan,Cabanatuan City,Nueva Ecija
Fatima,Cabanatuan City,Nueva Ecija
Guzman,Cabanatuan City,Nueva Ecija
Iba,Cabanatuan City,Nueva Ecija
Isla,Cabanatuan City,Nueva Ecija
Kalawakan,Cabanatuan City,Nueva Ecija
Labatucan,Cabanatuan City,Nueva Ecija
Lanao,Cabanatuan City,Nueva Ecija
Langla,Cabanatuan City,Nueva Ecija
Llanera,Cabanatuan City,Nueva Ecija
Mabini,Cabanatuan City,Nueva Ecija
Macabucod,Cabanatuan City,Nueva Ecija
Magsaysay,Cabanatuan City,Nueva Ecija
Malabon,Cabanatuan City,Nueva Ecija
Malimba,Cabanatuan City,Nueva Ecija
Manimman,Cabanatuan City,Nueva Ecija
Mapangpang,Cabanatuan City,Nueva Ecija
Marcos,Cabanatuan City,Nueva Ecija
Matalong,Cabanatuan City,Nueva Ecija
Mayapap,Cabanatuan City,Nueva Ecija
Nancayan,Cabanatuan City,Nueva Ecija
Padre Burgos,Cabanatuan City,Nueva Ecija
Pagatpat,Cabanatuan City,Nueva Ecija
Palian,Cabanatuan City,Nueva Ecija
Pambuan,Cabanatuan City,Nueva Ecija
Panabingan,Cabanatuan City,Nueva Ecija
Pangatyan,Cabanatuan City,Nueva Ecija
Pare,Cabanatuan City,Nueva Ecija
Poblacion,Cabanatuan City,Nueva Ecija
Polilio,Cabanatuan City,Nueva Ecija
Portillo,Cabanatuan City,Nueva Ecija
Pulong Buli,Cabanatuan City,Nueva Ecija
Pulong San Miguel,Cabanatuan City,Nueva Ecija
Pulong Santo,Cabanatuan City,Nueva Ecija
San Juan,Palanan,Cabanatuan City,Nueva Ecija
San Roque,Cabanatuan City,Nueva Ecija
Santa Arcadia,Cabanatuan City,Nueva Ecija
Santo Domingo,Cabanatuan City,Nueva Ecija
Santo Niño,Cabanatuan City,Nueva Ecija
Sapang Cawayan,Cabanatuan City,Nueva Ecija
Sumacab Este,Cabanatuan City,Nueva Ecija
Sumacab Weste,Cabanatuan City,Nueva Ecija
Talipusngo,Cabanatuan City,Nueva Ecija
Triunfo,Cabanatuan City,Nueva Ecija
Valdefuentes,Cabanatuan City,Nueva Ecija
Valdez,Cabanatuan City,Nueva Ecija
Villaserin,Cabanatuan City,Nueva Ecija
Zamora,Cabanatuan City,Nueva Ecija
Agoo,Cabanatuan City,Nueva Ecija
Baloc,Cabanatuan City,Nueva Ecija
Bebe,Cabanatuan City,Nueva Ecija
Bical,Cabanatuan City,Nueva Ecija
Bonga,Cabanatuan City,Nueva Ecija
Bunol,Bunol,Cabanatuan City,Nueva Ecija
Burnay,Cabanatuan City,Nueva Ecija
Cabu,Cabanatuan City,Nueva Ecija
Calaoacan,Cabanatuan City,Nueva Ecija
Calawacan,Cabanatuan City,Nueva Ecija
Camiling,Cabanatuan City,Nueva Ecija
Candabong,Cabanatuan City,Nueva Ecija
Cauayan,Cabanatuan City,Nueva Ecija
Cawayan,Cabanatuan City,Nueva Ecija
Concepcion,Cabanatuan City,Nueva Ecija
Cruz,Cabanatuan City,Nueva Ecija
Dannag,Cabanatuan City,Nueva Ecija
Dapdap,Cabanatuan City,Nueva Ecija
Dimasalang,Cabanatuan City,Nueva Ecija
Diteki,Cabanatuan City,Nueva Ecija
Divisoria,Cabanatuan City,Nueva Ecija
Dolores,Cabanatuan City,Nueva Ecija
Dona,Cabanatuan City,Nueva Ecija
Dulong Iloc,Cabanatuan City,Nueva Ecija
Dulong Silang,Cabanatuan City,Nueva Ecija
Escawan,Cabanatuan City,Nueva Ecija
G靠,Cabanatuan City,Nueva Ecija
General Luna,Cabanatuan City,Nueva Ecija
Gomez,Cabanatuan City,Nueva Ecija
Guadalupe,Cabanatuan City,Nueva Ecija
Ibaan,Cabanatuan City,Nueva Ecija
Ilocano,Cabanatuan City,Nueva Ecija
Imelda,Cabanatuan City,Nueva Ecija
Jalajala,Cabanatuan City,Nueva Ecija
La Purisima,Cabanatuan City,Nueva Ecija
La Torre,Cabanatuan City,Nueva Ecija
Lacab,Cabanatuan City,Nueva Ecija
Lafuente,Cabanatuan City,Nueva Ecija
Lanao,Cabanatuan City,Nueva Ecija
Libon,Cabanatuan City,Nueva Ecija
Lomboy,Cabanatuan City,Nueva Ecija
Longos,Cabanatuan City,Nueva Ecija
Luzon,Cabanatuan City,Nueva Ecija
Mabini,Cabanatuan City,Nueva Ecija
Macamunic,Cabanatuan City,Nueva Ecija
Magaleng,Cabanatuan City,Nueva Ecija
Magsaysay,Cabanatuan City,Nueva Ecija
Maguinao,Cabanatuan City,Nueva Ecija
Makabayan,Cabanatuan City,Nueva Ecija
Malabon,Cabanatuan City,Nueva Ecija
Malaya,Cabanatuan City,Nueva Ecija
Malimba,Cabanatuan City,Nueva Ecija
Maluid,Cabanatuan City,Nueva Ecija
Manic,Cabanatuan City,Nueva Ecija
Mapangpang,Cabanatuan City,Nueva Ecija
Maragol,Cabanatuan City,Nueva Ecija
Marilao,Cabanatuan City,Nueva Ecija
Marquez,Cabanatuan City,Nueva Ecija
Matacano,Cabanatuan City,Nueva Ecija
Mayapyap,Cabanatuan City,Nueva Ecija
Mayapap,Cabanatuan City,Nueva Ecija
Moriones,Cabanatuan City,Nueva Ecija
Motrico,Cabanatuan City,Nueva Ecija
Nancayan,Cabanatuan City,Nueva Ecija
Natonin,Cabanatuan City,Nueva Ecija
Neptali,Cabanatuan City,Nueva Ecija
Ocampo,Cabanatuan City,Nueva Ecija
Pacac,Cabanatuan City,Nueva Ecija
Padre Burgos,Cabanatuan City,Nueva Ecija
Pagal,Cabanatuan City,Nueva Ecija
Paitan,Cabanatuan City,Nueva Ecija
Palacpac,Cabanatuan City,Nueva Ecija
Palayan,Cabanatuan City,Nueva Ecija
Palanas,Cabanatuan City,Nueva Ecija
Palanog,Cabanatuan City,Nueva Ecija
Palestina,Cabanatuan City,Nueva Ecija
Palian,Cabanatuan City,Nueva Ecija
Pambuan,Cabanatuan City,Nueva Ecija
Panabingan,Cabanatuan City,Nueva Ecija
Pangatian,Cabanatuan City,Nueva Ecija
Parang,Cabanatuan City,Nueva Ecija
Pasong Bangkal,Cabanatuan City,Nueva Ecija
Pavon,Cabanatuan City,Nueva Ecija
Poblacion,Cabanatuan City,Nueva Ecija
Polilio,Cabanatuan City,Nueva Ecija
Prinzavor,Cabanatuan City,Nueva Ecija
Pulong Buli,Cabanatuan City,Nueva Ecija
Pulung Maragul,Cabanatuan City,Nueva Ecija
Pura,Cabanatuan City,Nueva Ecija
Quezon,Cabanatuan City,Nueva Ecija
Quintana,Cabanatuan City,Nueva Ecija
Rizal,Cabanatuan City,Nueva Ecija
Rosario,Cabanatuan City,Nueva Ecija
Sagbayan,Cabanatuan City,Nueva Ecija
San Agustin,Cabanatuan City,Nueva Ecija
San Antonio,Cabanatuan City,Nueva Ecija
San Bartolome,Cabanatuan City,Nueva Ecija
San Benito,Cabanatuan City,Nueva Ecija
San Carlos,Cabanatuan City,Nueva Ecija
San Cristobal,Cabanatuan City,Nueva Ecija
San Felipe,Cabanatuan City,Nueva Ecija
San Francisco,Cabanatuan City,Nueva Ecija
San Gabriel,Cabanatuan City,Nueva Ecija
San Guillermo,Cabanatuan City,Nueva Ecija
San Ignacio,Cabanatuan City,Nueva Ecija
San Jacinto,Cabanatuan City,Nueva Ecija
San Jose,Cabanatuan City,Nueva Ecija
San Juan,Cabanatuan City,Nueva Ecija
San Julian,Cabanatuan City,Nueva Ecija
San Lorenzo,Cabanatuan City,Nueva Ecija
San Lucas,Cabanatuan City,Nueva Ecija
San Miguel,Cabanatuan City,Nueva Ecija
San Nicolas,Cabanatuan City,Nueva Ecija
San Pablo,Cabanatuan City,Nueva Ecija
San Pedro,Cabanatuan City,Nueva Ecija
San Rafael,Cabanatuan City,Nueva Ecija
San Roque,Cabanatuan City,Nueva Ecija
San Sebastian,Cabanatuan City,Nueva Ecija
San Vicente,Cabanatuan City,Nueva Ecija
Santa Ana,Cabanatuan City,Nueva Ecija
Santa Barbara,Cabanatuan City,Nueva Ecija
Santa Cruz,Cabanatuan City,Nueva Ecija
Santa Elena,Cabanatuan City,Nueva Ecija
Santa Lucia,Cabanatuan City,Nueva Ecija
Santa Maria,Cabanatuan City,Nueva Ecija
Santa Rita,Cabanatuan City,Nueva Ecija
Santa Rosa,Cabanatuan City,Nueva Ecija
Santiago,Cabanatuan City,Nueva Ecija
Santo Domingo,Cabanatuan City,Nueva Ecija
Santo Niño,Cabanatuan City,Nueva Ecija
Santo Tomas,Cabanatuan City,Nueva Ecija
Santol,Cabanatuan City,Nueva Ecija
Sipay,Cabanatuan City,Nueva Ecija
Subic,Cabanatuan City,Nueva Ecija
Sullivan,Cabanatuan City,Nueva Ecija
Talugtug,Cabanatuan City,Nueva Ecija
Tamarong,Cabanatuan City,Nueva Ecija
Tandoc,Cabanatuan City,Nueva Ecija
Tanquigan,Cabanatuan City,Nueva Ecija
Tayug,Cabanatuan City,Nueva Ecija
Tibag,Cabanatuan City,Nueva Ecija
Tibangan,Cabanatuan City,Nueva Ecija
Tineke,Cabanatuan City,Nueva Ecija
Tondod,Cabanatuan City,Nueva Ecija
Toril,Cabanatuan City,Nueva Ecija
Triunfo,Cabanatuan City,Nueva Ecija
Tugatog,Cabanatuan City,Nueva Ecija
Tuliao,Cabanatuan City,Nueva Ecija
Tumana,Cabanatuan City,Nueva Ecija
Valdefuentes,Cabanatuan City,Nueva Ecija
Valdez,Cabanatuan City,Nueva Ecija
Victoria,Cabanatuan City,Nueva Ecija
Villa,Cabanatuan City,Nueva Ecija
Villa Flores,Cabanatuan City,Nueva Ecija
Villanueva,Cabanatuan City,Nueva Ecija
Villaserin,Cabanatuan City,Nueva Ecija
Yulo,Cabanatuan City,Nueva Ecija
Zamora,Cabanatuan City,Nueva Ecija
Zaragoza,Cabanatuan City,Nueva Ecija
Agoo,Carranglan,Nueva Ecija
Babar,Carranglan,Nueva Ecija
Bongabon,Carranglan,Nueva Ecija
Buenavista,Carranglan,Nueva Ecija
Bulaklak,Carranglan,Nueva Ecija
Bunol,Carranglan,Nueva Ecija
Burgos,Carranglan,Nueva Ecija
Camanang,Carranglan,Nueva Ecija
Carmen,Carranglan,Nueva Ecija
Diaz,Carranglan,Nueva Ecija
Dimalco,Carranglan,Nueva Ecija
Dolo,Carranglan,Nueva Ecija
Floyd,Carranglan,Nueva Ecija
Gandela,Carranglan,Nueva Ecija
Guzman,Carranglan,Nueva Ecija
Lluida,Carranglan,Nueva Ecija
Luna,Carranglan,Nueva Ecija
Mabini,Carranglan,Nueva Ecija
Macabucod,Carranglan,Nueva Ecija
Magsaysay,Carranglan,Nueva Ecija
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
Cabanabasan,Cuyapo,Nueva Ecija
Cabang,Cuyapo,Nueva Ecija
Cabulary,Cuyapo,Nueva Ecija
Calipua,Cuyapo,Nueva Ecija
Camangi,Cuyapo,Nueva Ecija
Candelaria,Cuyapo,Nueva Ecija
Candon,Cuyapo,Nueva Ecija
Caoayan,Cuyapo,Nueva Ecija
Capintalan,Cuyapo,Nueva Ecija
Caridad,Cuyapo,Nueva Ecija
Carmen,Cuyapo,Nueva Ecija
Castellano,Cuyapo,Nueva Ecija
Caturay,Cuyapo,Nueva Ecija
Cayanga,Cuyapo,Nueva Ecija
Cruz,Cuyapo,Nueva Ecija
Diaz,Cuyapo,Nueva Ecija
Dinalupihan,Cuyapo,Nueva Ecija
Dingatan,Cuyapo,Nueva Ecija
Dipaculao,Cuyapo,Nueva Ecija
Dolores,Cuyapo,Nueva Ecija
Dona,Cuyapo,Nueva Ecija
Dul角,Cuyapo,Nueva Ecija
Estancia,Cuyapo,Nueva Ecija
G. S. Rosario,Cuyapo,Nueva Ecija
Geronimo,Cuyapo,Nueva Ecija
Gomez,Cuyapo,Nueva Ecija
Guadalupe,Cuyapo,Nueva Ecija
Ilim,Cuyapo,Nueva Ecija
Imelda,Cuyapo,Nueva Ecija
La Purisima,Cuyapo,Nueva Ecija
La Torre,Cuyapo,Nueva Ecija
Lacab,Cuyapo,Nueva Ecija
Lanao,Cuyapo,Nueva Ecija
Langatian,Cuyapo,Nueva Ecija
Lomboy,Cuyapo,Nueva Ecija
Longos,Cuyapo,Nueva Ecija
Luna,Cuyapo,Nueva Ecija
Mabini,Cuyapo,Nueva Ecija
Macamunic,Cuyapo,Nueva Ecija
Macapagal,Cuyapo,Nueva Ecija
Magasing,Cuyapo,Nueva Ecija
Magsaysay,Cuyapo,Nueva Ecija
Maguinao,Cuyapo,Nueva Ecija
Maintel,Cuyapo,Nueva Ecija
Malabon,Cuyapo,Nueva Ecija
Malaya,Cuyapo,Nueva Ecija
Malimba,Cuyapo,Nueva Ecija
Maluid,Cuyapo,Nueva Ecija
Manic,Cuyapo,Nueva Ecija
Manzalar,Cuyapo,Nueva Ecija
Maragol,Cuyapo,Nueva Ecija
Marcos,Cuyapo,Nueva Ecija
Matacano,Cuyapo,Nueva Ecija
Mayapap,Cuyapo,Nueva Ecija
Morales,Cuyapo,Nueva Ecija
Nancayan,Cuyapo,Nueva Ecija
Natonin,Cuyapo,Nueva Ecija
Paitan,Cuyapo,Nueva Ecija
Palacpac,Cuyapo,Nueva Ecija
Palanas,Cuyapo,Nueva Ecija
Palanog,Cuyapo,Nueva Ecija
Palestina,Cuyapo,Nueva Ecija
Palian,Cuyapo,Nueva Ecija
Poblacion,Cuyapo,Nueva Ecija
Polilio,Cuyapo,Nueva Ecija
Prinzavor,Cuyapo,Nueva Ecija
Pulong Buli,Cuyapo,Nueva Ecija
Pulong San Miguel,Cuyapo,Nueva Ecija
Pura,Cuyapo,Nueva Ecija
Quezon,Cuyapo,Nueva Ecija
Quintana,Cuyapo,Nueva Ecija
Ramon,Cuyapo,Nueva Ecija
Rizal,Cuyapo,Nueva Ecija
Rosario,Cuyapo,Nueva Ecija
Sagbayan,Cuyapo,Nueva Ecija
San Agustin,Cuyapo,Nueva Ecija
San Antonio,Cuyapo,Nueva Ecija
San Bartolome,Cuyapo,Nueva Ecija
San Benito,Cuyapo,Nueva Ecija
San Carlos,Cuyapo,Nueva Ecija
San Cristobal,Cuyapo,Nueva Ecija
San Felipe,Cuyapo,Nueva Ecija
San Francisco,Cuyapo,Nueva Ecija
San Gabriel,Cuyapo,Nueva Ecija
San Guillermo,Cuyapo,Nueva Ecija
San Ignacio,Cuyapo,Nueva Ecija
San Jacinto,Cuyapo,Nueva Ecija
San Jose,Cuyapo,Nueva Ecija
San Juan,Cuyapo,Nueva Ecija
San Julian,Cuyapo,Nueva Ecija
San Lorenzo,Cuyapo,Nueva Ecija
San Lucas,Cuyapo,Nueva Ecija
San Miguel,Cuyapo,Nueva Ecija
San Nicolas,Cuyapo,Nueva Ecija
San Pablo,Cuyapo,Nueva Ecija
San Pedro,Cuyapo,Nueva Ecija
San Rafael,Cuyapo,Nueva Ecija
San Roque,Cuyapo,Nueva Ecija
San Sebastian,Cuyapo,Nueva Ecija
San Vicente,Cuyapo,Nueva Ecija
Santa Ana,Cuyapo,Nueva Ecija
Santa Barbara,Cuyapo,Nueva Ecija
Santa Cruz,Cuyapo,Nueva Ecija
Santa Elena,Cuyapo,Nueva Ecija
Santa Lucia,Cuyapo,Nueva Ecija
Santa Maria,Cuyapo,Nueva Ecija
Santa Rita,Cuyapo,Nueva Ecija
Santa Rosa,Cuyapo,Nueva Ecija
Santiago,Cuyapo,Nueva Ecija
Santo Domingo,Cuyapo,Nueva Ecija
Santo Niño,Cuyapo,Nueva Ecija
Santo Tomas,Cuyapo,Nueva Ecija
Santol,Cuyapo,Nueva Ecija
Sipay,Cuyapo,Nueva Ecija
Subic,Cuyapo,Nueva Ecija
Sullivan,Cuyapo,Nueva Ecija
Talavera,Cuyapo,Nueva Ecija
Talugtug,Cuyapo,Nueva Ecija
Tamarong,Cuyapo,Nueva Ecija
Tandoc,Cuyapo,Nueva Ecija
Tanquigan,Cuyapo,Nueva Ecija
Tayug,Cuyapo,Nueva Ecija
Tibag,Cuyapo,Nueva Ecija
Tibangan,Cuyapo,Nueva Ecija
Tineke,Cuyapo,Nueva Ecija
Tondod,Cuyapo,Nueva Ecija
Toril,Cuyapo,Nueva Ecija
Triunfo,Cuyapo,Nueva Ecija
Tugatog,Cuyapo,Nueva Ecija
Tuliao,Cuyapo,Nueva Ecija
Tumana,Cuyapo,Nueva Ecija
Valdefuentes,Cuyapo,Nueva Ecija
Valdez,Cuyapo,Nueva Ecija
Victoria,Cuyapo,Nueva Ecija
Villa,Cuyapo,Nueva Ecija
Villa Flores,Cuyapo,Nueva Ecija
Villanueva,Cuyapo,Nueva Ecija
Villaserin,Cuyapo,Nueva Ecija
Yulo,Cuyapo,Nueva Ecija
Zamora,Cuyapo,Nueva Ecija
Zaragoza,Cuyapo,Nueva Ecija
Agoo,Gabaldon,Nueva Ecija
Babar,Gabaldon,Nueva Ecija
Bagting,Gabaldon,Nueva Ecija
Bongabon,Gabaldon,Nueva Ecija
Buenavista,Gabaldon,Nueva Ecija
Bulaklak,Gabaldon,Nueva Ecija
Bunol,Gabaldon,Nueva Ecija
Burgos,Gabaldon,Nueva Ecija
Calaniman,Gabaldon,Nueva Ecija
Camanang,Gabaldon,Nueva Ecija
Carmen,Gabaldon,Nueva Ecija
Castellano,Gabaldon,Nueva Ecija
Diaz,Gabaldon,Nueva Ecija
Dimalco,Gabaldon,Nueva Ecija
Dolo,Gabaldon,Nueva Ecija
Floyd,Gabaldon,Nueva Ecija
Gandela,Gabaldon,Nueva Ecija
Guzman,Gabaldon,Nueva Ecija
Lluida,Gabaldon,Nueva Ecija
Luna,Gabaldon,Nueva Ecija
Mabini,Gabaldon,Nueva Ecija
Macabucod,Gabaldon,Nueva Ecija
Magsaysay,Gabaldon,Nueva Ecija
Minuli,Gabaldon,Nueva Ecija
Piut,Gabaldon,Nueva Ecija
Puncan,Gabaldon,Nueva Ecija
Putlan,Gabaldon,Nueva Ecija
Salazar,Gabaldon,Nueva Ecija
San Agustin,Gabaldon,Nueva Ecija
T. L. Padilla Pob.,Gabaldon,Nueva Ecija
F. C. Otic Pob.,Gabaldon,Nueva Ecija
D. L. Maglanoc Pob.,Gabaldon,Nueva Ecija
G. S. Rosario Pob.,Gabaldon,Nueva Ecija
Agoo,Gapan,Nueva Ecija
Babar,Gapan,Nueva Ecija
Bongabon,Gapan,Nueva Ecija
Buenavista,Gapan,Nueva Ecija
Bulaklak,Gapan,Nueva Ecija
Bunol,Gapan,Nueva Ecija
Burgos,Gapan,Nueva Ecija
Calaniman,Gapan,Nueva Ecija
Camanang,Gapan,Nueva Ecija
Carmen,Gapan,Nueva Ecija
Castellano,Gapan,Nueva Ecija
Diaz,Gapan,Nueva Ecija
Dimalco,Gapan,Nueva Ecija
Dolo,Gapan,Nueva Ecija
Floyd,Gapan,Nueva Ecija
Gandela,Gapan,Nueva Ecija
Guzman,Gapan,Nueva Ecija
Lluida,Gapan,Nueva Ecija
Luna,Gapan,Nueva Ecija
Mabini,Gapan,Nueva Ecija
Macabucod,Gapan,Nueva Ecija
Magsaysay,Gapan,Nueva Ecija
Minuli,Gapan,Nueva Ecija
Piut,Gapan,Nueva Ecija
Puncan,Gapan,Nueva Ecija
Putlan,Gapan,Nueva Ecija
Salazar,Gapan,Nueva Ecija
San Agustin,Gapan,Nueva Ecija
T. L. Padilla Pob.,Gapan,Nueva Ecija
F. C. Otic Pob.,Gapan,Nueva Ecija
D. L. Maglanoc Pob.,Gapan,Nueva Ecija
G. S. Rosario Pob.,Gapan,Nueva Ecija
Agoo,General Mamerto Natividad,Nueva Ecija
Babar,General Mamerto Natividad,Nueva Ecija
Bongabon,General Mamerto Natividad,Nueva Ecija
Buenavista,General Mamerto Natividad,Nueva Ecija
Bulaklak,General Mamerto Natividad,Nueva Ecija
Bunol,General Mamerto Natividad,Nueva Ecija
Burgos,General Mamerto Natividad,Nueva Ecija
Calaniman,General Mamerto Natividad,Nueva Ecija
Camanang,General Mamerto Natividad,Nueva Ecija
Carmen,General Mamerto Natividad,Nueva Ecija
Castellano,General Mamerto Natividad,Nueva Ecija
Diaz,General Mamerto Natividad,Nueva Ecija
Dimalco,General Mamerto Natividad,Nueva Ecija
Dolo,General Mamerto Natividad,Nueva Ecija
Floyd,General Mamerto Natividad,Nueva Ecija
Gandela,General Mamerto Natividad,Nueva Ecija
Guzman,General Mamerto Natividad,Nueva Ecija
Lluida,General Mamerto Natividad,Nueva Ecija
Luna,General Mamerto Natividad,Nueva Ecija
Mabini,General Mamerto Natividad,Nueva Ecija
Macabucod,General Mamerto Natividad,Nueva Ecija
Magsaysay,General Mamerto Natividad,Nueva Ecija
Minuli,General Mamerto Natividad,Nueva Ecija
Piut,General Mamerto Natividad,Nueva Ecija
Puncan,General Mamerto Natividad,Nueva Ecija
Putlan,General Mamerto Natividad,Nueva Ecija
Salazar,General Mamerto Natividad,Nueva Ecija
San Agustin,General Mamerto Natividad,Nueva Ecija
T. L. Padilla Pob.,General Mamerto Natividad,Nueva Ecija
F. C. Otic Pob.,General Mamerto Natividad,Nueva Ecija
D. L. Maglanoc Pob.,General Mamerto Natividad,Nueva Ecija
G. S. Rosario Pob.,General Mamerto Natividad,Nueva Ecija
Agoo,General Tinio,Nueva Ecija
Babar,General Tinio,Nueva Ecija
Bongabon,General Tinio,Nueva Ecija
Buenavista,General Tinio,Nueva Ecija
Bulaklak,General Tinio,Nueva Ecija
Bunol,General Tinio,Nueva Ecija
Burgos,General Tinio,Nueva Ecija
Calaniman,General Tinio,Nueva Ecija
Camanang,General Tinio,Nueva Ecija
Carmen,General Tinio,Nueva Ecija
Castellano,General Tinio,Nueva Ecija
Diaz,General Tinio,Nueva Ecija
Dimalco,General Tinio,Nueva Ecija
Dolo,General Tinio,Nueva Ecija
Floyd,General Tinio,Nueva Ecija
Gandela,General Tinio,Nueva Ecija
Guzman,General Tinio,Nueva Ecija
Lluida,General Tinio,Nueva Ecija
Luna,General Tinio,Nueva Ecija
Mabini,General Tinio,Nueva Ecija
Macabucod,General Tinio,Nueva Ecija
Magsaysay,General Tinio,Nueva Ecija
Minuli,General Tinio,Nueva Ecija
Piut,General Tinio,Nueva Ecija
Puncan,General Tinio,Nueva Ecija
Putlan,General Tinio,Nueva Ecija
Salazar,General Tinio,Nueva Ecija
San Agustin,General Tinio,Nueva Ecija
T. L. Padilla Pob.,General Tinio,Nueva Ecija
F. C. Otic Pob.,General Tinio,Nueva Ecija
D. L. Maglanoc Pob.,General Tinio,Nueva Ecija
G. S. Rosario Pob.,General Tinio,Nueva Ecija
Agoo,Guimba,Nueva Ecija
Babar,Guimba,Nueva Ecija
Bongabon,Guimba,Nueva Ecija
Buenavista,Guimba,Nueva Ecija
Bulaklak,Guimba,Nueva Ecija
Bunol,Guimba,Nueva Ecija
Burgos,Guimba,Nueva Ecija
Calaniman,Guimba,Nueva Ecija
Camanang,Guimba,Nueva Ecija
Carmen,Guimba,Nueva Ecija
Castellano,Guimba,Nueva Ecija
Diaz,Guimba,Nueva Ecija
Dimalco,Guimba,Nueva Ecija
Dolo,Guimba,Nueva Ecija
Floyd,Guimba,Nueva Ecija
Gandela,Guimba,Nueva Ecija
Guzman,Guimba,Nueva Ecija
Lluida,Guimba,Nueva Ecija
Luna,Guimba,Nueva Ecija
Mabini,Guimba,Nueva Ecija
Macabucod,Guimba,Nueva Ecija
Magsaysay,Guimba,Nueva Ecija
Minuli,Guimba,Nueva Ecija
Piut,Guimba,Nueva Ecija
Puncan,Guimba,Nueva Ecija
Putlan,Guimba,Nueva Ecija
Salazar,Guimba,Nueva Ecija
San Agustin,Guimba,Nueva Ecija
T. L. Padilla Pob.,Guimba,Nueva Ecija
F. C. Otic Pob.,Guimba,Nueva Ecija
D. L. Maglanoc Pob.,Guimba,Nueva Ecija
G. S. Rosario Pob.,Guimba,Nueva Ecija
Agoo,Jaen,Nueva Ecija
Babar,Jaen,Nueva Ecija
Bongabon,Jaen,Nueva Ecija
Buenavista,Jaen,Nueva Ecija
Bulaklak,Jaen,Nueva Ecija
Bunol,Jaen,Nueva Ecija
Burgos,Jaen,Nueva Ecija
Calaniman,Jaen,Nueva Ecija
Camanang,Jaen,Nueva Ecija
Carmen,Jaen,Nueva Ecija
Castellano,Jaen,Nueva Ecija
Diaz,Jaen,Nueva Ecija
Dimalco,Jaen,Nueva Ecija
Dolo,Jaen,Nueva Ecija
Floyd,Jaen,Nueva Ecija
Gandela,Jaen,Nueva Ecija
Guzman,Jaen,Nueva Ecija
Lluida,Jaen,Nueva Ecija
Luna,Jaen,Nueva Ecija
Mabini,Jaen,Nueva Ecija
Macabucod,Jaen,Nueva Ecija
Magsaysay,Jaen,Nueva Ecija
Minuli,Jaen,Nueva Ecija
Piut,Jaen,Nueva Ecija
Puncan,Jaen,Nueva Ecija
Putlan,Jaen,Nueva Ecija
Salazar,Jaen,Nueva Ecija
San Agustin,Jaen,Nueva Ecija
T. L. Padilla Pob.,Jaen,Nueva Ecija
F. C. Otic Pob.,Jaen,Nueva Ecija
D. L. Maglanoc Pob.,Jaen,Nueva Ecija
G. S. Rosario Pob.,Jaen,Nueva Ecija
Agoo,Laur,Nueva Ecija
Babar,Laur,Nueva Ecija
Bongabon,Laur,Nueva Ecija
Buenavista,Laur,Nueva Ecija
Bulaklak,Laur,Nueva Ecija
Bunol,Laur,Nueva Ecija
Burgos,Laur,Nueva Ecija
Calaniman,Laur,Nueva Ecija
Camanang,Laur,Nueva Ecija
Carmen,Laur,Nueva Ecija
Castellano,Laur,Nueva Ecija
Diaz,Laur,Nueva Ecija
Dimalco,Laur,Nueva Ecija
Dolo,Laur,Nueva Ecija
Floyd,Laur,Nueva Ecija
Gandela,Laur,Nueva Ecija
Guzman,Laur,Nueva Ecija
Lluida,Laur,Nueva Ecija
Luna,Laur,Nueva Ecija
Mabini,Laur,Nueva Ecija
Macabucod,Laur,Nueva Ecija
Magsaysay,Laur,Nueva Ecija
Minuli,Laur,Nueva Ecija
Piut,Laur,Nueva Ecija
Puncan,Laur,Nueva Ecija
Putlan,Laur,Nueva Ecija
Salazar,Laur,Nueva Ecija
San Agustin,Laur,Nueva Ecija
T. L. Padilla Pob.,Laur,Nueva Ecija
F. C. Otic Pob.,Laur,Nueva Ecija
D. L. Maglanoc Pob.,Laur,Nueva Ecija
G. S. Rosario Pob.,Laur,Nueva Ecija
Agoo,Licab,Nueva Ecija
Babar,Licab,Nueva Ecija
Bongabon,Licab,Nueva Ecija
Buenavista,Licab,Nueva Ecija
Bulaklak,Licab,Nueva Ecija
Bunol,Licab,Nueva Ecija
Burgos,Licab,Nueva Ecija
Calaniman,Licab,Nueva Ecija
Camanang,Licab,Nueva Ecija
Carmen,Licab,Nueva Ecija
Castellano,Licab,Nueva Ecija
Diaz,Licab,Nueva Ecija
Dimalco,Licab,Nueva Ecija
Dolo,Licab,Nueva Ecija
Floyd,Licab,Nueva Ecija
Gandela,Licab,Nueva Ecija
Guzman,Licab,Nueva Ecija
Lluida,Licab,Nueva Ecija
Luna,Licab,Nueva Ecija
Mabini,Licab,Nueva Ecija
Macabucod,Licab,Nueva Ecija
Magsaysay,Licab,Nueva Ecija
Minuli,Licab,Nueva Ecija
Piut,Licab,Nueva Ecija
Puncan,Licab,Nueva Ecija
Putlan,Licab,Nueva Ecija
Salazar,Licab,Nueva Ecija
San Agustin,Licab,Nueva Ecija
T. L. Padilla Pob.,Licab,Nueva Ecija
F. C. Otic Pob.,Licab,Nueva Ecija
D. L. Maglanoc Pob.,Licab,Nueva Ecija
G. S. Rosario Pob.,Licab,Nueva Ecija
Agoo,Llanera,Nueva Ecija
Babar,Llanera,Nueva Ecija
Bongabon,Llanera,Nueva Ecija
Buenavista,Llanera,Nueva Ecija
Bulaklak,Llanera,Nueva Ecija
Bunol,Llanera,Nueva Ecija
Burgos,Llanera,Nueva Ecija
Calaniman,Llanera,Nueva Ecija
Camanang,Llanera,Nueva Ecija
Carmen,Llanera,Nueva Ecija
Castellano,Llanera,Nueva Ecija
Diaz,Llanera,Nueva Ecija
Dimalco,Llanera,Nueva Ecija
Dolo,Llanera,Nueva Ecija
Floyd,Llanera,Nueva Ecija
Gandela,Llanera,Nueva Ecija
Guzman,Llanera,Nueva Ecija
Lluida,Llanera,Nueva Ecija
Luna,Llanera,Nueva Ecija
Mabini,Llanera,Nueva Ecija
Macabucod,Llanera,Nueva Ecija
Magsaysay,Llanera,Nueva Ecija
Minuli,Llanera,Nueva Ecija
Piut,Llanera,Nueva Ecija
Puncan,Llanera,Nueva Ecija
Putlan,Llanera,Nueva Ecija
Salazar,Llanera,Nueva Ecija
San Agustin,Llanera,Nueva Ecija
T. L. Padilla Pob.,Llanera,Nueva Ecija
F. C. Otic Pob.,Llanera,Nueva Ecija
D. L. Maglanoc Pob.,Llanera,Nueva Ecija
G. S. Rosario Pob.,Llanera,Nueva Ecija
Agoo,Lupao,Nueva Ecija
Babar,Lupao,Nueva Ecija
Bongabon,Lupao,Nueva Ecija
Buenavista,Lupao,Nueva Ecija
Bulaklak,Lupao,Nueva Ecija
Bunol,Lupao,Nueva Ecija
Burgos,Lupao,Nueva Ecija
Calaniman,Lupao,Nueva Ecija
Camanang,Lupao,Nueva Ecija
Carmen,Lupao,Nueva Ecija
Castellano,Lupao,Nueva Ecija
Diaz,Lupao,Nueva Ecija
Dimalco,Lupao,Nueva Ecija
Dolo,Lupao,Nueva Ecija
Floyd,Lupao,Nueva Ecija
Gandela,Lupao,Nueva Ecija
Guzman,Lupao,Nueva Ecija
Lluida,Lupao,Nueva Ecija
Luna,Lupao,Nueva Ecija
Mabini,Lupao,Nueva Ecija
Macabucod,Lupao,Nueva Ecija
Magsaysay,Lupao,Nueva Ecija
Minuli,Lupao,Nueva Ecija
Piut,Lupao,Nueva Ecija
Puncan,Lupao,Nueva Ecija
Putlan,Lupao,Nueva Ecija
Salazar,Lupao,Nueva Ecija
San Agustin,Lupao,Nueva Ecija
T. L. Padilla Pob.,Lupao,Nueva Ecija
F. C. Otic Pob.,Lupao,Nueva Ecija
D. L. Maglanoc Pob.,Lupao,Nueva Ecija
G. S. Rosario Pob.,Lupao,Nueva Ecija
Agoo,San Antonio,Nueva Ecija
Babar,San Antonio,Nueva Ecija
Bongabon,San Antonio,Nueva Ecija
Buenavista,San Antonio,Nueva Ecija
Bulaklak,San Antonio,Nueva Ecija
Bunol,San Antonio,Nueva Ecija
Burgos,San Antonio,Nueva Ecija
Calaniman,San Antonio,Nueva Ecija
Camanang,San Antonio,Nueva Ecija
Carmen,San Antonio,Nueva Ecija
Castellano,San Antonio,Nueva Ecija
Diaz,San Antonio,Nueva Ecija
Dimalco,San Antonio,Nueva Ecija
Dolo,San Antonio,Nueva Ecija
Floyd,San Antonio,Nueva Ecija
Gandela,San Antonio,Nueva Ecija
Guzman,San Antonio,Nueva Ecija
Lluida,San Antonio,Nueva Ecija
Luna,San Antonio,Nueva Ecija
Mabini,San Antonio,Nueva Ecija
Macabucod,San Antonio,Nueva Ecija
Magsaysay,San Antonio,Nueva Ecija
Minuli,San Antonio,Nueva Ecija
Piut,San Antonio,Nueva Ecija
Puncan,San Antonio,Nueva Ecija
Putlan,San Antonio,Nueva Ecija
Salazar,San Antonio,Nueva Ecija
San Agustin,San Antonio,Nueva Ecija
T. L. Padilla Pob.,San Antonio,Nueva Ecija
F. C. Otic Pob.,San Antonio,Nueva Ecija
D. L. Maglanoc Pob.,San Antonio,Nueva Ecija
G. S. Rosario Pob.,San Antonio,Nueva Ecija
Agoo,San Jose,Nueva Ecija
Babar,San Jose,Nueva Ecija
Bongabon,San Jose,Nueva Ecija
Buenavista,San Jose,Nueva Ecija
Bulaklak,San Jose,Nueva Ecija
Bunol,San Jose,Nueva Ecija
Burgos,San Jose,Nueva Ecija
Calaniman,San Jose,Nueva Ecija
Camanang,San Jose,Nueva Ecija
Carmen,San Jose,Nueva Ecija
Castellano,San Jose,Nueva Ecija
Diaz,San Jose,Nueva Ecija
Dimalco,San Jose,Nueva Ecija
Dolo,San Jose,Nueva Ecija
Floyd,San Jose,Nueva Ecija
Gandela,San Jose,Nueva Ecija
Guzman,San Jose,Nueva Ecija
Lluida,San Jose,Nueva Ecija
Luna,San Jose,Nueva Ecija
Mabini,San Jose,Nueva Ecija
Macabucod,San Jose,Nueva Ecija
Magsaysay,San Jose,Nueva Ecija
Minuli,San Jose,Nueva Ecija
Piut,San Jose,Nueva Ecija
Puncan,San Jose,Nueva Ecija
Putlan,San Jose,Nueva Ecija
Salazar,San Jose,Nueva Ecija
San Agustin,San Jose,Nueva Ecija
T. L. Padilla Pob.,San Jose,Nueva Ecija
F. C. Otic Pob.,San Jose,Nueva Ecija
D. L. Maglanoc Pob.,San Jose,Nueva Ecija
G. S. Rosario Pob.,San Jose,Nueva Ecija
Agoo,San Leonardo,Nueva Ecija
Babar,San Leonardo,Nueva Ecija
Bongabon,San Leonardo,Nueva Ecija
Buenavista,San Leonardo,Nueva Ecija
Bulaklak,San Leonardo,Nueva Ecija
Bunol,San Leonardo,Nueva Ecija
Burgos,San Leonardo,Nueva Ecija
Calaniman,San Leonardo,Nueva Ecija
Camanang,San Leonardo,Nueva Ecija
Carmen,San Leonardo,Nueva Ecija
Castellano,San Leonardo,Nueva Ecija
Diaz,San Leonardo,Nueva Ecija
Dimalco,San Leonardo,Nueva Ecija
Dolo,San Leonardo,Nueva Ecija
Floyd,San Leonardo,Nueva Ecija
Gandela,San Leonardo,Nueva Ecija
Guzman,San Leonardo,Nueva Ecija
Lluida,San Leonardo,Nueva Ecija
Luna,San Leonardo,Nueva Ecija
Mabini,San Leonardo,Nueva Ecija
Macabucod,San Leonardo,Nueva Ecija
Magsaysay,San Leonardo,Nueva Ecija
Minuli,San Leonardo,Nueva Ecija
Piut,San Leonardo,Nueva Ecija
Puncan,San Leonardo,Nueva Ecija
Putlan,San Leonardo,Nueva Ecija
Salazar,San Leonardo,Nueva Ecija
San Agustin,San Leonardo,Nueva Ecija
T. L. Padilla Pob.,San Leonardo,Nueva Ecija
F. C. Otic Pob.,San Leonardo,Nueva Ecija
D. L. Maglanoc Pob.,San Leonardo,Nueva Ecija
G. S. Rosario Pob.,San Leonardo,Nueva Ecija
Agoo,Santo Domingo,Nueva Ecija
Babar,Santo Domingo,Nueva Ecija
Bongabon,Santo Domingo,Nueva Ecija
Buenavista,Santo Domingo,Nueva Ecija
Bulaklak,Santo Domingo,Nueva Ecija
Bunol,Santo Domingo,Nueva Ecija
Burgos,Santo Domingo,Nueva Ecija
Calaniman,Santo Domingo,Nueva Ecija
Camanang,Santo Domingo,Nueva Ecija
Carmen,Santo Domingo,Nueva Ecija
Castellano,Santo Domingo,Nueva Ecija
Diaz,Santo Domingo,Nueva Ecija
Dimalco,Santo Domingo,Nueva Ecija
Dolo,Santo Domingo,Nueva Ecija
Floyd,Santo Domingo,Nueva Ecija
Gandela,Santo Domingo,Nueva Ecija
Guzman,Santo Domingo,Nueva Ecija
Lluida,Santo Domingo,Nueva Ecija
Luna,Santo Domingo,Nueva Ecija
Mabini,Santo Domingo,Nueva Ecija
Macabucod,Santo Domingo,Nueva Ecija
Magsaysay,Santo Domingo,Nueva Ecija
Minuli,Santo Domingo,Nueva Ecija
Piut,Santo Domingo,Nueva Ecija
Puncan,Santo Domingo,Nueva Ecija
Putlan,Santo Domingo,Nueva Ecija
Salazar,Santo Domingo,Nueva Ecija
San Agustin,Santo Domingo,Nueva Ecija
T. L. Padilla Pob.,Santo Domingo,Nueva Ecija
F. C. Otic Pob.,Santo Domingo,Nueva Ecija
D. L. Maglanoc Pob.,Santo Domingo,Nueva Ecija
G. S. Rosario Pob.,Santo Domingo,Nueva Ecija
Agoo,Talavera,Nueva Ecija
Babar,Talavera,Nueva Ecija
Bongabon,Talavera,Nueva Ecija
Buenavista,Talavera,Nueva Ecija
Bulaklak,Talavera,Nueva Ecija
Bunol,Talavera,Nueva Ecija
Burgos,Talavera,Nueva Ecija
Calaniman,Talavera,Nueva Ecija
Camanang,Talavera,Nueva Ecija
Carmen,Talavera,Nueva Ecija
Castellano,Talavera,Nueva Ecija
Diaz,Talavera,Nueva Ecija
Dimalco,Talavera,Nueva Ecija
Dolo,Talavera,Nueva Ecija
Floyd,Talavera,Nueva Ecija
Gandela,Talavera,Nueva Ecija
Guzman,Talavera,Nueva Ecija
Lluida,Talavera,Nueva Ecija
Luna,Talavera,Nueva Ecija
Mabini,Talavera,Nueva Ecija
Macabucod,Talavera,Nueva Ecija
Magsaysay,Talavera,Nueva Ecija
Minuli,Talavera,Nueva Ecija
Piut,Talavera,Nueva Ecija
Puncan,Talavera,Nueva Ecija
Putlan,Talavera,Nueva Ecija
Salazar,Talavera,Nueva Ecija
San Agustin,Talavera,Nueva Ecija
T. L. Padilla Pob.,Talavera,Nueva Ecija
F. C. Otic Pob.,Talavera,Nueva Ecija
D. L. Maglanoc Pob.,Talavera,Nueva Ecija
G. S. Rosario Pob.,Talavera,Nueva Ecija
Agoo,Zaragoza,Nueva Ecija
Babar,Zaragoza,Nueva Ecija
Bongabon,Zaragoza,Nueva Ecija
Buenavista,Zaragoza,Nueva Ecija
Bulaklak,Zaragoza,Nueva Ecija
Bunol,Zaragoza,Nueva Ecija
Burgos,Zaragoza,Nueva Ecija
Calaniman,Zaragoza,Nueva Ecija
Camanang,Zaragoza,Nueva Ecija
Carmen,Zaragoza,Nueva Ecija
Castellano,Zaragoza,Nueva Ecija
Diaz,Zaragoza,Nueva Ecija
Dimalco,Zaragoza,Nueva Ecija
Dolo,Zaragoza,Nueva Ecija
Floyd,Zaragoza,Nueva Ecija
Gandela,Zaragoza,Nueva Ecija
Guzman,Zaragoza,Nueva Ecija
Lluida,Zaragoza,Nueva Ecija
Luna,Zaragoza,Nueva Ecija
Mabini,Zaragoza,Nueva Ecija
Macabucod,Zaragoza,Nueva Ecija
Magsaysay,Zaragoza,Nueva Ecija
Minuli,Zaragoza,Nueva Ecija
Piut,Zaragoza,Nueva Ecija
Puncan,Zaragoza,Nueva Ecija
Putlan,Zaragoza,Nueva Ecija
Salazar,Zaragoza,Nueva Ecija
San Agustin,Zaragoza,Nueva Ecija
T. L. Padilla Pob.,Zaragoza,Nueva Ecija
F. C. Otic Pob.,Zaragoza,Nueva Ecija
D. L. Maglanoc Pob.,Zaragoza,Nueva Ecija
G. S. Rosario Pob.,Zaragoza,Nueva Ecija
Barangay I,Baler,Aurora
Barangay II,Baler,Aurora
Barangay III,Baler,Aurora
Barangay IV,Baler,Aurora
Barangay V,Baler,Aurora
Buhangin,Baler,Aurora
Calabuanan,Baler,Aurora
Obligacion,Baler,Aurora
Pingit,Baler,Aurora
Reserva,Baler,Aurora
Sabang,Baler,Aurora
Suclayin,Baler,Aurora
Zabali,Baler,Aurora
Barangay I,Casiguran,Aurora
Barangay II,Casiguran,Aurora
Barangay III,Casiguran,Aurora
Barangay IV,Casiguran,Aurora
Barangay V,Casiguran,Aurora
Buhangin,Casiguran,Aurora
Calabuanan,Casiguran,Aurora
Obligacion,Casiguran,Aurora
Pingit,Casiguran,Aurora
Reserva,Casiguran,Aurora
Sabang,Casiguran,Aurora
Suclayin,Casiguran,Aurora
Zabali,Casiguran,Aurora
Barangay I,Dinalungan,Aurora
Barangay II,Dinalungan,Aurora
Barangay III,Dinalungan,Aurora
Barangay IV,Dinalungan,Aurora
Barangay V,Dinalungan,Aurora
Buhangin,Dinalungan,Aurora
Calabuanan,Dinalungan,Aurora
Obligacion,Dinalungan,Aurora
Pingit,Dinalungan,Aurora
Reserva,Dinalungan,Aurora
Sabang,Dinalungan,Aurora
Suclayin,Dinalungan,Aurora
Zabali,Dinalungan,Aurora
Barangay I,Dipaculao,Aurora
Barangay II,Dipaculao,Aurora
Barangay III,Dipaculao,Aurora
Barangay IV,Dipaculao,Aurora
Barangay V,Dipaculao,Aurora
Buhangin,Dipaculao,Aurora
Calabuanan,Dipaculao,Aurora
Obligacion,Dipaculao,Aurora
Pingit,Dipaculao,Aurora
Reserva,Dipaculao,Aurora
Sabang,Dipaculao,Aurora
Suclayin,Dipaculao,Aurora
Zabali,Dipaculao,Aurora
Barangay I,Ditumbahan,Aurora
Barangay II,Ditumbahan,Aurora
Barangay III,Ditumbahan,Aurora
Barangay IV,Ditumbahan,Aurora
Barangay V,Ditumbahan,Aurora
Buhangin,Ditumbahan,Aurora
Calabuanan,Ditumbahan,Aurora
Obligacion,Ditumbahan,Aurora
Pingit,Ditumbahan,Aurora
Reserva,Ditumbahan,Aurora
Sabang,Ditumbahan,Aurora
Suclayin,Ditumbahan,Aurora
Zabali,Ditumbahan,Aurora
Barangay I,Maria Aurora,Aurora
Barangay II,Maria Aurora,Aurora
Barangay III,Maria Aurora,Aurora
Barangay IV,Maria Aurora,Aurora
Barangay V,Maria Aurora,Aurora
Buhangin,Maria Aurora,Aurora
Calabuanan,Maria Aurora,Aurora
Obligacion,Maria Aurora,Aurora
Pingit,Maria Aurora,Aurora
Reserva,Maria Aurora,Aurora
Sabang,Maria Aurora,Aurora
Suclayin,Maria Aurora,Aurora
Zabali,Maria Aurora,Aurora
Barangay I,San Luis,Aurora
Barangay II,San Luis,Aurora
Barangay III,San Luis,Aurora
Barangay IV,San Luis,Aurora
Barangay V,San Luis,Aurora
Buhangin,San Luis,Aurora
Calabuanan,San Luis,Aurora
Obligacion,San Luis,Aurora
Pingit,San Luis,Aurora
Reserva,San Luis,Aurora
Sabang,San Luis,Aurora
Suclayin,San Luis,Aurora
Zabali,San Luis,Aurora`;

        function parseLocationData(csv) {
            const data = {};
            const lines = csv.split(/\r?\n/).filter(line => line.trim().length > 0);
            for (let i = 1; i < lines.length; i++) {
                const parts = lines[i].split(',');
                if (parts.length >= 3) {
                    const barangay = parts[0].trim();
                    const municipality = parts[1].trim();
                    const province = parts[2].trim();
                    if (!province || !municipality || !barangay) continue;
                    if (!data[province]) data[province] = {};
                    if (!data[province][municipality]) data[province][municipality] = [];
                    if (!data[province][municipality].includes(barangay)) {
                        data[province][municipality].push(barangay);
                    }
                }
            }
            for (const province of Object.keys(data)) {
                for (const municipality of Object.keys(data[province])) {
                    data[province][municipality].sort((a, b) => a.localeCompare(b, 'en', { sensitivity: 'base' }));
                }
            }
            return data;
        }

        const locationData = parseLocationData(locationCsv);

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
        const transmitActionBtn = document.getElementById('transmit-selected-records');
        const bulkDeleteDialog = document.querySelector('.bulkDeleteDialog');
        const checkboxElements = document.querySelectorAll('.col-checkbox');
        const recordCheckboxes = document.querySelectorAll('.record-checkbox');
        const selectAllBox = document.getElementById('select-all');
        const unassignedToggle = document.getElementById('unassigned-toggle');
        const transmitAllBox = document.getElementById('select-all-transmit');
        const transmitCheckboxes = document.querySelectorAll('.record-checkbox-transmit');
        const transmitCheckboxElements = document.querySelectorAll('.col-checkbox-transmit');
        const selectedRecordIdsInput = document.getElementById('selected-record-ids');
        const bulkSelectedCount = document.getElementById('bulk-selected-count');

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
            transmitCheckboxes.forEach(cb => {
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
                transmitCheckboxes.forEach(cb => cb.checked = false);
                if(transmitAllBox) transmitAllBox.checked = false;
                if(transmitActionBtn) transmitActionBtn.disabled = true;
                if (bulkSelectedCount) bulkSelectedCount.textContent = '';
            }
        });

        const updateTransmitButtonState = () => {
            let anyChecked = Array.from(transmitCheckboxes).some(cb => cb.checked);
            if (transmitActionBtn) transmitActionBtn.disabled = !anyChecked;
            if (bulkSelectedCount) {
                const selectedCount = Array.from(transmitCheckboxes).filter(cb => cb.checked).length;
                bulkSelectedCount.textContent = selectedCount > 0 ? `${selectedCount} selected` : '';
            }
        };
        transmitCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateTransmitButtonState);
        });
        transmitAllBox?.addEventListener('change', function() {
            transmitCheckboxes.forEach(cb => cb.checked = this.checked);
            updateTransmitButtonState();
        });

        // 4. Handle Submission to Print Preview - OPEN IN NEW TAB
        transmitActionBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            
            const selectedIds = Array.from(document.querySelectorAll('.record-checkbox-transmit:checked'))
                                    .map(cb => cb.value);
            
            if (selectedIds.length > 0) {
                // First send to addToPrintPreview to store in session
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('admin.add-to-print-preview') }}";
                form.target = '_blank';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = "{{ csrf_token() }}";
                form.appendChild(csrfInput);
                
                const idsInput = document.createElement('input');
                idsInput.type = 'hidden';
                idsInput.name = 'record_ids';
                idsInput.value = JSON.stringify(selectedIds);
                form.appendChild(idsInput);
                
                document.body.appendChild(form);
                form.submit();
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
            let anyChecked = Array.from(recordCheckboxes).some(cb => cb.checked);
            if (deleteSelectedBtn) deleteSelectedBtn.disabled = !anyChecked;
            if (bulkSelectedCount) {
                const selectedCount = Array.from(recordCheckboxes).filter(cb => cb.checked).length;
                bulkSelectedCount.textContent = selectedCount > 0 ? `${selectedCount} selected` : '';
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
    </script>

    <script>
        // Synchronize horizontal scrolling between dedicated scrollbar and table
        document.addEventListener('DOMContentLoaded', function() {
            const scrollbarContainer = document.getElementById('horizontal-scrollbar-container');
            const tableWrapper = document.getElementById('table-wrapper');
            
            if (scrollbarContainer && tableWrapper) {
                // Sync scrollbar to table scroll
                tableWrapper.addEventListener('scroll', function() {
                    scrollbarContainer.scrollLeft = this.scrollLeft;
                });
                
                // Sync table to scrollbar scroll
                scrollbarContainer.addEventListener('scroll', function() {
                    tableWrapper.scrollLeft = this.scrollLeft;
                });
                
                // Update scrollbar content width to match table width
                function updateScrollbarWidth() {
                    const table = tableWrapper.querySelector('table');
                    if (table) {
                        const scrollbarContent = document.getElementById('horizontal-scrollbar-content');
                        if (scrollbarContent) {
                            scrollbarContent.style.width = table.offsetWidth + 'px';
                        }
                    }
                }
                
                // Initial update and update on table changes
                updateScrollbarWidth();
                
                // Monitor for table changes (pagination, filtering, etc.)
                const observer = new MutationObserver(function(mutations) {
                    updateScrollbarWidth();
                });
                
                if (tableWrapper) {
                    observer.observe(tableWrapper, {
                        childList: true,
                        subtree: true,
                        attributes: true
                    });
                }
            }
        });
    </script>

    <script>
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

    {{-- Real-time pending approvals polling --}}
    <script>
    (function() {
        var POLL_INTERVAL = 10000; // 10 seconds
        var lastTotal = {{ $pendingOfficers->count() + $pendingEmailHandlers->count() }};
        var lastOfficerIds = @json($pendingOfficers->pluck('id')->toArray());
        var lastEmailIds = @json($pendingEmailHandlers->pluck('id')->toArray());

        function updateBadge(count) {
            var badge = document.getElementById('pendingBadge');
            if (!badge) return;
            if (count > 0) {
                badge.style.display = 'inline-flex';
                badge.textContent = count;
            } else {
                badge.style.display = 'none';
            }
        }

        // Set initial badge
        updateBadge(lastTotal);

        function pollPendingApprovals() {
            fetch('{{ route("admin.api.pending-approvals") }}')
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.error) return;

                    var newOfficerIds = data.officers.map(function(o) { return o.id; });
                    var newEmailIds = data.emailHandlers.map(function(e) { return e.id; });

                    // Detect new officers
                    newOfficerIds.forEach(function(id) {
                        if (lastOfficerIds.indexOf(id) === -1) {
                            var officer = data.officers.find(function(o) { return o.id === id; });
                            if (officer) {
                                showToast('New Officer of the Day login: ' + officer.name, 'warning');
                            }
                        }
                    });

                    // Detect new email handlers
                    newEmailIds.forEach(function(id) {
                        if (lastEmailIds.indexOf(id) === -1) {
                            var handler = data.emailHandlers.find(function(e) { return e.id === id; });
                            if (handler) {
                                showToast('New Email handler login: ' + handler.name, 'warning');
                            }
                        }
                    });

                    lastOfficerIds = newOfficerIds;
                    lastEmailIds = newEmailIds;
                    lastTotal = data.totalPending;
                    updateBadge(data.totalPending);
                })
                .catch(function() {});
        }

        setInterval(pollPendingApprovals, POLL_INTERVAL);
    })();
    </script>

    </div> <!-- END NL Records Section -->

        </main>
    </div>

@endsection
