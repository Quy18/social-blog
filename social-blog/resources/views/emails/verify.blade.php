<x-mail::message>
# Xác minh email của bạn

Xin chào {{ $user->name }},

Nhấn vào nút bên dưới để xác thực email:

<x-mail::button :url="$url">
Verify Email
</x-mail::button>

Nếu bạn không yêu cầu tạo tài khoản, vui lòng bỏ qua email này.

Cảm ơn,<br>
{{ config('app.name') }}
</x-mail::message>

