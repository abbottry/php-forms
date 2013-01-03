<?php
/**
 * CheckboxHelper
 *
 * @package default
 * @author Ryan Abbott
 */
class CheckboxHelper extends FormHelper 
{
	/**
	 * __construct
	 *
	 * @param string $data 
	 * @author Ryan Abbott
	 */
	public function __construct($data = null) {
		if(isEmpty($data)) {
			return false;
		}
		
		parent::__construct($data);

		if(isset($data['checked'])) {
			$this->checked = (($data['checked'] === true || $data['checked'] == "true") ? true : false);	
		}
	}
	
	/**
	 * toString
	 *
	 * @return void
	 * @author Ryan Abbott
	 */
	public function toString($version = TOSTRING_FULL) {
		// determine which version of the object to dislay
		if($version == TOSTRING_FULL) {
			$html = "<input type=\"checkbox\" ". 
				((isset($this->id) && isNotEmpty($this->id)) ? "id=\"$this->id\" " : "") .
				((isset($this->name) && isNotEmpty($this->name)) ? "name=\"$this->name\" " : "") .
				((isset($this->class) && isNotEmpty($this->class)) ? "class=\"$this->class\" " : "") .
				((isset($this->value) && isNotEmpty($this->value)) ? "value=\"$this->value\" " : "") .
				((isset($this->checked) && isNotEmpty($this->checked) && $this->checked === true) ? "checked=\"checked\" " : "") . 
				" />\n";

			return $html;
		}
		else if($version == TOSTRING_MEDIUM) {
			return $this->toString(TOSTRING_FULL);
		}
		else if($version == TOSTRING_SHORT) {
			return $this->toString(TOSTRING_FULL);
		}
	}
}
?>