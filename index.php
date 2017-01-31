<?php
const SERVER_NUM = 3;

$url = isset($_GET['url']) ? $_GET['url'] : null;

if (filter_var(trim($url), FILTER_VALIDATE_URL)) {
    $res = file_get_contents($url);

    if (preg_match('#api_response\s*=\s*(\S+);#', $res, $match)) {
        $raw_list = json_decode($match[1], true)['vlink'];
        $chunk_size = count($raw_list) / SERVER_NUM;

        array_walk($raw_list, function (&$value) {
            if (preg_match('#src="(\S+)"#', base64_decode(rawurldecode($value)), $match)) {
                $value = $match[1];
            } else {
                $value = false;
            }
        });

        $video_list = [];
        foreach ($raw_list as $key => $value) {
            $res = file_get_contents($value);
            if (preg_match('#file:\s*"(//\S+)"#', $res, $match)) {
                $video_list[] = $match[1];
            } else {
                // $video_list[] = false;
            }
        }

        // $data = array_chunk($video_list, $chunk_size)[0];
        $data = $video_list;
    } else {
        $data = ['Error'];
    }
} else {
    $data = ['Please submit URL'];
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#F8981D">
    <title>jav4me</title>

    <link href="//cdn.bootcss.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col">
            <h2 class="text-center display-4">
                <a href="http://javfor.me/actress/miku-ohashi" target="_blank">miku-ohashi</a>
            </h2>

            <form style="margin-bottom: 16px;">
                <div class="input-group">
                    <input type="text" class="form-control" name="url" placeholder="url">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>

            <?php foreach ($data as $id => $each) { ?>
                <div class="input-group">
                    <input id="id-<?php echo $id; ?>" readonly class="form-control" placeholder="url"
                           value="<?php echo $each; ?>">
                    <a role="button" class="btn btn-secondary copy"
                       data-clipboard-target="#id-<?php echo $id; ?>">Copy</a>
                </div>
                <div class="form-control-feedback">SCENE&nbsp;<?php echo $id; ?></div>
            <?php } ?>
        </div>
    </div>
</div>

<script src="//cdn.bootcss.com/clipboard.js/1.5.16/clipboard.min.js"></script>
<script>
    window.onload = function () {
        (new Clipboard('.copy')).on('success', function (e) {
            e.trigger.innerHTML = 'Copied!';
        });
    }
</script>
</body>
</html>

