<?php
/**
 * Pop PHP Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.TXT.
 * It is also available through the world-wide-web at this URL:
 * http://www.popphp.org/LICENSE.TXT
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@popphp.org so we can send you a copy immediately.
 *
 * @category   Pop
 * @package    Pop_Feed
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Feed;

use Pop\File\File,
    Pop\Locale\Locale;

/**
 * @category   Pop
 * @package    Pop_Feed
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 * @version    0.9
 */
class Reader
{

    /**
     * Feed title
     * @var string
     */
    public $title = null;

    /**
     * Feed URL
     * @var string
     */
    public $url = null;

    /**
     * Feed description
     * @var string
     */
    public $desc = null;

    /**
     * Feed date
     * @var string
     */
    public $date = null;

    /**
     * Feed generator
     * @var string
     */
    public $generator = null;

    /**
     * Feed editor
     * @var string
     */
    public $editor = null;

    /**
     * Feed items
     * @var array
     */
    public $items = array();

    /**
     * Feed limit
     * @var array
     */
    protected $_limit = array();

    /**
     * XML Object
     * @var SimpleXMLElement
     */
    protected $_xml = null;

    /**
     * Feed type
     * @var string
     */
    protected $_feedType = null;

    /**
     * Feed source
     * @var string
     */
    protected $_feedSrc = null;

    /**
     * Feed item template
     * @var string
     */
    protected $_template = null;

    /**
     * Feed date format
     * @var string
     */
    protected $_dateFormat = 'm/d/Y h:i a';

    /**
     * Language object
     * @var Pop_Locale
     */
    protected $_lang = null;

    /**
     * Constructor
     *
     * Instantiate the feed object.
     *
     * @param  string $url
     * @param  int|string $limit
     * @throws Exception
     * @return void
     */
    public function __construct($url, $limit = null)
    {
        $this->_lang = new Locale();

        // Create the SimpleXMLElement and set the format to either XML or HTML.
        try {
            if (($this->_xml =@ new \SimpleXMLElement($url, LIBXML_NOWARNING, true)) !== false) {
                $this->_feedType = (isset($this->_xml->entry)) ? $this->_feedType = 'atom' : $this->_feedType = 'rss';

                // Set the type of feed, either a YouTube, Vimeo or normal RSS feed.
                if (strpos($url, 'youtube') !== false) {
                    $this->_feedSrc = 'youtube';
                } else if (strpos($url, 'vimeo') !== false) {
                    $this->_feedSrc = 'vimeo';
                } else if (strpos($url, 'viddler') !== false) {
                    $this->_feedSrc = 'viddler';
                } else {
                    $this->_youtube = false;
                }

                $this->_limit = $limit;

                // Parse the items from the feed.
                $this->_parseFeed();
            } else {
                throw new Exception($this->_lang->__('That feed URL cannot be read at this time. Please try again later.'));
            }

        // Else, throw an exception if there are any failures.
        } catch (\Exception $e) {
            throw new Exception($this->_lang->__('That feed URL cannot be read at this time. Please try again later.'));
        }
    }

    /**
     * Method to set item template
     *
     * @param  string $tmpl
     * @return Pop\Feed\Reader
     */
    public function setTemplate($tmpl)
    {
        if (file_exists($tmpl)) {
            $tmplFile = new File($tmpl);
            $this->_template = $tmpl->read();
        } else {
            $this->_template = $tmpl;
        }
        return $this;
    }

    /**
     * Method to set date format
     *
     * @param  string $date
     * @return Pop\Feed\Reader
     */
    public function setDateFormat($date)
    {
        $this->_dateFormat = $date;
        return $this;
    }

    /**
     * Method to get feed template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->_template;
    }

    /**
     * Method to get feed date format
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->_dateFormat;
    }

    /**
     * Method to get feed type
     *
     * @return string
     */
    public function getFeedType()
    {
        return $this->_feedType;
    }

