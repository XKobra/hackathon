<?php
namespace Cibum\ConcursoBundle\Updater;

use Doctrine\ORM\EntityManager;
use Cibum\ConcursoBundle\Entity\Proyecto;
use Cibum\ConcursoBundle\Entity\ProyectoRepository;
use Cibum\ConcursoBundle\Entity\Anual;

class Updater
{
    /** @var $em \Doctrine\ORM\EntityManager */
    private $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function batchUpdate()
    {
        $socrata = new Socrata('https://opendata.socrata.com/api', 'h3ut-rsd9');

        //pull data
        $data = array();

        /** @var $repo ProyectoRepository */
        $repo = $this->em->getRepository('CibumConcursoBundle:Proyecto');

        $snips = $repo->getAllSnips();
        $new = array_diff($data, $snips);

        foreach($new as $each) {
            // obtener cada proyecto
            $fila = array();

            $proyecto = new Proyecto();
            $proyecto->setNombre($fila[0]->getNombre());
            $proyecto->setDescripcion($fila[0]->getDescripcion());
            $proyecto->setSnip($fila[0]->getSnip());


            foreach($fila as $anual) {
                $anho = new Anual();
                $anho->setAnho($anual->getAnho());
                $anho->setAvance($anual->getAvance());

                foreach ($anho->getDistritos() as $distrito) {
                    $distrito = $this->em->getRepository('CibumConcursoBundle:Distrito')->findBy(array('nombre' => $distrito));
                    $anho->addDistrito($distrito);
                }

                $proyecto->addAnual($anho);
            }
        }
    }

    public function updateOne($project)
    {

    }

}
