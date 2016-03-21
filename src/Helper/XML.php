<?php
/**
 * HHVM
 *
 * Copyright (C) Tony Yip 2015.
 *
 * @author   Tony Yip <tony@opensource.hk>
 * @license  http://opensource.org/licenses/GPL-3.0 GNU General Public License
 */

namespace Elearn\Foundation\Helper;

use DOMDocument;
use DOMXPath;
use Elearn\Foundation\Exception\XMLException;
use Symfony\Component\CssSelector\CssSelector;
use Log;

class XML
{

    /**
     * @var DOMDocument
     */
    private $dom;

    /**
     * @var \DOMElement
     */
    private $root;

    /**
     * @var array
     */
    private $namespaces = [];

    /**
     * @param string $version
     * @param string $encoding
     */
    public function __construct($version = '1.0', $encoding = 'UTF-8')
    {
        $this->dom = new DOMDocument($version, $encoding);
    }

    public function clearNamespace()
    {
        $this->namespaces = [];
    }

    /**
     * Parse XML from string.
     *
     * @param string $source
     */
    public function parseString($source)
    {
        $this->dom->loadXML($source);
        $this->root = $this->dom->documentElement;
    }

    /**
     * Parse XML from file.
     *
     * @param string $source
     */
    public function parseFile($source)
    {
        $this->dom->load($source);
        $this->root = $this->dom->documentElement;
    }

    /**
     * Registers the namespace
     *
     * @param string $prefix
     * @param string $uri URI of namespace.
     */
    public function registerNamespace($prefix, $uri)
    {
        $this->namespaces[$prefix] = $uri;
    }

    /**
     * Evaluates the given XPath expression
     *
     * @param string $query XPath query
     * @param \DOMNode $context specified node for doing relative queries.
     *
     * @return \DOMNodeList matching node.
     */
    public function query($query, \DOMNode $context = null)
    {
        $xpath = new DOMXPath($this->dom);
        foreach ($this->namespaces as $prefix => $uri) {
            $xpath->registerNamespace($prefix, $uri);
        }

        $result = $xpath->query($query, $context);

        return $result;
    }

    /**
     * Evaluates the given CSS expression
     *
     * @param string $query css query
     * @param \DOMNode $context specified node for doing relative queries.
     *
     * @return \DOMNodeList matching node.
     */
    public function cssQuery($query, \DOMNode $context = null)
    {
        return $this->query(CssSelector::toXPath($query), $context);
    }

    /**
     * @return \DOMElement
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @return DOMDocument
     */
    public function getDocument()
    {
        return $this->dom;
    }

    /**
     * @param XML $doc
     * @param $schema
     * @param bool|false $debug
     *
     * @return DOMDocument
     * @throws XMLException
     */
    public static function validSchema(XML $doc, $schema, $debug = false)
    {
        assert('is_string($schema)');
        libxml_clear_errors();
        libxml_use_internal_errors(true);

        $oldEntityLoader = libxml_disable_entity_loader(false);
        $dom = $doc->getDocument();
        $res = $dom->schemaValidate($schema
        libxml_disable_entity_loader($oldEntityLoader);
        if (!$res || !$res instanceof DOMDocument) {
            $xmlErrors = libxml_get_errors();
            if ($debug) {
                foreach ($xmlErrors as $error) {
                    Log::error($error->message);
                }
            }
            throw new XMLException($xmlErrors);
        }

        return $dom;
    }
}