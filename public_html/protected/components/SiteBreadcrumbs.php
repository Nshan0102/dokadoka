<?php

class SiteBreadcrumbs extends CWidget
{
	public $before="<ul class='breadcrumbs'>\n";
	public $after="</ul>\n";
	public $links=array();
	public $separator="<li class='divider'>/</li>";
	public function run()
	{
		if(empty($this->links))
			return;

		$links=array("<li><a href='/'>Главная</a></li>");


		foreach($this->links as $label=>$url)
		{
			if(empty($url))
				$links[]="<li>".$label."</li>\n";
			else
				$links[]='<li><a href="'.$url.'">'.$label.'</a></li>';
		}
		echo $this->before.implode($this->separator,$links).$this->after;
	}
}