    /**
     * Method to render the feed
     *
     * @param  boolean $ret
     * @throws Exception
     * @return mixed
     */
    public function render($ret = false)
    {
        if (null === $this->_template) {
            throw new Exception($this->_lang->__('Error: The feed item template is not set.'));
        } else if (!isset($this->items[0])) {
            throw new Exception($this->_lang->__('Error: The feed currently has no content.'));
        } else {
            $output = '';

            if (null !== $this->_limit) {
                $lim = ($this->_limit > count($this->items)) ? count($this->items) : $this->_limit;
            } else {
                $lim = count($this->items);
            }

            // Loop through the items, formatting them into the template as needed, using the proper date format if appropriate.
            for ($i = 0; $i < $lim; $i++) {
                $tmpl = $this->_template;
                foreach ($this->items[$i] as $k => $v) {
                    if ((null !== $this->_dateFormat) && (stripos($k, 'date') !== false)) {
                        $val =  date($this->_dateFormat, strtotime($v));
                    } else {
                        $val = $v;
                    }
                    $tmpl = str_replace('[{' . $k . '}]', $val, $tmpl);
                }
                $output .= $tmpl;
            }

            // Return the final output.
            if ($ret) {
                return $output;
            } else {
                echo $output;
            }
        }
    }

    /**
     * Method to parse the items from the XML feed.
     *
     * @return void
     */
    protected function _parseFeed()
    {
        // If the feed type is YouTube, parse accordingly.
        if ($this->_feedSrc == 'youtube') {
            $this->title = (string)$this->_xml->title;
            $this->url = (string)$this->_xml->link->attributes()->href;
            $this->desc = (string)$this->_xml->subtitle;
            $this->date = (string)$this->_xml->updated;
            $this->generator = (string)$this->_xml->generator;
            $this->editor = (string)$this->_xml->author->name;

            foreach ($this->_xml->entry as $value) {
                // Parse the video ID and description.
                $id = substr($value->id, (strrpos($value->id, '/') + 1));
                $desc = substr($value->content, (strpos($value->content, '<span>') + 6));
                $desc = substr($desc, 0, strpos($desc, '</span>'));
                $image = 'http://img.youtube.com/vi/' . $id . '/default.jpg';

                // Add the values to the associative array.
                $this->items[] = array('title' => (string)$value->title,
                                       'description' => $desc,
                                       'link' => 'http://www.youtube.com/watch?v=' . $id,
                                       'pubDate' => (string)$value->published,
                                       'timeElapsed' => $this->_calcElapsedTime($value->published),
                                       'id' => $id,
                                       'image' => $image);
            }
        // Else, if the feed type is Vimeo, parse accordingly.
        } else if ($this->_feedSrc == 'vimeo') {
            $this->title = (string)$this->_xml->channel->title;
            $this->url = (string)$this->_xml->channel->link;
            $this->desc = (string)$this->_xml->channel->description;
            $this->date = (string)$this->_xml->channel->pubDate;
            $this->generator = (string)$this->_xml->channel->generator;
            $this->editor = (string)$this->_xml->channel->generator;

            foreach ($this->_xml->channel->item as $value) {
                $id = substr($value->link, (strrpos($value->link, '/') + 1));
                $image = substr($value->description, (strpos($value->description, '<img src="') + 10));
                $image = substr($image, 0, strpos($image, '"'));

                // Add the values to the associative array.
                $this->items[] = array('title' => (string)$value->title,
                                       'description' => (string)$value->description,
                                       'link' => (string)$value->link,
                                       'pubDate' => (string)$value->pubDate,
                                       'timeElapsed' => $this->_calcElapsedTime($value->pubDate),
                                       'id' => $id,
                                       'image' => $image);
            }

        // Else, if the feed type is Viddler, parse accordingly.
        } else if ($this->_feedSrc == 'viddler') {
            $this->title = (string)$this->_xml->channel->title;
            $this->url = (string)$this->_xml->channel->link;
            $this->desc = (string)$this->_xml->channel->description;
            $this->date = (string)$this->_xml->channel->pubDate;
            $this->generator = (string)$this->_xml->channel->generator;
            $this->editor = (string)$this->_xml->channel->generator;

            foreach ($this->_xml->channel->item as $value) {
                $id = substr($value->enclosure->attributes()->url, 0, -1);
                $id = substr($id, (strrpos($id, '/') + 1));
                $image = substr($value->description, (strpos($value->description, '<img src="') + 10));
                $image = substr($image, 0, strpos($image, '"'));

                // Add the values to the associative array.
                $this->items[] = array('title' => (string)$value->title,
                                       'description' => (string)$value->description,
                                       'link' => (string)$value->link,
                                       'pubDate' => (string)$value->pubDate,
                                       'timeElapsed' => $this->_calcElapsedTime($value->pubDate),
                                       'id' => $id,
                                       'image' => $image);
            }
        // Else, parse as a regular Atom feed.
        } else if ($this->_feedType == 'atom') {
            $this->title = (string)$this->_xml->title;
            $this->url = (string)$this->_xml->link->attributes()->href;
            $this->desc = (string)$this->_xml->subtitle;
            $this->date = (string)$this->_xml->updated;
            $this->generator = (string)$this->_xml->generator;
            $this->editor = (string)$this->_xml->author->name;

            foreach ($this->_xml->entry as $value) {
                // Add the values to the associative array.
                $this->items[] = array('title' => (string)$value->title,
                                       'description' => (string)$value->summary,
                                       'link' => (string)$value->link->attributes()->href,
                                       'pubDate' => (string)$value->published,
                                       'timeElapsed' => $this->_calcElapsedTime($value->published));
            }
        // Else, parse as a regular RSS feed.
        } else {
            $this->title = (string)$this->_xml->channel->title;
            $this->url = (string)$this->_xml->channel->link;
            $this->desc = (string)$this->_xml->channel->description;
            $this->date = (string)$this->_xml->channel->lastBuildDate;
            $this->generator = (string)$this->_xml->channel->generator;
            $this->editor = (string)$this->_xml->channel->managingEditor;

            foreach ($this->_xml->channel->item as $value) {
                // Add the values to the associative array.
                $this->items[] = array('title' => (string)$value->title,
                                       'description' => (string)$value->description,
                                       'link' => (string)$value->link,
                                       'pubDate' => (string)$value->pubDate,
                                       'timeElapsed' => $this->_calcElapsedTime($value->pubDate));
            }
        }
    }

