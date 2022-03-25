<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\NexusRawData;
use App\Entity\User;
use App\Entity\UserAccessToken;
use App\Repository\NexusRawDataRepository;
use App\Repository\UserAccessTokenRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

final class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private Environment $twigEnvironment,
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
            new DateTimeImmutable(datetime: 'midnight UTC - 1 week'),
            new DateTimeImmutable(datetime: 'midnight UTC')
        );
        $dataDailyCount = $this->nexusRawDataRepository->getPartialCount(
            new DateTimeImmutable(datetime: 'midnight UTC - 1 day'),
            new DateTimeImmutable(datetime: 'midnight UTC')
        );

        $context = [
            'user_total_count' => $userTotalCount,
            'token_total_count' => $tokenTotalCount,
            'data_total_count' => $dataTotalCount,
            'data_weekly_count' => $dataWeeklyCount,
            'data_daily_count' => $dataDailyCount,
        ];

        $content = $this->twigEnvironment->render(name: 'admin/dashboard/index.html.twig', context: $context);

        return new Response(content: $content);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle(title: 'Nexus Archive')
            ->renderContentMaximized()
            ->generateRelativeUrls();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard(label: 'Dashboard', icon: 'fa fa-home');
        yield MenuItem::section(label: 'Raw data');
        yield MenuItem::linkToCrud(label: 'Raw data', icon: 'fas fa-list', entityFqcn: NexusRawData::class);
        yield MenuItem::section(label: 'Users');
        yield MenuItem::linkToCrud(label: 'Users', icon: 'fas fa-users', entityFqcn: User::class);
        yield MenuItem::linkToCrud(
            label: 'User access tokens',
            icon: 'fas fa-users',
            entityFqcn: UserAccessToken::class
        );
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu(user: $user)
            ->displayUserAvatar(isDisplayed: false);
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->setTimezone(timezoneId: 'UTC')
            ->setDateTimeFormat(dateFormatOrPattern: 'YYYY-MM-dd HH:mm:ss z')
            ->showEntityActionsInlined();
    }
}
