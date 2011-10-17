<?php
class UserRole extends AppModel {

	var $name = 'UserRole';	
	var $actsAs = array('Acl' => array('requester'), 'Tree');
	var $viewPrefixes = array('admin' => 'admin');
	

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'user_role_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	/**
	 * This is function is used in conjunction with the Acl behavior included in the actsAs variable.
	 * It works transparently in the models afterSave() but requires that requires this method to be defined.
	 * http://book.cakephp.org/view/545/Using-the-AclBehavior
	 *
	 * Right now it appears to be working so that sub user roles create sub Aros
	 */
	function parentNode() {
   		if (!$this->id && empty($this->data)) {
	        return null;
	    }
	    $data = $this->data;
	    if (empty($this->data)) {
	        $data = $this->read();
	    }
	    if (empty($data['UserRole']['parent_id'])) {
	        return null;
	    } else {
			$aro = array(
				'UserRole' => array(
					'id' => $data['UserRole']['parent_id'],
					),
				);
	        return $aro;
	    }
	}
	
	/**
	 * 
	 */
	function afterSave($created) {
        if (!$created) :
			#So far I have not seen a use for this (11/27/2010).  So who ever put it here should comment about its use, or it will be deleted.
            $parent = $this->parentNode();
            $parent = $this->node($parent);
            $node = $this->node();
            $aro = $node[0];
            $aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];			
            $this->Aro->save($aro);			
        endif;
		
		# updates users view_prefix if its been changed
		if (!empty($this->data['UserRole']['id'])) :
			$this->User->updateAll(
				array('User.view_prefix' => "'".$this->data['UserRole']['view_prefix']."'"),
				array('User.user_role_id' => $this->data['UserRole']['id'])
				);
		endif;
	}
	
	
	function viewPrefixes() {
		return array(
			'admin' => 'admin',
			);
	}

}
?>