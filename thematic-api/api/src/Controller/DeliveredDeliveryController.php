<?php
// api/src/Controller/CreateBookPublication.php
namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class DeliveredDeliveryController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function __invoke(Order $order, Request $request): Order
    {
        $data = json_decode($request->getContent(), true);

        $order->setStatus("delivered");
        $this->em->flush();

        return $order;
    }
}
