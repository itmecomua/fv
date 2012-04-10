<?php
	class fvCDN {
		public static function getCDNRoot () {
			return fvSite::$fvConfig->get('cdn_root');
		}
	}
?>