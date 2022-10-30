<?php

declare(strict_types=1);

namespace Admin\Scripts;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

class AffiliatesSync extends Command
{
    
    protected $em;
    protected $config;
    protected $mail;

    public function __construct($em, $config)
    {
        $this->em = $em;
        $this->config = $config;
        $this->mail = new \Juliangorge\Mail\Plugin\MailPlugin($config);

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $start = microtime(true);

        $bucket = new \Admin\Service\AffiliatesBucket($this->em, $this->config);
        $results = $bucket->import();
        
        $output->writeln('Tiempo en ejecución: ' . round(microtime(true) - $start, 2) . ' segundos');

        $has_errors = (sizeof($results['errors']) > 0);
        if($has_errors){
            $this->mail->send($this->config['mail_errors'], 
                            'CRON: Error al actualizar', 
                            '<pre>' , print_r($results['errors']) , '</pre>',
                            false);
            return Command::FAILURE;
        }

        $output->writeln('Creaciones: ' . $results['stats']['created'] . ', actualizaciones: ' . $results['stats']['updates']);
        return Command::SUCCESS;
    }
}