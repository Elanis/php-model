<?php
/* ===============================================
			     PHP LANGUAGE LIB
					BY ELANIS
=============================================== */

class Language {
	private $languageList;
	private $defaultLanguage;
	private $language;
	private $cachedLanguage;

	/**
	 * Constructor
	 */
	public function __construct($lang = "") {
		global $config;

		$this->languageList = $config["languageList"];
		$this->cachedLanguage = "";

		foreach ($this->languageList as $lang_key => $lang_value) {
			$this->defaultLanguage = $lang_key;
			$this->language = $lang_key;
			break;
		}

		$this->setDefaultLanguage();
		$this->setLanguage($lang);
		$this->importLanguageFiles();
	}
	/**
	 * Sets the default language.
	 */
	private function setDefaultLanguage() {
		if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) { $_SERVER['HTTP_ACCEPT_LANGUAGE'] = $this->defaultLanguage; }
		
		$clientLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);  //Get Navigator language

		foreach ($this->languageList as $lang_key => $lang_value) {
			if($lang_key==$clientLanguage) { $this->defaultLanguage = $clientLanguage; }
		}
	}

	/**
	 * Sets the language.
	 */
	public function setLanguage($lang = "") {
		// Args
		$find = array_search($lang, $this->languageList);
		if(!$find) {
			if(isset($this->languageList[$lang])) {
				$lang = $this->languageList[$lang];
			}
			$find = array_search($lang, $this->languageList);
		}

		// GET
		if(!$find && isset($_GET["lang"])) {
			$lang = $_GET["lang"];

			$find = array_search($lang, $this->languageList);
			if(!$find) {
				if(isset($this->languageList[$lang])) {
					$lang = $this->languageList[$lang];
				}
				$find = array_search($lang, $this->languageList);
			}
		}

		// POST / SESSION
		if(!$find) {
			if(isset($_POST['selected-language']) && !empty($_POST['selected-language'])) {
				$lang = $_POST['selected-language'];
			} elseif(isset($_POST['langue']) && !empty($_POST['langue'])) {
				$lang = $_POST['langue'];
			} elseif(isset($_SESSION['lang']) && !empty($_SESSION['lang'])) {
				$lang = $_SESSION['lang'];
			} else {
				$lang = "";
			}

			$find = false;
			foreach ($this->languageList as $key => $value) {
				if($key == $lang || $value == $lang) {
					$find = $key;
					break;
				}
			}
		}

		if(!$find) { $this->language = $this->defaultLanguage; }
		else { $this->language = (string) $find; }

		$_SESSION['lang'] = $this->language;
	}

	/**
	 * Gets the language.
	 *
	 * @return     <type>  The language.
	 */
	public function getLanguage() {
		return $this->language;
	}

	public function drawCached() {
		echo $this->cachedLanguage;
	}

	/**
	 * Import (require) all language files from selected language
	 */
	private function importLanguageFiles() {
		ob_start();

		$filesList = scandir(DIR_LANG.$this->language);
		foreach ($filesList as $file_value) {
			$parts = explode(".",$file_value);
			if($parts[1]=="php") {
				require_once(DIR_LANG.$this->language.DIRECTORY_SEPARATOR.$file_value);
			}
		}

		$this->cachedLanguage = ob_get_contents(); // copie du contenu du tampon dans une cha??ne
		ob_end_clean(); // effacement du contenu du tampon et arr??t de son fonctionnement
	}

	/**
	 * Draws the language list (select).
	 */
	public function drawLanguageList() {
		echo '<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
		<select id="langue" name="langue" size="1" onchange="this.form.submit()">';
		foreach ($this->languageList as $lang_key => $lang_value) {
			echo '<option value="'.$lang_value.'"';
			if($lang_key==$this->language) { echo ' selected '; }
			echo '>'.$lang_value.'</option>';
		}
		echo '</select></form>';
	}
}