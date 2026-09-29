# サーバー管理

更新日: YYYY-MM-DD
親管理書: INDEX.md

## 1. 管理範囲

### 1.1 目的

本書は、Candy本番サーバーの現在の構成・接続・権限・公開環境・実行環境・定期実行・通信・ログ・READ-ONLY確認方法を管理する。

目的:
- 本番サーバーの現在状態を正確に把握する
- 障害調査時の確認先・確認順序を明確にする
- Server、DB、Git、Application仕様の責任範囲を分離する
- 未確認情報を推測で補完しない
- 本番調査を原則READ-ONLYで行う


### 1.2 管理対象

対象Server:
    Host      o4042s-134.kagoya.net
    Account   firststar
    Provider  KAGOYA

Candy本番:
    公開URL       https://www.55810.com/
    SSH上のRoot   /firststar/public_html/group/candy
    公開先        /public_html/group/candy

本書で管理する対象:
- Server基本情報
- OS・CPU・Memory・Storage
- SSH接続・Host Key・権限
- 本番配置先・公開設定・Permission
- Apache・PHP実行環境
- cron
- Port・DNS・HTTP・HTTPS・SSL
- Mail送信環境
- Web・PHP・FTP Log
- 障害調査方法
- ServerのREAD-ONLY確認方法

Server全体の情報とCandy固有の情報を区別して記録する。


### 1.3 情報の確認基準

確認手段:
- KAGOYA管理画面
- PuTTY / SSH
- Windows PowerShell
- Invoke-LiveServerRead.ps1
- 本番File・設定File
- その他READ-ONLYで確認できる手段

Invoke-LiveServerRead.ps1 は確認手段の1つであり、必須または唯一の確認経路ではない。

情報は以下に区別する。
- 確認済みの固定情報
- 確認時点の動的情報
- firststar権限では確認できない情報
- 未確認情報

動的情報は必要時に再確認する。

以下を同一視しない。

Port:
    LISTEN
    ≠ 外部到達可能
    ≠ Firewall Rule確認済み

PHP:
    Web実行環境
    ≠ CLI実行環境

cron:
    登録あり
    ≠ 起動確認
    ≠ 正常終了
    ≠ 業務処理成功

File:
    存在確認
    ≠ Application正常動作

取得できない情報は「未確認」または「firststarでは確認不可」と記録し、推測で確定しない。

確認のために本番変更が必要となる場合はREAD-ONLY調査として実行せず、変更作業として別途扱う。

## 2. 本番サーバー

### 2.1 サーバー基本情報

ホスティング : KAGOYA
アカウント名 : firststar
契約プラン : マネージド専用サーバー 042s Quad
サーバー名 : o4042s-134.kagoya.net
IPv4 : 153.127.232.193
IPv6 : 未設定
タイムゾーン : JST UTC +09:00

### 2.2 ホスティング・OS環境

サーバー種別 : マネージド専用サーバー
OS正式名称・Version : 未確認
Kernel : 2.6.32-642.6.2.el6.x86_64
Architecture : x86_64

### 2.3 CPU・メモリ・ストレージ

CPU : Intel(R) Xeon(R) CPU E3-1240L v3 @ 2.00GHz
論理CPU数 : 8
メモリ総容量 : 16,282,024 kB
Swap総容量 : 2,087,932 kB
Root Filesystem:
    Device      /dev/md1
    Filesystem  ext4
    Size        468G
    Mount       /

ディスク使用状況:
    Used        88G
    Available   357G
    Use         20%

KAGOYA管理画面表示:
    利用可能    476.0 Gbyte
    利用量       93.7 Gbyte
    空き容量    382.4 Gbyte
KAGOYA管理画面では `1 Kbyte = 1000 byte` として計算される。

ディスク使用量・空き容量、メモリ空き容量、Swap空き容量は動的値であり、固定仕様として扱わない。

### 2.4 ホスティング上の制約

firststar の権限:
- root権限なし
- sudo 利用不可
- /etc、/usr、/usr/local、/var への書込み不可
- systemctl、service、chkconfig 利用不可
- firewall-cmd、nft、iptables 利用不可
- /var/log はSSH環境から参照不可

このため、OS・Service・Firewall等の管理者権限を必要とする確認・変更は firststar では行わず、KAGOYA管理画面またはKAGOYAサポートで対応する。

## 3. 接続・権限

### 3.1 SSH接続

接続先 : firststar.kir.jp
IP : 153.127.232.193
Port : 22
User : firststar

PuTTY設定:
    Host Name        firststar.kir.jp
    Port             22
    Connection type  SSH

