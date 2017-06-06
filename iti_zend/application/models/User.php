<?php

class Application_Model_User extends Zend_Db_Table_Abstract
{
    protected $_name = 'users';

    function listUsers(){
        return $this->fetchAll()->toArray();
    }

    function deleteUser($id){
        $this->delete("id =$id");
    }

    function userDetails($id){
        return $this->find($id)->toArray()[0];
    }

    function addNewUSer($userData)
    {
        $row = $this->createRow();
        $row->fname = $userData['fname'];
        $row->lname = $userData['lname'];
        $row->gender = $userData['gender'];
        $row->email = $userData['email'];
        $row->track = $userData['track'];
        $row->save();
    }

    function updateUSer($id,$userData)
    {
      /* put userdata into another array because 
      user data which come from form has also the value of button
      so the no. of elements don't match the database columns
      */
        $user_data['fname'] = $userData['fname'];
        $user_data['lname'] = $userData['lname'];
        $user_data['gender'] = $userData['gender'];
        $user_data['email'] = $userData['email'];
        $user_data['track'] = $userData['track'];
        $this->update($user_data,"id=$id");
    }
}
