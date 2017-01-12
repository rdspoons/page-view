<?php
class PageView {
	
	private $head = <<< TAG
<!doctype html>
<html lang="us-en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	@styles@
	@scripts@
</head>
<body>
	<div class="container">
TAG;

	private $tail = <<< TAG
	</div>
</body>
</html>
TAG;

	private $styles = [
		"<link rel=\"stylesheet\" href=\"http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css\" />",
		"<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css\" />"
	];

	private $scripts = [
		"<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js\"></script>",
		"<script src=\"http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js\"></script>"
	];

	function addScript( $url ){
		$this->scripts[] = "<script src=\"" . $url . "\"></script>";
	}

	function addStyle( $url ){
		$this->styles[] = "<link rel=\"stylesheet\" href=\"" . $url . "\" />";
	}

	function header( $size , $content ) {
		return "\t\t<h$size>$content</h$size>\n";
	}

	function jumbotron( $title , $subtitle = '' ) {
		return "\t\t<div class=\"jumbotron\">\n\t\t\t<h1>$title</h1>\n\t\t\t<p>$subtitle</p>\n\t\t</div>\n";
	}

	function inputs( $data ){
		$s = '';
		$cnt = 0;
		forEach($data as $datum) {
			$s .= "\t\t<div class=\"row form-group\">\n" ;
			forEach($datum as $key => $value){
				$s .= "\t\t\t<div class=\"form-group\">\n\t\t\t\t<label class=\"col-sm-2 col-sm-offset-3 control-label\" for=\"" . $key . $cnt . "\">" . $key . "</label>\n"
					. "\t\t\t\t<input class=\"col-sm-3\" id=\"" . $key . $cnt . "\" name=\"" . $key . $cnt . "\" value=\"" . $value . "\">\n\t\t\t</div>\n";
			}
			$s .= "\t\t</div>\n";
			$cnt++;
		}
		return $s;
	}

	function form( $data, $props ){
		$s = "\t\t<form  class=\"form-horizontal\" ";
		if(isset($props)){
			$props = json_decode($props);
			forEach($props as $key => $value){
				$s .= "$key=\"$value\" ";
			}
			$data = implode("\n\t\t", explode("\n", $data));
			$s .= ">\n\t\t\t<div class=\"form-group\">\n\t\t$data\n\t\t\t</div>\n\t\t</form>\n";
		}
		return $s;
	}

	function page( $content ){
		$styles = implode("\n\t", $this->styles);
		$scripts = implode("\n\t", $this->scripts);
		$head = str_replace("@styles@", $styles, $this->head);
		$head = str_replace("@scripts@", $scripts, $head);
		return "$head\n$content\n$this->tail";
	}
}
?>

