<?php

namespace AppBundle\Command;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearLogsCommand extends Command
{
    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var null|string
     */
    private $logsDir;

    /**
     * @var null|string
     */
    private $env;

    /**
     * ClearLogsCommand constructor.
     *
     * @param null|string $logsDir
     * @param null|string $env
     */
    public function __construct($logsDir, $env)
    {
        parent::__construct();

        $this->logsDir = $logsDir;
        $this->env     = $env;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:logs:clear')
            ->setDescription('Deletes all logfiles')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->fs = new Filesystem();

        $log = $this->logsDir.'/'.$this->env.'.log';
        $this->io->comment(sprintf('Clearing the logs for the <info>%s</info> environment', $this->env));
        if (!$this->fs->exists($log)) {
            $this->fs->remove($log);
        }
        $this->io->success(sprintf('Logs for the "%s" environment was successfully cleared.', $this->env));
    }
}
