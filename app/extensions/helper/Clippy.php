<?php

namespace app\extensions\helper;

/**
 * Requires https://github.com/mojombo/clippy to be installed at app/webroot/flash/clippy.swf
 */

class Clippy extends \lithium\template\Helper {

	public function clip($text, array $options = array()) {
		$defaults = array('bgcolor' => '#FFFFFF');
		list($scope, $options) = $this->_options($defaults, $options);

		$bgcolor = $scope['bgcolor'];

$html = <<<EOD
    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
            width="110"
            height="14"
            id="clippy" >
    <param name="movie" value="/flash/clippy.swf"/>
    <param name="allowScriptAccess" value="always" />
    <param name="quality" value="high" />
    <param name="scale" value="noscale" />
    <param NAME="FlashVars" value="text=$text">
    <param name="bgcolor" value="$bgcolor">
    <embed src="/flash/clippy.swf"
           width="110"
           height="14"
           name="clippy"
           quality="high"
           allowScriptAccess="always"
           type="application/x-shockwave-flash"
           pluginspage="http://www.macromedia.com/go/getflashplayer"
           FlashVars="text=$text"
           bgcolor="$bgcolor"
    />
    </object>
EOD;
	
		return $html;
	}
}

?>
