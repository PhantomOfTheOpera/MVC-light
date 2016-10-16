<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 14.10.16
 * Time: 21:22
 */


class Sample extends ApiAbstract {

    public $param_1;
    public $param_2;


    public function select($id)  {
        //select from db
        $this->param_1 = "1";
        $this->param_2 = "2";

        echo Api::response($this, 200);
    }

    public function update($object)
    {
        $this->param_1 = $object->param_1;
        $this->param_2 = $object->param_2;

        // update in db

        echo Api::response("Updated", 200);

    }

    public function delete($id)
    {

        // delete from db
        echo Api::response("Deleted", 200);

    }

    public function insert($object)
    {
        $this->param_1 = $object->param_1;
        $this->param_2 = $object->param_2;

        // insert into db

        echo Api::response("Inserted", 200);

    }

    public function search($params)
    {
        $objects = [];

        // search in db

        echo Api::response($objects, 200);
    }
}