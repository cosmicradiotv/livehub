<?php

if (!function_exists('versioned')) {

	/**
	 * Get the path to a versioned file. Based on Elixir
	 *
	 * @param  string $file
	 *
	 * @return string
	 */
	function versioned($file)
	{
		static $manifest = null;

		$file_path = public_path() . '/rev-manifest.json';

		if (is_null($manifest) && file_exists($file_path)) {
			$manifest = json_decode(file_get_contents($file_path), true);
		}

		if (isset($manifest[$file])) {
			return $manifest[$file];
		}

		return $file;
	}
}
