<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name', 'Laravel') }} - Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f5f7fb;">

    <div class="container py-5">

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Heading -->
        <div class="text-center mb-5">
            <h2 class="fw-bold">Our Services</h2>
            <p class="text-muted">Select a service to continue</p>
        </div>

        <!-- Services Grid -->
        <div class="row g-4">

            <!-- Form Creation -->
            <div class="col-md-4">
                <a href="{{ route('invoice.form') }}" class="text-decoration-none text-dark">
                    <div class="card border-0 shadow-sm h-100 text-center p-3 hover-shadow">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-ui-checks-grid fs-1 text-primary"></i>
                            </div>
                            <h5 class="card-title fw-semibold">Invoice Creation</h5>
                            <p class="text-muted small">
                                Create invoice using form.
                            </p>
                            <span class="btn btn-primary btn-sm">Form</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Selection Letter -->
            <div class="col-md-4">
                <a href="{{ route('letter.form') }}"
                    class="card border-0 shadow-sm h-100 text-center p-3 text-decoration-none text-dark hover-shadow">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-envelope fs-1 text-success"></i>
                        </div>
                        <h5 class="card-title fw-semibold">Selection Letter</h5>
                        <p class="text-muted small">
                            Create Selection Letter.
                        </p>
                        <span class="btn btn-success btn-sm">Letter</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons (optional but recommended) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
