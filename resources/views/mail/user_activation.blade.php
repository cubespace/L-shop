Привет, {{ $username }}!
Мы рады видеть нового пользователя, который стал частью нашего проекта.
Для окончания процедуры регистрации, тебе необходимо перейти по ссылке:
<a href="{{ route('activate', ['user' => $userId, 'code' => $code]) }}">ТЫК...</a>