    /**
     * Method to calculate the elapsed time between the date passed and now.
     *
     * @param  string $dt
     * @return void
     */
    protected function _calcElapsedTime($dt)
    {
        // Calculate the difference.
        $elapsedTime = '';
        $timeDiff = time() - strtotime($dt);

        // If less than an hour.
        if ($timeDiff < 3600) {
            $elapsedTime = round($timeDiff / 60);
            if ($elapsedTime < 0) {
                $elapsedTime = 'A few seconds ago';
            } else if ($elapsedTime == 1) {
                $elapsedTime .= ' minute ago';
            } else {
                $elapsedTime .= ' minutes ago';
            }
        // If less than a day.
        } else if (($timeDiff >= 3600) && ($timeDiff < 86400)) {
            $elapsedTime = round(($timeDiff / 60) / 60);
            $elapsedTime .= ($elapsedTime == 1) ? ' hour ago' : ' hours ago';
        // If less than a month.
        } else if (($timeDiff >= 86400) && ($timeDiff < 2592000)) {
            $elapsedTime = round(((($timeDiff / 60) / 60) / 24));
            $elapsedTime .= ($elapsedTime == 1) ? ' day ago' : ' days ago';
        // If more than a month.
        } else if ($timeDiff >= 2592000) {
            $elapsedTime = round((((($timeDiff / 60) / 60) / 24) / 30));
            $elapsedTime .= ($elapsedTime == 1) ? ' month ago' : ' months ago';
        }

        // Return the calculated elapsed time.
        return $elapsedTime;
    }

}
