<?php
// api/src/Controller/CreateBookPublication.php
namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class AcceptDeliveryController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function __invoke(Order $order): Order
    {
        $order
            ->setDeliverer($this->getUser())
            ->setStatus("accepted");

        $this->em->flush();

        return $order;
    }
}
