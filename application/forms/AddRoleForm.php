<?php
class Application_Form_AddRoleForm extends Zend_Form
{
    public function init()
    {
        $name = $this->createElement('text','name');
        $name->setLabel('Name: *')
             ->setRequired(true);

        $description = $this->createElement('text','description');
        $description->setLabel('Description: *')
                    ->setRequired(true);

        $register = $this->createElement('submit','Register');
        $register->setLabel('Register')
                 ->setIgnore(true);

        $actions = array('create', 'edit', 'delete');
        $targets = array('user', 'report', 'role');

        foreach($targets as $target){
            foreach($actions as $action){
                $id = $action . '_' . $target;
                $checkbox = $this->CreateElement('checkbox', $id);
                $checkbox->setLabel($id);
                $this->addElement($checkbox);
            }
        }

        $this->addElements(array(
            $name,
            $description,
        ));
    }
}
