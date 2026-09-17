<x-layouts.master>
    <div class="page-content-wrapper sp-y">
        <div class="container container-wide">
            <div class="row">
                <div class="col-lg-7">
                    <h2 class="mb-4">შეკვეთის გაფორმება</h2>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
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
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ტელეფონი</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ქალაქი</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">მისამართი</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">შენიშვნა</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-brand">შეკვეთის დადასტურება</button>
                    </form>

                    <div class="checkout-installment mt-4"
                         data-configured="{{ $bogConfigured ? '1' : '0' }}"
                         data-sandbox="{{ $bogSandbox ? '1' : '0' }}">
                        <div class="checkout-installment__card">
                            <img src="{{ asset('assets/installment/bog.jpeg') }}" alt="Bank of Georgia" class="checkout-installment__logo">
                            <div class="checkout-installment__copy">
                                <p class="checkout-installment__title mb-1">განვადება საქართველოს ბანკით</p>
                                <p class="checkout-installment__text mb-3">აირჩიეთ განვადების პირობები და გადაიხადეთ ყოველთვიურად.</p>
                                @if($bogSandbox)
                                    <span class="checkout-installment__badge">Sandbox / ტესტი</span>
                                @endif
                            </div>
                        </div>
                        <button type="button" id="bog-installment-button" class="btn btn-brand mt-3">
                            მოითხოვე განვადება
                        </button>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="border p-3">
                        <h4 class="mb-3">შეკვეთის შეჯამება</h4>
                        <ul class="list-unstyled">
                            @foreach($items as $item)
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
                <button type="button" class="bog-demo-plan" data-month="3">3 თვე</button>
                <button type="button" class="bog-demo-plan" data-month="6">6 თვე</button>
                <button type="button" class="bog-demo-plan" data-month="12">12 თვე</button>
            </div>
            <button type="button" id="bog-demo-close" class="btn btn-outline-secondary mt-3">დახურვა</button>
        </div>
    </div>

    @if($bogConfigured)
        <script src="https://webstatic.bog.ge/bog-sdk/bog-sdk.js?version=2&client_id={{ $bogClientId }}"></script>
    @endif
    <script>
        (function () {
            var form = document.getElementById('checkout-form');
            var errorBox = document.getElementById('checkout-installment-error');
            var button = document.getElementById('bog-installment-button');
            var box = document.querySelector('.checkout-installment');
            var demoModal = document.getElementById('bog-demo-modal');
            var configured = box.getAttribute('data-configured') === '1';
            var amount = {{ (int) $total }};
            var endpoint = @json(route('checkout.installment'));
            var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function showError(message) {
                errorBox.textContent = message;
                errorBox.classList.remove('d-none');
            }

            function hideError() {
                errorBox.classList.add('d-none');
                errorBox.textContent = '';
            }

            function requestInstallment(month, discountCode, successCb, closeCb) {
                var body = new FormData(form);
                body.append('month', month);
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
                    .then(function (response) {
                        return response.json().then(function (data) {
                            return { ok: response.ok, data: data };
                        });
                    })
                    .then(function (result) {
                        if (result.ok && result.data.orderId) {
                            if (result.data.demo && result.data.redirectUrl) {
                                window.location.href = result.data.redirectUrl;
                                return;
                            }
                            if (successCb) {
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
                        if (closeCb) closeCb();
                    })
                    .catch(function () {
                        showError('განვადების შეკვეთა ვერ შეიქმნა.');
                        if (closeCb) closeCb();
                    });
            }

            function openDemoModal() {
                demoModal.hidden = false;
            }

            function closeDemoModal() {
                demoModal.hidden = true;
            }

            function openCalculator() {
                hideError();

                if (!form.reportValidity()) {
                    return;
                }

                if (!configured || !window.BOG || !window.BOG.Calculator) {
                    openDemoModal();
                    return;
                }

                window.BOG.Calculator.open({
                    amount: amount,
                    onRequest: function (selected, successCb, closeCb) {
                        requestInstallment(selected.month, selected.discount_code, successCb, closeCb);
                    }
                });
            }

            button.addEventListener('click', openCalculator);
            document.getElementById('bog-demo-close').addEventListener('click', closeDemoModal);
            demoModal.addEventListener('click', function (event) {
                if (event.target === demoModal) {
                    closeDemoModal();
                }
            });
            document.querySelectorAll('.bog-demo-plan').forEach(function (planButton) {
                planButton.addEventListener('click', function () {
                    closeDemoModal();
                    requestInstallment(planButton.getAttribute('data-month'), 'standard');
                });
            });
        })();
    </script>
</x-layouts.master>
