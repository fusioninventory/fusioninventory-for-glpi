<?php










namespace Symfony\Component\Console\Helper;

use Symfony\Component\Console\Output\OutputInterface;
use InvalidArgumentException;






class TableHelper extends Helper
{
const LAYOUT_DEFAULT = 0;
const LAYOUT_BORDERLESS = 1;
const LAYOUT_COMPACT = 2;






private $headers = array();






private $rows = array();


 private $paddingChar;
private $horizontalBorderChar;
private $verticalBorderChar;
private $crossingChar;
private $cellHeaderFormat;
private $cellRowFormat;
private $cellRowContentFormat;
private $borderFormat;
private $padType;






private $columnWidths = array();






private $numberOfColumns;




private $output;

public function __construct()
{
$this->setLayout(self::LAYOUT_DEFAULT);
}








public function setLayout($layout)
{
switch ($layout) {
case self::LAYOUT_BORDERLESS:
$this
->setPaddingChar(' ')
->setHorizontalBorderChar('=')
->setVerticalBorderChar(' ')
->setCrossingChar(' ')
->setCellHeaderFormat('<info>%s</info>')
->setCellRowFormat('%s')
->setCellRowContentFormat(' %s ')
->setBorderFormat('%s')
->setPadType(STR_PAD_RIGHT)
;
break;

case self::LAYOUT_COMPACT:
$this
->setPaddingChar(' ')
->setHorizontalBorderChar('')
->setVerticalBorderChar(' ')
->setCrossingChar('')
->setCellHeaderFormat('<info>%s</info>')
->setCellRowFormat('%s')
->setCellRowContentFormat('%s')
->setBorderFormat('%s')
->setPadType(STR_PAD_RIGHT)
;
break;

case self::LAYOUT_DEFAULT:
$this
->setPaddingChar(' ')
->setHorizontalBorderChar('-')
->setVerticalBorderChar('|')
->setCrossingChar('+')
->setCellHeaderFormat('<info>%s</info>')
->setCellRowFormat('%s')
->setCellRowContentFormat(' %s ')
->setBorderFormat('%s')
->setPadType(STR_PAD_RIGHT)
;
break;

default:
throw new InvalidArgumentException(sprintf('Invalid table layout "%s".', $layout));
break;
};

return $this;
}

public function setHeaders(array $headers)
{
$this->headers = array_values($headers);

return $this;
}

public function setRows(array $rows)
{
$this->rows = array();

return $this->addRows($rows);
}

public function addRows(array $rows)
{
foreach ($rows as $row) {
$this->addRow($row);
}

return $this;
}

public function addRow(array $row)
{
$this->rows[] = array_values($row);

$keys = array_keys($this->rows);
$rowKey = array_pop($keys);

foreach ($row as $key => $cellValue) {
if (!strstr($cellValue, "\n")) {
continue;
}

$lines = explode("\n", $cellValue);
$this->rows[$rowKey][$key] = $lines[0];
unset($lines[0]);

foreach ($lines as $lineKey => $line) {
$nextRowKey = $rowKey + $lineKey + 1;

if (isset($this->rows[$nextRowKey])) {
$this->rows[$nextRowKey][$key] = $line;
} else {
$this->rows[$nextRowKey] = array($key => $line);
}
}
}

return $this;
}

public function setRow($column, array $row)
{
$this->rows[$column] = $row;

return $this;
}








public function setPaddingChar($paddingChar)
{
if (!$paddingChar) {
throw new \LogicException('The padding char must not be empty');
}

$this->paddingChar = $paddingChar;

return $this;
}








public function setHorizontalBorderChar($horizontalBorderChar)
{
$this->horizontalBorderChar = $horizontalBorderChar;

return $this;
}








public function setVerticalBorderChar($verticalBorderChar)
{
$this->verticalBorderChar = $verticalBorderChar;

return $this;
}








public function setCrossingChar($crossingChar)
{
$this->crossingChar = $crossingChar;

return $this;
}








public function setCellHeaderFormat($cellHeaderFormat)
{
$this->cellHeaderFormat = $cellHeaderFormat;

return $this;
}








public function setCellRowFormat($cellRowFormat)
{
$this->cellRowFormat = $cellRowFormat;

return $this;
}








public function setCellRowContentFormat($cellRowContentFormat)
{
$this->cellRowContentFormat = $cellRowContentFormat;

return $this;
}








public function setBorderFormat($borderFormat)
{
$this->borderFormat = $borderFormat;

return $this;
}








public function setPadType($padType)
{
$this->padType = $padType;

return $this;
}















public function render(OutputInterface $output)
{
$this->output = $output;

$this->renderRowSeparator();
$this->renderRow($this->headers, $this->cellHeaderFormat);
if (!empty($this->headers)) {
$this->renderRowSeparator();
}
foreach ($this->rows as $row) {
$this->renderRow($row, $this->cellRowFormat);
}
if (!empty($this->rows)) {
$this->renderRowSeparator();
}

$this->cleanup();
}






private function renderRowSeparator()
{
if (0 === $count = $this->getNumberOfColumns()) {
return;
}

if (!$this->horizontalBorderChar && !$this->crossingChar) {
return;
}

$markup = $this->crossingChar;
for ($column = 0; $column < $count; $column++) {
$markup .= str_repeat($this->horizontalBorderChar, $this->getColumnWidth($column)).$this->crossingChar;
}

$this->output->writeln(sprintf($this->borderFormat, $markup));
}




private function renderColumnSeparator()
{
$this->output->write(sprintf($this->borderFormat, $this->verticalBorderChar));
}









private function renderRow(array $row, $cellFormat)
{
if (empty($row)) {
return;
}

$this->renderColumnSeparator();
for ($column = 0, $count = $this->getNumberOfColumns(); $column < $count; $column++) {
$this->renderCell($row, $column, $cellFormat);
$this->renderColumnSeparator();
}
$this->output->writeln('');
}








private function renderCell(array $row, $column, $cellFormat)
{
$cell = isset($row[$column]) ? $row[$column] : '';
$width = $this->getColumnWidth($column);


 if (function_exists('mb_strlen') && false !== $encoding = mb_detect_encoding($cell)) {
$width += strlen($cell) - mb_strlen($cell, $encoding);
}

$width += $this->strlen($cell) - $this->computeLengthWithoutDecoration($cell);

$content = sprintf($this->cellRowContentFormat, $cell);

$this->output->write(sprintf($cellFormat, str_pad($content, $width, $this->paddingChar, $this->padType)));
}






private function getNumberOfColumns()
{
if (null !== $this->numberOfColumns) {
return $this->numberOfColumns;
}

$columns = array(0);
$columns[] = count($this->headers);
foreach ($this->rows as $row) {
$columns[] = count($row);
}

return $this->numberOfColumns = max($columns);
}








private function getColumnWidth($column)
{
if (isset($this->columnWidths[$column])) {
return $this->columnWidths[$column];
}

$lengths = array(0);
$lengths[] = $this->getCellWidth($this->headers, $column);
foreach ($this->rows as $row) {
$lengths[] = $this->getCellWidth($row, $column);
}

return $this->columnWidths[$column] = max($lengths) + strlen($this->cellRowContentFormat) - 2;
}









private function getCellWidth(array $row, $column)
{
return isset($row[$column]) ? $this->computeLengthWithoutDecoration($row[$column]) : 0;
}




private function cleanup()
{
$this->columnWidths = array();
$this->numberOfColumns = null;
}

private function computeLengthWithoutDecoration($string)
{
$formatter = $this->output->getFormatter();
$isDecorated = $formatter->isDecorated();
$formatter->setDecorated(false);

$string = $formatter->format($string);
$formatter->setDecorated($isDecorated);

return $this->strlen($string);
}




public function getName()
{
return 'table';
}
}
