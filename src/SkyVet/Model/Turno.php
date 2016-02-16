<?php

namespace SkyVet\Model;


class Turno
{

    /**
     * @var integer|null
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $telefono;

    /**
     * @var string
     */
    private $comentarios;

    /**
     * Turno constructor.
     * @param array $options
     */
    public function __construct($options = array())
    {

        $defaults = array(
            'telefono' => '',
            'nombre' => '',
            'comentarios' => '',
            'id' => null,
            'date' => new \DateTime()
        );

        $options = array_merge($defaults, $options);

        $this->setNombre($options['nombre']);
        $this->setTelefono($options['telefono']);
        $this->setComentarios($options['comentarios']);
        $this->setId($options['id']);
        $this->setDate($options['date']);

    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param string $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param string $comentarios
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

}