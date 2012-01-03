<?php
class Combine {
	public $files;
	private $response;

	public function combine() {
		$combination = new Combination;
		$buffer = "";

		foreach ($this->$files as $file) {
			$buffer .= get_contents($file);
			$buffer .= "\n";
		}

		$combination->content = $buffer;
		$combination->content_type = get_content_type($this->files[0]);
		return $conbination;
	}

	private function get_contents($filename) {
		return file_get_contents($filename);
	}

	private function get_content_type($filename) {
		$extension = explode('.', $filename);
		$extension = $extension[count($extension) - 1];
		$content_type = "text/plain";

		switch ($extension) {
			case "js":
				$content_type = "text/javascript";
				break;
			case "css":
				$content_type = "text/css";
				break;
			case "html":
				$content_type = "text/html";
				break;
		}

		return $content_type;
	}
}

class Combination {
	public $content;
	public $content_type;
}
?>
