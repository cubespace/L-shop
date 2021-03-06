{{-- Layout and design by WhileD0S <https://vk.com/whiled0s>  --}}
@extends('layouts.shop')

@section('title')
    Документация по API L - Shop
@endsection

@section('js')
    <script type="text/javascript">
        hljs.initHighlightingOnLoad();
    </script>
@endsection

@section('content')
    <div id="content-container">
        <div class="z-depth-1 content-header text-center">
            <h1><i class="fa fa-cog fa-left-big"></i>Документация по API L - Shop</h1>
        </div>
        <div class="card card-block">
            <h3 class="card-title">Введение</h3>
            <p class="card-text">
                API (<b>a</b>pplication <b>p</b>rogramming <b>i</b>nterface) предоставляет удобный интерфейс для взаимодействия
                вашего приложения с L-Shop. Вы можете работать с L-Shop по средствам отправки http - запросов, следуя
                определенным правилам. Например, вы сможете авторизовывать пользователя, для того, чтобы ему не приходилось
                авторизовываться в магазине, если он уже вошёл в свой аккаунт на вашем ресурсе.
            </p>
            <h3 class="card-title">Основы</h3>
            <p class="card-text">
                Для взаимодействия с API вам необходимо отправить http-запрос (GET или POST, не важно) по определенному адресу.
                Каждый адрес соответствует опредленной функции, которая выполняется после успешной проверки подлинности данных.
                В зпросе необходимо передать какие-либо параметры, а также, контрольную сумму(hash) этих параметров.
                <p>
                    Пример GET - запроса на авторизацию пользователя:
                    <code>http://l-shop.ru/api/signin?username=d3lph1&hash=2080e2809b6ace6f6be9a1a22dba89aee2669ee1bb44e60476cfb9cf3d15c6a8</code>
                </p>
                <p>
                    Здесь:
                    <p><code>api</code> - эта часть url говорит о том, что запрос осуществяется к API</p>
                    <p><code>signin</code> - свидетельствует о том, что мы хоти авторизовать пользователя</p>
                    <p><code>username</code> - имя пользователя</p>
                    <p><code>hash</code> - контрольная сумма параметров запроса</p>
                </p>
            </p>
            <h4 class="card-title">Что такое хэш и с чем его едят</h4>
            <p class="card-text">
                Хэш (hash) или контрольная сумма - это строка фиксированной длины, полученная в результате выполнения
                определенного, необратимого алгоритма (алгоритмы бывают разные, <code>md5</code> - один из них) над
                исходной строкой. Захешировав строку,мы получаем хэш, однозначно (почти) идентифицирующий исходную строку.
                При этом получить эту самую исходную строку практически невозможно.
            <p>
                Например, в результате хэширования строки <code>test1234567890</code> алгоритмом sha256, мы получим
                вот такой хэш: <code>7afecb08fb6b7bab4bb45c755d857ae71d90ed2b37899f29b8bbc6ae758a5175</code>.
                Мы получили что-то вроде сигнатуры, однозначно идентифицирующей исходную строку,
                при этом, вычислить саму исходную строку, зная хэш, <strong>крайне</strong> затруднительно или
                невозможно вовсе.<br>Контрольная сумма берется от строки, построенной из параметров запроса
                и секретного API - ключа, который хранится у вас на сайте, а также здесь, в магазине. Изменить
                его вы можете в разделе <strong>Администрирование>Управление>API</strong>. Там же имеется возможность
                выбрать алгоритм расчета контрольной суммы, а также, установить разделитель параметров.
            </p>
            <h4 class="card-title">Составляем запрос</h4>
            <p>
                И так, представьте, что пользователь уже вошел в свой аккаунтам на вашем сайте. И вам необходимо
                выдать пользователю ссылку на магазин, работающей на L-Shop, да так, чтобы этот пользователь,
                перейдя по ссылке, автоматически вошел в свой акаунт магазина и был готов совершать покупки.
                Давайте составим запрос для этого дела.<br>
                Начало ссылки для API авторизации выглядит так: <code>http://l-shop.ru/api/signin?</code><br>
                Далее, следует передать параметр <code>username</code>, начением которого является имя пользователя
                (логин), которого требуется авторизовать.<br>
                Теперь составим контрольнуя сумму нашего запроса, для этого, Возьмем API - ключ, припишем к нему
                разделитель, и в конце концов, припишем к этому имя пользователя.<br>
                Например, если моим секреным ключем является строка <code>kR6rrpgUO2Hn3*aI?1~vHwvd~KcVUFIB</code>,
                разделителем - символ двоеточия (<code>:</code>), а имя пользователя - <code>d3lph1</code>,
                то в результате, мы получим вот такую строку: <code>kR6rrpgUO2Hn3*aI?1~vHwvd~KcVUFIB:d3lph1</code>.
                Возьмем хэш от этой строки тем алгоритмом, который вы указали в настройках API.<br>
                Например, если я использую алгоритм sha256, то результат будет таким:
                <code>22cc462bda02453b1bc7661a2045445756a9bffeb479d7668c3e03e7e4764da0</code>.<br>
                Припишем этот хэш, значением параметра <code>hash</code> нашего запроса.<br>
                В конце концов, получаем вот такой url:
                <code>http://l-shop.ru/api/signin?username=d3lph1&hash=22cc462bda02453b1bc7661a2045445756a9bffeb479d7668c3e03e7e4764da0</code>
            </p>
            <h3 class="card-title">Реализация</h3>
            <p>
                Конечно же, потребуется, так сказать, воплотить этот алгоритм в жизнь. Я написал для этого PHP - код,
                который составляет ссылку для авторизации пользователя.
            <pre class="php">
                <code>
/**
 * Разумеется, все эти данные вы будете вытаскивать из БД, конфига и тд.
 */
$key = 'kR6rrpgUO2Hn3*aI?1~vHwvd~KcVUFIB:d3lph1';   // Секретный ключ. Должен соответствовать секретному ключу в магазине.
$delimiter = ':';   // Разделитель параметров
$algo = 'sha256';   // Алгоритм расчета контрольной суммы
$url = 'http://l-shop.ru/api/signin?';  // Адрес API-авторизации
$username = 'D3lph1';   // Имя пользователя, которого необходимо авторизовать
$str = sprintf('%s%s%s', $key, $delimiter, $username);
$hash = hash($algo, $str);
$paramsStr = http_build_query([
    'username' => $username,
    'hash' => $hash
]);
$link = $url . $paramStr;   // Ссылка по переходу по которой, пользователь будет авторизован
                </code>
            </pre>
            </p>
            <h3 class="card-title">Информационная таблица</h3>
            <p>
                <div class="alert alert-info">
                    Здесь приведена информация для составления запроса и его контрольной суммы. Условные обозначения,
                    для удобства, находятся в фигурных скобках {}. Это означает, что писать эти скобки
                    <strong>не нужно</strong>.
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Функция</th>
                        <th>Параметры запроса</th>
                        <th>Схема строки, для расчета контрольной суммы</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Авторизация</td>
                        <td>username, hash</td>
                        <td>{КЛЮЧ}{РАЗДЕЛИТЕЛЬ}{ИМЯ ПОЛЬЗОВАТЕЛЯ}</td>
                    </tr>
                    </tbody>
                </table>
            </p>
        </div>
    </div>
@endsection
