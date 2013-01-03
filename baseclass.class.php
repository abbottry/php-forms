<?php

/**
 * BaseClass
 *
 * @package default
 * @author Ryan Abbott
 */
class BaseClass 
{
	protected $data = array();

	/**
	 * Magic __set() method
	 *
	 * @param string $name 
	 * @param string $value 
	 * @return void
	 * @author Ryan Abbott
	 */
    public function __set($name, $value) {
        //echo "Setting '$name' to '$value'\n";
        $this->data[$name] = $value;
    }

	/**
	 * Magic __get() method
	 *
	 * @param string $name 
	 * @return void
	 * @author Ryan Abbott
	 */
    public function __get($name) {
			//echo "Getting '$name'\n";
			if (array_key_exists($name, $this->data)) {
	
				if (is_array($this->data[$name])) {
					return (array) $this->data[$name];
				}
			
				return $this->data[$name];
			}

			$trace = debug_backtrace();
			trigger_error(
				'Undefined property via __get(): ' . $name .
				' in ' . $trace[0]['file'] .
				' on line ' . $trace[0]['line'],
				E_USER_NOTICE);

			return null;
		}

    /**
     * Magic __isset() method
     *
     * @param string $name 
     * @return void
     * @author Ryan Abbott
     */
    public function __isset($name) {
        //echo "Is '$name' set?\n";
        return isset($this->data[$name]);
    }

	/**
	 * Magic __unset method
	 *
	 * @param string $name 
	 * @return void
	 * @author Ryan Abbott
	 */
    public function __unset($name) {
        //echo "Unsetting '$name'\n";
        unset($this->data[$name]);
    }

		/**
		 * __toString
		 *
		 * @return void
		 * @author Ryan Abbott
		 */
		public function __toString() {
			return $this->toString(TOSTRING_MEDIUM);
		}

	/**
	 * toString
	 *
	 * @return void
	 * @author Ryan Abbott
	 */
	public function toString() {
		return "The " . get_class($this) ." object does not provide a toString() method.";
	}
	
	/**
	 * getMemberVars
	 *
	 * @return array of member variables
	 * @author Ryan Abbott
	 */
	public function getMemberVars() {
		return $this->data;
	}
	
	/**
	 * getStaticProperty
	 *
	 * @package default
	 * @author Ryan Abbott
	 */
	public function getStaticProperty($property) {
		if(!property_exists(get_class($this), $property)) {
			return false;
		}

		$vars = get_class_vars(get_class($this));
		
		return $vars[$property];
	}
	
	/**
	 * getRemoveUrl
	 *
	 * @return void
	 * @author Ryan Abbott
	 */
	public function getRemoveUrl() {
		if(isset($this->id) && isNotEmpty($this->id)) {
			return "/admin/" . strtolower(pluralize(get_class($this))) . "/remove.php?id=" . $this->id;			
		}
		else {
			return EMPTY_LINK;
		}
	}
	
	/**
	 * getViewUrl
	 *
	 * @return void
	 * @author Ryan Abbott
	 */
	public function getViewUrl() {
		if(isset($this->id) && isNotEmpty($this->id)) {
			return "/admin/" . strtolower(pluralize(get_class($this))) . "/view.php?id=" . $this->id;			
		}
		else {
			return EMPTY_LINK;
		}
	}
	
	/**
	 * getEditUrl
	 *
	 * @return void
	 * @author Ryan Abbott
	 */
	public function getEditUrl() {
		if(isset($this->id) && isNotEmpty($this->id)) {
			return "/admin/" . strtolower(pluralize(get_class($this))) . "/edit.php?id=" . $this->id;			
		}
		else {
			return EMPTY_LINK;
		}
	}
}
?>