<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

// パスワード認証とページ作成処理
$PASSWORD = '09013489867';
$is_authenticated = false;
$message = '';
$error = '';

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// パスワード認証チェック
if (isset($_POST['password'])) {
    if ($_POST['password'] === $PASSWORD) {
        $_SESSION['page_creator_authenticated'] = true;
        $is_authenticated = true;
    } else {
        $error = 'パスワードが正しくありません。';
    }
} elseif (isset($_SESSION['page_creator_authenticated']) && $_SESSION['page_creator_authenticated'] === true) {
    $is_authenticated = true;
}

// ログアウト処理
if (isset($_GET['logout'])) {
    unset($_SESSION['page_creator_authenticated']);
    $is_authenticated = false;
    $message = 'ログアウトしました。';
}

// ページ作成処理
if ($is_authenticated && isset($_POST['create_page']) && isset($_POST['page_name'])) {
    $page_name = trim($_POST['page_name']);
    
    // 入力値の検証（英数字・アンダースコア・ハイフンのみ許可）
    if (empty($page_name)) {
        $error = 'ページ名を入力してください。';
    } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $page_name)) {
        $error = 'ページ名は英数字・アンダースコア・ハイフンのみ使用できます。';
    } else {
        $base_dir = dirname(__FILE__);
        $include_dir = $base_dir . '/includefile/';
        $source_dir = $base_dir . '/source/';
        
        // 既存ファイルチェック
        $php_file = $base_dir . '/' . $page_name . '.php';
        $dataset_file = $include_dir . 'dataset_' . $page_name . '.php';
        $html_file = $source_dir . $page_name . '.html';
        
        $files_exist = array();
        if (file_exists($php_file)) $files_exist[] = $page_name . '.php';
        if (file_exists($dataset_file)) $files_exist[] = 'dataset_' . $page_name . '.php';
        if (file_exists($html_file)) $files_exist[] = $page_name . '.html';
        
        if (!empty($files_exist)) {
            $error = '以下のファイルが既に存在します: ' . implode(', ', $files_exist);
        } else {
            // テンプレートファイルの読み込み
            $php_template = file_get_contents($base_dir . '/create.php');
            $dataset_template = file_get_contents($include_dir . 'dataset_test.php');
            $html_template = file_get_contents($source_dir . 'create.html');
            
            // PHPファイルの作成（テンプレートからcreate.phpの内容を除いて、新しいページ名に置換）
            $php_content = "<?php\n";
            $php_content .= "error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);\n";
            $php_content .= "//データセット基本ファイル読込\n";
            $php_content .= "include(\"/home/firststar/public_html/group/candy/includefile/dataset_base.php\");\n";
            $php_content .= "\n\n";
            $php_content .= "?>\n";
            
            // datasetファイルの作成（テンプレートをそのまま使用）
            $dataset_content = $dataset_template;
            
            // HTMLファイルの作成（テンプレートをそのまま使用）
            $html_content = $html_template;
            
            // ファイル作成
            if (file_put_contents($php_file, $php_content) !== false &&
                file_put_contents($dataset_file, $dataset_content) !== false &&
                file_put_contents($html_file, $html_content) !== false) {
                
                // dataset_base.phpへの追記
                $dataset_base_file = $include_dir . 'dataset_base.php';
                $dataset_base_content = file_get_contents($dataset_base_file);
                
                // switch文への追記（case 'test.html':の後に追加）
                // より柔軟なパターンマッチング
                $switch_pattern = "/(case\s+'test\.html':\s*include\(INCLUDE_DIR\s*\.\s*'dataset_test\.php'\);\s*break;\s*\n)/";
                $new_case = "\tcase '" . $page_name . ".html':\n\t\tinclude(INCLUDE_DIR . 'dataset_" . $page_name . ".php');\n\t\tbreak;\n\n";
                
                if (preg_match($switch_pattern, $dataset_base_content)) {
                    $dataset_base_content = preg_replace($switch_pattern, "$1" . $new_case, $dataset_base_content);
                } else {
                    // test.htmlが見つからない場合は、create.htmlの後に追加
                    $switch_pattern2 = "/(case\s+'create\.html':\s*include\(INCLUDE_DIR\s*\.\s*'dataset_create\.php'\);\s*break;\s*\n)/";
                    if (preg_match($switch_pattern2, $dataset_base_content)) {
                        $dataset_base_content = preg_replace($switch_pattern2, "$1" . $new_case, $dataset_base_content);
                    } else {
                        // どちらも見つからない場合は、switch文の最初に追加
                        $switch_pattern3 = "/(switch\s*\(\s*\$hdir\s*\)\s*\{)/";
                        if (preg_match($switch_pattern3, $dataset_base_content)) {
                            $dataset_base_content = preg_replace($switch_pattern3, "$1\n" . $new_case, $dataset_base_content);
                        }
                    }
                }
                
                // index置換部分への追記（test.htmlの後に追加）
                $index_replace_pattern = "/(\$source\s*=\s*str_replace\('test\.html',\s*'test\.php',\s*\$source\);\s*\n)/";
                $new_replace = "\$source = str_replace('" . $page_name . ".html', '" . $page_name . ".php', \$source);\n";
                
                if (preg_match($index_replace_pattern, $dataset_base_content)) {
                    $dataset_base_content = preg_replace($index_replace_pattern, "$1" . $new_replace, $dataset_base_content);
                } else {
                    // test.htmlが見つからない場合は、create.htmlの後に追加
                    $index_replace_pattern2 = "/(\$source\s*=\s*str_replace\('create\.html',\s*'create\.php',\s*\$source\);\s*\n)/";
                    if (preg_match($index_replace_pattern2, $dataset_base_content)) {
                        $dataset_base_content = preg_replace($index_replace_pattern2, "$1" . $new_replace, $dataset_base_content);
                    } else {
                        // どちらも見つからない場合は、//index置換の後に追加
                        $index_replace_pattern3 = "/(\/\/index置換\s*\n)/";
                        if (preg_match($index_replace_pattern3, $dataset_base_content)) {
                            $dataset_base_content = preg_replace($index_replace_pattern3, "$1" . $new_replace, $dataset_base_content);
                        }
                    }
                }
                
                // dataset_base.phpを保存
                if (file_put_contents($dataset_base_file, $dataset_base_content) !== false) {
                    $message = 'ページ「' . htmlspecialchars($page_name) . '」を作成しました。';
                } else {
                    $error = 'ファイルは作成されましたが、dataset_base.phpへの追記に失敗しました。';
                }
            } else {
                $error = 'ファイルの作成に失敗しました。';
            }
        }
    }
}

