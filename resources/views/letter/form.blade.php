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
                    <h3 class="card-title mb-4 text-center">Letter Form</h3>

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
                        action="{{ isset($letter) ? route('letter.update', $letter->id) : route('letter.submit') }}">
                        @csrf

                        @isset($letter)
                            @method('PUT')
                        @endisset

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="letter_date" class="form-label">Letter Date</label>
                                <input type="date" class="form-control" id="letter_date" name="letter_date"
                                    value="{{ old('letter_date', $letter->letter_date ?? '') }}"
                                    placeholder="Select letter date" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="due_date" class="form-label">Acceptance TAT</label>
                                <input type="date" class="form-control" id="due_date" name="due_date"
                                    value="{{ old('due_date', $letter->due_date ?? '') }}" placeholder="Select due date"
                                    required>
                            </div>

                        </div>

                        <input type="hidden" id="candidate_name" name="candidate_name"
                            value="{{ old('candidate_name', $letter->candidate_name ?? '') }}">

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control name-field" id="first_name"
                                    placeholder="Enter first name">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control name-field" id="middle_name"
                                    placeholder="Enter middle name">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control name-field" id="last_name"
                                    placeholder="Enter last name">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="candidate_address" class="form-label">Job Title (max 60 letter)</label>
                            <input type="text" class="form-control" id="candidate_address" name="candidate_address"
                                placeholder="Enter Job Title (max 60 letter)" maxlength="63"
                                oninput="
                                    if(this.value.length > 63){
                                        this.value = this.value.substring(0, 63);
                                    }
                                "
                                required value="{{ old('candidate_address', $letter->candidate_address ?? '') }}">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                {{ isset($letter) ? 'Update Letter' : 'Submit Letter' }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('letter.list') }}" class="btn btn-primary">Letter List</a>
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

                fetch(`{{ route('letter.checkEmail') }}?email=${encodeURIComponent(email)}`)
                    .then(res => res.json())
                    .then(res => {
                        if (res.exists && res.data.id) {
                            // Redirect to edit page for this letter ID
                            const editUrl = `{{ url('/letter') }}/${res.data.id}/edit`;
                            window.location.href = editUrl;
                        } else {
                            // Email does not exist
                            emailInput.classList.remove('border-danger');
                            emailInput.classList.add('border-success');
                            emailNotice.textContent = "No existing candidate found";
                            emailNotice.classList.remove('text-danger');
                            emailNotice.classList.add('text-success');
                        }
                    })
                    .catch(err => console.error(err));
            };

            emailInput.addEventListener('input', checkEmail);
            emailInput.addEventListener('mouseup', checkEmail);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dueDate = document.getElementById('due_date');
            const installWrapper = document.getElementById('installAmtWrapper');
            const installAmt = document.getElementById('install_amt');
            const packageSelect = document.getElementById('package');

            // Package-wise max amounts
            const packageLimits = {
                career_starter: 2999,
                growth_package: 3999,
                career_acceleration: 4999
            };

            function getMaxAmount() {
                return packageLimits[packageSelect.value] || 0;
            }

            function toggleInstallAmt() {
                if (dueDate.value) {
                    installWrapper.style.display = 'block';
                    updateMaxAmount();
                } else {
                    installWrapper.style.display = 'none';
                    installAmt.value = '';
                }
            }

            function updateMaxAmount() {
                const max = getMaxAmount();
                installAmt.max = max;

                if (installAmt.value && installAmt.value > max) {
                    installAmt.value = max;
                }
            }

            // Initial state (edit mode support)
            toggleInstallAmt();

            // Show/Hide based on due date
            dueDate.addEventListener('change', toggleInstallAmt);

            // Update max when package changes
            packageSelect.addEventListener('change', updateMaxAmount);

            // Enforce max while typing
            installAmt.addEventListener('input', function() {
                const max = getMaxAmount();
                if (this.value > max) {
                    this.value = max;
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameFields = document.querySelectorAll('.name-field');

            nameFields.forEach(field => {
                field.addEventListener('input', function() {
                    // Remove non-English letters
                    let value = this.value.replace(/[^a-zA-Z]/g, '');

                    // Capitalize first letter only
                    if (value.length > 0) {
                        value = value.charAt(0).toUpperCase() + value.slice(1);
                    }

                    this.value = value;
                });
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const letterDate = document.getElementById('letter_date');
            const dueDate = document.getElementById('due_date');

            function syncDueDateMin() {
                if (letterDate.value) {
                    // Restrict due date to letter date or later
                    dueDate.min = letterDate.value;

                    // If due date is earlier, auto-correct it
                    if (dueDate.value && dueDate.value < letterDate.value) {
                        dueDate.value = letterDate.value;
                    }
                }
            }

            // Initial check (edit mode)
            syncDueDateMin();

            // Update restriction when letter date changes
            letterDate.addEventListener('change', syncDueDateMin);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('candidate_email');

            emailInput.addEventListener('input', function() {
                let value = this.value.toLowerCase(); // force lowercase

                // Remove all '@' characters
                value = value.replace(/#/g, '');

                // before '@' logic (now entire value) : letters, numbers, -, _, .
                value = value.replace(/[^a-z0-9\-_@\.]/g, '');

                this.value = value;
            });
        });
    </script>





</body>

</html>
