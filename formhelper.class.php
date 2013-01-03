<?php
/**
 * FormHelper
 *
 * @package default
 * @author Ryan Abbott
 */
class FormHelper 
{
	protected $db;
	
	/**
	 * Magic __construct method
	 *
	 * @author Ryan Abbott
	 */
	public function __construct($data) {
		$this->db = new MySQL();
		
		if($data != null) {
			// every form element could contain the following
			if(isset($data['class'])) {
				$this->class = $data['class'];
			}
			if(isset($data['name'])) {
				$this->name = $data['name'];
			}
			if(isset($data['id'])) {
				$this->id = $data['id'];	
			}
			if(isset($data['value'])) {
				$this->value = $data['value'];	
			}
			if(isset($data['disabled'])) {
				$this->disabled = $data['disabled'];	
			}
			if(isset($data['label'])) {
				$this->label = $data['label'];	
			}
		}
	}

	/**
	 * toString
	 *
	 * @return void
	 * @author Ryan Abbott
	 */
	public function toString() {
		return "The get_class(this) object does not provide a toString() method.";
	}
	
	public function __toString() {
		return $this->toString();
	}
}
?>