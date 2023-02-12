<?php
// api/src/Controller/CreateBookPublication.php
namespace App\Controller;

use App\Entity\Order;
use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * This controller is used to mock a service that would be called
 *   to activate a restaurant.
 */
#[AsController]
class PreparingOrderController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function __invoke(Order $order): Order
    {
        $order->setStatus("preparing");
        $this->em->flush();

        return $order;
    }
}
