<?php

use Sparkout\SparkTable;

// ajax function called by datatable

$columns = array(
			0 => 'id',
			1 => 'first_name',
			2 => 'email',
			3 => 'phone',
			4 => 'gender',
			5 => 'date_of_birth',
			6 => 'account_type',
			7 => 'status',
			8 => 'action'
        );

        $sortable = ['first_name','name','email','phone'];
                
        $result = [];
        $result[] = array('key'=>'id','html'=>false);
        $result[] = array('key'=>'first_name','html'=>false);
        $result[] = array('key'=>'email','html'=>false);
        $result[] = array('key'=>'phone','html'=>false);
        $result[] = array('key'=>'gender','html'=>false);
        $result[] = array('key'=>'date_of_birth','html'=>false);
        $result[] = array('key'=>'account_type','html'=>false,'value'=>'otp_verified');
        $result[] = array('key'=>'status','html'=>false);
        $result[] = array(
            'key'=>'action',
            'html'=>true,
            'start'=>'<fieldset class="form-group" style="min-width: 100px;"> <select class="form-control" onchange="status_update(this.value,',
            'end'=>')" id="basicSelect" style="cursor: pointer;"> <option selected="selected">Make..</option> <option value="5">View</option> <option value="0">Block</option> <option value="1">Active</option> <option value="2">Delete</option> </select> </fieldset>',
            'value'=>'id'
        ); 

        $sparkTable = new SparkTable($this->users,$this->request);
        return $sparkTable->columns($columns)->sortable($sortable)->process()->render($result);