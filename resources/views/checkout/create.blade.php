<x-layouts.master>
    <div class="page-content-wrapper sp-y">
        <div class="container container-wide">
            <div class="row">
                <div class="col-lg-7">
                    <h2 class="mb-4">შეკვეთის გაფორმება</h2>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div id="checkout-installment-error" class="alert alert-danger d-none"></div>

                    <form id="checkout-form" method="POST" action="{{ route('checkout.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">სახელი</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ტელეფონი</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ქალაქი</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city') }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">მისამართი</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address') }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">შენიშვნა</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>
                    </form>

                    <div class="checkout-payments mt-4" data-sdk="{{ $bogPublicKey ? '1' : '0' }}"
                        data-configured="{{ $bogConfigured ? '1' : '0' }}" data-sandbox="{{ $bogSandbox ? '1' : '0' }}">
                        <button type="button" id="bog-bnpl-button" class="bog-bnpl-button">
                            <img src="{{ asset('assets/installment/bog-bnpl-button.png') }}" alt="ნაწილ-ნაწილ გადახდა">
                        </button>

                        <div class="checkout-installment">
                            <div class="checkout-installment__card">
                                <img src="{{ asset('assets/installment/bog.jpeg') }}" alt="Bank of Georgia"
                                    class="checkout-installment__logo">
                                <div class="checkout-installment__copy">
                                    <p class="checkout-installment__title mb-1">განვადება საქართველოს ბანკით</p>
                                    <p class="checkout-installment__text mb-3">აირჩიეთ განვადების პირობები. მინიმალური
                                        ვადა 5 თვეა.</p>
                                    @if ($bogSandbox && !$bogPublicKey)
                                        <span class="checkout-installment__badge">Sandbox / ტესტი</span>
                                    @endif
                                </div>
                            </div>
                            <button type="button" id="bog-installment-button" class="btn btn-brand mt-3">
                                მოითხოვე განვადება
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="border p-3">
                        <h4 class="mb-3">შეკვეთის შეჯამება</h4>
                        <ul class="list-unstyled">
                            @foreach ($items as $item)
                                <li class="d-flex justify-content-between mb-2">
                                    <span>
                                        {{ $item['product']->title }} × {{ $item['quantity'] }}
                                        <br>
                                        <x-installation-status :with-installation="$item['with_installation']" />
                                    </span>
                                    <strong>{{ $item['line_total'] }} ₾</strong>
                                </li>
                            @endforeach
                        </ul>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>ჯამი</strong>
                            <strong>{{ $total }} ₾</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="bog-demo-modal" class="bog-demo-modal" hidden>
        <div class="bog-demo-modal__dialog">
            <h3 class="bog-demo-modal__title">აირჩიეთ განვადების ვადა</h3>
            <p class="bog-demo-modal__text">ლოკალური sandbox ტესტი — ბანკის რეალური გასაღებები ჯერ არ არის ჩართული.</p>
            <div class="bog-demo-modal__plans">
                <button type="button" class="bog-demo-plan" data-plan="bnpl" data-month="4" data-code="ZERO" hidden>4 თვე</button>
                <button type="button" class="bog-demo-plan" data-plan="installment" data-month="5" data-code="standard">5 თვე</button>
                <button type="button" class="bog-demo-plan" data-plan="installment" data-month="6" data-code="standard">6 თვე</button>
                <button type="button" class="bog-demo-plan" data-plan="installment" data-month="12" data-code="standard">12 თვე</button>
            </div>
            <button type="button" id="bog-demo-close" class="btn btn-outline-secondary mt-3">დახურვა</button>
        </div>
    </div>

    @if ($bogPublicKey)
        <script src="https://webstatic.bog.ge/bog-sdk/bog-sdk.js?version=2&client_id={{ $bogPublicKey }}"></script>
    @endif
    <script>
        (function() {
            var form = document.getElementById('checkout-form');
            var errorBox = document.getElementById('checkout-installment-error');
            var bnplButton = document.getElementById('bog-bnpl-button');
            var installmentButton = document.getElementById('bog-installment-button');
            var box = document.querySelector('.checkout-payments');
            var demoModal = document.getElementById('bog-demo-modal');
            var sdkEnabled = box.getAttribute('data-sdk') === '1';
            var amount = {{ (int) $total }};
            var endpoint = @json(route('checkout.installment'));
            var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function showError(message) {
                if (!errorBox) {
                    return;
                }
                errorBox.textContent = message;
                errorBox.classList.remove('d-none');
            }

            function hideError() {
                if (!errorBox) {
                    return;
                }
                errorBox.classList.add('d-none');
                errorBox.textContent = '';
            }

            function closeCalculator() {
                if (window.BOG && window.BOG.Calculator && typeof window.BOG.Calculator.close === 'function') {
                    window.BOG.Calculator.close();
                }
            }

            function requestInstallment(month, discountCode, successCb, closeCb, plan) {
                var body = new FormData(form);
                body.append('month', month);
                body.append('plan', plan || 'bnpl');
                if (discountCode) {
                    body.append('discount_code', discountCode);
                }

                fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: body
                    })
                    .then(function(response) {
                        return response.json().then(function(data) {
                            return {
                                ok: response.ok,
                                data: data
                            };
                        });
                    })
                    .then(function(result) {
                        if (result.ok && result.data.orderId) {
                            if (typeof successCb === 'function') {
                                if (result.data.demo) {
                                    showError(
                                        'განვადების გასაგრძელებლად საჭიროა საქართველოს ბანკის API გასაღებები.');
                                    closeCalculator();
                                    return;
                                }
                                successCb(result.data.orderId);
                                return;
                            }
                            if (result.data.redirectUrl) {
                                window.location.href = result.data.redirectUrl;
                            }
                            return;
                        }

                        var message = 'განვადების შეკვეთა ვერ შეიქმნა.';
                        if (result.data && result.data.message) {
                            message = result.data.message;
                        } else if (result.data && result.data.errors) {
                            message = Object.values(result.data.errors).flat().join(' ');
                        }
                        showError(message);
                        if (typeof successCb === 'function') {
                            closeCalculator();
                            return;
                        }
                        if (closeCb) closeCb();
                    })
                    .catch(function() {
                        showError('განვადების შეკვეთა ვერ შეიქმნა.');
                        if (typeof successCb === 'function') {
                            closeCalculator();
                            return;
                        }
                        if (closeCb) closeCb();
                    });
            }

            function openDemoModal(plan) {
                var installment = plan === 'installment';
                demoModal.querySelector('.bog-demo-modal__title').textContent = installment ?
                    'აირჩიეთ განვადების ვადა' :
                    'ნაწილ-ნაწილ გადახდა';
                document.querySelectorAll('.bog-demo-plan').forEach(function(planButton) {
                    planButton.hidden = planButton.getAttribute('data-plan') !== plan;
                });
                demoModal.hidden = false;
            }

            function closeDemoModal() {
                demoModal.hidden = true;
            }

            function limitInstallmentMonths(data) {
                var changed = false;

                if (!data || typeof data !== 'object') {
                    return false;
                }

                if (Array.isArray(data.discounts) && data.discounts.some(function(item) {
                        return item && item.month != null;
                    })) {
                    data.discounts = data.discounts.filter(function(item) {
                        return Number(item.month) >= 5;
                    });
                    changed = true;
                }

                if (data.ranges && limitInstallmentMonths(data.ranges)) {
                    changed = true;
                }

                return changed;
            }

            function openCalculator(options, limitMonths) {
                if (!limitMonths) {
                    window.BOG.Calculator.open(options);
                    return;
                }

                var originalParse = JSON.parse;
                var restored = false;

                function restore() {
                    if (restored) {
                        return;
                    }
                    restored = true;
                    JSON.parse = originalParse;
                }

                JSON.parse = function(text, reviver) {
                    var data = originalParse.call(JSON, text, reviver);
                    if (limitInstallmentMonths(data)) {
                        restore();
                    }
                    return data;
                };

                window.setTimeout(restore, 10000);
                window.BOG.Calculator.open(options);
            }

            function calculatorOptions(plan) {
                var installment = plan === 'installment';

                return {
                    amount: amount,
                    bnpl: installment ? false : true,
                    onClose: function() {},
                    onRequest: function(selected, successCb, closeCb) {
                        if (installment && Number(selected.month) < 5) {
                            showError('განვადების მინიმალური ვადა 5 თვეა.');
                            if (typeof closeCb === 'function') {
                                closeCb();
                            }
                            return false;
                        }

                        requestInstallment(selected.month, selected.discount_code, successCb, closeCb, plan);
                        return false;
                    },
                    onComplete: function(payload) {
                        if (payload && payload.redirectUrl) {
                            window.location.href = payload.redirectUrl;
                            return false;
                        }
                    }
                };
            }

            function startPayment(plan) {
                hideError();

                if (!form.reportValidity()) {
                    return;
                }

                if (!sdkEnabled || !window.BOG || !window.BOG.Calculator) {
                    openDemoModal(plan);
                    return;
                }

                openCalculator(calculatorOptions(plan), plan === 'installment');
            }

            bnplButton.addEventListener('click', function() {
                startPayment('bnpl');
            });
            installmentButton.addEventListener('click', function() {
                startPayment('installment');
            });
            document.getElementById('bog-demo-close').addEventListener('click', closeDemoModal);
            demoModal.addEventListener('click', function(event) {
                if (event.target === demoModal) {
                    closeDemoModal();
                }
            });
            document.querySelectorAll('.bog-demo-plan').forEach(function(planButton) {
                planButton.addEventListener('click', function() {
                    closeDemoModal();
                    requestInstallment(
                        planButton.getAttribute('data-month'),
                        planButton.getAttribute('data-code'),
                        null,
                        null,
                        planButton.getAttribute('data-plan')
                    );
                });
            });
        })();
    </script>
</x-layouts.master>
