

# Understanding Http Protocol servers

### Content-Type 
**IMAGE Content-Type**<br/>
JPEG (JPG): image/jpeg<br/>
PNG: image/png<br/>
GIF: image/gif<br/>
SVG: image/svg+xml<br/>
BMP: image/bmp<br/>
WEBP: image/webp<br/>
ICO: image/x-icon<br/>

~~~java
//php
header("Content-Type: image/png");
~~~

### Cache-Control

~~~java
//php
header("Cache-Control: public, max-age=3600"); 
~~~

public ： リソースを全てのユーザーにキャッシュできるように設定<br/>
max-age=3600: リソースを3600秒(1時間)キャッシュ<br/>

### Set-Cookie

~~~java
//php
setcookie("user_id", "12345", time() + 3600, "/", "", true, true); 
~~~
**setcookie関数引数**
「user_id」 ： クッキー名 <br/>
「12345」 ： クッキーの値<br/>
time() + 3600: クッキーの有効期限(現在時刻から1時間)<br/>
「/」 ： クッキーのパス（サイト全体で有効）<br/>
「」 ： ドメイン<br/>
true: HTTPS専用設定（Secure）<br/>
true：JavaScriptでアクセス不可（HttpOnly）<br/>

### Set Session & Cookie

~~~java
//php
$response = "HTTP/1.1 200 OK\r\n";
$response .= "Content-Type: $contentType\r\n";
$response .= "Content-Length: " . strlen($fileContent) . "\r\n";
$response .= "Cache-Control: public, max-age=3600\r\n"; // 1시간 동안 캐시
$response .= "Set-Cookie: user_id=12345; Path=/; HttpOnly\r\n"; // 쿠키 설정
$response .= "\r\n";
$response .= $fileContent;
~~~

### Default Header
ContentType: レスポンスのコンテンツタイプを指定 <br/>
GET /table.json HTTP/1.1: クライアントがリクエストしたファイル(file)とHTTPバージョン(HTTP/1.1)を示し。<br/>
Host: リクエストを送信するサーバーのホスト(127.0.0.1)とポート(8080)を示。<br/>
Connection: keep-alive: TCP接続を維持し、次のリクエストを同じ接続で処理。<br/>
sec-ch-ua: クライアントのブラウザ情報とそのバージョン。<br/>
sec-ch-ua-mobile: モバイルからのアクセスかどうかを示します。?0はモバイルではないことを意味。<br/>
sec-ch-ua-platform: OS情報で、macOS。<br/>
Upgrade-Insecure-Requests: クライアントがHTTPからHTTPSへの安全な接続を希望するかを示し。<br/>
User-Agent: クライアントのソフトウェア情報（ブラウザ、OSなど）を示し。<br/>
Accept: クライアントが受け入れ可能なコンテンツタイプ（HTML、XML、画像など）を示し。<br/>
Sec-Fetch-Site: リソースのリクエスト元サイトの出所情報。<br/>
Sec-Fetch-Mode: クライアントのリソース取得モードです。navigateはページ移動を意味。<br/>
Sec-Fetch-User: ユーザーがページ移動を行ったかどうかを示します。?1は移動済みを意味。<br/>
Sec-Fetch-Dest: リクエスト先のリソースタイプです（例: ドキュメント、画像）。<br/>
Accept-Encoding: クライアントが受け入れ可能な圧縮形式。<br/>
Accept-Language: クライアントが受け入れ可能な言語を優先度順に示し<br/>