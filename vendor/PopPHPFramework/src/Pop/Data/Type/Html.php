<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/nicksagona/PopPHP
 * @category   Pop
 * @package    Pop_Data
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Data\Type;

/**
 * Html data type class
 *
 * @category   Pop
 * @package    Pop_Data
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.4.0
 */
class Html
{

    /**
     * Encode the data into its native format.
     *
     * @param  mixed  $data
     * @param  array  $options
     * @return string
     */
    public static function encode($data, array $options = null)
    {
        $output = '';
        $indent = (isset($options['indent'])) ? $options['indent'] : '    ';
        $date = (isset($options['date'])) ? $options['date'] : 'M j, Y';
        $exclude = (isset($options['exclude'])) ? $options['exclude'] : array();
        $process = null;
        $submit = null;

        if (!is_array($exclude)) {
            $exclude = array($exclude);
        }

        if (isset($options['form']) && is_array($options['form'])) {
            $process = (isset($options['form']['process'])) ? $options['form']['process'] : null;
            $submit = (isset($options['form']['submit'])) ? $options['form']['submit'] : null;
            $output .= $indent . '<form';
            foreach ($options['form'] as $attrib => $value) {
                if (($attrib != 'process') && ($attrib != 'submit')) {
                    $output .= ' ' . $attrib . '="' . $value . '"';
                }
            }
            $output .= '>' . PHP_EOL;
        }

        $output .= $indent . '    <table';
        if (isset($options['table']) && is_array($options['table'])) {
            foreach ($options['table'] as $attrib => $value) {
                $output .= ' ' . $attrib . '="' . $value . '"';
            }
        }
        $output .= '>' . PHP_EOL;

        // Initialize and clean the header fields.
        foreach ($data as $ary) {
            $tempAry = array_keys((array)$ary);
        }

        $headerAry = array();
        foreach ($tempAry as $value) {
            if (!in_array($value, $exclude)) {
                $headerAry[] = ucwords(str_replace('_', ' ' , (string)$value));
            }
        }
        if (isset($options['form'])) {
            $headerAry[] = ((null !== $submit) && is_array($submit) && isset($submit['value'])) ? $submit['value'] : '&nbsp;';
        }

        // Set header output.
        $output .= $indent . '        <tr><th>' . implode('</th><th>', $headerAry) . '</th></tr>' . PHP_EOL;

        // Initialize and clean the field values.
        $i = 1;
        foreach ($data as $value) {
            $rowAry = array();
            foreach ($value as $key => $val) {
                if (!in_array($key, $exclude)) {
                    if ((strtotime((string)$val) !== false) && (stripos($key, 'date') !== false)) {
                        $v = (date($date, strtotime($val)) != '12/31/1969') ? date($date, strtotime((string)$val)) : '';
                    } else {
                        $v = (string)$val;
                    }
                    if (isset($options[$key])) {
                        $tmpl = $options[$key];
                        foreach ($value as $ky => $vl) {
                            $tmpl = str_replace('[{' . $ky . '}]', $vl, $tmpl);
                        }
                        $v = $tmpl;
                    }
                    $rowAry[] = $v;
                }
            }
            if (isset($options['form'])) {
                if (null !== $process) {
                    $tmpl = str_replace('[{i}]', $i, $process);
                    foreach ($value as $ky => $vl) {
                        $tmpl = str_replace('[{' . $ky . '}]', $vl, $tmpl);
                    }
                } else {
                    $tmpl = '&nbsp;';
                }
                $rowAry[] = $tmpl;
            }
            $i++;

            // Set field output.
            $output .= $indent . '        <tr><td>' . implode('</td><td>', $rowAry) . '</td></tr>' . PHP_EOL;
        }

        if (isset($options['form'])) {
            $submitBtn = '<input type="submit" name="submit"';

            if ((null !== $submit) && is_array($submit)) {
                foreach ($submit as $attrib => $value) {
                    $submitBtn .= ' ' . $attrib . '="' . $value . '"';
                }
            } else {
                $submitBtn .= ' value="Submit"';
            }

            $submitBtn .= ' />';

            $output .= $indent . '        <tr><td colspan="' . count($headerAry) . '" style="text-align: right;">' . $submitBtn . '</td></tr>' . PHP_EOL;
            $output .= $indent . '    </table>' . PHP_EOL;
            $output .= $indent . '</form>' . PHP_EOL;
        } else {
            $output .= $indent . '    </table>' . PHP_EOL;
        }

        return $output;
    }

}