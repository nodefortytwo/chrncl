<?php
class Template{
	static $css = array(), $js = array(), $template = array(), $vars = array(), $content = array();

	public function __construct(){
		//$this->addFile('template', 'templates/default.wrapper.html', System::$core_modules['template'], 'global');
	}

	public static function addCss($path, $module = null){
		if(is_null($module)){
			$module = get_calling_module();
		}
		self::addFile('css', $path, $module, 'local');
	}

	public static function addJs($path, $module = null){
		if(is_null($module)){
			$module = get_calling_module();
		}
		self::addFile('js', $path, $module, 'local');
	}

	public static function addTemplate($path, $type="html", $module = null){
		//self::addFile('template', $path, get_calling_module(), 'global');
		self::$content[] = array(
							'source' => 'file',
							'type' => $type,
							'module' => get_calling_module(),
							'file' => $path
							);
	}

	public static function addFile($type, $path, $module, $scope){
		if(!begins_with($path, '//') && !begins_with($path, 'http')){
			//if this isn't external
			$path = $module['path'] . '/' . $path;
		}else{
			$scope = 'external';
		}

		switch($type){
			case 'js':
				self::$js[$scope][] = $path;
				break;
			case 'css':
				self::$css[$scope][] = $path;
				break;
			case 'template':
				self::$template[$scope][] = $path;
				break;
		}
	}

	private function compileCss(){
		$css_complied = array();
		if(isset(self::$css['external'])){
			$css_complied = self::$css['external'];
			unset(self::$css['external']);
		}		
		foreach(self::$css as $scope=>$files){
			foreach($files as $file){
				$scope .= file_get_contents($file);
				$css_complied[] = $file;
			}
			//merge here


		}
		$var = '';
		foreach($css_complied as $file){
			$file = str_replace('./', '/', $file);
			$var .= '<link href="'.$file.'" rel="stylesheet">'. "\n";
		}

		$this->addVariable('css', $var);

	}

	private function compileJs(){
		$js_complied = array();
		if(isset(self::$js['external'])){
			$js_complied = self::$js['external'];
			unset(self::$js['external']);
		}		
		foreach(self::$js as $scope=>$files){
			foreach($files as $file){
				$scope .= file_get_contents($file);
				$js_complied[] = $file;
			}
			//merge here
		}
		$var = '';
		foreach($js_complied as $file){
			$file = str_replace('./', '/', $file);
			$var .= '<script src="'.$file.'?'.md5(rand(0,9999)).'"></script>' . "\n";
		}

		$this->addVariable('js', $var);

	}

	public function addVariable($var, $val){
		self::$vars[$var] = $val;
	}

	public function addVariables($array){
		foreach($array as $var => $val){
			$this->addVariable($var, $val);
		}
	}

	public function c($content, $type='html'){
		self::$content[] = array(
							'source' => 'inline',
							'type' => $type,
							'content' => $content,
							'module' => get_calling_module()
							);
		return $this;
	}

	public function render(){
		$content = '';
		$this->compileCss();
		$this->compileJs();
		foreach(self::$content as $template){
			switch($template['source']){
				case "file":
					$path =  $template['module']['path'] . '/' . $template['file'];
					$content .= file_get_contents($path);
					break;
				case "inline":
					$content .= $template['content'];
					break;
			}
		}
		foreach(self::$vars as $var=>$val){
			$content = str_replace('{{'.$var.'}}', $val, $content);
		}

		$this->addVariable('content', $content);
		$this->addVariable('page_title', Config::get('site_name', 'New Site'));
		if(!isset(self::$vars['container_class'])){
			$this->addVariable('container_class', 'container');
		}
		$content = file_get_contents('./libs/core/template/templates/default.wrapper.html');
		foreach(self::$vars as $var=>$val){
			$content = str_replace('{{'.$var.'}}', $val, $content);
		}


		return $content;
	}
}
//partials are link normal tempalates except they only render the body content;
class Partial{
	public $content = '', $vars = array();
	public function addCss($path){
		Template::addCss($path, get_calling_module());
	}

	public function addJs($path){
		Template::addJs($path, get_calling_module());
	}

	public function __construct($file, $type = 'html'){
		$module = get_calling_module();
		$this->content = file_get_contents($module['path'] . '/' .$file);
	}

	public function addVariable($var, $val){
		$this->vars[$var] = $val;
	}

	public function addVariables($array){
		foreach($array as $var => $val){
			$this->addVariable($var, $val);
		}
	}

	public function render(){
		foreach($this->vars as $var=>$val){
			$this->content = str_replace('{{'.$var.'}}', $val, $this->content);
		}
		return $this->content;
	}
}