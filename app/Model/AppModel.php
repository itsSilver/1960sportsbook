<?php
 
class AppModel extends Model {

    function getName() {
        return $this->name;
    }

    function getPluralName() {
        return Inflector::pluralize($this->name);
    }

    public function getView($id) {
        $options['fields'] = array();
        $options['recursive'] = -1;
        $options['conditions'] = array($this->name . '.id' => $id);

        foreach ($this->_schema as $key => $value) {
            if (($key != 'id') && ($key != 'order'))
                $options['fields'][] = $this->name . '.' . $key;
        }        
        $data = $this->find('first', $options);        
        return $data;
    }

    function getIndex() {
        return array();
    }

    function getIndexFields() {
        $options['fields'] = array();
        foreach ($this->_schema as $key => $value) {
            if ($key != 'order')
                $options['fields'][] = $this->name . '.' . $key;
        }
        return $options['fields'];
    }

    function getSearch() {
        $options['fields'] = array();
        foreach ($this->_schema as $key => $value) {
            if (($key != 'id') && ($key != 'order'))
                $options['fields'][] = $this->name . '.' . $key;
        }
        return $options['fields'];
    }

    function getAdd() {
        $fields = array();
        foreach ($this->_schema as $key => $value) {
            if (($key != 'id') && ($key != 'order') && ($key != 'min_bet') && ($key != 'max_bet'))
                $fields[] = $this->name . '.' . $key;
        }
        $fields = $this->getBelongings($fields);
        return $fields;
    }

    function getEdit() {
        $fields = array();
        foreach ($this->_schema as $key => $value) {
            if (($key != 'id') && ($key != 'order'))
                $fields[] = $this->name . '.' . $key;
        }
        $fields = $this->getBelongings($fields);
        return $fields;
    }

    function getTranslate() {
        $first = true;
        $args['fields'][] = 'id';
        $fields = $this->actsAs['Translate'];
        foreach ($fields as $key => $value) {
            if ($first) {
                $first = false;
                $args['fields'][] = $key;
            } else
                $args['fields'][] = $value;
        }
        return $args['fields'];
    }

	//TODO fix this crap
    function getBelongings($fields) {
        foreach ($fields as $key => $value) {
            $model = $this->belongs($value);
            if ($model) {
                unset($fields[$key]);
                $fields[$value] = array(
                    'type' => 'select',
                    'options' => $this->{$model}->find('list')
                );
            }
        }
        return $fields;
    }

    function getIdNames($data) {
        if (empty($data))
            return $data;
        $newData = $data;
        if (isset($data[$this->name]))
            $data = array('0' => $data);
        foreach ($data as &$row) {
            foreach ($row[$this->name] as $key => $value) {
                $model = $this->belongs($key);
                if ($model != false) {
                    $options['recursive'] = 0;
                    $options['conditions'] = array($model . '.id' => $value);

                    $parent = ($this->{$model}->find('first', $options));
                    if (isset($parent[$model]['name']))
                        $row[$this->name][$key] = $parent[$model]['name'];
                    else
                        $row[$this->name][$key] = $parent[$model]['username'];
                }
                if ($key == 'active') {
                    if ($value == 1)
                        $row[$this->name][$key] = __('Yes', true);
                    else
                        $row[$this->name][$key] = __('No', true);
                }
            }
        }
        if (isset($newData[$this->name]))
            $data = $data[0];
        
        return $data;
    }

    function belongs($value) {
        $value = (str_replace($this->name . '.', '', $value));
        $belonging = (str_replace('_id', '', $value));
        $belonging = ucfirst($belonging);
        if (isset($this->belongsTo[$belonging])) {
            return $belonging;
        }
        return false;
    }

    function getSearchConditions($data) {
        $conditions = array();
        foreach ($data[$this->name] as $key => $value) {
            if (!empty($value)) {
                $conditions[$this->name . '.' . $key . ' LIKE'] = '%' . $value . '%';
            }
        }
        return $conditions;
    }

    function getActions() {
        $actions = array();
        $actions[] = array('name' => __('View', true), 'action' => 'view', 'controller' => NULL);
        $actions[] = array('name' => __('Edit', true), 'action' => 'edit', 'controller' => NULL);
        $actions[] = array('name' => __('Delete', true), 'action' => 'delete', 'controller' => NULL);
        return $actions;
    }

    function getItem($id, $recursive = -1, $contain = null) {
        $conditions = array($this->name . '.id' => $id);
        $options['conditions'] = array(
            $this->name . '.id' => $id
        );
        if (isset($contain)) {
            $this->contain();
        } else {
            $options['recursive'] = $recursive;
        }
        return $this->find('first', $options);
    }

