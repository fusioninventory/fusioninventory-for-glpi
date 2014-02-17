<?php











namespace Composer\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;




class AboutCommand extends Command
{
protected function configure()
{
$this
->setName('about')
->setDescription('Short information about Composer')
->setHelp(<<<EOT
<info>php composer.phar about</info>
EOT
)
;
}

protected function execute(InputInterface $input, OutputInterface $output)
{
$output->writeln(<<<EOT
<info>Composer - Package Management for PHP</info>
<comment>Composer is a dependency manager tracking local dependencies of your projects and libraries.
See http://getcomposer.org/ for more information.</comment>
EOT
);

}
}
