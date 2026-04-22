@extends('layout.layout')

@section('title', 'Admin')

@section('page-styles')
<style>
    html, body {
        max-width: 100%;
    }
</style>
@endsection

@section('content')
    <style>
        .received-by { display: none; }
        
        /* Left Sidebar Panel - Collapsible on Hover */
        .sidebar-panel {
            position: fixed;
            left: 0;
            top: 0;
            width: 60px;
            height: 100vh;
            background-color: #2c3e50;
            padding-top: 60px;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .sidebar-panel:hover {
            width: 200px;
        }
        
        .sidebar-button {
            display: flex;
            align-items: center;
            width: 200px;
            padding: 16px 18px;
            color: white;
            background-color: transparent;
            border: none;
            text-align: left;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            white-space: nowrap;
        }
        
        .sidebar-button:hover {
            background-color: #34495e;
            border-left-color: #3498db;
        }
        
        .sidebar-button.active {
            background-color: #3498db;
            border-left-color: #fff;
        }
        
        .sidebar-button span:first-child {
            font-size: 20px;
            min-width: 24px;
            text-align: center;
            margin-right: 16px;
            transition: margin 0.3s ease;
        }

        .sidebar-button span:last-child {
            opacity: 0;
            transition: opacity 0.2s ease 0.1s;
        }

        .sidebar-panel:hover .sidebar-button span:last-child {
            opacity: 1;
        }
        
        /* Main content offset */
        .main-content {
            margin-left: 60px;
            padding: 20px;
            transition: margin-left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body:hover .main-content {
            margin-left: 60px;
        }
    </style>

    <!-- Left Side Panel -->
    <div class="sidebar-panel">
        <button type="button" class="sidebar-button active" id="btn-dashboard">
            <span>📊</span>
            <span>Dashboard</span>
        </button>
        <button type="button" class="sidebar-button" id="btn-nl-records">
            <span>📋</span>
            <span>NL Records</span>
        </button>
    </div>

    <div class="main-content">
    <style media="print">
        @page { size: landscape; }
        h1, p, form, .no-print { display: none !important; }
        table { display: table !important; width: 100%; border-collapse: collapse; }
        tbody, thead, tr, td, th { display: table-cell !important; width: auto; height: auto; margin: 0; padding: 8px; border: 1px solid #000; }
        thead, tbody { display: table-row-group !important; }
        tr { display: table-row !important; page-break-inside: avoid; }
        .page-break { page-break-after: always; }
        .received-by { display: block; position: fixed; bottom: 20px; right: 20px; font-size: 14px; }
    </style>
    <h1>Admin Page</h1>
    <p>USHEL admin page</p>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 4px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 4px;">
            {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div style="background-color: #fff3cd; color: #856404; padding: 10px; margin-bottom: 20px; border: 1px solid #ffeaa7; border-radius: 4px;">
            {{ session('warning') }}
        </div>
    @endif

    @if(session('info'))
        <div style="background-color: #d1ecf1; color: #0c5460; padding: 10px; margin-bottom: 20px; border: 1px solid #bee5eb; border-radius: 4px;">
            {{ session('info') }}
        </div>
    @endif

    <!-- Global Action Buttons -->
    <div style="position: fixed; bottom: 5px; right: 5px; display: flex; flex-direction: column; gap: 5px; z-index: 999;">
        <button type="button" class="fab-main-button" id="openPendingODModal" style="
            min-width: 140px;
            height: 30px;
            border-radius: 10px;
            padding: 5px 10px;
            text-align: center;
            border: none;
            background-color: #2E7D32;
            color: white;
            font-size: 15px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            transition: all 0.25s ease;
        " title="Pending OD Approvals">
            👤 OD APPROVAL
        </button>
        
        <button type="button" class="fab-main-button" id="openAdminUsersModal" style="
            min-width: 140px;
            height: 30px;
            border-radius: 10px;
            padding: 5px 10px;
            text-align: center;
            border-radius: 10px;
            border: none;
            background-color: #1565C0;
            color: white;
            font-size: 15px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            transition: all 0.25s ease;
        " title="Admin Users">
            ⚙️ ADMINS
        </button>
    </div>

    <!-- Pending OD Approvals Modal -->
    <dialog class="largeModal" id="pendingODModal">
        <h3 style="margin-bottom: 20px;">Pending OD Approvals</h3>
        @if($pendingOfficers->isEmpty())
            <p style="text-align: center; padding: 30px; color: #757575;">No pending officer approvals.</p>
        @else
            <ul style="max-height: 400px; overflow-y: auto;">
                @foreach($pendingOfficers as $officer)
                    <li style="padding: 12px 0; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee;">
                        {{ $officer->name }}
                        <form action="{{ route('admin.officers.approve', $officer->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="fab-button fab-approve">
                                Approve
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
        <div class="dialog-actions" style="margin-top: 20px;">
            <button type="button" class="cancel-btn closePendingODModal">Close</button>
        </div>
    </dialog>

    <!-- Admin Users Modal -->
    <dialog class="largeModal" id="adminUsersModal">
        <h3 style="margin-bottom: 20px;">Admin Users</h3>
        <div style="margin-bottom: 15px; text-align: right;">
            <button type="button" class="fab-button fab-approve addAdminButton">Add New Admin</button>
        </div>
        @if($admins->isEmpty())
            <p style="text-align: center; padding: 30px; color: #757575;">No admin users found.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; max-height: 350px; overflow-y: auto; display: block;">
                <thead>
                    <tr style="border-bottom: 1px solid #ccc;">
                        <th style="text-align: left; padding: 8px; width: 40%;">Username</th>
                        <th style="text-align: left; padding: 8px; width: 30%;">Password</th>
                        <th style="text-align: left; padding: 8px; width: 30%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 8px;">{{ $admin->username }}</td>
                            <td style="padding: 8px;">••••••••</td>
                            <td style="padding: 8px;">
                                <button type="button" class="fab-button fab-edit editAdminButton" data-id="{{ $admin->id }}" data-username="{{ $admin->username }}">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <div class="dialog-actions" style="margin-top: 20px;">
            <button type="button" class="cancel-btn closeAdminUsersModal">Close</button>
        </div>
    </dialog>

    <!-- Dashboard Section -->
    <div id="dashboard-section">
    <!-- DashboardDD -->
    <div class="no-print" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc;">
        <h2>Dashboard</h2>
        
        <!-- Filters -->
        <form method="GET" action="{{ route('admin') }}" style="margin: 15px 0; padding: 15px; background: #f5f5f5; border-radius: 4px;">
            <input type="hidden" name="tab" value="dashboard">
            <p style="font-weight: bold; margin-bottom: 10px;">DASHBOARD FILTERS:</p>
            <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: end;">
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Line</label>
                    <select name="dash_line" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Lines</option>
                        @foreach($allLines as $line)
                            <option value="{{ $line }}" {{ request('dash_line') == $line ? 'selected' : '' }}>{{ $line }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Program</label>
                    <select name="dash_program" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Programs</option>
                        @foreach($allPrograms as $program)
                            <option value="{{ $program }}" {{ request('dash_program') == $program ? 'selected' : '' }}>{{ $program }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Province</label>
                    <select name="dash_province" id="dashProvince" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Provinces</option>
                        <option value="Aurora" {{ request('dash_province') == 'Aurora' ? 'selected' : '' }}>Aurora</option>
                        <option value="Nueva Ecija" {{ request('dash_province') == 'Nueva Ecija' ? 'selected' : '' }}>Nueva Ecija</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Municipality</label>
                    <select name="dash_municipality" id="dashMunicipality" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Municipalities</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Barangay</label>
                    <select name="dash_barangay" id="dashBarangay" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Barangays</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Source</label>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                        <label style="font-size: 12px;"><input type="checkbox" name="dash_source[]" value="OD" {{ is_array(request('dash_source')) && in_array('OD', request('dash_source')) ? 'checked' : '' }}> OD</label>
                        <label style="font-size: 12px;"><input type="checkbox" name="dash_source[]" value="Email" {{ is_array(request('dash_source')) && in_array('Email', request('dash_source')) ? 'checked' : '' }}> Email</label>
                        <label style="font-size: 12px;"><input type="checkbox" name="dash_source[]" value="Facebook" {{ is_array(request('dash_source')) && in_array('Facebook', request('dash_source')) ? 'checked' : '' }}> Facebook</label>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Date Filter</label>
                    <select name="dash_date_type" id="dashDateType" onchange="toggleDashDateFilters()" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Dates</option>
                        <option value="single" {{ request('dash_date_type') == 'single' ? 'selected' : '' }}>Specific Date</option>
                        <option value="range" {{ request('dash_date_type') == 'range' ? 'selected' : '' }}>Date Range</option>
                        <option value="month" {{ request('dash_date_type') == 'month' ? 'selected' : '' }}>Month</option>
                    </select>
                </div>
                <div id="dashSingleDate" class="dash-date-filter" style="display: {{ request('dash_date_type') == 'single' ? 'flex' : 'none' }}; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Date</label>
                    <input type="date" name="dash_date_single" value="{{ request('dash_date_single') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div id="dashDateRange" class="dash-date-filter" style="display: {{ request('dash_date_type') == 'range' ? 'flex' : 'none' }}; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">From</label>
                    <input type="date" name="dash_date_from" value="{{ request('dash_date_from') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div id="dashDateRangeTo" class="dash-date-filter" style="display: {{ request('dash_date_type') == 'range' ? 'flex' : 'none' }}; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">To</label>
                    <input type="date" name="dash_date_to" value="{{ request('dash_date_to') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div id="dashMonthFilter" class="dash-date-filter" style="display: {{ request('dash_date_type') == 'month' ? 'flex' : 'none' }}; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Month</label>
                    <input type="month" name="dash_date_month" value="{{ request('dash_date_month') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <button type="submit" style="padding: 8px 16px; background-color: #1976D2; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Filter</button>
                <a href="{{ route('admin') }}" style="padding: 8px 16px; background-color: #757575; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block; line-height: 20px;">Clear</a>
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
                } else if (dateType === 'month') {
                    document.getElementById('dashMonthFilter').style.display = 'flex';
                }
            }
        </script>
        
        {{-- Filtered Display Summary --}}
        @php
            $summaryParts = [];
            
            if(request('dash_barangay')) $summaryParts[] = 'Barangay ' . request('dash_barangay');
            if(request('dash_municipality')) $summaryParts[] = request('dash_municipality');
            if(request('dash_province')) $summaryParts[] = request('dash_province');
            if(request('dash_program')) $summaryParts[] = request('dash_program') . ' program';
            if(request('dash_line')) $summaryParts[] = request('dash_line') . ' line';
            
            // Handle multiple sources
            $sources = request('dash_source');
            if(is_array($sources) && count($sources) > 0) {
                $summaryParts[] = implode(', ', $sources) . ' source(s)';
            } elseif(is_string($sources) && !empty($sources)) {
                $summaryParts[] = $sources . ' source';
            }
            
            $dateText = '';
            if(request('dash_date_type') == 'single' && request('dash_date_single')) {
                $dateText = 'on ' . date('F j, Y', strtotime(request('dash_date_single')));
            } elseif(request('dash_date_type') == 'range' && request('dash_date_from') && request('dash_date_to')) {
                $dateText = 'from ' . date('F j, Y', strtotime(request('dash_date_from'))) . ' to ' . date('F j, Y', strtotime(request('dash_date_to')));
            } elseif(request('dash_date_type') == 'range' && request('dash_date_from') && !request('dash_date_to')) {
                $dateText = 'from ' . date('F j, Y', strtotime(request('dash_date_from')));
            } elseif(request('dash_date_type') == 'range' && !request('dash_date_from') && request('dash_date_to')) {
                $dateText = 'until ' . date('F j, Y', strtotime(request('dash_date_to')));
            } elseif(request('dash_date_type') == 'month' && request('dash_date_month')) {
                $dateText = 'for ' . date('F Y', strtotime(request('dash_date_month') . '-01'));
            }
            
            $summary = 'Displaying summary';
            if(count($summaryParts) > 0) {
                $summary .= ' for ' . implode(', ', $summaryParts);
            }
            if($dateText) {
                $summary .= ' ' . $dateText;
            }
        @endphp
        
        <div style="background: #e3f2fd; padding: 12px 15px; border-radius: 4px; margin: 15px 0; font-weight: 500; border-left: 4px solid #1976D2;">
            {{ $summary }}
        </div>

        <table style="width: 100%; border-collapse: collapse; margin: 15px 0;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th style="border: 1px solid #ccc; padding: 12px; text-align: left; width: 60%;">Metric</th>
                    <th style="border: 1px solid #ccc; padding: 12px; text-align: center; width: 40%;">Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid #ccc; padding: 10px; font-weight: 500;">Total Records</td>
                    <td style="border: 1px solid #ccc; padding: 10px; text-align: center; font-weight: bold; font-size: 16px;">{{ $totalRecords }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ccc; padding: 10px; font-weight: 500;">Recent Records (Last 7 days)</td>
                    <td style="border: 1px solid #ccc; padding: 10px; text-align: center; font-weight: bold; font-size: 16px;">{{ $recentRecords }}</td>
                </tr>
            </tbody>
        </table>

        <h4 style="margin: 20px 0 10px 0;">Records by Program</h4>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th style="border: 1px solid #ccc; padding: 10px; text-align: left; width: 70%;">Program Name</th>
                    <th style="border: 1px solid #ccc; padding: 10px; text-align: center; width: 30%;">Record Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recordsByProgram as $program => $count)
                <tr>
                    <td style="border: 1px solid #ccc; padding: 8px;">{{ $program }}</td>
                    <td style="border: 1px solid #ccc; padding: 8px; text-align: center; font-weight: 500;">{{ $count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="no-print" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc;">
        <h2>Active Officers of the Day</h2>
        @if($activeOfficers->isEmpty())
            <p style="padding: 15px; text-align: center; color: #757575;">No active officers currently logged in.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                    <tr style="background-color: #f0f0f0;">
                        <th style="border: 1px solid #ccc; padding: 10px; text-align: left;">#</th>
                        <th style="border: 1px solid #ccc; padding: 10px; text-align: left;">Officer Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeOfficers as $index => $officer)
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 8px; width: 50px; text-align: center;">{{ $index + 1 }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">{{ $officer->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    </div> <!-- END Dashboard Section -->

    <!-- NL Records Section -->
    <div id="nl-records-section" style="display: none;">

    <form action="{{ route('admin.logout') }}" method="POST" style="display: inline; margin-bottom: 20px;">
        @csrf
        <button type="submit" style="padding: 8px 16px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Go back</button>
    </form>

    <!-- Transmittal Management -->
    <div class="no-print" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9;">
        <label>
            <input type="checkbox" id="unassigned-toggle" {{ request('unassigned_only') ? 'checked' : '' }}>
            Show only records without admin transmittal numbers
        </label>
    </div>

    <!-- TABLE FILTERS -->
    <div class="no-print" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; background: #fff;">
        <h3 style="margin-top: 0; margin-bottom: 15px;">TABLE FILTERS</h3>
        <form method="GET" action="{{ route('admin') }}" style="margin: 0;">
            <input type="hidden" name="tab" value="nl-records">
            <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: end;">
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
                    <label style="font-size: 12px; font-weight: bold;">Date Encoded</label>
                    <input type="date" name="date_encoded" value="{{ request('date_encoded') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
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
    </div>

    <p>NL RECORDS:</p>
    <button id="delete-multiple" class="btn btn-danger">
        Delete Multiple
    </button>
    <button id="delete-selected" class="btn btn-warning" disabled>
        Delete Selected
    </button>
    <span id="bulk-selected-count" style="margin-left: 10px; color: #555; font-size: 13px;"></span>
    <button type="button" id="select-records-transmit" class="btn btn-info">
        Select Records for Transmit
    </button>
    <button type="button" id="transmit-selected-records" class="btn btn-success" disabled>
        Transmit Selected Records
    </button>
    <form id="bulk-form" method="POST" action="{{ route('admin.bulk-delete') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="record_ids" id="selected-record-ids">
        <div class="no-print" style="margin-bottom: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('admin.export-excel', request()->query()) }}" target="_blank" style="padding: 8px 16px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; text-decoration: none;">Export to CSV</a>
        </div>
        <div id="table-loading-indicator" style="display: none; margin-bottom: 10px; color: #1565C0; font-weight: 600;">Loading records...</div>
        <div style="overflow-x: auto; width: 100%; margin-bottom: 20px; border: 1px solid #ccc; position: relative;">
            <x-table :records="$records" :showEncoder="true" :showFilters="false" :showAdminTransmittal="true" :allPrograms="$allPrograms" :allLines="$allLines" :allSources="$allSources" :allModes="$allModes" :showCheckbox="false" />
        </div>
        @if($records->isEmpty())
            <div style="padding: 16px; margin-bottom: 12px; border: 1px solid #e0e0e0; background: #fafafa; color: #555;">
                No records found for the current filters.
            </div>
        @endif
        <div class="no-print" style="margin: 10px 0;">
            {{ $records->links() }}
        </div>
    </form>
    <div class="received-by">Received By: ____________________</div>
    <dialog class="editRecordDialog">
        <form class="editRecordform" method="POST">
            @csrf
            @method('PUT')
            <label for="farmerName">Farmer Name:</label>
            <input type="text" id="farmerName" name="farmerName">
            
            <label for="editProvince">Province:</label>
            <select name="province" id="editProvince" required>
                <option value="">Select Province</option>
                <option value="Aurora">Aurora</option>
                <option value="Nueva Ecija">Nueva Ecija</option>
            </select>
            
            <label for="editMunicipality">Municipality:</label>
            <select name="municipality" id="editMunicipality" required disabled>
                <option value="">Select Municipality</option>
            </select>
            
            <label for="editBarangay">Barangay:</label>
            <select name="barangay" id="editBarangay" required disabled>
                <option value="">Select Barangay</option>
            </select>
            
            <input type="hidden" name="address" id="editRecordAddress">
            
            <label for="line">Line:</label>
            <select name="line" id="line">
                <option value="">Select Line</option>
                <option value="rice">rice</option>
                <option value="corn">corn</option>
                <option value="high-value">High-Value Crops</option>
                <option value="clti">CLTI</option>
                <option value="livestock">Livestock</option>
                <option value="non-crop">Non-Crop</option>
                <option value="fisheries">Fisheries</option>
            </select>
            
            <label for="program">Program:</label>
            <select name="program" id="program">
                <option value="">Select Program</option>
                <option value="RSBSA">RSBSA</option>
                <option value="AGRI-SENSO">AGRI-SENSO</option>
                <option value="ACEF">ACEF</option>
                <option value="ANYO">ANYO</option>
                <option value="OTHER-LI LC">OTHER-LI LC</option>
                <option value="OTHER-LBP ACP">OTHER-LBP ACP</option>
                <option value="REGULAR">REGULAR</option>
                <option value="SELF-FINANCED">SELF-FINANCED</option>
            </select>
            
            <label for="source">Source:</label>
            <select name="source" id="source">
                <option value="">Select Source</option>
                <option value="OD">OD</option>
                <option value="Email">Email</option>
                <option value="Facebook">Facebook</option>
            </select>
            
            <label for="causeOfDamage">Cause of Damage:</label>
            <input type="text" id="causeOfDamage" name="causeOfDamage">
            
            <label for="modeOfPayment">Mode of payment:</label>
            <select name="modeOfPayment" id="modeOfPayment">
                <option value="">Select Mode of payment</option>
                <option value="check">Check</option>
                <option value="palawan">Palawan Pay</option>
                <option value="not_indicated">Not indicated</option>
            </select>
            <label for="accounts">Account (sender email/username):</label>
            <input type="text" id="accounts" name="accounts">
            <label for="date_occurrence">Date occurrence:</label>
            <input type="date" id="date_occurrence" name="date_occurrence">
            
            <label for="remarks">Remarks - Care of:</label>
            <input type="text" id="remarks" name="remarks">
            
            <label for="transmittal_number">Control Number:</label>
            <input type="text" id="transmittal_number" name="transmittal_number" placeholder="e.g., 2026-0420-001...">
            
            <label for="admin_transmittal_number">Admin Transmittal Number:</label>
            <input type="text" id="admin_transmittal_number" name="admin_transmittal_number" placeholder="e.g., 001, 002, 003...">

            <label for="clear_admin_transmittal_number" style="display: block; margin-top: 8px;">
                <input type="checkbox" id="clear_admin_transmittal_number" name="clear_admin_transmittal_number" value="1">
                Clear Admin Transmittal Number
            </label>
            
            <button type="submit">Update Record</button>
        </form>
        <button class="closeEditRecordDialog">Close</button>
    </dialog>
    <dialog class="deleteRecordDialog">
        <form class="deleteRecordForm" method="POST">
            @csrf
            @method('DELETE')
            <p>Delete this record?</p>
            <p class="deleteRecordMessage"></p>
            <button type="submit">Confirm Delete</button>
            <button type="button" class="cancelDeleteRecord">Cancel</button>
        </form>
    </dialog>

    <!-- Bulk Delete Confirmation Dialog -->
    <dialog class="bulkDeleteDialog">
        <h3>Confirm Bulk Delete</h3>
        <p>The following records will be deleted:</p>
        <ul class="bulk-delete-list"></ul>
        <p>Are you sure you want to proceed?</p>
        <button type="button" id="confirm-bulk-delete">Confirm Delete</button>
        <button type="button" class="cancelBulkDelete">Cancel</button>
    </dialog>

    <!-- Edit Admin Dialog -->
    <dialog class="editAdminDialog">
        <form class="editAdminForm" method="POST">
            @csrf
            @method('PUT')
            <h3>Edit Admin Credentials</h3>
            <label for="adminUsername">Username:</label>
            <input type="text" id="adminUsername" name="username" required>
            <label for="adminPassword">Password:</label>
            <input type="password" id="adminPassword" name="password" required placeholder="Enter new password">
            <button type="submit">Update</button>
            <button type="button" class="closeEditAdminDialog">Cancel</button>
        </form>
    </dialog>

    <!-- Approve Officer Dialog -->
    <dialog class="approveOfficerDialog">
        <h3>Confirm Officer Approval</h3>
        <p>Are you sure you want to approve <strong id="approveOfficerName"></strong>?</p>
        <form id="approveOfficerForm" method="POST">
            @csrf
            <div class="dialog-actions">
                <button type="submit" class="confirm-btn">Confirm Approve</button>
                <button type="button" class="cancel-btn closeApproveDialog">Cancel</button>
            </div>
        </form>
    </dialog>

    <!-- Add Admin Dialog -->
    <dialog class="addAdminDialog">
        <form action="{{ route('admin.users.create') }}" method="POST">
            @csrf
            <h3>Add New Admin User</h3>
            <label for="newAdminUsername">Username:</label>
            <input type="text" id="newAdminUsername" name="username" required>
            <label for="newAdminPassword">Password:</label>
            <input type="password" id="newAdminPassword" name="password" required placeholder="Minimum 6 characters">
            <div class="dialog-actions">
                <button type="submit" class="confirm-btn">Create Admin</button>
                <button type="button" class="cancel-btn closeAddAdminDialog">Cancel</button>
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

        // OD Approval Modal
        const openPendingODModal = document.getElementById('openPendingODModal');
        const pendingODModal = document.getElementById('pendingODModal');
        const closePendingODModal = document.querySelector('.closePendingODModal');

        if (openPendingODModal && pendingODModal) {
            openPendingODModal.addEventListener('click', function() {
                pendingODModal.showModal();
            });
        }

        if (closePendingODModal && pendingODModal) {
            closePendingODModal.addEventListener('click', function() {
                pendingODModal.close();
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
            const firstCheckboxCell = checkboxElements[0];
            if (!firstCheckboxCell) return;

            const isHidden = firstCheckboxCell.style.display === 'none';

            checkboxElements.forEach(el => {
                el.style.display = isHidden ? 'table-cell' : 'none';
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
            let isHidden = transmitCheckboxElements[0].style.display === 'none';
            
            transmitCheckboxElements.forEach(el => {
                el.style.display = isHidden ? 'table-cell' : 'none';
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
            }
        });
        const updateTransmitButtonState = () => {
            let anyChecked = Array.from(transmitCheckboxes).some(cb => cb.checked);
            if (transmitActionBtn) transmitActionBtn.disabled = !anyChecked;
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
    
    </div> <!-- END NL Records Section -->
    
    </div> <!-- END main-content -->

@endsection