// 認証されていない場合は、パスワード入力フォームを表示
if (!$is_authenticated) {
    ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ページ作成ツール - 認証</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: #dc3545;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8d7da;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ページ作成ツール</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="password">パスワード:</label>
                <input type="password" id="password" name="password" required autofocus>
            </div>
            <button type="submit">認証</button>
        </form>
    </div>
</body>
</html>
    <?php
    exit;
}

// 認証済みの場合は、ページ作成フォームを表示
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ページ作成ツール</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .btn-logout {
            background-color: #6c757d;
        }
        .btn-logout:hover {
            background-color: #5a6268;
        }
        .message {
            color: #155724;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #d4edda;
            border-radius: 4px;
        }
        .error {
            color: #dc3545;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8d7da;
            border-radius: 4px;
        }
        .info {
            color: #856404;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #fff3cd;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ページ作成ツール</h1>
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <div class="info">
            <strong>作成されるファイル:</strong><br>
            1. {ページ名}.php（ルートディレクトリ）<br>
            2. includefile/dataset_{ページ名}.php<br>
            3. source/{ページ名}.html<br>
            <br>
            dataset_base.phpにも自動的に追記されます。
        </div>
        <form method="POST">
            <div class="form-group">
                <label for="page_name">ページ名（英数字・アンダースコア・ハイフンのみ）:</label>
                <input type="text" id="page_name" name="page_name" required pattern="[a-zA-Z0-9_-]+" placeholder="例: mypage">
            </div>
            <button type="submit" name="create_page">作成</button>
            <a href="?logout=1"><button type="button" class="btn-logout">ログアウト</button></a>
        </form>
    </div>
</body>
</html>
<?php
exit;
?>
