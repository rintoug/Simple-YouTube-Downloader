<?php
/**
 * Tutsplanet
 *
 * This class narrates the functions to support download a video from YouTube
 * @class YouTubeDownloader
 * @author Tutsplanet
 *
 */

Class YouTubeDownloader {

    /**
     * Get the YouTube code from a video URL
     * @param $url
     * @return mixed
     */
    public function getYouTubeCode($url) {
        parse_str( parse_url( $url, PHP_URL_QUERY ), $vars );
        return $vars['v'];
    }

    /**
     * Process the video url and return details of the video
     * @param $vid
     * @return array|void
     */

    public function processVideo($vid) {
        parse_str(file_get_contents("https://youtube.com/get_video_info?video_id=".$vid),$info);



        $playabilityJson = json_decode($info['player_response']);
        $IsPlayable = $playabilityJson->playabilityStatus->status;

        //writing to log file
        if($IsPlayable != 'OK') {
            $log = date("c")." ".$info['player_response']."\n";
            file_put_contents('./video.log', $log, FILE_APPEND);
        }


        if(!empty($info) && $info['status'] == 'ok' && $IsPlayable == 'OK') {
            $streams = $info['url_encoded_fmt_stream_map']; // Fetch all available streams
            $streams = explode(',',$streams);
            $videoOptions = array();
            $i=0;
            foreach($streams as $stream) {
                parse_str($stream, $data); //decode the stream
                //Process Type
                $rowType = explode(";",$data['type']);
                $rowTypeNext = explode("/",$rowType[0]);

                $videoOptions[$i]['link'] = $data['url'];
                $videoOptions[$i]['type'] = $rowTypeNext[1];
                $videoOptions[$i]['quality'] = $data['quality'];
                $i++;
            }
            $result = array('info'=>$info,'videos'=>$videoOptions);
            return $result;
        }
        else {
            return;
        }
    }
}