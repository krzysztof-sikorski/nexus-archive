<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\NexusRawDataRepository;
use App\Repository\UserAccessTokenRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

final class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private UserRepository $userRepository,
        private UserAccessTokenRepository $userAccessTokenRepository,
        private NexusRawDataRepository $nexusRawDataRepository
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $userTotalCount = $this->userRepository->getTotalCount();
        $tokenTotalCount = $this->userAccessTokenRepository->getTotalCount();
        $dataTotalCount = $this->nexusRawDataRepository->getTotalCount();
        $dataWeeklyCount = $this->nexusRawDataRepository->getPartialCount(
            new DateTimeImmutable('midnight UTC - 1 week'),
            new DateTimeImmutable('midnight UTC')
        );
        $dataDailyCount = $this->nexusRawDataRepository->getPartialCount(
            new DateTimeImmutable('midnight UTC - 1 day'),
            new DateTimeImmutable('midnight UTC')
        );

        return $this->render(
            'admin/dashboard/index.html.twig',
            [
                'user_total_count' => $userTotalCount,
                'token_total_count' => $tokenTotalCount,
                'data_total_count' => $dataTotalCount,
                'data_weekly_count' => $dataWeeklyCount,
                'data_daily_count' => $dataDailyCount,
            ]
        );
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Nexus Archive')
            ->renderContentMaximized()
            ->generateRelativeUrls();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->displayUserAvatar(false);
    }
}
