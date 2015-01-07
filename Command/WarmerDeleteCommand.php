<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command removes warmers from elasticsearch index.
 */
class WarmerDeleteCommand extends AbstractElasticsearchCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('es:warmer:delete')
            ->addArgument(
                'names',
                InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                'Warmers names to delete from index.',
                []
            )
            ->addOption(
                'connection',
                'c',
                InputOption::VALUE_REQUIRED,
                'Connection name to delete warmers from.',
                'default'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $names = $input->getArgument('names');
        $this->getConnection($input->getOption('connection'))->deleteWarmers($names);

        $message = '';
        if (empty($names)) {
            $message = 'All warmers have been deleted from <info>%s</info> index.';
        } else {
            $callback = function ($val) {
                return '<info>' . $val . '</info>';
            };
            $message = implode(', ', array_map($callback, $names))
                . ' warmer(s) have been deleted from <info>%s</info> index.';
        }

        $output->writeln(sprintf($message, $input->getOption('connection')));

        return 0;
    }
}