    public function findItem($conditions, $recursive = -1, $contain = null, $limit = null) {
        foreach ($conditions as $key => $value) {
            if (!empty($value)) {
                $options['conditions'][$this->name . '.' . $key . ' LIKE'] = '%' . $value . '%';
            }
        }
        if (isset($limit)) {
            $options['limit'] = $limit;
        }
        if (isset($contain)) {
            $this->contain($contain);
        } else {
            $options['recursive'] = $recursive;
        }
        return $this->find('all', $options);
    }

    function getParentId($id) {
        if (isset($this->belongsTo)) {
            $options['conditions'] = array(
                $this->name . '.id' => $id
            );
            $options['recursive'] = 0;
            $parent = $this->find('first', $options);
            $belongsTo = array_keys($this->belongsTo);
            $belongsTo = $belongsTo[0];
            return $parent[$belongsTo]['id'];
        }
        return NULL;
    }

    function getParent() {
        if (!empty($this->belongsTo)) {
            $belongsTo = array_keys($this->belongsTo);
            $belongsTo = $belongsTo[0];
            return $belongsTo;
        }
        return NULL;
    }

    function getValidation() {
        return $this->validate;
    }

    function getTabs($params) {

        $tabs = array();
        $tabs[$params['controller'] . 'admin_index'] = array(
            'name' => __('List', true),
            'url' => (array('controller' => $params['controller'], 'action' => 'index'))
        );
        $tabs[$params['controller'] . 'admin_add'] = array(
            'name' => __('Add', true),
            'url' => (array('controller' => $params['controller'], 'action' => 'add'))
        );
        $tabs[$params['controller'] . 'admin_search'] = array(
            'name' => __('Search', true),
            'url' => (array('controller' => $params['controller'], 'action' => 'search'))
        );
        if (isset($params['action'])) {
            if (($params['action'] == 'admin_view') || ($params['action'] == 'admin_edit')) {
                $tabs[$params['controller'] . 'admin_view'] = array(
                    'name' => __('View', true),
                    'url' => (array('controller' => $params['controller'], 'action' => 'view', $params['pass'][0]))
                );
                $tabs[$params['controller'] . 'admin_edit'] = array(
                    'name' => __('Edit', true),
                    'url' => (array('controller' => $params['controller'], 'action' => 'edit', $params['pass'][0]))
                );
            }
        }
        if (!isset($tabs[$params['controller'] . $params['action']]['name'])) {
            $tabs[$params['controller'] . $params['action']]['name'] = Inflector::humanize(str_replace('admin_', '', $params['action']));
        }
        $tabs[$params['controller'] . $params['action']]['active'] = 1;
        $tabs[$params['controller'] . $params['action']]['url'] = '#';

        return $tabs;		
    }

    function __makeTab($name, $action, $controller = NULL, $var = NULL, $active = false) {
        $url = array(
            'name' => $name,
            'url' => (array('controller' => $controller, 'action' => $action, $var))
        );
        if ($active)
            $url['active'] = true;
        return $url;
    }

    function isOrderable() {
        $schema = $this->schema();
        if (isset($schema['order']))
            return true;
        return false;
    }

    function moveUp($id) {
        $model = $this->name;
        $options['conditions'] = array(
            $model . '.id' => $id
        );
        $item = $this->find('first', $options);
        $order = $item[$model]['order'];
        $options['conditions'] = array(
            $model . '.order <' => $order
        );
        $options['order'] = $model . '.order DESC';
        $item2 = $this->find('first', $options);
        if (empty($item2))
            return;
        $item[$model]['order'] = $item2[$model]['order'];
        $item2[$model]['order'] = $order;
        $this->save($item);
        $this->save($item2);
    }

    function moveDown($id) {
        $model = $this->name;
        $options['conditions'] = array(
            $model . '.id' => $id
        );
        $item = $this->find('first', $options);
        $order = $item[$model]['order'];
        $options['conditions'] = array(
            $model . '.order >' => $order
        );
        $options['order'] = $model . '.order ASC';
        $item2 = $this->find('first', $options);
        if (empty($item2))
            return;
        $item[$model]['order'] = $item2[$model]['order'];
        $item2[$model]['order'] = $order;
        $this->save($item);
        $this->save($item2);
    }

    function findLastOrder() {
        $model = $this->name;
        $options['order'] = $model . '.order DESC';
        $item = $this->find('first', $options);
        if (!empty($item))
            return $item[$model]['order'];
        return 0;
    }

    function getSqlDate() {
        return gmdate('Y-m-d H:i:s');
    }

}

?>
