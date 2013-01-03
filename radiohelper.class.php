<?php
/**
 * RadioHelper
 *
 * @package default
 * @author Ryan Abbott
 */
class RadioHelper extends FormHelper 
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
			if(isset($data['checked']) && $data['checked'] === true) {
				$this->checked = "checked";	
			}
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
	public function addDatabaseOptions($table, $keyColumn, $valueColumn, $conditions = null, $order = null) {
		$sql = "SELECT $keyColumn, $valueColumn FROM $table" . (($conditions != null) ? " WHERE $conditions" : "") . (($order != null) ? " ORDER BY $order" : "");
		$this->db->query($sql);
		
		if($this->db->numRows() > 0) {
			while($row = mysql_fetch_array($this->db->result)) {
				$this->options[] = new Option($row[$keyColumn], $row[$valueColumn]);
			}
		}
	}
	
	public function getDatabaseOptions($table, $keyColumn, $valueColumn, $conditions = null, $order = null) {
		$sql = "SELECT $keyColumn, $valueColumn FROM $table" . (($conditions != null) ? " WHERE $conditions" : "") . (($order != null) ? " ORDER BY $order" : "");
		$this->db->query($sql);
		
		if($this->db->numRows() > 0) {
			while($row = mysql_fetch_array($this->db->result)) {
				$options[$row[$keyColumn]] = $row[$valueColumn];
			}
		}
		
		return $options;
	}
	
	/**
	 * toString
	 *
	 * @return void
	 * @author Ryan Abbott
	 */
	public function toString($selected = "none", $version = TOSTRING_FULL) {
		// determine which version of the object to dislay
		if($version == TOSTRING_FULL) {
			$html = "<input type=\"radio\" ". 
				(($this->id != "") ? "id=\"$this->id\" " : "") .
				(($this->name != "") ? "name=\"$this->name\" " : "") .
				(($this->class != "") ? "class=\"$this->class\" " : "") .
				(($this->disabled != "" && $this->disabled === true) ? "disabled=\"disabled\" " : "") .
				(($this->value != "") ? "value=\"$this->value\" " : "") .
				(($this->checked != "") ? "checked=\"checked\" " : "") . " />\n";
				
			if(isset($this->label)) {
				$html = "<label for=\"$this->id\">$html $this->label</label>";
			}

			return $html;
		}
	}
}
?>