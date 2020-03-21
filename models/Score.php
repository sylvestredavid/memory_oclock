<?php

/**
 * cette class est notre modèle qui a pour seule fonction d'instancier des objets Score
 * Class Score
 */
class Score
{
    private $id;
    private $name;
    private $time;
    private $dificulty;
    /**
     * Score constructor.
     * @param $name
     * @param $time
     * @param $dificulty
     */
    public function __construct($name, $time, $dificulty)
    {
        $this->name = $name;
        $this->time = $time;
        $this->dificulty = $dificulty;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getDificulty()
    {
        return $this->dificulty;
    }

    /**
     * @param mixed $dificulty
     */
    public function setDificulty($dificulty)
    {
        $this->dificulty = $dificulty;
    }


}