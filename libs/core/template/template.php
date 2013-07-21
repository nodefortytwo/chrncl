<?php

function template_init(){
	Template::addCss('css/bootstrap.min.css');
	Template::addCss('css/bootstrap-responsive.min.css');
	Template::addCss('css/core.css');

	
	Template::addJs('js/jquery.js');
	Template::addJs('js/jquery.ui.map.full.min.js');
	//Template::addJs('//cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js');
	Template::addJs('js/bootstrap.min.js');
	//Template::addJs('js/handlebars.js');
	//Template::addJs('js/ember.js');
	//Template::addJs('js/ember-data-latest.min.js');
	//Template::addJs('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.0.0/moment.min.js');
	Template::addJs('js/holder.js');
	//Template::addJs('js/hammer.min.js');
	//Template::addJs('js/jquery.hammer.min.js');
	//Template::addJs('js/scrollfix.js');

	Template::addVariable('project_name', 'Chrncl');
}