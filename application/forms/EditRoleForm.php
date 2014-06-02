<?php
class Application_Form_EditRoleForm extends Zend_Form
{
    public function init()
    {
        $name = $this->createElement('text','name');
        $name->setLabel('Name: *')
             ->setRequired(true);

        $description = $this->createElement('text','description');
        $description->setLabel('Description: *')
                    ->setRequired(true);

        $register = $this->createElement('submit','Edit');
        $register->setLabel('Register')
                 ->setIgnore(true);

        $actions = array('create', 'edit', 'delete');
        $targets = array('user', 'report', 'role');

        foreach($targets as $target){
            foreach($actions as $action){
                $checkBoxes[] = new Zend_Form_Element_Checkbox($action . '_' . $target);
            }
        }

        $this->addElements($checkBoxes);

        $this->addElements(array(
            $name,
            $description,
        ));
        
    }
}
