<?php
namespace SkyVet\Model;

/**
 * Class Turno
 *
 * @package SkyVet\Model
 */
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
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $defaults = [
            'telefono' => '',
            'nombre' => '',
            'comentarios' => '',
            'id' => null,
            'date' => new \DateTime()
        ];

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
     *
     * @return void
     */
    public function setDate(\DateTime $date)
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
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
