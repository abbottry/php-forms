<?php
/**
 * SelectHelper
 *
 * @package default
 * @author Ryan Abbott
 */
class SelectHelper extends FormHelper 
{
	/**
	 * __construct
	 *
	 * @param string $data 
	 * @author Ryan Abbott
	 */
	public function __construct($data = null) {
		parent::__construct($data);
		
		if($data != null) {
			if(isset($data['options'])) {
				$this->addOptions($data['options']);
			}	
		}
	}
	
	/**
	 * addOption
	 *
	 * @param string $option 
	 * @return void
	 * @author Ryan Abbott
	 */
	public function addOption($value, $text, $selected = "") {
		$this->options[] = new Option($value, $text, $selected);
	}
	
	/**
	 * addOptions
	 *
	 * @param string $options 
	 * @return void
	 * @author Ryan Abbott
	 */
	public function addOptions($options) {
		if(!is_array($options)) {
			return false;
		}
		
		foreach($options as $value => $text) {
			$this->addOption($value, $text);
		}
	}
	
	/**
	 * addDatabaseOptions
	 *
	 * @param string $table 
	 * @param string $keyColumn 
	 * @param string $valueColumn 
	 * @param string $conditions 
	 * @return void
	 * @author Ryan Abbott
	 */
	public function addDatabaseOptions($table, $keyColumn, $valueColumn = null, $conditions = null, $order = null) {
		$sql = "SELECT $keyColumn" . (($valueColumn != null) ? ", $valueColumn" : "") . " FROM $table" . (($conditions != null) ? " WHERE $conditions" : "") . (($order != null) ? " ORDER BY $order" : "");
		$this->db->query($sql);
		
		if($this->db->numRows() > 0) {
			while($row = mysql_fetch_array($this->db->result)) {
				$this->options[] = new Option($row[$keyColumn], $row[(($valueColumn != null) ? $valueColumn : $keyColumn)]);
			}
		}
	}
	
	public function getDatabaseOptions($table, $keyColumn, $valueColumn = null, $conditions = null, $order = null) {
		$options = array();
		
		$sql = "SELECT $keyColumn" . (($valueColumn != null) ? ", $valueColumn" : "") . " FROM $table" . (($conditions != null) ? " WHERE $conditions" : "") . (($order != null) ? " ORDER BY $order" : "");
		$this->db->query($sql);
		
		if($this->db->numRows() > 0) {
			while($row = mysql_fetch_array($this->db->result)) {
				$options[$row[$keyColumn]] = $row[(($valueColumn != null) ? $valueColumn : $keyColumn)];
			}
		}
		
		return $options;
	}
	
	/**
	 * toString
	 *
	 * @param string $selected 
	 * @param string $version 
	 * @return void
	 * @author Ryan Abbott
	 */
	public function toString($selected = "none", $version = TOSTRING_FULL) {
		// determine which version of the object to dislay
		if($version == TOSTRING_FULL) {
			$html = "<select". 
				(($this->name != "") ? " name=\"$this->name\"" : "") .
				(($this->id != "") ? " id=\"$this->id\"" : "") .
				(($this->class != "") ? " class=\"$this->class\"" : "") .
				(($this->disabled != "") ? " disabled=\"disabled\"" : "") .">\n";
			
			foreach($this->options as $option) {
				$html .= $option->toString() ."\n";
			}
			
			$html .= "</select>\n";
			
			return $html;
		}
	}
}

/**
 * Option
 *
 * @package default
 * @author Ryan Abbott
 */
class Option extends BaseClass 
{
	/**
	 * __construct
	 *
	 * @param string $key 
	 * @param string $value 
	 * @author Ryan Abbott
	 */
	public function __construct($key, $value, $selected = "") {
		$this->key = $key;
		$this->value = $value;
		if(isset($selected) && $selected === true) {
			$this->selected = true;
		}
		else {
			$this->selected = false;
		}
	}
	
	/**
	 * toString
	 *
	 * @param string $version 
	 * @return void
	 * @author Ryan Abbott
	 */
	public function toString($version = TOSTRING_FULL) {
		// determine which version of the object to dislay
		if($version == TOSTRING_FULL) {
			return "<option value=\"$this->key\"".(($this->selected) ? " selected=\"selected\"" : "").">$this->value</option>";
		}
	}
}
?>