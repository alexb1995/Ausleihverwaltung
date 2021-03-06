<?php
/**
 * HistoricDetailQueryRequest
 *
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * sWIm Activiti REST API
 *
 * here be dragons
 *
 * OpenAPI spec version: v0.2.0
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Swagger\Client\Model;

use \ArrayAccess;
use \Swagger\Client\ObjectSerializer;

/**
 * HistoricDetailQueryRequest Class Doc Comment
 *
 * @category Class
 * @package     Swagger\Client
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class HistoricDetailQueryRequest implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'HistoricDetailQueryRequest';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'start' => 'int',
        'size' => 'int',
        'sort' => 'string',
        'order' => 'string',
        'id' => 'string',
        'process_instance_id' => 'string',
        'execution_id' => 'string',
        'activity_instance_id' => 'string',
        'task_id' => 'string',
        'select_only_form_properties' => 'bool',
        'select_only_variable_updates' => 'bool'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'start' => 'int32',
        'size' => 'int32',
        'sort' => null,
        'order' => null,
        'id' => null,
        'process_instance_id' => null,
        'execution_id' => null,
        'activity_instance_id' => null,
        'task_id' => null,
        'select_only_form_properties' => null,
        'select_only_variable_updates' => null
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'start' => 'start',
        'size' => 'size',
        'sort' => 'sort',
        'order' => 'order',
        'id' => 'id',
        'process_instance_id' => 'processInstanceId',
        'execution_id' => 'executionId',
        'activity_instance_id' => 'activityInstanceId',
        'task_id' => 'taskId',
        'select_only_form_properties' => 'selectOnlyFormProperties',
        'select_only_variable_updates' => 'selectOnlyVariableUpdates'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'start' => 'setStart',
        'size' => 'setSize',
        'sort' => 'setSort',
        'order' => 'setOrder',
        'id' => 'setId',
        'process_instance_id' => 'setProcessInstanceId',
        'execution_id' => 'setExecutionId',
        'activity_instance_id' => 'setActivityInstanceId',
        'task_id' => 'setTaskId',
        'select_only_form_properties' => 'setSelectOnlyFormProperties',
        'select_only_variable_updates' => 'setSelectOnlyVariableUpdates'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'start' => 'getStart',
        'size' => 'getSize',
        'sort' => 'getSort',
        'order' => 'getOrder',
        'id' => 'getId',
        'process_instance_id' => 'getProcessInstanceId',
        'execution_id' => 'getExecutionId',
        'activity_instance_id' => 'getActivityInstanceId',
        'task_id' => 'getTaskId',
        'select_only_form_properties' => 'getSelectOnlyFormProperties',
        'select_only_variable_updates' => 'getSelectOnlyVariableUpdates'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$swaggerModelName;
    }

    

    

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['start'] = isset($data['start']) ? $data['start'] : null;
        $this->container['size'] = isset($data['size']) ? $data['size'] : null;
        $this->container['sort'] = isset($data['sort']) ? $data['sort'] : null;
        $this->container['order'] = isset($data['order']) ? $data['order'] : null;
        $this->container['id'] = isset($data['id']) ? $data['id'] : null;
        $this->container['process_instance_id'] = isset($data['process_instance_id']) ? $data['process_instance_id'] : null;
        $this->container['execution_id'] = isset($data['execution_id']) ? $data['execution_id'] : null;
        $this->container['activity_instance_id'] = isset($data['activity_instance_id']) ? $data['activity_instance_id'] : null;
        $this->container['task_id'] = isset($data['task_id']) ? $data['task_id'] : null;
        $this->container['select_only_form_properties'] = isset($data['select_only_form_properties']) ? $data['select_only_form_properties'] : false;
        $this->container['select_only_variable_updates'] = isset($data['select_only_variable_updates']) ? $data['select_only_variable_updates'] : false;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {

        return true;
    }


    /**
     * Gets start
     *
     * @return int
     */
    public function getStart()
    {
        return $this->container['start'];
    }

    /**
     * Sets start
     *
     * @param int $start start
     *
     * @return $this
     */
    public function setStart($start)
    {
        $this->container['start'] = $start;

        return $this;
    }

    /**
     * Gets size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->container['size'];
    }

    /**
     * Sets size
     *
     * @param int $size size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->container['size'] = $size;

        return $this;
    }

    /**
     * Gets sort
     *
     * @return string
     */
    public function getSort()
    {
        return $this->container['sort'];
    }

    /**
     * Sets sort
     *
     * @param string $sort sort
     *
     * @return $this
     */
    public function setSort($sort)
    {
        $this->container['sort'] = $sort;

        return $this;
    }

    /**
     * Gets order
     *
     * @return string
     */
    public function getOrder()
    {
        return $this->container['order'];
    }

    /**
     * Sets order
     *
     * @param string $order order
     *
     * @return $this
     */
    public function setOrder($order)
    {
        $this->container['order'] = $order;

        return $this;
    }

    /**
     * Gets id
     *
     * @return string
     */
    public function getId()
    {
        return $this->container['id'];
    }

    /**
     * Sets id
     *
     * @param string $id id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->container['id'] = $id;

        return $this;
    }

    /**
     * Gets process_instance_id
     *
     * @return string
     */
    public function getProcessInstanceId()
    {
        return $this->container['process_instance_id'];
    }

    /**
     * Sets process_instance_id
     *
     * @param string $process_instance_id process_instance_id
     *
     * @return $this
     */
    public function setProcessInstanceId($process_instance_id)
    {
        $this->container['process_instance_id'] = $process_instance_id;

        return $this;
    }

    /**
     * Gets execution_id
     *
     * @return string
     */
    public function getExecutionId()
    {
        return $this->container['execution_id'];
    }

    /**
     * Sets execution_id
     *
     * @param string $execution_id execution_id
     *
     * @return $this
     */
    public function setExecutionId($execution_id)
    {
        $this->container['execution_id'] = $execution_id;

        return $this;
    }

    /**
     * Gets activity_instance_id
     *
     * @return string
     */
    public function getActivityInstanceId()
    {
        return $this->container['activity_instance_id'];
    }

    /**
     * Sets activity_instance_id
     *
     * @param string $activity_instance_id activity_instance_id
     *
     * @return $this
     */
    public function setActivityInstanceId($activity_instance_id)
    {
        $this->container['activity_instance_id'] = $activity_instance_id;

        return $this;
    }

    /**
     * Gets task_id
     *
     * @return string
     */
    public function getTaskId()
    {
        return $this->container['task_id'];
    }

    /**
     * Sets task_id
     *
     * @param string $task_id task_id
     *
     * @return $this
     */
    public function setTaskId($task_id)
    {
        $this->container['task_id'] = $task_id;

        return $this;
    }

    /**
     * Gets select_only_form_properties
     *
     * @return bool
     */
    public function getSelectOnlyFormProperties()
    {
        return $this->container['select_only_form_properties'];
    }

    /**
     * Sets select_only_form_properties
     *
     * @param bool $select_only_form_properties select_only_form_properties
     *
     * @return $this
     */
    public function setSelectOnlyFormProperties($select_only_form_properties)
    {
        $this->container['select_only_form_properties'] = $select_only_form_properties;

        return $this;
    }

    /**
     * Gets select_only_variable_updates
     *
     * @return bool
     */
    public function getSelectOnlyVariableUpdates()
    {
        return $this->container['select_only_variable_updates'];
    }

    /**
     * Sets select_only_variable_updates
     *
     * @param bool $select_only_variable_updates select_only_variable_updates
     *
     * @return $this
     */
    public function setSelectOnlyVariableUpdates($select_only_variable_updates)
    {
        $this->container['select_only_variable_updates'] = $select_only_variable_updates;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param  integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param  integer $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param  integer $offset Offset
     * @param  mixed   $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param  integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(
                ObjectSerializer::sanitizeForSerialization($this),
                JSON_PRETTY_PRINT
            );
        }

        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}

