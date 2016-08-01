<?php

namespace Tests\Ndewez\EventsBundle\Model;

class MyModel
{
    /** @var string */
    private $field1;

    /** @var string */
    private $field2;

    /**
     * @return string
     */
    public function getField1()
    {
        return $this->field1;
    }

    /**
     * @param string $field1
     *
     * @return MyModel
     */
    public function setField1($field1)
    {
        $this->field1 = $field1;

        return $this;
    }

    /**
     * @return string
     */
    public function getField2()
    {
        return $this->field2;
    }

    /**
     * @param string $field2
     *
     * @return MyModel
     */
    public function setField2($field2)
    {
        $this->field2 = $field2;

        return $this;
    }
}
