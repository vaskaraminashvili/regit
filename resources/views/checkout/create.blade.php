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

                    <form method="POST" action="{{ route('checkout.store') }}">
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
                </div>
                <div class="col-lg-5">
                    <div class="border p-3">
                        <h4 class="mb-3">შეკვეთის შეჯამება</h4>
                        <ul class="list-unstyled">
                            @foreach($items as $item)
                                <li class="d-flex justify-content-between mb-2">
                                    <span>{{ $item['product']->title }} × {{ $item['quantity'] }}</span>
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
</x-layouts.master>
