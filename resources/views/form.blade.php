<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Invoice Form</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional: Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1.2rem rgba(0, 0, 0, .1);
        }

        .form-label {
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4e73df, #224abe);
            border: none;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card p-4">
                    <h3 class="card-title mb-4 text-center">Invoice Form</h3>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('invoice.submit') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="invoice_number" class="form-label">Invoice Number</label>
                                <input type="text" class="form-control" id="invoice_number" name="invoice_number"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="invoice_date" class="form-label">Invoice Date</label>
                                <input type="date" class="form-control" id="invoice_date" name="invoice_date"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="candidate_name" class="form-label">Candidate Name</label>
                                <input type="text" class="form-control" id="candidate_name" name="candidate_name"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="candidate_email" class="form-label">Candidate Email</label>
                            <input type="email" class="form-control" id="candidate_email" name="candidate_email"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="candidate_address" class="form-label">Candidate Address</label>
                            <textarea class="form-control" id="candidate_address" name="candidate_address" rows="2" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="package" class="form-label">Package</label>
                            <select class="form-select" id="package" name="package" required>
                                <option value="" selected disabled>Select Package</option>
                                <option value="career_starter">Career Starter</option>
                                <option value="growth_package">Growth Package</option>
                                <option value="career_acceleration">Career Acceleration</option>
                            </select>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Submit Invoice</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
