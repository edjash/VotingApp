<?php

/**
 * The base class for Models.
 *
 * Models derived from this should end with the word 'Model'. For example:
 * UserModel extends Model
 */
class Model
{
		/** @var array Each Model class must implement this array */
		public $properties = array();

    /** @var array an array of validation errors, populated by the validateData method */
    public $errors = array();

		/**
		 * Validate passed data against the Model $properties and populate $errors
		 *
		 * @param  array  $data The data to validate, usually $_POST data.
		 * @return bool         Return true if the data is valid, false if not.
		 */
    public function validateData($data = array())
    {
        if (!is_array($this->properties)) {
            return false;
        }
        foreach ($this->properties as $name => $options) {
            if (!isset($data[$name])) {
                if (!in_array('optional', $options)) {
                    $this->setError($name, 'is required');
                }
                continue;
            }

            $value = trim($data[$name]);
            if (isset($options['maxlen'])) {
                $num = $options['maxlen'];
                if (strlen($value) > $num) {
                    $this->setError($name, 'must not be longer than '.$num.' characters');
                }
            }
            if (isset($options['minlen'])) {
                $num = $options['minlen'];
                if (strlen($value) < $num) {
                    $this->setError($name, 'must be atleast '.$num.' characters');
                }
            }
            if (isset($options['email'])) {
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->setError($name, 'must be a valid email address');
                }
            }
            if (in_array('unique', $options)) {
                if ($this->valueExists($name, $value)) {
                    $this->setError($name, 'already_exists');
                }
            }
        }

        return count($this->errors) ? false : true;
    }

		/**
		 * Return true or false if the value exists in the model table
		 *
		 * @param  string $field The field to find the value in
		 * @param  string $value The value to search for
		 * @return bool          Returns true if the value is found, false if not
		 */
    private function valueExists($field, $value)
    {
        return Database::valueExists($field, $value, $this->getTableName());
    }

		/**
		 * Fetch a single row based on ID
		 *
		 * @param  integer $id The primary key for this row
		 * @return array 			 Returns an array of data
		 */
    public function fetchSingle($id)
    {
        return Database::fetchRow($this->getTableName(), array("id" => $id));
    }

		/**
		 * Fetch every row in the model table
		 * @return array Returns an array of table rows
		 */
    public function fetchAll()
    {
        $table = $this->getTableName();
        return Database::fetchArray("SELECT * FROM `$table`", PDO::FETCH_UNIQUE|PDO::FETCH_ASSOC);
    }

		/**
		 * Trims white space and converts to lowercase where specified
		 *
		 * @param  array $data Array of data to process, usuall $_POST data.
		 * @return array       Returns the modified array.
		 */
    public function filterData($data)
    {
        $clean = array();
        foreach ($this->properties as $name => $options) {
            if (!isset($data[$name])) {
                continue;
            }
            $value = trim($data[$name]);

            if (in_array("lower", $options)) {
                $value = strtolower($value);
            }
            $clean[$name] = $value;
        }
        return $clean;
    }

		/**
		 * Validates the model and then saves it to the database.
		 *
		 * @param  array  $data The data to save.
		 * @return integer|false Returns the insert id, or false on failure.
		 */
    public function save($data = array())
    {
        $data = $this->filterData($data);

        if (!$this->validateData($data)) {
            return false;
        }

        foreach ($this->properties as $name => $options) {
            if (in_array('hash', $options)) {
                $data[$name] = sha1($data[$name]);
            }
        }

        return Database::save($data, $this->getTableName());
    }

		/**
		 * Resolves the table name used by this Model
		 * @return string Returns the table name
		 */
    public function getTableName()
    {
        return isset($this->table_name) ? $this->table_name : get_class($this);
    }

		/**
		 * Sets a validation error.
		 *
		 * @param string $name    Property name
		 * @param string $message Validation error message
		 */
    private function setError($name, $message)
    {
        $this->errors[$name] = $message;
    }
}
