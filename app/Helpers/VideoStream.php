<?php
namespace App\Helpers;
use App\Helpers\SplitCurlByLines;

class VideoStream{
    private $url = "";
    private $sizeReq = 10 * 1000000; //get 10MB per range request
    private $curlStream = NULL;
    private $followRedirects = true; //maybe disable if not trusted
    private $start  = -1;
    private $end    = -1;
    private $size   = -1;
 
    function __construct($URL) 
    {
        $this->url = $URL;
        $this->setSize(); //set the size and check if the video is available at the same time
    }
     
     
    /**
     * Set proper header to serve the video content
     */
    private function setHeader()
    {
        ob_get_clean();
        header("Content-Type: video/mp4");
        header("Cache-Control: max-age=2592000, public");
        header("Expires: ".gmdate('D, d M Y H:i:s', time()+2592000) . ' GMT');
        $this->start = 0;

        $this->end = $this->size;
        header("Accept-Ranges: 0-" . ($this->size + 1));
        if (isset($_SERVER['HTTP_RANGE'])) {
        	$ranges = explode("-", explode("=", $_SERVER['HTTP_RANGE'])[1]); //explode twice to get the ranges in an array
            $this->start = $ranges[0]; //starting range

            if($ranges[1] === ""){ //no end specified, set it ourselves
            	$this->end = $ranges[0] + $this->sizeReq;
            	if($this->end > $this->size) $this->end = $this->size; //set it to the end if the request would be too big
            } else { //set it to the requested length
            	$this->end = $ranges[1];
            }

            $length = $this->end - $this->start + 1;

            header('HTTP/1.1 206 Partial Content');
            header("Content-Length: ".$length);
            header("Content-Range: bytes $this->start-$this->end/" . ($this->size + 1));
        }
        else
        {
            header("Content-Length: ". ($this->end - $this->start + 1));
        }        
    }
    
    /**
     * close curretly opened stream
     */
    private function end()
    {
    	curl_close($this->curlStream);
        exit;
    }
     
    /**
     * perform the streaming of calculated range
     */
    private function stream()
    {
    	$splitter = new SplitCurlByLines();

    	$this->curlStream = curl_init();
		curl_setopt($this->curlStream, CURLOPT_URL, $this->url);
		curl_setopt($this->curlStream, CURLOPT_WRITEFUNCTION, array($splitter, 'curlCallback')); //we need this so we "live"stream our results
		curl_setopt($this->curlStream, CURLOPT_ENCODING, 'gzip, deflate');

		$headers = array();
		$headers[] = "Pragma: no-cache";
		$headers[] = "Dnt: 1";
		$headers[] = "Accept-Encoding: identity;q=1, *;q=0";
		$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36";
		$headers[] = "Accept: */*";
		$headers[] = "Cache-Control: no-cache";
		$headers[] = "Connection: keep-alive";
		$headers[] = "Range: bytes={$this->start}-{$this->end}";
		curl_setopt($this->curlStream, CURLOPT_HTTPHEADER, $headers);

		curl_exec($this->curlStream);
		$splitter->processLine($splitter->currentLine);
    }
     
    /**
     * get the size of the external video
     */
    private function setSize()
    {
		$headers = get_headers($this->url, 1);
		if(isset($headers["Location"]) && $this->followRedirects){ //following the rabit hole, might be risky
			$this->url = $headers["Location"];
			$this->setSize();
			return;
		}
		if(strpos($headers[0], '200 OK') === false){ //URL is not OK
			throw new Exception("URL not valid, not a 200 reponse code");
		}
		if(!isset($headers["Content-Length"])){
			throw new Exception("URL not valid, could not find the video size"); 
		}
		$this->size = (int) $headers["Content-Length"];
    }

    /**
     * Start streaming video content
     */
    function start()
    {
        $this->setHeader();
        $this->stream();
        $this->end();
    }
}