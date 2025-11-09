@if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<h2>Vui lòng xác minh email</h2>
<p>Một email xác minh đã được gửi tới địa chỉ của bạn.</p>

<form method="POST" action="{{ route('verification.send') }}">
  @csrf
  <button type="submit">Gửi lại email xác minh</button>
</form>
