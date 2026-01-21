<!DOCTYPE html>
<html>
<head>
    <title>Invoice List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Invoice List</h3>
        <a href="{{ route('home') }}" class="btn btn-primary">Create Invoice</a>
    </div>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Candidate</th>
                <th>Email</th>
                <th>Package</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($invoices as $invoice)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $invoice->invoice_number }}</td>
                <td>{{ $invoice->invoice_date }}</td>
                <td>{{ $invoice->candidate_name }}</td>
                <td>{{ $invoice->candidate_email }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $invoice->package)) }}</td>
                <td>
                    <a href="{{ route('invoice.pdf', $invoice->id) }}"
                       class="btn btn-sm btn-success">
                        View Invoice
                    </a>
                    <a href="{{ route('invoice.pdf', $invoice->id) }}"
                       class="btn btn-sm btn-success">
                        Edit Invoice
                    </a>
                    <a href="{{ route('invoice.pdf', $invoice->id) }}"
                       class="btn btn-sm btn-success">
                        Delete Invoice
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No invoices found</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $invoices->links('pagination::bootstrap-5') }}
    </div>
</div>

</body>
</html>
