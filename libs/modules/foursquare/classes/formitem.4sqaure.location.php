<?php
class FormItem4sqLocation extends FormItem{
	public function render(){

		$placeid = Form::elem(array(
				'id' => $this['id'],
				'type' => 'text'
			));


		$html = '<div class="row-fluid">';
			$html .= '<div clas="span12">' . $placeid . '</div>';
		$html .= '</div>';


		return $html;
	}
}