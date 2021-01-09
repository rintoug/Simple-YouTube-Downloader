<?php
require_once "class.youtube.php";
$yt  = new YouTubeDownloader();
$downloadLinks ='';
$error='';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $videoLink = $_POST['video_link'];
    if(!empty($videoLink)) {
        $vid = $yt->getYouTubeCode($videoLink);
        if($vid) {
            $result = $yt->processVideo($vid);
            
            if($result) {
                //print_r($result);
                $info = $result['videos']['info'];
                $formats = $result['videos']['formats'];
                $adapativeFormats = $result['videos']['adapativeFormats'];

                

                $videoInfo = json_decode($info['player_response']);

                $title = $videoInfo->videoDetails->title;
                $thumbnail = $videoInfo->videoDetails->thumbnail->thumbnails{0}->url;
            }
            else {
                $error = "Something went wrong";
            }

        }
    }
    else {
        $error = "Please enter a YouTube video URL";
    }
}
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Download YouTube video</title>
    <!-- Font-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
        .formSmall {
            width: 700px;
            margin: 20px auto 20px auto;
        }
    </style>

</head>
<body>
    <div class="container">
        <form method="post" action="" class="formSmall">
            <div class="row">
                <div class="col-lg-12">
                    <h7 class="text-align"> Download YouTube Video</h7>
                </div>
                <div class="col-lg-12">
                    <div class="input-group">
                        <input type="text" class="form-control" name="video_link" placeholder="Paste link.. e.g. https://www.youtube.com/watch?v=OK_JCtrrv-c">
                        <span class="input-group-btn">
                        <button type="submit" name="submit" id="submit" class="btn btn-primary">Go!</button>
                      </span>
                    </div><!-- /input-group -->
                </div>
            </div><!-- .row -->
        </form>

        <?php if($error) :?>
            <div style="color:red;font-weight: bold;text-align: center"><?php print $error?></div>
        <?php endif;?>

        <?php if(!empty($formats)):?>
        <div class="row formSmall">
            <div class="col-lg-3">
                <img src="<?php print $thumbnail?>">
            </div>
            <div class="col-lg-9">
                <?php print $title?>
            </div>
        </div>

        <div class="card formSmall">
            <div class="card-header">
                <strong>With Video & Sound</strong>
            </div>
            <div class="card-body">
                <table class="table ">
                    <tr>
                        <td>Type</td>
                        <td>Quality</td>
                        <td>Download</td>
                    </tr>
                    <?php foreach ($formats as $video) :?>
                        <tr>
                            <td><?php print $video['type']?></td>
                            <td><?php print $video['quality']?></td>
                            <td><a href="downloader.php?link=<?php print urlencode($video['link'])?>&title=<?php print urlencode($title)?>&type=<?php print urlencode($video['type'])?>">Download</a> </td>
                        </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </div>

        <div class="card formSmall">
            <div class="card-header">
                <strong>Videos video only/ Audios audio only</strong>
            </div>
            <div class="card-body">
                <table class="table ">
                    <tr>
                        <td>Type</td>
                        <td>Quality</td>
                        <td>Download</td>
                    </tr>
                    <?php foreach ($adapativeFormats as $video) :?>
                        <tr>
                            <td><?php print $video['type']?></td>
                            <td><?php print $video['quality']?></td>
                            <td><a href="downloader.php?link=<?php print urlencode($video['link'])?>&title=<?php print urlencode($title)?>&type=<?php print urlencode($video['type'])?>">Download</a> </td>
                        </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </div>
        <?php endif;?>
    </div>
</body>
</html>