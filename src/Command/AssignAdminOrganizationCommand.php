<?php

namespace App\Command;

use App\Repository\OrganizationRepository;
use App\Repository\TeacherOrganizationRepository;
use App\Repository\TeacherRepository;
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
    description: 'Make a teacher the admin of one organization, or of all of them.',
)]
class AssignAdminOrganizationCommand extends Command
{
    public function __construct(
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
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the teacher account')
            ->addArgument('organization', InputArgument::OPTIONAL, 'Organization id to bind the account to')
            ->addOption('super', null, InputOption::VALUE_NONE, 'Grant cross-organization access instead');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');
        $teacher = $this->teacherRepository->findOneBy(['email' => $email]);
        if (null === $teacher) {
            $io->error(sprintf('No teacher with email "%s".', $email));

            return Command::FAILURE;
        }

        if ($input->getOption('super')) {
            $teacher->setRoles($this->withRole($teacher->getRoles(), OrganizationContext::ROLE_CROSS_ORGANIZATION));
            $this->entityManager->flush();

            $io->success(sprintf('"%s" now administers every organization.', $email));

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

        // The organization is not stored on the account: it already lives in
        // teacher_organization, which OrganizationContext reads. Recording it
        // twice would let the two answers drift apart.
        $memberships = $this->teacherOrganizationRepository->findBy(['teacher' => $teacher]);
        $organizations = array_map(
            static fn ($membership) => $membership->getOrganization()->getId(),
            $memberships
        );

        if (!in_array($organization->getId(), $organizations, true)) {
            $io->error(sprintf(
                'Teacher "%s" does not work for "%s". Add the membership first — a teacher administers the organization they belong to.',
                $email,
                $organization->getName()
            ));

            return Command::FAILURE;
        }

        $roles = array_values(array_diff($teacher->getRoles(), [OrganizationContext::ROLE_CROSS_ORGANIZATION]));
        $teacher->setRoles($this->withRole($roles, 'ROLE_ADMIN'));
        $this->entityManager->flush();

        if (count(array_unique($organizations)) > 1) {
            $io->warning('This teacher belongs to several organizations, so their admin context stays ambiguous until that is resolved.');
        }

        $io->success(sprintf('"%s" now administers "%s" only.', $email, $organization->getName()));

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
