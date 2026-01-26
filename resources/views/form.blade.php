<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Form</title>

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

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif


                    <form method="POST"
                        action="{{ isset($invoice) ? route('invoice.update', $invoice->id) : route('invoice.submit') }}">
                        @csrf

                        @isset($invoice)
                            @method('PUT')
                        @endisset

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="invoice_number" class="form-label">Invoice Number</label>
                                <input type="text" class="form-control" id="invoice_number" name="invoice_number"
                                    value="{{ old('invoice_number', $invoice->invoice_number ?? $invoiceNumber) }}"
                                    placeholder="Auto-generated invoice number" readonly>
                            </div>

                            <div class="col-md-6">
                                <label for="invoice_date" class="form-label">Invoice Date</label>
                                <input type="date" class="form-control" id="invoice_date" name="invoice_date"
                                    value="{{ old('invoice_date', $invoice->invoice_date ?? '') }}"
                                    placeholder="Select invoice date" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date"
                                    value="{{ old('due_date', $invoice->due_date ?? '') }}"
                                    placeholder="Select due date" required>
                            </div>

                            <div class="col-md-6">
                                <label for="candidate_mobile" class="form-label">Candidate Mobile</label><br>
                                <input type="text" class="form-control" id="candidate_mobile" name="candidate_mobile"
                                    maxlength="20" inputmode="numeric"
                                    value="{{ old('candidate_mobile', $invoice->candidate_mobile ?? '') }}"
                                    placeholder="Enter mobile number" required>

                            </div>


                        </div>

                        <input type="hidden" id="candidate_name" name="candidate_name"
                            value="{{ old('candidate_name', $invoice->candidate_name ?? '') }}">

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name"
                                    placeholder="Enter first name">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name"
                                    placeholder="Enter middle name (optional)">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" placeholder="Enter last name">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="candidate_email" class="form-label">Candidate Email</label>
                            <input type="email" class="form-control" id="candidate_email" name="candidate_email"
                                value="{{ old('candidate_email', $invoice->candidate_email ?? '') }}"
                                placeholder="example@email.com" required>
                            <small id="emailNotice" class="form-text text-success mt-1"></small>
                        </div>






                        <div class="mb-3">
                            <label for="candidate_address" class="form-label">Candidate Address</label>
                            <textarea class="form-control" id="candidate_address" name="candidate_address" rows="3"
                                placeholder="Enter address (max 3 lines)" style="resize:none; overflow-y:hidden;"
                                oninput="
                                this.style.height='';
                                this.style.height=this.scrollHeight+'px';

                                let lines = this.value.split('\n');

                                if (lines.length > 3) {
                                    lines = lines.slice(0, 3);
                                }

                                lines = lines.map(line => line.substring(0, 36));
                                this.value = lines.join('\n');"
                                required>{{ old('candidate_address', $invoice->candidate_address ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="package" class="form-label">Package</label>
                            <select class="form-select" id="package" name="package" required>
                                <option value="" disabled>Select Package</option>

                                <option value="career_starter"
                                    {{ old('package', $invoice->package ?? '') == 'career_starter' ? 'selected' : '' }}>
                                    Career Starter
                                </option>

                                <option value="growth_package"
                                    {{ old('package', $invoice->package ?? '') == 'growth_package' ? 'selected' : '' }}>
                                    Growth Package
                                </option>

                                <option value="career_acceleration"
                                    {{ old('package', $invoice->package ?? '') == 'career_acceleration' ? 'selected' : '' }}>
                                    Career Acceleration
                                </option>
                            </select>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                {{ isset($invoice) ? 'Update Invoice' : 'Submit Invoice' }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('invoice.list') }}" class="btn btn-primary">Invoice List</a>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css" />

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js"></script>


    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const fullNameInput = document.getElementById('candidate_name');
            const first = document.getElementById('first_name');
            const middle = document.getElementById('middle_name');
            const last = document.getElementById('last_name');

            // 🔹 Split full name into parts (on load)
            if (fullNameInput.value) {
                let parts = fullNameInput.value.trim().split(/\s+/);
                first.value = parts[0] || '';
                last.value = parts.length > 1 ? parts[parts.length - 1] : '';
                middle.value = parts.length > 2 ? parts.slice(1, -1).join(' ') : '';
            }

            // 🔁 Combine names back into candidate_name
            function syncFullName() {
                fullNameInput.value = [first.value, middle.value, last.value]
                    .filter(Boolean)
                    .join(' ')
                    .trim();
            }

            first.addEventListener('input', syncFullName);
            middle.addEventListener('input', syncFullName);
            last.addEventListener('input', syncFullName);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const input = document.querySelector("#candidate_mobile");

            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                nationalMode: false,
                formatOnDisplay: true,
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                geoIpLookup: function(callback) {
                    fetch("https://ipapi.co/json/")
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("US"));
                }
            });

            // 🔹 Load existing value (edit mode)
            if (input.value) {
                iti.setNumber(input.value);
            }

            // 🔁 Format as XXX-XXX-XXXX while typing
            input.addEventListener('input', function() {
                let digits = input.value.replace(/\D/g, '').substring(0, 10);
                let formatted = digits;

                if (digits.length > 3 && digits.length <= 6) {
                    formatted = digits.slice(0, 3) + '-' + digits.slice(3);
                } else if (digits.length > 6) {
                    formatted = digits.slice(0, 3) + '-' + digits.slice(3, 6) + '-' + digits.slice(6);
                }

                input.value = formatted;
            });

            // 🔁 On submit → save full international number
            input.closest('form').addEventListener('submit', function() {
                input.value = iti.getNumber(); // +1XXXXXXXXXX
            });

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('candidate_email');
            const emailNotice = document.getElementById('emailNotice');

            if (!emailInput || !emailNotice) return;

            const checkEmail = () => {
                const email = emailInput.value.trim();
                if (!email) {
                    emailNotice.textContent = '';
                    emailInput.classList.remove('border-success', 'border-danger');
                    return;
                }

                fetch(`{{ route('invoice.checkEmail') }}?email=${encodeURIComponent(email)}`)
                    .then(res => res.json())
                    .then(res => {
                        if (res.exists) {
                            const d = res.data;

                            // Redirect to edit page for this invoice ID
                            if (d.id) {
                                const editUrl = `{{ url('/invoice') }}/${d.id}/edit`;
                                window.location.href = editUrl;
                                return; // stop further execution
                            }

                            // Fallback: if no ID returned, just fill the fields
                            document.getElementById('candidate_mobile').value = d.candidate_mobile ?? '';
                            document.getElementById('candidate_address').value = d.candidate_address ?? '';
                            document.getElementById('package').value = d.package ?? '';
                            document.getElementById('invoice_date').value = d.invoice_date ?? '';
                            document.getElementById('due_date').value = d.due_date ?? '';
                            document.getElementById('candidate_name').value = d.candidate_name ?? '';

                            emailInput.classList.remove('border-danger');
                            emailInput.classList.add('border-success');
                            emailNotice.textContent = "Existing candidate data loaded";
                            emailNotice.classList.remove('text-danger');
                            emailNotice.classList.add('text-success');
                        } else {
                            emailInput.classList.remove('border-success');
                            emailInput.classList.add('border-danger');
                            emailNotice.textContent = "No existing candidate found";
                            emailNotice.classList.remove('text-success');
                            emailNotice.classList.add('text-danger');
                        }
                    })
                    .catch(err => console.error(err));
            };

            emailInput.addEventListener('input', checkEmail);
            emailInput.addEventListener('mouseup', checkEmail);
        });
    </script>







</body>

</html>