接続後の確認:
    whoami
    hostname
    pwd

期待値:
    User  firststar
    Host  o4042s-134.kagoya.net

### 3.2 SSHホスト鍵

Algorithm : ssh-rsa
Size : 2048 bit
Fingerprint : SHA256:pd3aC2b5kEW5ME9EIz5wgA25qdZRMxypMFN9E86EApc

SSH接続時にホスト鍵の確認・変更警告が表示された場合は、Fingerprintが上記と一致することを確認してから登録する。

Windows OpenSSH known_hosts : %USERPROFILE%\.ssh\known_hosts

登録確認:
    ssh-keygen -F firststar.kir.jp
    ssh-keygen -F 153.127.232.193

現在提示されるRSAホスト鍵の確認:
    ssh-keyscan -t rsa firststar.kir.jp 2>$null | ssh-keygen -lf -

known_hostsの古い登録を削除する場合は、Fingerprint確認後に実行する。
    ssh-keygen -R firststar.kir.jp
    ssh-keygen -R 153.127.232.193

ホスト鍵確認を無効化して接続しない。

### 3.3 接続ユーザー・認証

User : firststar
認証方式 : Password認証
UID : 67286
Group : kirusr
GID : 101

Host Key ErrorとUser認証Errorを区別する。
    Host key verification failed : SSHホスト鍵・known_hosts
    Permission denied : User認証・認証方式

### 3.4 権限・操作可能範囲

OS管理権限の制約は「2.4 ホスティング上の制約」で管理する。
ファイル・ディレクトリの操作可否は対象ごとに確認する。

確認:
    stat PATH
    test -r PATH && echo READ=YES || echo READ=NO
    test -w PATH && echo WRITE=YES || echo WRITE=NO
    test -x PATH && echo EXECUTE_OR_TRAVERSE=YES || echo EXECUTE_OR_TRAVERSE=NO

本番配置先のOwner・Group・Permissionは「4. 本番配置・公開環境」で管理する。

### 3.5 接続障害の切り分け

1. DNS確認

    nslookup firststar.kir.jp

期待する名前解決:
    firststar.kir.jp
    → o4042s-134.kagoya.net
    → 153.127.232.193

2. Port 22確認

    Test-NetConnection firststar.kir.jp -Port 22

3. SSHホスト鍵確認

    ssh-keyscan -t rsa firststar.kir.jp 2>$null | ssh-keygen -lf -

取得したFingerprintを「3.2 SSHホスト鍵」の管理値と照合する。

4. known_hosts確認

    ssh-keygen -F firststar.kir.jp
    ssh-keygen -F 153.127.232.193

5. User認証確認

    PuTTYで firststar として接続する。

Windows OpenSSHで接続確認する場合:
    ssh -o HostKeyAlgorithms=+ssh-rsa firststar@firststar.kir.jp

判定:
    DNS解決失敗 : DNS・名前解決
    TcpTestSucceeded=False : Network・Port 22
    Host key verification failed : SSHホスト鍵・known_hosts
    Permission denied : User認証・認証方式
    SSH接続後のPermission denied : 対象ファイル・ディレクトリ権限

## 4. 本番配置・公開環境

### 4.1 本番配置先

本番配置先 : /firststar/public_html/group/candy
KAGOYA上の公開先表記 : /public_html/group/candy

本番Root:
    Owner  firststar
    Group  kirusr
    Mode   777

### 4.2 公開URL・公開先ディレクトリ

公開URL : https://www.55810.com/
公開先 : /public_html/group/candy
SSH上の配置先 : /firststar/public_html/group/candy

.htaccess により以下に統一する。
- http → https
- 55810.com → www.55810.com
- /group/candy/ を含むURL → 公開Root URL
- index.php / index.html 等を明示したURL → ディレクトリURL

DirectoryIndex:
    index.php
    index.html
    index.xhtml

### 4.3 公開領域・アクセス制限

公開Root : /firststar/public_html/group/candy

