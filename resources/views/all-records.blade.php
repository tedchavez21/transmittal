@extends('layout.layout')

@section('title', 'All NL Records')

@section('page-styles')
<style>
/* System-matched navigation bar */
.records-nav {
    position: sticky;
    top: 0;
    z-index: 30;
    padding: 0;
    border: none;
    border-radius: 0;
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    backdrop-filter: blur(20px);
    box-shadow: 0 4px 20px rgba(0, 108, 53, 0.08), 0 1px 3px rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(0, 108, 53, 0.1);
}

.nav-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 10px 20px;
    min-height: 52px;
}

.nav-left {
    display: flex;
    align-items: center;
    flex: 1;
}

.nav-right {
    display: flex;
    align-items: center;
}

.nav-brand {
    display: flex;
    align-items: center;
    gap: 16px;
}

.nav-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #006c35 0%, #008a43 100%);
    border-radius: 10px;
    color: white;
    box-shadow: 0 4px 12px rgba(0, 108, 53, 0.25);
    transition: all 0.3s ease;
}

.nav-icon:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 108, 53, 0.35);
}

.nav-icon svg {
    width: 18px;
    height: 18px;
}

.nav-text h1 {
    font-size: 16px;
    margin: 0;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.nav-text p {
    margin: 2px 0 0 0;
    font-size: 11px;
    color: #64748b;
    font-weight: 600;
    letter-spacing: 0.02em;
    opacity: 0.8;
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.back-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    color: #475569;
    font-weight: 600;
    font-size: 11px;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.back-btn:hover {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border-color: #006c35;
    color: #006c35;
    box-shadow: 0 2px 8px rgba(0, 108, 53, 0.15);
    transform: translateY(-1px);
}

.back-btn svg {
    width: 16px;
    height: 16px;
    transition: transform 0.2s ease;
}

.back-btn:hover svg {
    transform: translateX(-2px);
}

/* Main content area */
.records-main {
    padding: 12px 12px 16px 12px;
    min-height: calc(100vh - 72px);
    overflow-y: auto;
    overscroll-behavior: contain;
}

/* System-matched filter section */
.table-filters {
    border-radius: 16px !important;
    border: 1px solid var(--border) !important;
    background: rgba(255,255,255,0.92) !important;
    box-shadow: 0 10px 24px rgba(0,0,0,0.06) !important;
    margin-bottom: 8px !important;
    padding: 8px !important;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 8px;
    align-items: start;
}

.form-field {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.form-field label {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-field input,
.form-field select {
    padding: 6px 8px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 12px;
    background: white;
    transition: all 0.2s;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.form-field input:focus,
.form-field select:focus {
    outline: none;
    border-color: #006c35;
    box-shadow: 0 0 0 3px rgba(0, 108, 53, 0.1);
}

.filter-actions {
    display: flex;
    gap: 6px;
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid #e2e8f0;
    justify-content: flex-end;
}

.btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-primary {
    background: linear-gradient(135deg, #006c35 0%, #008a43 100%);
    color: white;
    box-shadow: 0 2px 4px rgba(0, 108, 53, 0.2);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #005428 0%, #006c35 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 108, 53, 0.3);
}

.btn-muted {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
}

.btn-muted:hover {
    background: #e2e8f0;
    color: #475569;
}

/* System-matched table container */
.table-wrapper {
    width: 100%;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    max-height: calc(100vh - 380px);
    overflow-y: auto;
    overflow-x: auto;
}

/* System-matched table styling */
table {
    width: 100%;
    max-width: 1200px;
    border-collapse: separate;
    border-spacing: 0;
    border: 1px solid rgba(15, 23, 42, 0.10);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
    border-radius: 14px;
    overflow: hidden;
    margin: 0;
}

table thead tr:first-child {
    background: linear-gradient(135deg, rgba(0, 108, 53, 0.96) 0%, rgba(99, 140, 8, 0.90) 100%);
}


table th {
    padding: 8px 10px;
    text-align: left;
    font-weight: 900;
    font-size: 12px;
    color: white;
    white-space: nowrap;
}

table tbody tr {
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    transition: background-color 0.2s ease;
}

table tbody tr:nth-child(odd) {
    background-color: #ffffff;
}

table tbody tr:nth-child(even) {
    background-color: rgba(248, 250, 252, 0.65);
}

table tbody tr:hover {
    background-color: rgba(220, 252, 231, 0.55);
}

table td {
    padding: 8px 10px;
    font-size: 12px;
    color: #334155;
}

table td:last-child {
    color: var(--primary);
}

/* System-matched view button */
table input[type="checkbox"] {
    cursor: pointer;
    width: 18px;
    height: 18px;
    accent-color: #006c35;
}

.view-btn {
    background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 2px 4px rgba(14, 165, 233, 0.2);
}

.view-btn:hover {
    background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(14, 165, 233, 0.3);
}

/* System-matched pagination */
.pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 12px;
    padding: 8px 12px;
    background: rgba(255,255,255,0.92);
    border-radius: 12px;
    border: 1px solid var(--border);
    box-shadow: 0 10px 24px rgba(0,0,0,0.06);
}

.pagination-link {
    padding: 6px 12px;
    border-radius: 6px;
    background: linear-gradient(135deg, #006c35 0%, #008a43 100%);
    color: white;
    text-decoration: none;
    font-weight: 500;
    font-size: 12px;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 108, 53, 0.1);
}

.pagination-link:hover {
    background: linear-gradient(135deg, #005a2d 0%, #006c35 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 108, 53, 0.2);
}

.pagination-info {
    padding: 6px 12px;
    border-radius: 6px;
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    font-size: 12px;
    border: 1px solid #e2e8f0;
}

/* System-matched modal */
.largeModal {
    border-radius: 16px !important;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
    background: white !important;
    backdrop-filter: blur(8px) !important;
}

.modal-header {
    padding: 20px 24px 16px;
    border-bottom: 1px solid #e2e8f0;
    background: linear-gradient(135deg, rgba(0, 108, 53, 0.96) 0%, rgba(99, 140, 8, 0.90) 100%);
    color: white;
    border-radius: 16px 16px 0 0;
}

.modal-title {
    font-size: 18px;
    font-weight: 900;
    margin: 0;
    color: white;
}

.modal-body {
    padding: 20px 24px;
    max-height: 60vh;
    overflow-y: auto;
}

.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
    border-radius: 0 0 16px 16px;
    text-align: right;
}

.record-detail {
    display: grid;
    grid-template-columns: 180px 1fr;
    gap: 16px;
    padding: 12px 0;
    border-bottom: 1px solid #e2e8f0;
    align-items: start;
}

.record-detail:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 700;
    color: #374151;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    color: #64748b;
    font-size: 14px;
    font-weight: 500;
    word-break: break-word;
}

.close-btn {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 2px 4px rgba(100, 116, 139, 0.2);
}

.close-btn:hover {
    background: linear-gradient(135deg, #475569 0%, #334155 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(100, 116, 139, 0.3);
}

/* Responsive design */
@media (max-width: 768px) {
    .nav-content {
        padding: 12px 16px;
        gap: 16px;
        min-height: 60px;
    }
    
    .nav-icon {
        width: 40px;
        height: 40px;
    }
    
    .nav-icon svg {
        width: 20px;
        height: 20px;
    }
    
    .nav-text h1 {
        font-size: 18px;
    }
    
    .nav-text p {
        font-size: 12px;
    }
    
    .filter-grid {
        grid-template-columns: 1fr;
    }
    
    .record-detail {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    table {
        font-size: 12px;
    }
    
    table th,
    table td {
        padding: 10px 8px;
    }
}
</style>
@endsection

@section('content')
<!-- Navigation Bar -->
<nav class="records-nav no-print">
    <div class="nav-content">
        <div class="nav-left">
            <div class="nav-brand">
                <div class="nav-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="nav-text">
                    <h1>All NL Records</h1>
                    <p>View and search all Notice of Loss records</p>
                </div>
            </div>
        </div>
        <div class="nav-right">
            <div class="nav-actions">
                <a href="{{ route('welcome') }}" class="back-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    <span>Back to Home</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="records-main">
    <!-- Filters Section -->
    <div class="no-print table-filters">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #006c35 0%, #008a43 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
            </div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #1e293b;">Table Filters</h3>
        </div>

        <form id="filterForm" method="GET" action="{{ route('all-records') }}">
            <div class="filter-grid">
                <div class="form-field">
                    <label>Search Farmer</label>
                    <input type="text" name="farmerName" value="{{ request('farmerName') }}" placeholder="Enter farmer name">
                </div>
                
                <div class="form-field">
                    <label>Search Encoder</label>
                    <input type="text" name="encoderName" value="{{ request('encoderName') }}" placeholder="Enter encoder name">
                </div>
                
                <div class="form-field">
                    <label>Source</label>
                    <select name="source">
                        <option value="">All Sources</option>
                        @foreach($allSources as $source)
                            <option value="{{ $source }}" {{ request('source') == $source ? 'selected' : '' }}>{{ $source }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-field">
                    <label>Municipality</label>
                    <input type="text" name="municipality" value="{{ request('municipality') }}" placeholder="Enter municipality">
                </div>
                
                <div class="form-field">
                    <label>Date Received</label>
                    <input type="date" name="date_received" value="{{ request('date_received') }}">
                </div>
                
                <div class="form-field">
                    <label>Date Encoded</label>
                    <input type="date" name="created_at" value="{{ request('created_at') }}">
                </div>
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary" form="filterForm">Apply Filters</button>
                <a href="{{ route('all-records') }}" class="btn btn-muted">Clear All</a>
            </div>
        </form>
    </div>

    <!-- Records Table -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Farmer</th>
                    <th>Encoder</th>
                    <th>Source</th>
                    <th>Municipality</th>
                    <th>Date Received</th>
                    <th>Date Encoded</th>
                    <th>Date of Occurrence</th>
                    <th>TRANSMITTAL #</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td>{{ $record->farmerName }}</td>
                        <td>{{ $record->encoderName }}</td>
                        <td>{{ $record->source }}</td>
                        <td>{{ $record->municipality }}</td>
                        <td>{{ $record->date_received ? \Carbon\Carbon::parse($record->date_received)->format('M d, Y') : 'N/A' }}</td>
                        <td>{{ $record->created_at->format('M d, Y') }}</td>
                        <td>{{ $record->date_occurrence ? (function($date) { try { return \Carbon\Carbon::parse($date)->format('M d, Y'); } catch (\Exception $e) { return $date; } })($record->date_occurrence) : 'N/A' }}</td>
                        <td>{{ $record->admin_transmittal_number ?? 'N/A' }}</td>
                        <td>
                            <button class="view-btn" onclick="viewRecord({{ $record->id }})">View</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 40px; color: #64748b; font-weight: 500;">
                            No records found matching your filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($records->hasPages())
        <div class="pagination-container">
            @if ($records->onFirstPage())
                <span class="pagination-info">Previous</span>
            @else
                <a href="{{ $records->appends(request()->query())->previousPageUrl() }}" class="pagination-link">Previous</a>
            @endif
            
            <span class="pagination-info">
                Page {{ $records->currentPage() }} of {{ $records->lastPage() }}
            </span>
            
            @if ($records->hasMorePages())
                <a href="{{ $records->appends(request()->query())->nextPageUrl() }}" class="pagination-link">Next</a>
            @else
                <span class="pagination-info">Next</span>
            @endif
        </div>
    @endif
</main>

<!-- View Record Modal -->
<dialog class="largeModal rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(640px,calc(100vw-2rem))]" id="viewModal">
    <div class="modal-header">
        <h3 class="modal-title">Record Details</h3>
    </div>
    <div class="modal-body" id="modalBody">
        <!-- Record details will be loaded here -->
    </div>
    <div class="modal-footer">
        <button class="close-btn" onclick="closeModal()">Close</button>
    </div>
</dialog>

<script>
// Ensure filter form works properly
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            // Let the form submit normally
            return true;
        });
    }
});

function viewRecord(recordId) {
    fetch(`/api/records/${recordId}`)
        .then(response => response.json())
        .then(data => {
            const modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = `
                <div class="record-detail">
                    <div class="detail-label">Farmer Name:</div>
                    <div class="detail-value">${data.farmerName}</div>
                </div>
                <div class="record-detail">
                    <div class="detail-label">Encoder Name:</div>
                    <div class="detail-value">${data.encoderName}</div>
                </div>
                <div class="record-detail">
                    <div class="detail-label">Source:</div>
                    <div class="detail-value">${data.source}</div>
                </div>
                <div class="record-detail">
                    <div class="detail-label">Municipality:</div>
                    <div class="detail-value">${data.municipality}</div>
                </div>
                <div class="record-detail">
                    <div class="detail-label">Barangay:</div>
                    <div class="detail-value">${data.barangay || 'N/A'}</div>
                </div>
                <div class="record-detail">
                    <div class="detail-label">Date Received:</div>
                    <div class="detail-value">${data.date_received || 'N/A'}</div>
                </div>
                <div class="record-detail">
                    <div class="detail-label">Date Encoded:</div>
                    <div class="detail-value">${data.created_at}</div>
                </div>
                <div class="record-detail">
                    <div class="detail-label">Transmittal #:</div>
                    <div class="detail-value">${data.admin_transmittal_number || 'N/A'}</div>
                </div>
                <div class="record-detail">
                    <div class="detail-label">Remarks:</div>
                    <div class="detail-value">${data.remarks || 'N/A'}</div>
                </div>
                <div class="record-detail">
                    <div class="detail-label">Line:</div>
                    <div class="detail-value">${data.line || 'N/A'}</div>
                </div>
                <div class="record-detail">
                    <div class="detail-label">Payment Method:</div>
                    <div class="detail-value">${data.modeOfPayment || 'N/A'}</div>
                </div>
            `;
            
            const modal = document.getElementById('viewModal');
            if (typeof modal.showModal === 'function') {
                modal.showModal();
            } else {
                modal.setAttribute('open', 'open');
            }
        })
        .catch(error => {
            console.error('Error fetching record:', error);
            showModalMessage('Error loading record details', 'error');
        });
}

function closeModal() {
    const modal = document.getElementById('viewModal');
    modal.close();
}

// Close modal when clicking outside
document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection
