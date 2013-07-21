<?php
class FormItem4sqLocation extends FormItem{
	public function render(){
		Template::addJs('js/foursquare.location.search.js');
		$placeid = Form::elem(array(
				'id' => $this['id'],
				'type' => 'hidden',
				'default' => $this['default']
			));

		$venue = new Venue($this['default']);

		$search = Form::elem(array(
				'id' => $this['id'] . '_location',
				'type' => 'text',
				'label' => 'Location Search',
				'default' => $venue['name']
			));


		$html = '<div class="row-fluid foursquare-location-search" data-id="'.$this['id'].'">';
			$html .= '<div clas="span12">' . $search . '</div>';
			$html .= $placeid;
		$html .= '</div>';


		return $html;
	}
}