.htaccess によるアクセス制限:
    /source/             → 404
    /source/*.html       → 404
    /includefile/        → 403

source、includefile は公開Root内に存在するが、通常の公開コンテンツとして直接アクセスさせない。

### 4.4 Owner・Group・Permission

本番Root:
    Owner  firststar
    Group  kirusr
    Mode   777

通常File:
    Owner  firststar
    Group  kirusr
    Mode   604

主要Directory:
    Owner  firststar
    Group  kirusr
    Mode   705

.well-known:
    Owner  firststar
    Group  kirusr
    Mode   755

Permissionは対象ごとに確認し、Root・File・Directoryを同一のModeとして扱わない。

### 4.5 書込領域・シンボリックリンク

firststar から本番Rootおよび主要Directoryへ書込み可能。

主な書込可能Directory:
    /firststar/public_html/group/candy
    /firststar/public_html/group/candy/css
    /firststar/public_html/group/candy/customers
    /firststar/public_html/group/candy/font
    /firststar/public_html/group/candy/imgCss
    /firststar/public_html/group/candy/imgHtml
    /firststar/public_html/group/candy/includefile
    /firststar/public_html/group/candy/js
    /firststar/public_html/group/candy/member
    /firststar/public_html/group/candy/movie
    /firststar/public_html/group/candy/preview
    /firststar/public_html/group/candy/source

本番Root配下4階層以内のシンボリックリンク : 確認なし


## 5. Web実行環境

### 5.1 Webサーバー

Webサーバー : Apache 2.4.54
Process : /usr/local/apache2/bin/httpd

Process User:
    master  root
    worker  nobody
    worker  firststar（UID 67286）

PHP-FPM Process : 確認なし

### 5.2 PHP実行方式

PHP Version : 7.2.12
Web SAPI : apache2handler
Loaded php.ini : /usr/local/php7.2/etc/php.ini

Candy本番Root配下4階層以内のPHP関連設定File:
    .htaccess  あり
    php.ini    なし
    .user.ini  なし

.htaccess にPHP実行方式を変更する AddHandler / SetHandler 設定はない。

### 5.3 PHP設定・拡張

Web実効値:
    memory_limit         256M
    post_max_size        2000M
    upload_max_filesize  2000M
    max_execution_time   3600
    date.timezone        Asia/Tokyo
    default_charset      UTF-8
    display_errors       1
    sendmail_path        /usr/sbin/sendmail -t -i

主要Module:
    curl
    json
    mbstring
    mysqli
    openssl
    pdo_mysql
    session

Candy内のRuntime設定変更:
    member/api.php  display_errors = 0

includefile/dataset_base.php の display_errors 設定はコメントアウトされており実行されない。

### 5.4 CLI PHP

実行File : /usr/bin/php
/usr/local/bin/php : /usr/bin/php へのシンボリックリンク
PHP Version : 7.2.12
SAPI : CGI/FastCGI
Loaded php.ini : なし
Additional ini : なし

CLI実効値:
    memory_limit         128M
    post_max_size        8M
    upload_max_filesize  2M
    date.timezone        未設定
    sendmail_path        /usr/sbin/sendmail -t -i

主要Module:
    curl
    json
    mbstring
    mysqli
    openssl
    pdo_mysql
    session

このPHP CGI Binaryでは -r Optionを使用できない。

### 5.5 Web・CLI実行環境の差異

Web:
    SAPI                 apache2handler
    php.ini              /usr/local/php7.2/etc/php.ini
    memory_limit         256M
    post_max_size        2000M
    upload_max_filesize  2000M
    date.timezone        Asia/Tokyo

CLI:
    SAPI                 CGI/FastCGI
    php.ini              なし
    memory_limit         128M
    post_max_size        8M
    upload_max_filesize  2M
    date.timezone        未設定

WebとCLIではPHP実行環境・設定が異なるため、CLIの設定値をWeb実行時の設定として扱わない。

## 6. 定期実行

### 6.1 Candy関連cron

出勤表クリア:
    実行時刻  毎日 02:30
    Command   /usr/local/bin/php /home/firststar/public_html/candy/candy/today.php
    出力      /dev/null 2>&1

インフォメーションクリア:
    実行時刻  毎日 02:00
    Command   /usr/local/bin/php /home/firststar/public_html/admin2/candy/infoClear.php
    出力      /dev/null 2>&1

お気に入りキャスト通知:
    実行時刻  毎日 07:30
    Command   /usr/local/bin/php /home/firststar/public_html/group/control/includefile/sys/userapi/cron_favcast_sendmail.php
    出力      /dev/null 2>&1

現在のCandy HP本番配置先:
    /home/firststar/public_html/group/candy

上記3件はいずれも現在のCandy HP本番配置先を直接実行するcronではない。
Candy関連の旧処理・管理処理・共通処理として別Pathから実行されている。

以下はコメントアウトされており実行されない。

    # 30 7 * * * /usr/local/bin/php /home/firststar/public_html/group/candyNew/favNotify.php > /dev/null 2>&1

### 6.2 実行結果の確認

cronへの登録と処理の正常完了は別に扱う。

3件とも標準出力・標準Errorを /dev/null へ破棄しているため、cron出力から実行結果は確認できない。

処理結果を確認する場合は、各Scriptの処理内容・Application Log・更新対象を確認する。

## 7. 通信・SSL

### 7.1 Port・到達性

Web公開Port:
    HTTP   80/tcp
    HTTPS  443/tcp

外部PCからの到達性:
    80/tcp   到達可能
    443/tcp  到達可能

SSHの22/tcpは「3. 接続・権限」、DBの3306/tcpはDB_MANAGEMENT.mdで管理する。

### 7.2 DNS・名前解決

55810.com:
    A      153.127.232.193
    AAAA   なし
    CNAME  なし

www.55810.com:
    A      153.127.232.193
    CNAME  なし

TTL : 600秒

55810.com と www.55810.com は、本番サーバーのIPv4 153.127.232.193を直接参照する。

### 7.3 HTTP・HTTPS

正式公開URL : https://www.55810.com/

.htaccess の設定:
- http → https
- 55810.com → www.55810.com

Redirect : 301

### 7.4 SSL・TLS証明書

対象Domain : www.55810.com
Subject : CN=www.55810.com
SAN : DNS Name=www.55810.com
Issuer : CN=YR1, O=Let's Encrypt, C=US
有効開始 : 2026-09-16 13:32:43
有効期限 : 2026-12-15 13:32:42

SSL証明書の有効期限は動的情報のため、必要時に現在値を再確認する。

### 7.5 メール送信環境

Web PHP:
    sendmail_path  /usr/sbin/sendmail -t -i

Server:
    25/tcp LISTEN

Mail Server内部設定、Mail Queue、System Mail Logは firststar のSSH環境から確認できない。

メール送信機能固有の処理内容・宛先・成功条件は、本書ではなく該当する機能仕様で管理する。

## 8. ログ・障害調査

### 8.1 WebアクセスLog

確認経路:
    KAGOYA管理画面
    → Webサイト
    → ログサービス
    → アクセスログ

確認できる主な情報:
- アクセス日時
- 接続元IP
- Request Method・URL
- HTTP Status
- Response Size
- Referer
- User-Agent

KAGOYAでは過去5日間のアクセスログを保存できる。

404、Redirect、URL到達、HTTP Status等の確認に使用する。

### 8.2 Web・PHP Error Log

確認経路:
    KAGOYA管理画面
    → Webサイト
    → ログサービス
    → エラーログ

確認範囲:
    過去5日間の最新100件

主な確認対象:
- PHP Error
- PHP Warning
- PHP Notice
- Apache Error
- Script実行Error

Candy内で error_log() に出力されたErrorも、本ログを確認対象とする。

500 Error、PHP実行Error、Warning等の調査では最初に確認する。


### 8.3 FTP Log

確認経路:
    KAGOYA管理画面
    → Webサイト
    → ログサービス
    → FTPログ

確認範囲:
    過去5日間の最新100件

確認できる主な情報:
- 接続日時
- 接続元
- 対象File
- Upload / Download / Delete
- 転送Size
- 転送完了 / 未完了

本番FileのUpload・Delete・転送障害を確認する場合に使用する。


### 8.4 ログ保存・確認範囲

KAGOYAログサービス:
    アクセスログ  過去5日間
    エラーログ    過去5日間の最新100件
    FTPログ       過去5日間の最新100件

必要なログはKAGOYA管理画面からServer上の指定Directoryへ保存できる。

保存したLogは保存時点の記録であり、リアルタイム更新されるLogとして扱わない。

firststar のSSH環境ではOS System Logは確認できない。

cron登録Commandは標準出力・標準Errorを /dev/null へ破棄しているため、cron出力Logは残らない。

OS・SSH・Mail Server等のKAGOYA管理領域のLogが必要な場合は、KAGOYAへ確認する。


### 8.5 障害調査の確認順序

ページを開けない・404:
    8.1 WebアクセスLog
    ↓
    4.2 公開URL・公開先ディレクトリ
    ↓
    .htaccess
    ↓
    8.2 Web・PHP Error Log

500 Error・PHP Error:
    8.2 Web・PHP Error Log
    ↓
    5. Web実行環境
    ↓
    必要に応じてDB_MANAGEMENT.md

本番Fileの反映・転送異常:
    8.3 FTP Log
    ↓
    GIT_MANAGEMENT.md
    ↓
    本番File・HTTP表示確認

cron処理:
    6. 定期実行
    ↓
    実行対象Script
    ↓
    処理結果・更新対象
    ↓
    必要に応じて8.2 Web・PHP Error Log

OS・Server管理領域の障害:
    firststar で確認可能な範囲を確認
    ↓
    KAGOYA管理画面
    ↓
    必要に応じてKAGOYAサポート

## 9. READ-ONLY確認

### 9.1 基本方針

本番サーバーの調査・確認はREAD-ONLYで行う。

確認手段:
- Invoke-LiveServerRead.ps1
- PuTTY / SSH
- Windows PowerShell
- KAGOYA管理画面
- その他、対象情報を変更せず確認できる手段

Invoke-LiveServerRead.ps1 はREAD-ONLY確認手段の1つであり、必須または唯一の確認経路ではない。

特定の手段で確認できない場合は、別のREAD-ONLY手段で確認する。

確認できない情報を推測で補完しない。


### 9.2 Invoke-LiveServerRead.ps1

Path:
    C:\Codex\Candy\scripts\Invoke-LiveServerRead.ps1

接続先:
    Host  firststar.kir.jp
    User  firststar
    Port  22

専用SSH秘密鍵:
    C:\Users\nishi\.ssh\candy_readonly_rsa

専用known_hosts:
    C:\Users\nishi\.ssh\candy_readonly_known_hosts

Server Host Key Fingerprint:
    SHA256:pd3aC2b5kEW5ME9EIz5wgA25qdZRMxypMFN9E86EApc

Server側Wrapper:
    /firststar/candy-readonly/candy-server-readonly

Wrapper:
    Owner  firststar
    Group  kirusr
    Mode   700

authorized_keys:
    /firststar/.ssh/authorized_keys
    Mode 600

Candy専用鍵はforced commandでWrapperへ固定し、任意Shell・任意Commandを許可しない。

2026-09-19 実機検証:
    PowerShell Syntax        PASS
    SelfTest                 EXITCODE=0
    10 Action                全てEXITCODE=0
    任意Command hostname     DENIED
    DENY_EXITCODE            64


### 9.3 確認対象

Invoke-LiveServerRead.ps1 の許可Action:

    SelfTest
    Server
    Php
    Process
    Network
    Disk
    Cron
    Files
    WebConfig
    Mail

SelfTest:
- Server接続
- 接続User
- Candy本番Root存在確認

Server:
- Host
- Kernel
- Architecture
- User / Group
- Server時刻

Php:
- PHP Version
- CLI PHP設定
- PHP Module

Process:
- Apache / PHP関連Process

Network:
- TCP LISTEN状態

Disk:
- FileSystem容量
- inode

Cron:
- firststarから確認可能なcron設定

Files:
- Candy本番Root
- Owner / Group / Permission
- Top Level File
- 書込可能Directory
- Symbolic Link

WebConfig:
- .htaccess
- php.ini / .user.ini存在確認
- Candy公開設定

Mail:
- PHP Mail設定
- Mail関連Command利用可否
- Port 25 LISTEN状態

DB構造・DB内容のREAD-ONLY確認はDB_MANAGEMENT.mdで管理する。


### 9.4 実行禁止条件

READ-ONLY確認では以下を行わない。

- File・Directoryの作成、変更、削除
- Permission・Owner・Group変更
- Program・設定File変更
- cron変更
- Service起動・停止・再起動
- Apache・PHP設定変更
- Firewall変更
- Mail Server設定変更
- DB変更
- Git操作による本番反映
- 任意Shell・任意CommandをREAD-ONLY専用鍵で実行
- Port Forwarding
- Agent Forwarding
- X11 Forwarding

Invoke-LiveServerRead.ps1 は以下の場合、確認結果を正常として扱わない。

- Host Key Fingerprint不一致
- 専用鍵が存在しない
- known_hostsが存在しない
- SSH認証失敗
- Actionが許可リスト外
- SelfTest失敗
- Candy本番Rootを確認できない
- ExitCodeが0以外

確認のために本番変更が必要になる場合は、READ-ONLY調査を終了し、変更作業として別途扱う。


### 9.5 確認結果の扱い

READ-ONLY確認結果は、取得時点の実環境情報として扱う。

以下を区別する。

- 確認済みの固定情報
- 確認時点の動的情報
- firststar権限では確認できない情報
- 未確認情報

確認成功と業務処理成功を同一視しない。

例:
    cron登録あり
    ≠ cron起動確認
    ≠ 正常終了確認
    ≠ 業務処理成功

    Port LISTEN
    ≠ 外部到達可能
    ≠ Firewall Rule確認済み

    File存在
    ≠ Application正常動作

動的情報は必要時に再取得する。

取得不能な情報は「未確認」または「firststarでは確認不可」と記録し、推測で確定しない。