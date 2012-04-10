<?php
class html_mime_mail {
	public  $headers;
	public $multipart;
	public $mime;
	public $html;
	public $parts = array();

	function html_mime_mail($headers="") {
	    $this->headers=$headers;
	}

	function add_html($html="")
	{
	    $this->html.=$html;
	}

	function build_html($orig_boundary, $kod) {
	    $this->multipart.="--$orig_boundary\n";
	    $this->multipart.="Content-Type: text/html; charset=utf8\n";
	    $this->multipart.="Content-Transfer-Encoding: Quot-Printed\n\n";
	    $this->multipart.="$this->html\n\n";
	}

	function add_attachment($path="", $name = "", $content_file = false, $c_type = null) {
	    if (!file_exists($path.$name)) {
		    print "File $path.$name dosn't exist.";
		    return;
	    }
	    $fp=fopen($path.$name,"r");
	    if (!$fp) {
	      	print "File $path.$name coudn't be read.";
		    return;
	    }
	    $file=fread($fp, filesize($path.$name));
	    fclose($fp);

	    if (is_null($c_type)) {
	    	$c_type = mime_content_type($path.$name);
	    }

	    $this->parts[] = array("body" => $file, "name" => $name, "c_type" => $c_type, "content_file" => $content_file);
	}

	function build_part($i) {
	    $message_part="";
	    $message_part.="Content-Type: ".$this->parts[$i]["c_type"];

	    if ($this->parts[$i]["name"]!="")
	    	$message_part.="; name=\"".$this->parts[$i]["name"]."\"\n";
	    else
	    	$message_part.="\n";

	    $message_part.="Content-Transfer-Encoding: base64\n";

	    if ($this->parts[$i]["content_file"]) {
			$message_part.="Content-ID: <" . md5($this->parts[$i]["name"]) . ">\n\n";
		}
	    else {
		    $message_part.="Content-Disposition: attachment; filename=\"" . $this->parts[$i]["name"] . "\"\n\n";
	    }
	    $message_part.=chunk_split(base64_encode($this->parts[$i]["body"]))."\n";

	    return $message_part;
	}

	function build_message($kod) {
	    $boundary="=_".md5(uniqid(time()));
	    $this->headers.="MIME-Version: 1.0\n";
	    $this->headers.="Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
	    $this->multipart="";
	    $this->multipart.="This is a MIME encoded message.\n\n";

	    for ($i=(count($this->parts)-1); $i>=0; $i--) {
	    	if ($this->parts[$i]['content_file'])
	    		$this->html = preg_replace("/src=(\"|\')[^\"]*\/?". preg_quote($this->parts[$i]["name"]) ."(\"|\')/i", "src=\"cid:" . md5($this->parts[$i]["name"]) . "\"", $this->html);
	    }

	    $this->build_html($boundary, $kod);

	    for ($i=(count($this->parts)-1); $i>=0; $i--) {
	    	$this->multipart.="--$boundary\n".$this->build_part($i);
	    }

	    $this->mime = $this->multipart . "--$boundary--\n";
	}

	function SendMail($From,$FromName,$To,$ToName,$Subject, $content_type = "text/plain", $CC=null, $BCC=null) {
		global $Config;

		$From or user_error("sender address missing");
		$To or user_error("recipient address missing");
		$headers.="From: \"".'=?utf-8?B?'.base64_encode($FromName).'?='."\" <".$From.">\r\n";
		$To = "\"".'=?utf-8?B?'.base64_encode($ToName).'?='."\" <".$To.">\r\n";

		$headers.="Reply-To: \"".'=?utf-8?B?'.base64_encode($FromName).'?='."\" <".$From.">\r\n";
		if (!is_null($CC)) {
			if (is_array($CC)) {
				$CC_LIST = '';
				foreach ($CC as $name=>$email)
					$CC_LIST .= (!empty($CC_LIST) ? ", " : '') . '"'.$name.'" <'.$email.'>';
				$headers .= empty($CC_LIST) ? '' : "CC: $CC_LIST \r\n";
			}
			else $headers.="CC: ".$CC." <".$CC.">\r\n";
		}
		if (!is_null($BCC)) {
			if (is_array($BCC)) {
				$BCC_LIST = '';
				foreach ($BCC as $name=>$email)
					$BCC_LIST .= (!empty($BCC_LIST) ? ", " : '') . '"'.$name.'" <'.$email.'>';
				$headers .= empty($BCC_LIST) ? '' : "BCC: $BCC_LIST \r\n";
			}
			else $headers.="BCC: ".$BCC." <".$BCC.">\r\n";
		}
		$headers.="X-Mailer: PHP Mailer by Alex Tonkov\r\n";
		$headers.="Content-Transfer-Encoding: 8bit\r\n";

 		//message ends
 		if ($Subject)
 			$Subject = '=?utf-8?B?'.base64_encode($Subject).'?=';

		return mail($To, $Subject, $this->mime, $this->headers . $headers);
	}// SendMail function
}