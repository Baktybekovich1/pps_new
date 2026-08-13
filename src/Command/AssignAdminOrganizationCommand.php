<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\OrganizationRepository;
use App\Repository\TeacherOrganizationRepository;
use App\Repository\TeacherRepository;
use App\Repository\UserRepository;
use App\Tenant\OrganizationContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:admin:assign',
    description: 'Bind an admin account to one organization, or let it administer all of them.',
)]
class AssignAdminOrganizationCommand extends Command
{
    public function __construct(
        private readonly UserRepository                $userRepository,
        private readonly TeacherRepository             $teacherRepository,
        private readonly OrganizationRepository        $organizationRepository,
        private readonly TeacherOrganizationRepository $teacherOrganizationRepository,
        private readonly EntityManagerInterface        $entityManager,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Username of the admin account')
            ->addArgument('organization', InputArgument::OPTIONAL, 'Organization id to bind the account to')
            ->addOption('super', null, InputOption::VALUE_NONE, 'Grant cross-organization access instead');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');

        // Two kinds of principal reach the API: User accounts from the form
        // login, and Teacher accounts from Google OAuth. The React admin panel
        // runs on the latter — GetRoleController reads teacher.roles — so both
        // have to be assignable here.
        $user = $this->userRepository->findOneBy(['username' => $username])
            ?? $this->teacherRepository->findOneBy(['email' => $username]);

        if (null === $user) {
            $io->error(sprintf('No user or teacher named "%s".', $username));

            return Command::FAILURE;
        }

        if ($input->getOption('super')) {
            $user->setRoles($this->withRole($user->getRoles(), OrganizationContext::ROLE_CROSS_ORGANIZATION));
            if ($user instanceof User) {
                $user->setOrganization(null);
            }
            $this->entityManager->flush();

            $io->success(sprintf('"%s" now administers every organization.', $username));

            return Command::SUCCESS;
        }

        $organizationId = $input->getArgument('organization');
        if (null === $organizationId) {
            $io->error('Pass an organization id, or --super for cross-organization access.');

            return Command::FAILURE;
        }

        $organization = $this->organizationRepository->find($organizationId);
        if (null === $organization) {
            $io->error(sprintf('No organization with id %s.', $organizationId));

            return Command::FAILURE;
        }

        $roles = array_values(array_diff($user->getRoles(), [OrganizationContext::ROLE_CROSS_ORGANIZATION]));
        $user->setRoles($this->withRole($roles, 'ROLE_ADMIN'));

        if ($user instanceof User) {
            $user->setOrganization($organization);
        } else {
            // A teacher's organization already comes from teacher_organization,
            // which OrganizationContext reads; assigning it here would give the
            // same teacher two competing answers.
            $memberships = $this->teacherOrganizationRepository->findBy(['teacher' => $user]);
            $organizations = array_map(
                static fn ($membership) => $membership->getOrganization()->getId(),
                $memberships
            );

            if (!in_array($organization->getId(), $organizations, true)) {
                $io->error(sprintf(
                    'Teacher "%s" does not work for "%s". Add the membership first — a teacher administers the organization they belong to.',
                    $username,
                    $organization->getName()
                ));

                return Command::FAILURE;
            }

            if (count(array_unique($organizations)) > 1) {
                $io->warning('This teacher belongs to several organizations, so their admin context stays ambiguous until that is resolved.');
            }
        }

        $this->entityManager->flush();

        $io->success(sprintf('"%s" now administers "%s" only.', $username, $organization->getName()));

        return Command::SUCCESS;
    }

    /**
     * @param string[] $roles
     *
     * @return string[]
     */
    private function withRole(array $roles, string $role): array
    {
        $roles[] = $role;

        return array_values(array_unique($roles));
    }
}
