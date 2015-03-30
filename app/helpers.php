<?php

if (! function_exists('versioned')) {

	/**
	 * Get the path to a versioned file. Based on Elixir
	 *
	 * @param  string $file
	 *
	 * @return string
	 */
	function versioned($file) {
		static $manifest = null;

		if (is_null($manifest)) {
			$manifest = json_decode(file_get_contents(public_path() . '/rev-manifest.json'), true);
		}

		if (isset($manifest[$file])) {
			return $manifest[$file];
		}

		return $file;
	}
}