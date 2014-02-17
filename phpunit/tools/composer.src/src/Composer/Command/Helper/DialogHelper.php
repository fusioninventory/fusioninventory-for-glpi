<?php











namespace Composer\Command\Helper;

use Symfony\Component\Console\Helper\DialogHelper as BaseDialogHelper;

class DialogHelper extends BaseDialogHelper
{











public function getQuestion($question, $default = null, $sep = ':')
{
return $default !== null ?
sprintf('<info>%s</info> [<comment>%s</comment>]%s ', $question, $default, $sep) :
sprintf('<info>%s</info>%s ', $question, $sep);
}
}
