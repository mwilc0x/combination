<?php
/**
 * FileCombiner Utility Class
 *
 * This class handles the operations required to read files and concatenate their names and contents together.
 */

class FileCombiner {
	public $files;
	public $slug;
	public $concatenation;
	private $response;

	public function generate_slug($separator="::") {
		$buff = "";

		foreach ($this->files as $file) {
			$buff .= $file . $separator;
		}

		rtrim($buff, $separator);
		$this->slug = $buff;
		return $buff;
	}

	public function combine() {
		$content_buffer = "";

		// Loop thru files, build concatenated contents.
		foreach ($this->files as $file) {
			try {
				$content_buffer .= get_contents($file);
				$content_buffer .= "\n";
			} catch (Exception $e) {
				echo "/* $file not found. Skipping... */";
				continue; // moves to the next item in the $files array
			}
		}

		$this->concatenation = $content_buffer;
		return $content_buffer;
	}

	private function get_contents($filename) {
		if ($contents = file_get_contents($filename)) {
			return $contents;
		} else {
			throw new Exception("Could not read file $filename");
		}
	}

	private function get_content_type($filename) {
		$extension = explode('.', $filename);
		$extension = $extension[count($extension) - 1];

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
			default:
				$content_type = "text/plain";
		}

		return $content_type;
	}
}

?>
