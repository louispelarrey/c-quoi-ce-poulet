<?php
// api/src/Controller/CreateBookPublication.php
namespace App\Controller;

use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * This controller is used to mock a service that would be called
 *   to activate a restaurant.
 */
#[AsController]
class ActivateRestaurantController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function __invoke(Restaurant $restaurant): Restaurant
    {
        $restaurant->setIsActivated(true);
        $user = $restaurant->getOwner();
        $user->setRoles(['ROLE_USER', 'ROLE_RESTAURANT']);
        $this->em->persist($restaurant);
        $this->em->persist($user);
        $this->em->flush();

        return $restaurant;
    }
}
