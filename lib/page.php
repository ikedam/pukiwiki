<?php
// Copyright (C)
//   2014 IKEDA Yasuyuki
// License: GPL v2 or (at your option) any later version
//
// A class that represents pages.

class Page
{
	/// available formats
	private static $FORMATS = array(
		"pukiwiki" => array( "ext" => "txt", "name" => "PukiWiki", ),
		"markdown" => array( "ext" => "md", "name" => "Markdown", ),
	);
	private static $EXT_TO_FORMATS = array(
		"txt" => "pukiwiki",
		"md" => "markdown",
	);
	private static $DEFAULT_FORMAT = "markdown";
	
	
	/// page title
	private $title;
	
	/// format of this page
	/// pukiwiki or markdown
	private $format;
	
	/// already saved to file (that means cannot change format).
	private $persist;
	
	public function __construct($title, $format=NULL, $persist=TRUE)
	{
		$this->title = $title;
		$this->format = is_null($format)?self::$DEFAULT_FORMAT:$format;
		$this->persist = $persist;
	}
	
	public static function getInstanceByTitle($title)
	{
		foreach(self::$FORMATS as $format => $formatinfo)
		{
			$page = new Page($title, $format);
			if(file_exists(DATA_DIR . $page->getFilename()))
			{
				return $page;
			}
		}
		
		return null;
	}
	
	public static function getOrCreateInstanceByTitle($title)
	{
		$page = self::getInstanceByTitle($title);
		if(is_null($page))
		{
			return new Page($title, self::$DEFAULT_FORMAT);
		}
		
		return $page;
	}
	
	public static function getExtensions()
	{
		$ret = array();
		foreach(self::$FORMATS as $format => $formatinfo)
		{
			array_push($ret, $formatinfo["ext"]);
		}
		
		return $ret;
	}
	
	public static function getDefaultFormat()
	{
		return self::$DEFAULT_FORMAT;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function getFormat()
	{
		return $this->format;
	}
	
	public function getFormatName()
	{
		$format = $this->getFormat();
		if(array_key_exists($format, self::$FORMATS))
		{
			return self::$FORMATS[$format]["name"];
		}
		return "";
	}
	
	public function isPersist()
	{
		return $this->persist;
	}
	
	public function checkPersist()
	{
		$this->persist = file_exists(DATA_DIR . $this->getFilename());
		return $this->persist;
	}
	
	public function getFileExtension()
	{
		if(array_key_exists($this->format, self::$FORMATS))
		{
			return self::$FORMATS[$this->format]["ext"];
		}
		return $FORMATS[self::$DEFAULT_FORMAT]["ext"];
	}
	
	public function getFilename()
	{
		$t = $this->getTitle();
		return encode($t) . "." . $this->getFileExtension();
	}
}


?>
